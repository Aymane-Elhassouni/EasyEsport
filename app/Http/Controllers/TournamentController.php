<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentAnnouncement;
use App\Services\Interfaces\TournamentServiceInterface;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function __construct(
        protected TournamentServiceInterface $tournamentService,
        protected TeamServiceInterface $teamService
    ) {}

    public function index()
    {
        $dtos = $this->tournamentService->getAllActive();
        
        $tournaments = $dtos->map(function ($dto) {
            return new \App\Presenters\TournamentPresenter($dto);
        });

        $games = Game::all();
        return view('pages.tournaments.index', compact('tournaments', 'games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255|unique:tournaments,name',
            'game_id'              => 'required|exists:games,id',
            'format'               => 'required|string',
            'max_teams'            => 'required|integer|min:2',
            'players_per_team'     => 'required|integer|min:1',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after:start_date',
            'has_group_stage'      => 'boolean',
            'teams_per_group'      => 'required_if:has_group_stage,1|nullable|integer|min:2',
            'qualifiers_per_group' => 'required_if:has_group_stage,1|nullable|integer|min:1|lte:teams_per_group',
        ]);

        try {
            $tournament = $this->tournamentService->createTournament($data);
            return redirect()->route('tournaments.show', $tournament->slug)->with('success', 'Tournament created!');
        } catch (\Exception $e) {
            return back()->withErrors(['players_per_team' => $e->getMessage()])->withInput();
        }
    }

    public function show(string $slug)
    {
        $dto = $this->tournamentService->getTournamentDetail($slug);
        $tournament = new \App\Presenters\TournamentPresenter($dto);
        
        $userTeam = $this->teamService->getCurrentTeamForUser(Auth::id());
        
        $registrationStatus = ($userTeam && $tournament->id) 
            ? $this->tournamentService->getTeamRegistrationStatus($tournament->id, $userTeam->id) 
            : null;

        $upcomingMatches = $this->tournamentService->getUpcomingMatches($tournament->id);
            
        $isGroupStageCompleted = $this->tournamentService->isGroupStageCompleted(\App\Models\Tournament::find($tournament->id));

        return view('pages.tournaments.detail', compact('tournament', 'userTeam', 'registrationStatus', 'upcomingMatches', 'isGroupStageCompleted'));
    }

    public function register(Request $request, string $slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        if ($this->tournamentService->registerTeam($tournament->id, $request->input('team_id'))) {
            return back()->with('success', 'Your team has been registered successfully!');
        }

        return back()->with('error', 'Registration failed. Your team is already registered or not eligible.');
    }

    public function storeAnnouncement(Request $request, Tournament $tournament)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);
        $this->tournamentService->createAnnouncement($tournament->id, Auth::id(), $data);
        return back()->with('success', 'Announcement posted.');
    }

    public function destroyAnnouncement(TournamentAnnouncement $announcement)
    {
        $this->tournamentService->deleteAnnouncement($announcement);
        return back()->with('success', 'Announcement deleted.');
    }

    public function myAnnouncements()
    {
        $team          = $this->teamService->getCurrentTeamForUser(Auth::id());
        $announcements = $team
            ? $this->tournamentService->getAnnouncementsForTeam($team->id)
            : collect();

        return view('pages.player.announcements', compact('announcements', 'team'));
    }

    public function myApplications()
    {
        $team = $this->teamService->getCurrentTeamForUser(Auth::id());

        $applications = $team
            ? $this->tournamentService->getTeamApplications($team->id)
            : collect();

        $statusConfig = [
            'pending'    => ['color' => 'warning', 'icon' => '⏳', 'label' => 'Awaiting Review'],
            'approved'   => ['color' => 'success', 'icon' => '✅', 'label' => 'Approved'],
            'rejected'   => ['color' => 'danger',  'icon' => '❌', 'label' => 'Rejected'],
            'waitlisted' => ['color' => 'gray',    'icon' => '🕐', 'label' => 'Waitlisted'],
        ];

        return view('pages.player.applications', compact('applications', 'statusConfig', 'team'));
    }

    public function registrations()
    {
        $registrations = $this->tournamentService->getAllRegistrations();

        $counts = [
            'all'      => $registrations->count(),
            'pending'  => $registrations->where('status', 'pending')->count(),
            'approved' => $registrations->where('status', 'approved')->count(),
            'rejected' => $registrations->where('status', 'rejected')->count(),
        ];

        $statusConfig = [
            'pending'    => ['color' => 'warning', 'icon' => '⏳', 'label' => 'Awaiting Review'],
            'approved'   => ['color' => 'success', 'icon' => '✅', 'label' => 'Approved'],
            'rejected'   => ['color' => 'danger',  'icon' => '❌', 'label' => 'Rejected'],
            'waitlisted' => ['color' => 'gray',    'icon' => '🕐', 'label' => 'Waitlisted'],
        ];

        return view('pages.admin.registrations', compact('registrations', 'counts', 'statusConfig'));
    }

    public function validateApp(Request $request, Tournament $tournament)
    {
        $request->validate([
            'registration_id' => 'required|exists:tournament_registrations,id',
            'status'          => 'required|in:approved,rejected',
        ]);

        $this->tournamentService->updateRegistrationStatus($request->registration_id, $request->status);

        return back()->with('success', 'Registration ' . $request->status . '.');
    }

    public function launch(Tournament $tournament)
    {
        try {
            $this->tournamentService->launchTournament($tournament->id);
            return back()->with('success', "🚀 Tournament Launched! All match pairings have been generated and announcements sent.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generateKnockout(Tournament $tournament)
    {
        try {
            $this->tournamentService->qualifyToKnockout($tournament);
            return back()->with('success', 'Knockout bracket generated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function tournamentTeams(Tournament $tournament)
    {
        $tournament->load(['registrations' => fn($q) => $q->where('status', 'approved'), 'registrations.team.captain', 'registrations.team.members']);
        return view('pages.admin.tournament-teams', compact('tournament'));
    }
}
