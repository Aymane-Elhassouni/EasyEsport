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
}
