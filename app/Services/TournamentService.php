<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Services\Interfaces\TournamentServiceInterface;
use Illuminate\Support\Collection;

class TournamentService implements TournamentServiceInterface
{
    public function getAllActive(): Collection
    {
        return Tournament::with(['game'])
            ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
            ->whereIn('status', ['pending', 'ongoing'])
            ->latest('start_date')
            ->get();
    }

    public function getTournamentDetail(int $id): Tournament
    {
        return Tournament::with([
            'game',
            'registrations.team',
            'brackets.matches.teamA',
            'brackets.matches.teamB'
        ])
        ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
        ->findOrFail($id);
    }

    public function registerTeam(int $tournamentId, int $teamId): bool
    {
        // 1. Basic validation
        $tournament = Tournament::findOrFail($tournamentId);
        
        // Check if already registered
        $exists = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->exists();
            
        if ($exists) {
            return false;
        }

        // 2. Create registration (pending approval)
        return TournamentRegistration::create([
            'tournament_id' => $tournamentId,
            'team_id'       => $teamId,
            'status'        => 'pending',
            'registered_at' => now(),
        ]) instanceof TournamentRegistration;
    }
}
