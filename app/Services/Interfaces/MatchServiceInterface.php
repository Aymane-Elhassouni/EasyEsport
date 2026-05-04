<?php

namespace App\Services\Interfaces;

interface MatchServiceInterface
{
    public function uploadScreenshot(int $matchId, array $data): void;
    public function openDispute(int $matchId, int $userId, string $reason): void;
    public function settleDispute(int $matchId, int $winnerId): void;
}
