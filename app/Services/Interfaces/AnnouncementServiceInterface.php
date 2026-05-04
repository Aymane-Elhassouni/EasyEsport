<?php

namespace App\Services\Interfaces;

use App\Models\Tournament;
use App\Models\TournamentAnnouncement;
use Illuminate\Support\Collection;

interface AnnouncementServiceInterface
{
    public function createForTournament(Tournament $tournament, int $userId, array $data): TournamentAnnouncement;
    public function update(TournamentAnnouncement $announcement, array $data): TournamentAnnouncement;
    public function delete(TournamentAnnouncement $announcement): void;
    public function getForTournament(int $tournamentId): Collection;
    public function getForTeam(int $teamId): Collection;
}
