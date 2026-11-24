<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ROUTES = [
        'orders.documents.accept',
        'orders.feedback.store',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('routes')) {
            return;
        }

        foreach (self::ROUTES as $route) {
            DB::table('routes')->updateOrInsert(
                ['route' => $route],
                [
                    'permission_name' => 'orders.show',
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('routes')) {
            DB::table('routes')->whereIn('route', self::ROUTES)->delete();
        }
    }
};
