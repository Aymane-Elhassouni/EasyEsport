<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Tournament;
use App\Models\TournamentAnnouncement;
use App\Presenters\AnnouncementPresenter;
use App\Services\Interfaces\AnnouncementServiceInterface;
use App\Services\Interfaces\TournamentServiceInterface;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementServiceInterface $announcementService,
        protected TournamentServiceInterface   $tournamentService,
        protected AnnouncementPresenter        $presenter,
        protected TeamServiceInterface         $teamService,
    ) {}

    public function adminIndex()
    {
        $user = Auth::user();
        $tournaments = $this->tournamentService->getTournamentsWithAnnouncements($user);
        
        // Use the existing getAllActive for the dropdown as it already filters by owner for admins
        $allTournaments = $this->tournamentService->getAllActive();

        return view('pages.admin.announcements', compact('tournaments', 'allTournaments'));
    }

    public function index()
    {
        $user = Auth::user();
        $team = $this->teamService->getCurrentTeamForUser($user->id);

        if (!$team) {
            $membership = $user->teamMemberships()->with('team')->latest()->first();
            $team = $membership?->team;
        }

        $announcements = $team
            ? $this->presenter->presentCollection($this->announcementService->getForTeam($team->id))
            : collect();

        return view('pages.player.announcements', compact('announcements', 'team'));
    }

    public function show(TournamentAnnouncement $announcement)
    {
        $announcement->load(['tournament.game', 'author']);
        $data = $this->presenter->present($announcement);
        return view('pages.announcements.show', compact('data', 'announcement'));
    }

    public function store(StoreAnnouncementRequest $request, Tournament $tournament)
    {
        $data = $request->validated();
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner');
        }
        $this->announcementService->createForTournament($tournament, Auth::id(), $data);
        return back()->with('success', 'Announcement posted.');
    }

    public function update(StoreAnnouncementRequest $request, TournamentAnnouncement $announcement)
    {
        try {
            \Log::info('Updating announcement:', [
                'id' => $announcement->id,
                'slug' => $announcement->slug,
                'data' => $request->all()
            ]);
            
            $data = $request->validated();
            
            \Log::info('Validated data', $data);
            
            if ($request->hasFile('banner')) {
                $data['banner'] = $request->file('banner');
            }
            
            $this->announcementService->update($announcement, $data);
            
            \Log::info('Update successful');
            
            return back()->with('success', 'Announcement updated successfully');
        } catch (\Exception $e) {
            \Log::error('Update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy(TournamentAnnouncement $announcement)
    {
        $this->announcementService->delete($announcement);
        return back()->with('success', 'Announcement deleted.');
    }
}
