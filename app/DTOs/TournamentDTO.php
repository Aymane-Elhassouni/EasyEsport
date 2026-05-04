<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class TournamentDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $format,
        public readonly int $maxTeams,
        public readonly int $playersPerTeam,
        public readonly int $qualifiersPerGroup = 0,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?string $gameName = null,
        public readonly ?string $gameLogo = null,
        public readonly ?string $gameSlug = null,
        public readonly int $registeredTeamsCount = 0,
        public readonly int $pendingRegistrationsCount = 0,
        public readonly bool $hasGroupStage = false,
        public readonly string $status = 'Upcoming',
        public readonly Collection $announcements = new Collection(),
        public readonly Collection $registrations = new Collection(),
        public readonly Collection $brackets = new Collection(),
        public readonly Collection $groups = new Collection()
    ) {}

    public static function fromModel(\App\Models\Tournament $tournament): self
    {
        return new self(
            id: $tournament->id,
            name: $tournament->name,
            slug: $tournament->slug,
            format: $tournament->format,
            maxTeams: $tournament->max_teams,
            playersPerTeam: $tournament->players_per_team,
            qualifiersPerGroup: $tournament->qualifiers_per_group ?? 0,
            startDate: $tournament->start_date?->format('Y-m-d'),
            endDate: $tournament->end_date?->format('Y-m-d'),
            gameName: $tournament->game?->name,
            gameLogo: $tournament->game?->logo,
            gameSlug: $tournament->game?->slug,
            registeredTeamsCount: $tournament->registrations_count ?? $tournament->registrations()->count(),
            pendingRegistrationsCount: $tournament->registrations()->where('status', 'pending')->count(),
            hasGroupStage: (bool) $tournament->has_group_stage,
            status: ($tournament->start_date && $tournament->start_date > now()) ? 'Upcoming' : (($tournament->end_date && $tournament->end_date < now()) ? 'Finished' : 'Live'),
            announcements: $tournament->announcements ?? new Collection(),
            registrations: $tournament->registrations ?? new Collection(),
            brackets: $tournament->brackets ?? new Collection(),
            groups: $tournament->groups ?? new Collection()
        );
    }
}
