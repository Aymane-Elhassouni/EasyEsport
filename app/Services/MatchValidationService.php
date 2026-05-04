<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Team;
use App\Services\TournamentService;
use App\Services\GroupStageService;
use Illuminate\Support\Facades\Log;

class MatchValidationService
{
    public function __construct(
        protected TournamentService $tournamentService,
        protected GroupStageService $groupStageService
    ) {}
    /**
     * Process OCR results and determine if a winner can be automatically validated.
     */
    public function validateMatchResults(GameMatch $match): void
    {
        // 1. Check if we have both screenshots (or if one is high enough confidence)
        // For simplicity, let's look at the ocr_confidence and current status
        
        if ($match->status === 'dispute') {
            return; // Already in dispute, skip auto-validation
        }

        // Logic: If confidence is very high (> 85%), we can trust it.
        // If we have both screenshots and they agree, even better.
        
        if ($match->ocr_confidence > 85) {
            $this->finalizeMatch($match);
        } elseif ($match->ocr_confidence < 60 && $match->team_a_screenshot && $match->team_b_screenshot) {
            // Low confidence on both means dispute
            $match->update(['status' => 'dispute']);
            Log::warning("Match #{$match->id} moved to dispute due to low OCR confidence.");

            // Notify Admin (optional) or Teams that manual review is needed
            app(\App\Services\NotificationService::class)->notifyTeam($match->team_a_id, "Match Under Review", "OCR confidence was low. An admin will verify the results shortly.");
            app(\App\Services\NotificationService::class)->notifyTeam($match->team_b_id, "Match Under Review", "OCR confidence was low. An admin will verify the results shortly.");
        }
    }

    /**
     * Finalize the match, set the winner, and advance them in the bracket.
     */
    public function finalizeMatch(GameMatch $match): void
    {
        // Determine winner based on scores
        if ($match->score_a > $match->score_b) {
            $match->winner_id = $match->team_a_id;
        } elseif ($match->score_b > $match->score_a) {
            $match->winner_id = $match->team_b_id;
        } else {
            $match->update(['status' => 'dispute']);
            return;
        }

        $match->status = 'validated';
        $match->save();
        $match->load('winner');

        // Update group standings if applicable
        $this->groupStageService->updateStandings($match);

        Log::info("Match #{$match->id} validated. Winner: Team #{$match->winner_id}");

        // Notify winner about successful validation
        if ($match->winner && $match->winner->captain_id) {
            app(\App\Services\NotificationService::class)->send(
                $match->winner->captain_id, 
                "Victory Validated!", 
                "Your match result has been confirmed. Congratulations!", 
                ['icon' => '✅']
            );
        }

        // Advance winner to the next match using TournamentService (which also sends notifications)
        $this->tournamentService->advanceWinner($match);

        // Check if this was the last group match
        $tournament = $match->bracket->tournament;
        if ($match->group_id && $this->tournamentService->isGroupStageCompleted($tournament)) {
            // Notify Admin
            app(\App\Services\NotificationService::class)->send(
                $tournament->created_by,
                "🏁 Group Stage Finished!",
                "All matches in the group stage for '{$tournament->name}' are completed. You can now generate the knockout bracket.",
                ['icon' => '🏆', 'action_url' => route('tournaments.show', $tournament->slug)]
            );
        }
    }
}
