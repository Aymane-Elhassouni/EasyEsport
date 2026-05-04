<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Services\Interfaces\MatchServiceInterface;
use Illuminate\Support\Facades\DB;

class MatchService implements MatchServiceInterface
{
    public function uploadScreenshot(int $matchId, array $data): void
    {
        $match = GameMatch::findOrFail($matchId);
        
        $teamSide = $data['team_side'] ?? 'a'; // 'a' or 'b'
        $file = $data['screenshot'];
        
        $path = $file->store('screenshots', 'public');

        if ($teamSide === 'a') {
            $match->update([
                'team_a_screenshot' => $path,
                'status' => 'validating'
            ]);
        } else {
            $match->update([
                'team_b_screenshot' => $path,
                'status' => 'validating'
            ]);
        }

        // Dispatch OCR Job
        \App\Jobs\AnalyzeMatchScreenshotJob::dispatch($match, $path, $teamSide);
    }

    public function openDispute(int $matchId, int $userId, string $reason): void
    {
        DB::transaction(function () use ($matchId, $userId, $reason) {
            $match = GameMatch::findOrFail($matchId);
            
            $match->disputes()->create([
                'user_id' => $userId,
                'reason'  => $reason,
                'status'  => 'open',
            ]);

            $match->update(['status' => 'disputed']);
        });
    }

    public function settleDispute(int $matchId, int $winnerId): void
    {
        $match = GameMatch::findOrFail($matchId);
        $match->winner_id = $winnerId;
        $match->status = 'validated';
        $match->save();

        // Advance winner using the validation service
        app(\App\Services\MatchValidationService::class)->finalizeMatch($match);
    }
}
