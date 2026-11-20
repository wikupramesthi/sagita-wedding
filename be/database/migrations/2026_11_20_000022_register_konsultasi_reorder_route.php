<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('routes')->updateOrInsert(
            ['route' => 'konsultasi.reorder'],
            [
                'permission_name' => 'konsultasi.index',
                'status' => 1,
                'description' => 'Akses konsultasi.reorder',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('routes')->where('route', 'konsultasi.reorder')->delete();
    }
};
