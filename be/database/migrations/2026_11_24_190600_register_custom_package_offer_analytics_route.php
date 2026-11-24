<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('routes')->where('route', 'package-offers.analytics')->exists()) {
            return;
        }

        DB::table('routes')->insert([
            'route' => 'package-offers.analytics',
            'permission_name' => 'package.index',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('routes')->where('route', 'package-offers.analytics')->delete();
    }
};
