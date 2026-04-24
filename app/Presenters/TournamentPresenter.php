<?php

namespace App\Presenters;

use App\Models\Tournament;
use Carbon\Carbon;

class TournamentPresenter
{
    public function present(Tournament $tournament)
    {
        $startDate = Carbon::parse($tournament->start_date);
        
        return (object) [
            'id'             => $tournament->id,
            'name'           => $tournament->name,
            'game_name'      => $tournament->game->name ?? 'Mixed',
            'game_logo'      => $tournament->game->logo ?? 'https://api.dicebear.com/7.x/identicon/svg?seed=gaming',
            'format'         => strtoupper($tournament->format),
            'status_label'   => $this->getStatusLabel($tournament->status),
            'status_color'   => $this->getStatusColor($tournament->status),
            'date_human'     => $startDate->format('d M, Y'),
            'time_human'     => $startDate->format('H:i'),
            'participants'   => $tournament->registrations_count ?? 0,
            'max_participants' => $tournament->max_teams,
            'prize_pool'     => '$5,000', // Should be dynamic if table allows
        ];
    }

    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'INSCRIPTIONS OUVERTES',
            'ongoing' => 'EN COURS',
            'completed' => 'TERMINÉ',
            default => 'TBD',
        };
    }

    private function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => 'success',
            'ongoing' => 'primary',
            'completed' => 'gray',
            default => 'gray',
        };
    }
}
