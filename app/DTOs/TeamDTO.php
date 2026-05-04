<?php

namespace App\DTOs;

class TeamDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $logo,
        public readonly int $membersCount,
        public readonly ?int $captainId = null,
        public readonly string $logoUrl = '',
        public readonly string $status = 'Recruiting'
    ) {}

    public static function fromModel(\App\Models\Team $team): self
    {
        return new self(
            id: $team->id,
            name: $team->name,
            slug: $team->slug,
            logo: $team->logo,
            membersCount: $team->members_count ?? $team->members()->count(),
            captainId: $team->captain_id,
            logoUrl: $team->logo_url,
            status: ($team->members_count ?? $team->members()->count()) < 6 ? 'Recruiting' : 'Full'
        );
    }
}
