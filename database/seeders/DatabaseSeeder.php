<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Role;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\TournamentRegistration;
use App\Models\Bracket;
use App\Models\GameMatch;
use App\Models\TournamentAnnouncement;
use App\Models\Dispute;
use App\Models\Invitation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin']);
        $captainRole    = Role::firstOrCreate(['name' => 'captain']);
        $playerRole     = Role::firstOrCreate(['name' => 'player']);

        // 2. Demo Admins
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'firstname' => 'Super',
                'lastname'  => 'Admin',
                'password'  => Hash::make('password'),
                'role_id'   => $superAdminRole->id,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'firstname' => 'Aymane',
                'lastname'  => 'Admin',
                'password'  => Hash::make('password'),
                'role_id'   => $adminRole->id,
            ]
        );

        // 3. Create 50 Players/Captains (Increased from 30)
        $users = collect();
        for ($i = 1; $i <= 50; $i++) {
            $users->push(User::updateOrCreate(
                ['email' => "player$i@example.com"],
                [
                    'firstname' => "Player$i",
                    'lastname'  => "Lastname$i",
                    'password'  => Hash::make('password'),
                    'role_id'   => $playerRole->id,
                ]
            ));
        }

        // 4. Games
        $valorant = Game::firstOrCreate(['slug' => 'valorant'], ['name' => 'Valorant',           'type' => 'squad', 'players_per_team' => 5]);
        $lol      = Game::firstOrCreate(['slug' => 'league-of-legends'], ['name' => 'League of Legends',  'type' => 'squad', 'players_per_team' => 5]);
        $cs2      = Game::firstOrCreate(['slug' => 'cs2'], ['name' => 'Counter-Strike 2',   'type' => 'squad', 'players_per_team' => 5]);
        $rocket   = Game::firstOrCreate(['slug' => 'rocket-league'], ['name' => 'Rocket League',      'type' => 'squad', 'players_per_team' => 3]);

        // 5. Teams (15 teams total)
        $teams = collect();
        $teamNames = [
            'Fnatic', 'G2 Esports', 'Sentinels', 'LOUD', 'Paper Rex', 
            'DRX', 'T1', 'Cloud9', 'Vitality', 'NAVI',
            'Team Liquid', 'FaZe Clan', 'Optic Gaming', 'NRG', '100 Thieves'
        ];
        
        foreach ($teamNames as $index => $name) {
            $captain = $users[$index];
            $captain->update(['role_id' => $captainRole->id]);
            
            $team = Team::updateOrCreate(
                ['name' => $name],
                [
                    'captain_id' => $captain->id,
                    'logo'       => "https://api.dicebear.com/7.x/identicon/svg?seed=$name",
                    'slug'       => Str::slug($name),
                ]
            );
            
            // Add some members (4 more per team)
            for ($j = 1; $j <= 4; $j++) {
                $memberIndex = 15 + ($index * 2) + $j; // Spread members a bit more
                if (isset($users[$memberIndex])) {
                    $userId = $users[$memberIndex]->id;
                    if (!$team->members()->where('user_id', $userId)->exists()) {
                        $team->members()->create(['user_id' => $userId]);
                    }
                }
            }
            
            $teams->push($team);
        }

        // 6. Tournaments (Extended)
        $t1 = Tournament::updateOrCreate(
            ['name' => 'Valorant Champions Tour'],
            [
                'game_id'          => $valorant->id,
                'format'           => 'single_elimination',
                'max_teams'        => 8,
                'players_per_team' => 5,
                'created_by'       => $superAdmin->id,
                'start_date'       => now()->addDays(5),
                'status'           => 'pending',
                'slug'             => 'valorant-champions-tour',
            ]
        );

        $t2 = Tournament::updateOrCreate(
            ['name' => 'LoL Pro League Spring'],
            [
                'game_id'          => $lol->id,
                'format'           => 'round_robin',
                'max_teams'        => 10,
                'players_per_team' => 5,
                'created_by'       => $superAdmin->id,
                'start_date'       => now()->subDays(2),
                'status'           => 'ongoing',
                'slug'             => 'lol-pro-league-spring',
            ]
        );

        $t_finished = Tournament::updateOrCreate(
            ['name' => 'CS2 Winter Blast'],
            [
                'game_id'          => $cs2->id,
                'format'           => 'single_elimination',
                'max_teams'        => 4,
                'players_per_team' => 5,
                'created_by'       => $admin->id,
                'start_date'       => now()->subMonth(),
                'status'           => 'finished',
                'slug'             => 'cs2-winter-blast',
            ]
        );

        // 7. Registrations
        foreach ($teams->take(10) as $team) {
            TournamentRegistration::updateOrCreate(
                ['tournament_id' => $t2->id, 'team_id' => $team->id],
                ['status' => 'approved', 'registered_at' => now()->subDays(3)]
            );
        }

        foreach ($teams->slice(0, 8) as $index => $team) {
            TournamentRegistration::updateOrCreate(
                ['tournament_id' => $t1->id, 'team_id' => $team->id],
                ['status' => $index < 6 ? 'approved' : 'pending', 'registered_at' => now()]
            );
        }

        foreach ($teams->slice(10, 4) as $team) {
            TournamentRegistration::updateOrCreate(
                ['tournament_id' => $t_finished->id, 'team_id' => $team->id],
                ['status' => 'approved', 'registered_at' => now()->subMonth()]
            );
        }

        // 8. Brackets & Matches
        // Ongoing LoL Round Robin
        $b2 = Bracket::firstOrCreate(['tournament_id' => $t2->id, 'round' => 1]);
        for ($i = 0; $i < 4; $i++) {
            $m = GameMatch::updateOrCreate(
                ['bracket_id' => $b2->id, 'team_a_id' => $teams[$i*2]->id, 'team_b_id' => $teams[$i*2+1]->id],
                [
                    'score_a' => $i < 2 ? rand(1, 2) : 0,
                    'score_b' => $i < 2 ? rand(0, 1) : 0,
                    'status'  => $i < 2 ? 'completed' : ($i == 2 ? 'disputed' : 'scheduled'),
                    'played_at' => $i < 3 ? now()->subDay() : null,
                ]
            );

            if ($m->status === 'disputed') {
                Dispute::updateOrCreate(
                    ['match_id' => $m->id],
                    [
                        'raised_by' => $teams[$i*2]->captain_id,
                        'reason'    => 'Opponent used unauthorized software (suspicious aim).',
                        'status'    => 'open',
                    ]
                );
            }
        }

        // Finished CS2 Tournament
        $bf = Bracket::firstOrCreate(['tournament_id' => $t_finished->id, 'round' => 1]);
        $finalTeams = $teams->slice(10, 4)->values();
        for ($i = 0; $i < 2; $i++) {
            GameMatch::updateOrCreate(
                ['bracket_id' => $bf->id, 'team_a_id' => $finalTeams[$i*2]->id, 'team_b_id' => $finalTeams[$i*2+1]->id],
                ['score_a' => 2, 'score_b' => 0, 'status' => 'completed', 'played_at' => now()->subWeeks(3)]
            );
        }

        // 9. Invitations
        foreach ($users->slice(40, 5) as $invitedPlayer) {
            Invitation::updateOrCreate(
                ['team_id' => $teams[0]->id, 'invited_user_id' => $invitedPlayer->id],
                ['status' => 'pending', 'sent_at' => now()]
            );
        }

        // 10. Announcements
        TournamentAnnouncement::updateOrCreate(
            ['title' => 'Fair Play Reminder'],
            [
                'tournament_id' => $t2->id,
                'created_by'    => $superAdmin->id,
                'body'          => 'We have noticed an increase in disputes. Please remember our fair play guidelines.',
                'status'        => 'public',
            ]
        );

        TournamentAnnouncement::updateOrCreate(
            ['title' => 'Final Rankings Released'],
            [
                'tournament_id' => $t_finished->id,
                'created_by'    => $admin->id,
                'body'          => 'Congratulations to Team Liquid for winning the CS2 Winter Blast!',
                'status'        => 'public',
            ]
        );
    }
}
