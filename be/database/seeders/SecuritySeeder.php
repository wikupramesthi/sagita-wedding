<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SecuritySeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'menu.keamanan',
            'login-activity.index',
            'login-activity.destroy',
            'login-lockout.index',
            'login-lockout.destroy',
            'audit-log.index',
            'audit-log.destroy',
            'failed-login.index',
            'failed-login.destroy',
        ];

        $menuGroup = MenuGroup::firstOrCreate(
            ['permission_name' => 'menu.keamanan'],
            [
                'name' => 'Keamanan',
                'icon' => 'bx-shield-alt-2',
                'status' => true,
                'position' => 99,
            ]
        );

        $items = [
            ['name' => 'Aktivitas Login', 'route' => 'security.login-activity.index', 'permission_name' => 'login-activity.index', 'position' => 1],
            ['name' => 'Log Audit', 'route' => 'security.audit-log.index', 'permission_name' => 'audit-log.index', 'position' => 2],
            ['name' => 'Login Gagal', 'route' => 'security.failed-login.index', 'permission_name' => 'failed-login.index', 'position' => 3],
            ['name' => 'Blokir Login', 'route' => 'security.login-lockout.index', 'permission_name' => 'login-lockout.index', 'position' => 4],
        ];

        foreach ($items as $item) {
            MenuItem::firstOrCreate(
                ['route' => $item['route'], 'menu_group_id' => $menuGroup->id],
                [
                    'name' => $item['name'],
                    'status' => true,
                    'permission_name' => $item['permission_name'],
                    'position' => $item['position'],
                ]
            );
        }

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($permissions);
        }
    }
}
