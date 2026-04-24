<?php

namespace App\Presenters;

use App\DTO\ProfileViewData;
use App\Models\ProfilePlayer;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProfilePresenter
{
    // Status keys only — CSS classes belong in Blade views
    private const STATUS_LABELS = [
        'free'    => 'Free Agent',
        'in_team' => 'In Team',
    ];

    public function present(User $user, ProfilePlayer $profile, ?object $currentTeam): ProfileViewData
    {
        $matches     = (int) ($profile->total_matches ?? 0);
        $winRate     = (int) round($profile->win_rate ?? 0);
        $wins        = $matches > 0 ? (int) round(($winRate / 100) * $matches) : 0;
        $statusKey   = strtolower($profile->status ?? 'free');
        $displayName = $this->resolveDisplayName($user);
        $rankName    = $user->playerGameProfiles?->first()?->rank ?? $this->resolveRank($winRate);

        return new ProfileViewData(
            userId:      $user->id,
            displayName: $displayName,
            handle:      Str::slug($displayName, '_'),
            initials:    $this->resolveInitials($user),
            role:        $user->role?->name ?? 'Player',
            memberSince: $user->created_at?->format('M Y') ?? '',
            matches:     $matches,
            winRate:     $winRate,
            trophies:    (int) ($profile->total_trophies ?? 0),
            wins:        $wins,
            losses:      max($matches - $wins, 0),
            status:      $statusKey,
            statusLabel: self::STATUS_LABELS[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey)),
            statusClass: $statusKey, // pass key only, resolve CSS in Blade
            rankName:    $rankName,
            focusLabel:  $this->resolveFocusLabel($matches, $winRate),
            teamName:    $currentTeam?->name ?? 'No Active Team',
            teamMembers: (int) ($currentTeam?->member_count ?? 0),
            joinedAt:    !empty($currentTeam?->joined_at) ? Carbon::parse($currentTeam->joined_at) : null,
            currentTeam: $currentTeam,
        );
    }

    private function resolveDisplayName(User $user): string
    {
        $full = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
        return $full !== '' ? $full : ($user->name ?? 'Player');
    }

    private function resolveInitials(User $user): string
    {
        $first = Str::substr($user->firstname ?? '', 0, 1);
        $last  = Str::substr($user->lastname ?? '', 0, 1);
        $initials = strtoupper($first . $last);
        return $initials !== '' ? $initials : 'PL';
    }

    private function resolveRank(int $winRate): string
    {
        return match (true) {
            $winRate >= 85 => 'Champion',
            $winRate >= 75 => 'Diamond',
            $winRate >= 60 => 'Elite',
            $winRate >= 45 => 'Challenger',
            default        => 'Rookie',
        };
    }

    private function resolveFocusLabel(int $matches, int $winRate): string
    {
        return match (true) {
            $matches >= 100 && $winRate >= 70 => 'Playoff Ready',
            $matches >= 40                    => 'Season Locked',
            $matches > 0                      => 'Grinding Queue',
            default                           => 'Awaiting Debut',
        };
    }
}
