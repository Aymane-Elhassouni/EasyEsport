<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdminRole = \App\Models\Role::create([
            'name' => 'super admin',
            'description' => 'Global System Administrator with full access'
        ]);

        $adminRole = \App\Models\Role::create([
            'name' => 'admin',
            'description' => 'Administrator with management access'
        ]);

        $captainRole = \App\Models\Role::create([
            'name' => 'captain',
            'description' => 'Team Leader and representative'
        ]);

        $playerRole = \App\Models\Role::create([
            'name' => 'player',
            'description' => 'Professional Esport Player'
        ]);

        User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);
    }
}
