<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\GameMatch;
use App\Models\Notification;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Services\Interfaces\DashboardServiceInterface;
use App\Services\Interfaces\ProfileServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct(
        protected ProfileServiceInterface $profileService
    ) {}

    public function getDashboardData(int $userId): array
    {
        $user = \App\Models\User::with(['profile', 'playerGameProfiles'])->findOrFail($userId);
        $profile = $user->profile;
        
        $teamIds = TeamMember::where('user_id', $userId)
            ->pluck('team_id')
            ->merge(Team::where('captain_id', $userId)->pluck('id'))
            ->unique();

        $matches  = (int) ($profile->total_matches ?? 0);
        $winRate  = (int) ($profile->win_rate ?? 0);
        $wins     = $matches > 0 ? (int) round(($winRate / 100) * $matches) : 0;

        return [
            'profile'           => $profile,
            'winRate'           => $winRate,
            'trophies'          => (int) ($profile->total_trophies ?? 0),
            'matches'           => $matches,
            'wins'              => $wins,
            'rankName'          => $user->playerGameProfiles?->first()?->rank ?? 'Unranked',
            'activeTournaments' => $this->getActiveTournaments($teamIds),
            'nextMatch'         => $this->getNextMatch($teamIds),
            'recentMatches'     => $this->getRecentMatches($teamIds),
            'pendingMatch'      => $this->getPendingMatch($teamIds),
            'upcomingMatches'   => $this->getUpcomingMatches($teamIds),
            'notifications'     => $this->getNotifications($userId),
            'unreadCount'       => Notification::where('user_id', $userId)->where('is_read', false)->count(),
            'pendingDisputes'   => $this->getPendingDisputesCount($teamIds),
        ];
    }

    private function getActiveTournaments($teamIds)
    {
        return Tournament::whereIn('status', ['ongoing', 'pending'])
            ->whereHas('registrations', fn($q) =>
                $q->whereIn('team_id', $teamIds)->where('status', 'approved')
            )
            ->with(['game', 'registrations'])
            ->latest('start_date')
            ->take(4)
            ->get();
    }

    private function getNextMatch($teamIds)
    {
        return GameMatch::where(fn($q) =>
                $q->whereIn('team_a_id', $teamIds)->orWhereIn('team_b_id', $teamIds)
            )
            ->where('status', 'scheduled')
            ->with(['teamA', 'teamB'])
            ->orderBy('played_at')
            ->first();
    }

    private function getRecentMatches($teamIds)
    {
        return GameMatch::where(fn($q) =>
                $q->whereIn('team_a_id', $teamIds)->orWhereIn('team_b_id', $teamIds)
            )
            ->where('status', 'completed')
            ->with(['teamA', 'teamB'])
            ->orderByDesc('played_at')
            ->take(10)
            ->get();
    }

    private function getPendingMatch($teamIds)
    {
        return GameMatch::where(fn($q) =>
                $q->whereIn('team_a_id', $teamIds)->orWhereIn('team_b_id', $teamIds)
            )
            ->where('status', 'ongoing')
            ->with('ocrAnalysis')
            ->first();
    }

    private function getUpcomingMatches($teamIds)
    {
        return GameMatch::where(fn($q) =>
                $q->whereIn('team_a_id', $teamIds)->orWhereIn('team_b_id', $teamIds)
            )
            ->where('status', 'scheduled')
            ->with(['teamA', 'teamB', 'bracket.tournament.game'])
            ->orderBy('played_at')
            ->take(5)
            ->get();
    }

    private function getNotifications(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->with(['team', 'tournament'])
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
    }

    private function getPendingDisputesCount($teamIds): int
    {
        return Dispute::whereHas('match', fn($q) =>
            $q->whereIn('team_a_id', $teamIds)->orWhereIn('team_b_id', $teamIds)
        )->where('status', 'open')->count();
    }
}
