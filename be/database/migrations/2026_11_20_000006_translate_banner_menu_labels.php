<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('menu_groups')->where('permission_name', 'banner.index')->update([
            'name' => 'Perpustakaan Media',
            'updated_at' => $now,
        ]);

        DB::table('menu_items')->where('route', 'banner.index')->update([
            'name' => 'Semua Media',
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        $now = now();

        DB::table('menu_groups')->where('permission_name', 'banner.index')->update([
            'name' => 'Media Library',
            'updated_at' => $now,
        ]);

        DB::table('menu_items')->where('route', 'banner.index')->update([
            'name' => 'All Media',
            'updated_at' => $now,
        ]);
    }
};
