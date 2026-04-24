<?php

namespace App\Services\Interfaces;

use App\Models\Tournament;
use Illuminate\Support\Collection;

interface TournamentServiceInterface
{
    public function getAllActive(): Collection;
    public function getTournamentDetail(int $id): Tournament;
    public function registerTeam(int $tournamentId, int $teamId): bool;
}
