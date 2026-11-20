<?php

namespace Database\Seeders;

use App\Models\EmailSetting;
use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\Route;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmailSettingSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        EmailSetting::singleton();

        MenuGroup::where('name', 'Pengaturan')->update([
            'name' => 'Pengaturan Sistem',
            'icon' => 'bx-cog',
            'position' => 12,
        ]);

        $permissions = [
            'email-setting.index',
            'email-setting.update',
            'email-setting.test',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $group = MenuGroup::where('name', 'Pengaturan Sistem')->first()
            ?? MenuGroup::firstOrCreate(
                ['permission_name' => 'menu-item.index', 'name' => 'Pengaturan Sistem'],
                ['icon' => 'bx-cog', 'status' => true, 'position' => 12]
            );

        MenuItem::updateOrCreate(
            ['route' => 'email-setting.index', 'menu_group_id' => $group->id],
            [
                'name' => 'Pengaturan Email',
                'status' => true,
                'permission_name' => 'email-setting.index',
                'position' => 1,
            ]
        );

        $routes = [
            'email-setting.index' => 'email-setting.index',
            'email-setting.update' => 'email-setting.update',
            'email-setting.test' => 'email-setting.test',
        ];

        foreach ($routes as $route => $permission) {
            Route::firstOrCreate(
                ['route' => $route],
                ['permission_name' => $permission, 'status' => true]
            );
        }

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
