<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $routes = [
        'konsultasi.bulk-undo' => 'konsultasi.bulk',
        'konsultasi.saved-filters.default' => 'konsultasi.index',
    ];

    public function up(): void
    {
        foreach ($this->routes as $route => $permission) {
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
        DB::table('routes')->whereIn('route', array_keys($this->routes))->delete();
    }
};
