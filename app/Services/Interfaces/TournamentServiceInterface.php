<?php

namespace App\Services\Interfaces;

use App\Models\Tournament;
use Illuminate\Support\Collection;

interface TournamentServiceInterface
{
    public function getAllActive(): Collection;
    public function getAllRegistrations();
    public function getTeamApplications(int $teamId);
    public function getTournamentDetail(string $slug): \App\DTOs\TournamentDTO;
    public function registerTeam(int $tournamentId, int $teamId): bool;
    public function updateRegistrationStatus(int $registrationId, string $status): void;
    public function launchTournament(int $tournamentId): void;
    public function createAnnouncement(int $tournamentId, int $userId, array $data): void;
    public function deleteAnnouncement(\App\Models\TournamentAnnouncement $announcement): void;
    public function getAnnouncementsForTeam(int $teamId);
    public function createTournament(array $data): Tournament;
    public function getUpcomingMatches(int $tournamentId);
    public function getTournamentsWithAnnouncements(\App\Models\User $user): Collection;
    public function getTeamRegistrationStatus(int $tournamentId, int $teamId): ?string;
}
