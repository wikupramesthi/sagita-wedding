<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $routes = [
        'konsultasi.import' => 'konsultasi.import',
        'konsultasi.import.template' => 'konsultasi.import',
        'konsultasi.import.preview' => 'konsultasi.import',
        'konsultasi.import.commit' => 'konsultasi.import',
        'konsultasi.saved-filters' => 'konsultasi.index',
        'konsultasi.saved-filters.store' => 'konsultasi.index',
        'konsultasi.saved-filters.apply' => 'konsultasi.index',
        'konsultasi.saved-filters.destroy' => 'konsultasi.index',
        'konsultasi.whatsapp' => 'konsultasi.index',
        'konsultasi.calendar-ics' => 'konsultasi.index',
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
