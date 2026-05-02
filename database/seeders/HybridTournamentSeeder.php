<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Game;
use App\Services\TournamentService;
use App\Services\MatchValidationService;
use Illuminate\Database\Seeder;

class HybridTournamentSeeder extends Seeder
{
    public function run(TournamentService $service): void
    {
        // 0. Ensure a game exists
        $game = Game::first() ?? Game::create([
            'name' => 'Valorant',
            'slug' => 'valorant',
            'logo' => 'https://api.dicebear.com/7.x/identicon/svg?seed=valorant',
            'type' => 'squad'
        ]);

        // Cleanup existing test data if needed
        Tournament::where('name', 'Valorant Pro League - Season 1')->delete();

        // 1. Create 16 Users (Captains)
        $users = User::factory()->count(16)->create();

        // 2. Create 16 Teams with these Captains
        $teams = collect();
        foreach ($users as $user) {
            $teams->push(Team::create([
                'name' => 'Team ' . $user->firstname . ' ' . $user->lastname,
                'captain_id' => $user->id,
            ]));
        }

        // 3. Create Hybrid Tournament
        $tournament = Tournament::create([
            'name' => 'Valorant Pro League - Season 1',
            'game_id' => $game->id,
            'slug' => 'valorant-pro-league-s1',
            'format' => 'hybrid',
            'max_teams' => 16,
            'players_per_team' => 5,
            'has_group_stage' => true,
            'teams_per_group' => 4,
            'qualifiers_per_group' => 2,
            'status' => 'pending',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
            'created_by' => User::first()->id ?? User::factory()->create()->id,
        ]);

        // 3. Register & Approve Teams
        foreach ($teams as $team) {
            $tournament->registrations()->create([
                'team_id' => $team->id,
                'status' => 'approved',
                'registered_at' => now(),
            ]);
        }

        // 4. Launch the Machine!
        $service->launchTournament($tournament->id);
        $this->command->info('Tournament Hybrid launched with 16 teams and 4 groups!');

        // 5. Simulate Group Stage Results
        $matches = \App\Models\GameMatch::where('tournament_id', $tournament->id)
            ->whereNotNull('group_id')
            ->get();

        foreach ($matches as $match) {
            // Random scores
            $scoreA = rand(10, 13);
            $scoreB = rand(0, 9);
            
            $match->update([
                'score_a' => $scoreA,
                'score_b' => $scoreB,
                'winner_id' => $scoreA > $scoreB ? $match->team_a_id : $match->team_b_id,
                'status' => 'validated',
                'ocr_confidence' => 100
            ]);

            // Trigger standings update
            app(\App\Services\GroupStageService::class)->updateStandings($match);
        }

        $this->command->info('Group stage results simulated!');

        // 6. Finalize Groups & Generate Knockout (The moment of truth)
        $service->qualifyToKnockout($tournament);

        $this->command->info('Knockout bracket generated successfully!');
    }
}
