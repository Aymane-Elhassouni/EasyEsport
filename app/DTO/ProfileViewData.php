<?php

namespace App\DTO;

use Carbon\Carbon;

final class ProfileViewData
{
    public function __construct(
        public readonly int     $userId,
        public readonly string  $displayName,
        public readonly string  $handle,
        public readonly string  $initials,
        public readonly string  $role,
        public readonly string  $memberSince,
        public readonly int     $matches,
        public readonly int     $winRate,
        public readonly int     $trophies,
        public readonly int     $wins,
        public readonly int     $losses,
        public readonly string  $status,
        public readonly string  $statusLabel,
        public readonly string  $statusClass,
        public readonly string  $rankName,
        public readonly string  $focusLabel,
        public readonly ?string $teamName,
        public readonly int     $teamMembers,
        public readonly ?Carbon $joinedAt,
        public readonly ?object $currentTeam,
    ) {}
}
