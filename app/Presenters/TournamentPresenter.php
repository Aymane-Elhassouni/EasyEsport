<?php

namespace App\Presenters;

use App\DTOs\TournamentDTO;

class TournamentPresenter
{
    public function __construct(
        protected TournamentDTO $tournament
    ) {}

    public static function make(\App\Models\Tournament $tournament): self
    {
        return new self(TournamentDTO::fromModel($tournament));
    }

    /**
     * Compatibility method for older components
     */
    public function present(\App\Models\Tournament $tournament): self
    {
        return self::make($tournament);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->tournament->status) {
            'Upcoming' => 'bg-info/10 text-info',
            'Live'     => 'bg-success/10 text-success',
            'Finished' => 'bg-gray-500/10 text-gray-500',
            default    => 'bg-primary/10 text-primary',
        };
    }

    public function getFormattedDateRange(): string
    {
        $start = $this->tournament->startDate ?? 'TBD';
        $end = $this->tournament->endDate ?? 'TBD';
        return "{$start} - {$end}";
    }

    public function getRegistrationProgress(): float
    {
        if ($this->tournament->maxTeams <= 0) return 0;
        return ($this->tournament->registeredTeamsCount / $this->tournament->maxTeams) * 100;
    }

    public function getRouteKey()
    {
        return $this->tournament->slug;
    }

    public function __toString()
    {
        return (string) $this->getRouteKey();
    }

    public function __get($name)
    {
        // Defensive checks and mappings for legacy view/component code
        if ($name === 'game') {
            return (object)[
                'name' => $this->tournament->gameName,
                'slug' => $this->tournament->gameSlug
            ];
        }

        if ($name === 'game_name') return $this->tournament->gameName;
        
        if ($name === 'status_color') {
            return match($this->tournament->status) {
                'Upcoming' => 'info',
                'Live'     => 'success',
                'Finished' => 'gray-500',
                default    => 'primary',
            };
        }

        if ($name === 'status_label') return $this->tournament->status;
        if ($name === 'participants') return $this->tournament->registeredTeamsCount;
        if ($name === 'registrations_count') return $this->tournament->registeredTeamsCount;
        if ($name === 'max_participants') return $this->tournament->maxTeams;
        if ($name === 'max_teams') return $this->tournament->maxTeams;
        if ($name === 'date_human') return $this->getFormattedDateRange();
        if ($name === 'prize_pool') return 'TBD';
        if ($name === 'format_label') {
             return match($this->tournament->format) {
                'single_elimination' => 'Single Elimination',
                'double_elimination' => 'Double Elimination',
                'league'             => 'League',
                'round_robin'        => 'Round Robin',
                default              => str_replace('_', ' ', ucwords($this->tournament->format, '_')),
            };
        }

        $camelName = \Illuminate\Support\Str::camel($name);
        
        if (property_exists($this->tournament, $camelName)) {
            return $this->tournament->$camelName;
        }

        return $this->tournament->$name;
    }
}
