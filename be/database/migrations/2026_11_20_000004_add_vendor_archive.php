<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $routes = [
            'vendor.archive' => 'vendor.index',
            'vendor.restore' => 'vendor.update',
        ];

        foreach ($routes as $name => $permission) {
            $existing = DB::table('routes')->where('route', $name)->first();

            if ($existing) {
                DB::table('routes')->where('id', $existing->id)->update([
                    'permission_name' => $permission,
                    'status' => 1,
                    'updated_at' => $now,
                ]);

                continue;
            }

            DB::table('routes')->insert([
                'id' => (DB::table('routes')->max('id') ?? 0) + 1,
                'route' => $name,
                'permission_name' => $permission,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $menu = DB::table('menu_items')->where('route', 'vendor.archive')->first();

        if (! $menu) {
            DB::table('menu_items')->insert([
                'id' => (DB::table('menu_items')->max('id') ?? 0) + 1,
                'name' => 'Arsip Vendor',
                'icon' => 'bx-archive',
                'route' => 'vendor.archive',
                'status' => 1,
                'permission_name' => 'vendor.index',
                'menu_group_id' => DB::table('menu_groups')->where('permission_name', 'vendor.index')->value('id') ?? 10,
                'position' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'vendor.archive')->delete();
        DB::table('routes')->whereIn('route', ['vendor.archive', 'vendor.restore'])->delete();
    }
};
