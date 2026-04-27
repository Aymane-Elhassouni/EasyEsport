<?php

namespace App\Services\Interfaces;

use App\Models\Team;

interface TeamServiceInterface
{
    public function createTeam(int $userId, array $data): Team;
    public function getCurrentTeamForUser(int $userId): ?Team;
    public function getManageableTeam(int $teamId): Team;
    public function updateTeam(int $teamId, array $data): bool;
    public function kickMember(int $teamId, int $userId): bool;
    public function handleInvitation(int $invitationId, string $status): bool;
    public function requestJoin(int $teamId, int $userId): void;
    public function invitePlayer(int $teamId, string $emailOrUsername, int $captainId): void;
}
