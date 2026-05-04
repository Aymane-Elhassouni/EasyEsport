<?php

namespace App\Services;

use App\DAO\Interfaces\AnnouncementDAOInterface;
use App\Models\Tournament;
use App\Models\TournamentAnnouncement;
use App\Services\Interfaces\AnnouncementServiceInterface;
use Illuminate\Support\Collection;

class AnnouncementService implements AnnouncementServiceInterface
{
    public function __construct(
        protected AnnouncementDAOInterface $dao
    ) {}

    public function createForTournament(Tournament $tournament, int $userId, array $data): TournamentAnnouncement
    {
        return $this->dao->create([
            'tournament_id' => $tournament->id,
            'created_by'    => $userId,
            'title'         => $data['title'],
            'body'          => $data['body'],
            'status'        => $data['status'],
            'banner_file'   => $data['banner'] ?? null,
        ]);
    }

    public function update(TournamentAnnouncement $announcement, array $data): TournamentAnnouncement
    {
        return $this->dao->update($announcement, [
            'title'       => $data['title'],
            'body'        => $data['body'],
            'status'      => $data['status'],
            'banner_file' => $data['banner'] ?? null,
        ]);
    }

    public function delete(TournamentAnnouncement $announcement): void
    {
        $this->dao->delete($announcement);
    }

    public function getForTournament(int $tournamentId): Collection
    {
        return $this->dao->getByTournament($tournamentId);
    }

    public function getForTeam(int $teamId): Collection
    {
        return $this->dao->getForTeam($teamId);
    }
}
