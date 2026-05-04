<?php

namespace App\DAO\Interfaces;

use App\Models\TournamentAnnouncement;
use Illuminate\Support\Collection;

interface AnnouncementDAOInterface
{
    public function create(array $data): TournamentAnnouncement;
    public function update(TournamentAnnouncement $announcement, array $data): TournamentAnnouncement;
    public function delete(TournamentAnnouncement $announcement): void;
    public function getByTournament(int $tournamentId): Collection;
    public function getForTeam(int $teamId): Collection;
}
