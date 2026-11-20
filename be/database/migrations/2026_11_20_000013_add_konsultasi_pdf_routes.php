<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ([
            'konsultasi.pdf-preview',
            'konsultasi.pdf-stream',
            'konsultasi.pdf-download',
        ] as $name) {
            if (DB::table('routes')->where('route', $name)->exists()) {
                continue;
            }

            DB::table('routes')->insert([
                'id' => (DB::table('routes')->max('id') ?? 0) + 1,
                'route' => $name,
                'permission_name' => 'konsultasi.index',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('routes')->whereIn('route', [
            'konsultasi.pdf-preview',
            'konsultasi.pdf-stream',
            'konsultasi.pdf-download',
        ])->delete();
    }
};
