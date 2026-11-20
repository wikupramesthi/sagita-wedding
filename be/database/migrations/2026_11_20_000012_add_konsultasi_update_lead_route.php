<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('routes')->where('route', 'konsultasi.update-lead')->exists()) {
            return;
        }

        $now = now();

        DB::table('routes')->insert([
            'id' => (DB::table('routes')->max('id') ?? 0) + 1,
            'route' => 'konsultasi.update-lead',
            'permission_name' => 'konsultasi.index',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('routes')->where('route', 'konsultasi.update-lead')->delete();
    }
};
