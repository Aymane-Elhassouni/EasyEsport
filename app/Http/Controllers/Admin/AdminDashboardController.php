<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $adminDashboardService
    ) {}

    /**
     * Affiche le dashboard principal de l'administration.
     */
    public function index()
    {
        $stats = $this->adminDashboardService->getGlobalStats();
        $activity = $this->adminDashboardService->getRecentActivity();
        $myTournaments = $this->adminDashboardService->getMyTournaments(auth()->id());

        return view('pages.admin.dashboard', array_merge($stats, $activity, [
            'my_tournaments' => $myTournaments
        ]));
    }

    public function superAdminDashboard()
    {
        $stats = $this->adminDashboardService->getGlobalStats();
        $activity = $this->adminDashboardService->getRecentActivity();

        return view('pages.super_admin.dashboard', array_merge($stats, $activity));
    }

    public function logs()
    {
        $logs = $this->adminDashboardService->getSystemLogs();
        return view('pages.super_admin.logs', compact('logs'));
    }

    public function ocrMonitor()
    {
        return view('pages.admin.ocr');
    }

    public function teams()
    {
        $teams = $this->adminDashboardService->getAllTeams();
        return view('pages.admin.teams', compact('teams'));
    }

    public function destroyTeam(Team $team)
    {
        $this->adminDashboardService->deleteTeam($team);
        return back()->with('success', 'Team deleted.');
    }

    public function disputes()
    {
        $disputes = \App\Models\GameMatch::where('status', 'dispute')
            ->with(['teamA', 'teamB', 'bracket.tournament'])
            ->get();
        return view('pages.admin.disputes', compact('disputes'));
    }
}
