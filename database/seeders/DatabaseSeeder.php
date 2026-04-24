<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Role;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole      = Role::create(['name' => 'admin']);
        $captainRole    = Role::create(['name' => 'captain']);
        $playerRole     = Role::create(['name' => 'player']);

        // 2. Permissions
        $this->call(PermissionSeeder::class);

        // 3. Demo Users
        $superAdmin = User::create([
            'firstname' => 'Super',
            'lastname'  => 'Admin',
            'email'     => 'superadmin@example.com',
            'password'  => Hash::make('password'),
            'role_id'   => $superAdminRole->id,
        ]);

        $admin = User::create([
            'firstname' => 'Demo',
            'lastname'  => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password'),
            'role_id'   => $adminRole->id,
        ]);

        $player = User::create([
            'firstname' => 'Demo',
            'lastname'  => 'Player',
            'email'     => 'demo@example.com',
            'password'  => Hash::make('password'),
            'role_id'   => $playerRole->id,
        ]);

        // 4. Games
        $valorant = Game::create(['name' => 'Valorant',           'slug' => 'valorant']);
        $lol      = Game::create(['name' => 'League of Legends',  'slug' => 'league-of-legends']);
        $cs2      = Game::create(['name' => 'Counter-Strike 2',   'slug' => 'cs2']);

        // 5. Sample Team (captain = admin user)
        $admin->update(['role_id' => $captainRole->id]);
        Team::create([
            'name'       => 'Team Alpha',
            'captain_id' => $admin->id,
        ]);

        // 6. Sample Tournaments
        Tournament::create([
            'name'       => 'Valorant Elite Season 4',
            'game_id'    => $valorant->id,
            'format'     => 'single elimination',
            'max_teams'  => 16,
            'created_by' => $superAdmin->id,
            'start_date' => now()->addDays(2),
            'end_date'   => now()->addDays(5),
            'status'     => 'pending',
        ]);

        Tournament::create([
            'name'       => 'LoL Summer Open',
            'game_id'    => $lol->id,
            'format'     => 'league',
            'max_teams'  => 8,
            'created_by' => $superAdmin->id,
            'start_date' => now()->subDays(10),
            'end_date'   => now()->addDays(2),
            'status'     => 'ongoing',
        ]);
    }
}
