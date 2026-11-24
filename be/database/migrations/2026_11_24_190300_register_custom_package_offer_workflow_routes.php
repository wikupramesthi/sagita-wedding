<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ROUTES = [
        ['route' => 'package-offers.edit', 'permission_name' => 'package.update'],
        ['route' => 'package-offers.update', 'permission_name' => 'package.update'],
        ['route' => 'package-offers.send', 'permission_name' => 'package.update'],
        ['route' => 'package-offer-requests.mine', 'permission_name' => 'package.member'],
        ['route' => 'package-offer-requests.create', 'permission_name' => 'package.member'],
        ['route' => 'package-offer-requests.store', 'permission_name' => 'package.member'],
        ['route' => 'package-offer-requests.index', 'permission_name' => 'package.index'],
        ['route' => 'package-offer-requests.status', 'permission_name' => 'package.update'],
        ['route' => 'package-offer-requests.convert', 'permission_name' => 'package.store'],
    ];

    public function up(): void
    {
        foreach (self::ROUTES as $route) {
            if (DB::table('routes')->where('route', $route['route'])->exists()) {
                continue;
            }

            DB::table('routes')->insert([
                ...$route,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('routes')->whereIn('route', array_column(self::ROUTES, 'route'))->delete();
    }
};
