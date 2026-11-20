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
        'orders.bulk',
        'orders.exportCsv',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        if (Schema::hasTable('routes')) {
            foreach ($this->permissions as $route) {
                DB::table('routes')->updateOrInsert(
                    ['route' => $route],
                    [
                        'permission_name' => $route,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($this->permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        if (Schema::hasTable('routes')) {
            DB::table('routes')->whereIn('route', $this->permissions)->delete();
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
