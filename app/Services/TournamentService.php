<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentAnnouncement;
use App\Models\TournamentRegistration;
use App\Models\Bracket;
use App\Models\GameMatch;
use App\Services\Interfaces\TournamentServiceInterface;
use App\Services\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\DAOs\TournamentDAO;

class TournamentService implements TournamentServiceInterface
{
    public function __construct(
        protected NotificationService $notificationService,
        protected TournamentDAO $tournamentDao
    ) {}

    public function getAllActive(): Collection
    {
        return $this->tournamentDao->getAllActive()->map(function ($tournament) {
            return \App\DTOs\TournamentDTO::fromModel($tournament);
        });
    }

    public function createTournament(array $data): Tournament
    {
        $game = \App\Models\Game::findOrFail($data['game_id']);
        
        // Squad games validation
        if ($game->type === 'squad' && ($data['players_per_team'] ?? 0) <= 1) {
            throw new \Exception('Squad games must have more than 1 player per team.');
        }

        $data['created_by'] = auth()->id();
        $data['status'] = 'pending';

        return Tournament::create($data);
    }

    public function getUpcomingMatches(int $tournamentId)
    {
        return GameMatch::where('tournament_id', $tournamentId)
            ->where('status', 'pending')
            ->whereNotNull('team_a_id')
            ->whereNotNull('team_b_id')
            ->with(['teamA', 'teamB'])
            ->orderBy('scheduled_at', 'asc')
            ->get();
    }

    public function getTournamentsWithAnnouncements(\App\Models\User $user): Collection
    {
        $query = Tournament::with(['announcements.author', 'game']);

        if ($user->hasRole('admin')) {
            $query->where('created_by', $user->id);
        }

        return $query->latest()->get();
    }

    public function getTournamentDetail(string $slug): \App\DTOs\TournamentDTO
    {
        $tournament = $this->tournamentDao->findBySlug($slug);
        return \App\DTOs\TournamentDTO::fromModel($tournament);
    }

    public function getAnnouncementsForTeam(int $teamId)
    {
        return TournamentAnnouncement::with(['tournament.game', 'author'])
            ->whereHas('tournament.registrations', fn($q) => $q->where('team_id', $teamId))
            ->latest()
            ->get();
    }

    public function createAnnouncement(int $tournamentId, int $userId, array $data): void
    {
        TournamentAnnouncement::create([
            'tournament_id' => $tournamentId,
            'created_by'    => $userId,
            'title'         => $data['title'],
            'body'          => $data['body'],
        ]);
    }

    public function deleteAnnouncement(TournamentAnnouncement $announcement): void
    {
        $announcement->delete();
    }

    public function getTeamApplications(int $teamId)
    {
        return TournamentRegistration::with(['tournament.game'])
            ->where('team_id', $teamId)
            ->latest()
            ->get();
    }

    public function getAllRegistrations()
    {
        $user = auth()->user();
        $query = TournamentRegistration::with(['tournament', 'team.captain', 'team.members']);

        if ($user && $user->hasRole('admin')) {
            $query->whereHas('tournament', fn($q) => $q->where('created_by', $user->id));
        }

        return $query->latest()->get();
    }

    public function updateRegistrationStatus(int $registrationId, string $status): void
    {
        TournamentRegistration::findOrFail($registrationId)->update(['status' => $status]);
    }

    public function registerTeam(int $tournamentId, int $teamId): bool
    {
        // 1. Basic validation
        $tournament = Tournament::findOrFail($tournamentId);
        
        // Check if already registered
        $exists = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->exists();
            
        if ($exists) {
            return false;
        }

        // 2. Create registration (pending approval)
        return TournamentRegistration::create([
            'tournament_id' => $tournamentId,
            'team_id'       => $teamId,
            'status'        => 'pending',
            'registered_at' => now(),
        ]) instanceof TournamentRegistration;
    }

