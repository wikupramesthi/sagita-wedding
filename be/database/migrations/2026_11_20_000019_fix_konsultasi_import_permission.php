<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        'konsultasi.bulk',
        'konsultasi.delete',
        'konsultasi.export',
        'konsultasi.import',
        'konsultasi.merge',
    ];

    public function up(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        DB::table('routes')->updateOrInsert(
            ['route' => 'konsultasi.import'],
            [
                'permission_name' => 'konsultasi.import',
                'status' => 1,
                'description' => 'Akses konsultasi.import',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Role::whereHas('permissions', fn ($query) => $query->where('name', 'konsultasi.index'))
            ->get()
            ->each(fn (Role $role) => $role->givePermissionTo($this->permissions));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::table('routes')->where('route', 'konsultasi.import')->update([
            'permission_name' => 'konsultasi.export',
            'updated_at' => now(),
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
