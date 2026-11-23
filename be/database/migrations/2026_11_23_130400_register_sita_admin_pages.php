<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        'sita.analytics.view',
        'sita.leads.view',
        'sita.leads.update',
        'sita.synonyms.manage',
    ];

    private array $routes = [
        'sita.analytics.index' => 'sita.analytics.view',
        'sita.leads.index' => 'sita.leads.view',
        'sita.leads.show' => 'sita.leads.view',
        'sita.leads.update' => 'sita.leads.update',
        'sita.leads.destroy' => 'sita.leads.update',
        'sita.synonyms.index' => 'sita.synonyms.manage',
        'sita.synonyms.store' => 'sita.synonyms.manage',
        'sita.synonyms.update' => 'sita.synonyms.manage',
        'sita.synonyms.destroy' => 'sita.synonyms.manage',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        foreach (['super-admin', 'admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $role->givePermissionTo($this->permissions);
            }
        }

        $now = now();

        if (Schema::hasTable('routes')) {
            foreach ($this->routes as $route => $permission) {
                DB::table('routes')->updateOrInsert(
                    ['route' => $route],
                    [
                        'permission_name' => $permission,
                        'status' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        if (Schema::hasTable('menu_groups') && Schema::hasTable('menu_items')) {
            $groupName = 'Sita';
            $groupId = DB::table('menu_groups')->where('name', $groupName)->value('id');

            if (! $groupId) {
                $groupId = DB::table('menu_groups')->insertGetId([
                    'name' => $groupName,
                    'icon' => 'bi-robot',
                    'status' => true,
                    'permission_name' => 'sita.analytics.view',
                    'position' => ((int) DB::table('menu_groups')->max('position')) + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $items = [
                ['Analitik', 'sita.analytics.index', 'sita.analytics.view'],
                ['Prospek', 'sita.leads.index', 'sita.leads.view'],
                ['Sinonim', 'sita.synonyms.index', 'sita.synonyms.manage'],
            ];

            foreach ($items as $position => [$name, $route, $permission]) {
                DB::table('menu_items')->updateOrInsert(
                    ['route' => $route, 'menu_group_id' => $groupId],
                    [
                        'name' => $name,
                        'icon' => null,
                        'status' => true,
                        'permission_name' => $permission,
                        'position' => $position + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->whereIn('route', [
                'sita.analytics.index',
                'sita.leads.index',
                'sita.synonyms.index',
            ])->delete();
        }

        if (Schema::hasTable('menu_groups')) {
            DB::table('menu_groups')->where('permission_name', 'sita.analytics.view')->delete();
        }

        if (Schema::hasTable('routes')) {
            DB::table('routes')->whereIn('route', array_keys($this->routes))->delete();
        }

        if (Schema::hasTable('permissions')) {
            $ids = DB::table('permissions')->whereIn('name', $this->permissions)->pluck('id');

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
            }

            DB::table('permissions')->whereIn('id', $ids)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
