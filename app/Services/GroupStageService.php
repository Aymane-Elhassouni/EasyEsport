<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\GroupTeamStats;

class GroupStageService
{
    /**
     * Update group standings after a match is validated.
     */
    public function updateStandings(GameMatch $match): void
    {
        if (!$match->group_id) return;

        $this->updateTeamStats($match->group_id, $match->team_a_id, $match->score_a, $match->score_b);
        $this->updateTeamStats($match->group_id, $match->team_b_id, $match->score_b, $match->score_a);
    }

    protected function updateTeamStats(int $groupId, int $teamId, int $ownScore, int $oppScore): void
    {
        $stats = GroupTeamStats::firstOrCreate([
            'group_id' => $groupId,
            'team_id'  => $teamId,
        ]);

        $stats->played += 1;
        $stats->score_diff += ($ownScore - $oppScore);

        if ($ownScore > $oppScore) {
            $stats->wins += 1;
            $stats->points += 3;
        } elseif ($ownScore === $oppScore) {
            $stats->draws += 1;
            $stats->points += 1;
        } else {
            $stats->losses += 1;
        }

        $stats->save();
    }
}
