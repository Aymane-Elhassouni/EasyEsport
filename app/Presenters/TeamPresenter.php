<?php

namespace App\Presenters;

use App\DTOs\TeamDTO;

class TeamPresenter
{
    public function __construct(
        protected TeamDTO $team
    ) {}

    public static function make(\App\Models\Team $team): self
    {
        return new self(TeamDTO::fromModel($team));
    }

    public function present(\App\Models\Team $team): self
    {
        return self::make($team);
    }

    public function getStatusBadgeClass(): string
    {
        return $this->team->membersCount < 6 ? 'text-success' : 'text-danger';
    }

    public function getFormattedMembersCount(): string
    {
        return "{$this->team->membersCount}/6 Members";
    }

    public function canJoin(): bool
    {
        return $this->team->membersCount < 6;
    }

    public function hasPendingJoinRequest(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return \App\Models\Invitation::where('team_id', $this->team->id)
            ->where('invited_user_id', auth()->id())
            ->where('type', 'join_request')
            ->where('status', 'pending')
            ->exists();
    }

    public function getRouteKey()
    {
        return $this->team->slug;
    }

    public function __toString()
    {
        return (string) $this->getRouteKey();
    }

    public function __get($name)
    {
        $camelName = \Illuminate\Support\Str::camel($name);
        
        if (property_exists($this->team, $camelName)) {
            return $this->team->$camelName;
        }

        return $this->team->$name;
    }
}
