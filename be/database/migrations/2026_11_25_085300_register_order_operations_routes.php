<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ROUTES = [
        'orders.calendar' => 'orders.index',
        'orders.cancellations.index' => 'orders.index',
        'orders.cancellations.store' => 'orders.store',
        'orders.cancellations.review' => 'orders.index',
        'orders.cancellations.refund' => 'orders.index',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('routes')) {
            return;
        }

        foreach (self::ROUTES as $route => $permission) {
            DB::table('routes')->updateOrInsert(
                ['route' => $route],
                [
                    'permission_name' => $permission,
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
            DB::table('routes')->whereIn('route', array_keys(self::ROUTES))->delete();
        }
    }
};
