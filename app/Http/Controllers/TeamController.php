<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct(
        protected TeamServiceInterface $teamService
    ) {}

    public function manage(int $id)
    {
        $team = $this->teamService->getManageableTeam($id);
        
        // Ensure user is authorized (Captain or Member)
        if (Auth::id() !== $team->captain_id && !$team->members->contains('user_id', Auth::id())) {
            abort(403, 'Unauthorized access to team headquarters.');
        }

        return view('pages.teams.manage', compact('team'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Add other fields as needed
        ]);

        $this->teamService->updateTeam($id, $validated);

        return back()->with('success', 'Team settings updated successfully.');
    }

    public function kickMember(int $teamId, int $userId)
    {
        $this->teamService->kickMember($teamId, $userId);
        return back()->with('success', 'Member removed from roster.');
    }

    public function handleInvitation(int $invitationId, Request $request)
    {
        $status = $request->input('status'); // 'accepted' or 'declined'
        $this->teamService->handleInvitation($invitationId, $status);
        return back()->with('success', 'Application processed.');
    }
}
