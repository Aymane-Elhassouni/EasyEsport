<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct(
        protected TeamServiceInterface $teamService
    ) {}

    public function index()
    {
        $currentTeam = $this->teamService->getCurrentTeamForUser(Auth::id());

        if ($currentTeam) {
            return redirect()->route('teams.manage', $currentTeam->slug);
        }

        $teams = Team::withCount('members')->with('invitations')->latest()->get();
        return view('pages.teams.index', compact('teams'));
    }

    public function redirect()
    {
        $team = $this->teamService->getCurrentTeamForUser(Auth::id());

        if ($team) {
            return redirect()->route('teams.manage', $team->slug);
        }

        return redirect()->route('teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
        ]);

        if ($this->teamService->getCurrentTeamForUser(Auth::id())) {
            return back()->withErrors(['name' => 'You already belong to a team.']);
        }

        $team = $this->teamService->createTeam(Auth::id(), $validated);

        return redirect()->route('teams.manage', $team->slug)->with('success', 'Team created successfully.');
    }

    public function manage(Team $team)
    {
        $team->load([
            'captain',
            'members.user.profile',
            'members.user.playerGameProfiles',
            'invitations' => fn($q) => $q->where('status', 'pending'),
            'invitations.invitedUser.profile',
            'invitations.invitedUser.playerGameProfiles',
        ]);

        if (Auth::id() !== $team->captain_id && !$team->members->contains('user_id', Auth::id())) {
            abort(403);
        }

        return view('pages.teams.manage', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $data = [];

        if ($request->filled('name')) {
            $request->validate(['name' => 'string|max:255']);
            $data['name'] = $request->input('name');
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|max:2048']);
            $data['logo'] = $request->file('logo')->store('teams/logos', 's3');
        }

        if (!empty($data)) {
            $this->teamService->updateTeam($team->id, $data);
        }

        return back()->with('success', 'Team updated successfully.');
    }

    public function kickMember(Team $team, int $userId)
    {
        $this->teamService->kickMember($team->id, $userId);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Member removed from roster.');
    }

    public function requestJoin(Team $team)
    {
        $this->teamService->requestJoin($team->id, Auth::id());

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('teams')->with('success', 'Join request sent!');
    }

    public function invite(Request $request, Team $team)
    {
        $request->validate(['email' => 'required|email']);

        $this->teamService->invitePlayer($team->id, $request->email, Auth::id());

        return back()->with('success', 'Invitation sent successfully.');
    }

    public function searchPlayers(Request $request, Team $team)
    {
        $query = $request->input('q', '');

        $players = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'player'))
            ->where(function($q) use ($query) {
                $q->where('firstname', 'like', "%{$query}%")
                  ->orWhere('lastname', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->whereDoesntHave('teamMemberships')
            ->whereDoesntHave('captainOf')
            ->limit(8)
            ->get(['id', 'firstname', 'lastname', 'email', 'logo']);

        return response()->json($players->map(fn($u) => [
            'id'     => $u->id,
            'name'   => $u->firstname . ' ' . $u->lastname,
            'email'  => $u->email,
            'avatar' => $u->avatar_url,
        ]));
    }

    public function handleInvitation(\App\Models\Invitation $invitation, Request $request)
    {
        $status = $request->input('status');
        $this->teamService->handleInvitation($invitation->id, $status);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Application processed.');
    }
}
