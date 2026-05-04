<?php

namespace App\DTOs;

class OcrAnalysisDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $playerName,
        public readonly ?string $rank,
        public readonly ?string $stats,
        public readonly float $confidence,
        public readonly string $scannedAt
    ) {}

    public static function fromModel(\App\Models\OcrAnalysis $analysis): self
    {
        return new self(
            id: $analysis->id,
            playerName: $analysis->player_name,
            rank: $analysis->rank,
            stats: $analysis->stats,
            confidence: (float) $analysis->confidence,
            scannedAt: $analysis->created_at->toDateTimeString()
        );
    }
}