    public function launchTournament(int $tournamentId): void
    {
        DB::transaction(function () use ($tournamentId) {
            $tournament = Tournament::with(['registrations' => fn($q) => $q->where('status', 'approved')])
                ->findOrFail($tournamentId);

            if ($tournament->status !== 'pending') {
                throw new \Exception("Tournament is already " . $tournament->status);
            }

            $teams = $tournament->registrations->pluck('team_id')->toArray();
            if (count($teams) < 2) {
                throw new \Exception("At least 2 approved teams are required to launch.");
            }

            // Randomize seeding
            shuffle($teams);

            // Update status
            $tournament->update(['status' => 'ongoing']);

            // Generate Matches
            if ($tournament->has_group_stage) {
                $this->generateGroupStage($tournament, $teams);
            } elseif ($tournament->format === 'round_robin' || $tournament->format === 'league') {
                $this->generateRoundRobinMatches($tournament, $teams);
            } else {
                // Default to Single Elimination / Full Bracket
                $this->generateEliminationMatches($tournament, $teams);
            }

            // Auto-create Announcement for Round 1
            $round1 = $tournament->brackets()->where('round', 1)->first();
            $matches = GameMatch::with(['teamA', 'teamB'])
                ->where('bracket_id', $round1->id)
                ->get();

            $matchList = $matches->map(function ($m) {
                $a = $m->teamA->name ?? 'TBD';
                $b = $m->teamB->name ?? 'TBD';
                return "• {$a} vs {$b}";
            })->implode("\n");

            TournamentAnnouncement::create([
                'tournament_id' => $tournamentId,
                'created_by'    => $tournament->created_by,
                'title'         => 'Tournament Launched - First Matches Revealed!',
                'body'          => "The tournament has officially started! Here are the matches for the first round:\n\n" . $matchList,
                'status'        => 'public'
            ]);

            // Notify all participating team captains
            foreach ($teams as $teamId) {
                $this->notificationService->notifyTeam($teamId, 
                    "🏆 Tournament Started!", 
                    "The tournament '{$tournament->name}' has officially begun. Check your upcoming matches!",
                    true,
                    ['tournament_id' => $tournament->id, 'icon' => '🚀', 'action_url' => route('tournaments.show', $tournament->slug)]
                );
            }
        });
    }

    public function isGroupStageCompleted(Tournament $tournament): bool
    {
        if (!$tournament->has_group_stage) return true;

        $totalMatches = GameMatch::where('tournament_id', $tournament->id)
            ->whereNotNull('group_id')
            ->count();

        $completedMatches = GameMatch::where('tournament_id', $tournament->id)
            ->whereNotNull('group_id')
            ->where('status', 'validated')
            ->count();

        return $totalMatches > 0 && $totalMatches === $completedMatches;
    }

