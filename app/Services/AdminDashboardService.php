<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\GameMatch;
use App\Models\Dispute;

class AdminDashboardService
{
    /**
     * Récupère les statistiques globales pour le dashboard Admin.
     */
    public function getGlobalStats(): array
    {
        return [
            'total_users'       => User::count(),
            'total_teams'       => Team::count(),
            'active_tournaments' => Tournament::where('status', 'ongoing')->count(),
            'pending_disputes'   => Dispute::where('status', 'pending')->count(),
            'total_matches'     => GameMatch::count(),
        ];
    }

    /**
     * Récupère les matchs récents nécessitant une attention ou terminés.
     */
    public function getRecentActivity(): array
    {
        return [
            'recent_matches' => GameMatch::with(['teamA', 'teamB'])
                ->latest()
                ->take(5)
                ->get(),
            'pending_registrations' => Tournament::withCount('registrations')
                ->where('status', 'pending')
                ->get(),
        ];
    }

    /**
     * Récupère les tournois créés par l'utilisateur connecté.
     */
    public function getMyTournaments(int $userId)
    {
        return Tournament::with(['game'])
            ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
            ->where('created_by', $userId)
            ->latest()
            ->get();
    }

    public function getAllTeams()
    {
        return Team::withCount('members')->with('captain')->latest()->get();
    }

    public function deleteTeam(Team $team): void
    {
        $team->delete();
    }

    public function getSystemLogs(int $limit = 100): array
    {
        $logFile = storage_path('logs/laravel.log');
        $logs    = [];

        if (!file_exists($logFile)) return $logs;

        $lines   = array_reverse(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/';

        foreach ($lines as $line) {
            if (preg_match($pattern, $line, $m)) {
                $logs[] = ['date' => $m[1], 'env' => $m[2], 'level' => strtolower($m[3]), 'message' => $m[4]];
                if (count($logs) >= $limit) break;
            }
        }

        return $logs;
    }
}
