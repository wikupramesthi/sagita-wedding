<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_groups')
            ->where('permission_name', 'konsultasi.index')
            ->update([
                'name' => 'Konsultasi',
                'updated_at' => now(),
            ]);

        DB::table('menu_items')
            ->where('route', 'konsultasi.index')
            ->update([
                'name' => 'Konsultasi Masuk',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('menu_groups')
            ->where('permission_name', 'konsultasi.index')
            ->update([
                'name' => 'Data Prospek',
                'updated_at' => now(),
            ]);

        DB::table('menu_items')
            ->where('route', 'konsultasi.index')
            ->update([
                'name' => 'Konversi Lead',
                'updated_at' => now(),
            ]);
    }
};