    protected function generateRoundRobinMatches(Tournament $tournament, array $teams, ?int $groupId = null): void
    {
        $bracket = Bracket::create(['tournament_id' => $tournament->id, 'round' => 1]);
        $count = count($teams);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                GameMatch::create([
                    'bracket_id'    => $bracket->id,
                    'tournament_id' => $tournament->id,
                    'group_id'      => $groupId,
                    'team_a_id'     => $teams[$i],
                    'team_b_id'     => $teams[$j],
                    'status'        => 'pending',
                ]);
            }
        }
    }

    protected function generateEliminationMatches(Tournament $tournament, array $teams): void
    {
        $count = count($teams);
        $numRounds = ceil(log($count, 2));
        
        $brackets = [];
        for ($r = 1; $r <= $numRounds; $r++) {
            $brackets[$r] = Bracket::create([
                'tournament_id' => $tournament->id,
                'round'         => $r,
            ]);
        }
        
        $matchesPerRound = [];
        
        // 1. Create ALL matches in reverse order (Final to Round 1) to link next_match_id
        for ($r = $numRounds; $r >= 1; $r--) {
            $matchesInRound = pow(2, $numRounds - $r);
            for ($m = 1; $m <= $matchesInRound; $m++) {
                $nextMatch = ($r < $numRounds) ? $matchesPerRound[$r + 1][ceil($m / 2)] : null;
                $position = ($m % 2 != 0) ? 'top' : 'bottom';
                
                $match = GameMatch::create([
                    'bracket_id'    => $brackets[$r]->id,
                    'tournament_id' => $tournament->id,
                    'next_match_id' => $nextMatch?->id,
                    'position'      => $nextMatch ? $position : null,
                    'status'        => 'pending',
                ]);
                $matchesPerRound[$r][$m] = $match;
            }
        }
        
        // 2. Populate Round 1 matches with teams
        $round1Matches = $matchesPerRound[1];
        
        $teamIndex = 0;
        foreach ($round1Matches as $match) {
            if ($teamIndex < $count) {
                $match->team_a_id = $teams[$teamIndex++];
            }
            if ($teamIndex < $count) {
                $match->team_b_id = $teams[$teamIndex++];
            }
            
            // Handle Bye (If only one team in match)
            if ($match->team_a_id && !$match->team_b_id) {
                $match->status = 'completed';
                $match->winner_id = $match->team_a_id;
                $match->score_a = 1;
                $match->score_b = 0;
            }
            
            $match->save();
            
            // Auto-advance if completed
            if ($match->status === 'completed' && $match->next_match_id) {
                $this->advanceWinner($match);
            }
        }
    }

    public function advanceWinner(GameMatch $match): void
    {
        if (!$match->winner_id || !$match->next_match_id) return;
        
        $nextMatch = $match->nextMatch;
        if ($match->position === 'top') {
            $nextMatch->team_a_id = $match->winner_id;
        } else {
            $nextMatch->team_b_id = $match->winner_id;
        }
        
        $nextMatch->save();

        // Notify winner captain about advancement
        $this->notificationService->notifyTeam($match->winner_id, 
            "🔥 Team Advanced!", 
            "Congratulations! Your team has advanced to the next round in '{$match->bracket->tournament->name}'.",
            true,
            ['tournament_id' => $match->bracket->tournament_id, 'icon' => '🔥', 'action_url' => route('tournaments.show', $match->bracket->tournament->slug)]
        );
    }

    protected function generateGroupStage(Tournament $tournament, array $teams): void
    {
        shuffle($teams);
        $chunks = array_chunk($teams, $tournament->teams_per_group);

        foreach ($chunks as $index => $groupTeamIds) {
            $group = \App\Models\TournamentGroup::create([
                'tournament_id' => $tournament->id,
                'name'          => 'Group ' . chr(65 + $index), // A, B, C...
            ]);

            // Initialize stats for each team in the group
            foreach ($groupTeamIds as $teamId) {
                \App\Models\GroupTeamStats::create([
                    'group_id' => $group->id,
                    'team_id'  => $teamId,
                ]);
            }

            // Create Round Robin matches inside the group
            $this->generateRoundRobinMatches($tournament, $groupTeamIds, $group->id);
        }
    }

    public function qualifyToKnockout(Tournament $tournament): void
    {
        // 1. Get qualifiers from each group (Top 2 by default)
        $allQualifiers = [];
        $tournament->load('groups.teamStats');

        foreach ($tournament->groups as $group) {
            $winners = $group->teamStats()
                ->orderByDesc('points')
                ->orderByDesc('score_diff')
                ->take($tournament->qualifiers_per_group)
                ->pluck('team_id')
                ->toArray();
            
            $allQualifiers[$group->name] = $winners;
        }

        // 2. Cross-Seeding Logic (A1 vs B2, B1 vs A2...)
        $finalSeeding = [];
        $groupNames = array_keys($allQualifiers);
        
        for ($i = 0; $i < count($groupNames); $i += 2) {
            $group1 = $groupNames[$i];
            $group2 = $groupNames[$i + 1] ?? null;

            if ($group2) {
                // A1 vs B2
                $finalSeeding[] = $allQualifiers[$group1][0] ?? null; // 1st A
                $finalSeeding[] = $allQualifiers[$group2][1] ?? null; // 2nd B
                
                // B1 vs A2
                $finalSeeding[] = $allQualifiers[$group2][0] ?? null; // 1st B
                $finalSeeding[] = $allQualifiers[$group1][1] ?? null; // 2nd A
            } else {
                // Only one group left? (Odd number of groups)
                // Just take the qualifiers as they are
                $finalSeeding = array_merge($finalSeeding, $allQualifiers[$group1]);
            }
        }

        // 3. Generate Elimination Matches for the Knockout Stage
        $this->generateEliminationMatches($tournament, array_filter($finalSeeding));
        
        // Mark that the knockout stage has started
        $tournament->update(['status' => 'ongoing']);
    }

    public function getTeamRegistrationStatus(int $tournamentId, int $teamId): ?string
    {
        return TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->value('status');
    }
}
