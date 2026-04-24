<?php

namespace App\Presenters;

use App\Models\Team;

class TeamPresenter
{
    /**
     * Format team data for the management view.
     */
    public function present(Team $team): object
    {
        // Simple mock calculations for now, should be replaced with real Match queries later
        $winRate = 75.5; // Example
        $earnings = 12500;
        $elo = 2840;

        return (object) [
            'id'            => $team->id,
            'name'          => $team->name,
            'logo'          => $team->logo,
            'captain_id'    => $team->captain_id,
            'winRate'       => number_format($winRate, 1) . '%',
            'earnings'      => '$' . number_format($earnings),
            'elo'           => number_format($elo),
            'rosterCount'   => $team->members->count(),
            'maxSlots'      => 6, // Esport standard
        ];
    }
}
