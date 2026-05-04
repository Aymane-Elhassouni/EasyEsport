<?php

namespace App\DAO\Eloquent;

use App\DAO\Interfaces\AnnouncementDAOInterface;
use App\Models\TournamentAnnouncement;
use Illuminate\Support\Collection;

class EloquentAnnouncementDAO implements AnnouncementDAOInterface
{
    public function create(array $data): TournamentAnnouncement
    {
        if (isset($data['banner_file'])) {
            $data['banner'] = $data['banner_file']->store('announcements/banners', 's3');
            unset($data['banner_file']);
        }
        return TournamentAnnouncement::create($data);
    }

    public function update(TournamentAnnouncement $announcement, array $data): TournamentAnnouncement
    {
        if (isset($data['banner_file']) && $data['banner_file']) {
            $data['banner'] = $data['banner_file']->store('announcements/banners', 's3');
        }
        
        unset($data['banner_file']);
        
        $announcement->update($data);
        return $announcement;
    }

    public function delete(TournamentAnnouncement $announcement): void
    {
        $announcement->delete();
    }

    public function getByTournament(int $tournamentId): Collection
    {
        return TournamentAnnouncement::with(['author'])
            ->where('tournament_id', $tournamentId)
            ->latest()
            ->get();
    }

    public function getForTeam(int $teamId): Collection
    {
        return TournamentAnnouncement::with(['tournament.game', 'author'])
            ->whereHas('tournament.teams', function($query) use ($teamId) {
                $query->where('teams.id', $teamId);
            })
            ->where('status', 'public')
            ->latest()
            ->get();
    }
}
