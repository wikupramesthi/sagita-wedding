<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'package.promo.index',
            'package.promo.store',
            'package.promo.update',
            'package.promo.destroy',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        foreach (['super-admin', 'admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }

        if (Schema::hasTable('routes')) {
            $now = now();
            $routes = [
                ['route' => 'package-promos.index', 'permission_name' => 'package.promo.index'],
                ['route' => 'package-promos.store', 'permission_name' => 'package.promo.store'],
                ['route' => 'package-promos.update', 'permission_name' => 'package.promo.update'],
                ['route' => 'package-promos.destroy', 'permission_name' => 'package.promo.destroy'],
            ];
            foreach ($routes as $route) {
                DB::table('routes')->updateOrInsert(
                    ['route' => $route['route']],
                    ['permission_name' => $route['permission_name'], 'status' => 1, 'created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        if (Schema::hasTable('menu_groups') && Schema::hasTable('menu_items')) {
            $group = DB::table('menu_groups')->where('permission_name', 'package.member')->first();
            if ($group) {
                $exists = DB::table('menu_items')
                    ->where('menu_group_id', $group->id)
                    ->where('route', 'package-promos.index')
                    ->exists();
                if (! $exists) {
                    $position = (int) DB::table('menu_items')->where('menu_group_id', $group->id)->max('position');
                    DB::table('menu_items')->insert([
                        'name' => 'Promo',
                        'icon' => null,
                        'route' => 'package-promos.index',
                        'status' => 1,
                        'permission_name' => 'package.promo.index',
                        'menu_group_id' => $group->id,
                        'position' => $position + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('route', 'package-promos.index')->delete();
        }
        if (Schema::hasTable('routes')) {
            DB::table('routes')->whereIn('route', [
                'package-promos.index', 'package-promos.store', 'package-promos.update', 'package-promos.destroy',
            ])->delete();
        }

        $permissions = [
            'package.promo.index', 'package.promo.store', 'package.promo.update', 'package.promo.destroy',
        ];
        foreach (Role::all() as $role) {
            $role->revokePermissionTo($permissions);
        }
        Permission::whereIn('name', $permissions)->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};