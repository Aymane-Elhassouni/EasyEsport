<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Role Admin if it doesn't exist
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator with full access']
        );

        // 2. Create the Admin User
        User::firstOrCreate(
            ['email' => 'adminsystem@example.com'],
            [
                'firstname' => 'Admin',
                'lastname'  => 'System',
                'password'  => Hash::make('password123'),
                'role_id'   => $adminRole->id,
            ]
        );
    }
}
