<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private const PERMISSIONS = [
        ['name' => 'tournament.create',  'display_name' => 'Create Tournament',  'module' => 'tournament'],
        ['name' => 'tournament.manage',  'display_name' => 'Manage Tournaments', 'module' => 'tournament'],
        ['name' => 'match.validate',     'display_name' => 'Validate Match',     'module' => 'match'],
        ['name' => 'team.manage',        'display_name' => 'Manage Team',        'module' => 'team'],
        ['name' => 'player.invite',      'display_name' => 'Invite Player',      'module' => 'team'],
        ['name' => 'profile.edit',       'display_name' => 'Edit Own Profile',   'module' => 'profile'],
    ];

    private const ROLE_PERMISSIONS = [
        'super_admin' => [
            'tournament.create',
            'tournament.manage',
            'match.validate',
            'team.manage',
            'player.invite',
            'profile.edit',
        ],
        'admin' => [
            'tournament.create',
            'tournament.manage',
            'match.validate',
            'profile.edit',
        ],
        'captain' => [
            'team.manage',
            'player.invite',
            'match.validate',
            'profile.edit',
        ],
        'player' => [
            'profile.edit',
        ],
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $data) {
            Permission::firstOrCreate(['name' => $data['name']], $data);
        }

        foreach (self::ROLE_PERMISSIONS as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            $ids = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
            $role->permissions()->syncWithoutDetaching($ids);
        }
    }
}
