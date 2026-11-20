<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    private array $permissions = [
        'konsultasi.bulk',
        'konsultasi.delete',
        'konsultasi.export',
        'konsultasi.import',
        'konsultasi.merge',
    ];

    private array $routePermissions = [
        'konsultasi.bulk' => 'konsultasi.bulk',
        'konsultasi.destroy' => 'konsultasi.delete',
        'konsultasi.export' => 'konsultasi.export',
        'konsultasi.pdf-preview' => 'konsultasi.export',
        'konsultasi.pdf-stream' => 'konsultasi.export',
        'konsultasi.pdf-download' => 'konsultasi.export',
        'konsultasi.duplicates' => 'konsultasi.merge',
        'konsultasi.merge' => 'konsultasi.merge',
        'konsultasi.import' => 'konsultasi.import',
        'konsultasi.saved-filters' => 'konsultasi.index',
        'konsultasi.whatsapp' => 'konsultasi.index',
        'konsultasi.calendar-ics' => 'konsultasi.index',
    ];

    public function up(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($this->routePermissions as $route => $permission) {
            DB::table('routes')->updateOrInsert(
                ['route' => $route],
                [
                    'permission_name' => $permission,
                    'status' => 1,
                    'description' => 'Akses '.$route,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->routePermissions) as $route) {
            DB::table('routes')->where('route', $route)->update([
                'permission_name' => 'konsultasi.index',
                'updated_at' => now(),
            ]);
        }

        Permission::whereIn('name', $this->permissions)->delete();
    }
};
