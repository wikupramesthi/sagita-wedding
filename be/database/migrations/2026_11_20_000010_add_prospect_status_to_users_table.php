<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'prospect_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('prospect_status', [
                    'new_registered',
                    'interested',
                    'consultation',
                    'negotiation',
                    'customer',
                    'inactive',
                ])->default('new_registered');
            });
        }

        if (! Schema::hasColumn('users', 'prospect_status_updated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('prospect_status_updated_at')->nullable()->after('prospect_status');
            });
        }

        DB::table('routes')->updateOrInsert(
            ['route' => 'pengguna.update-status'],
            [
                'permission_name' => 'pengguna.index',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('menu_items')
            ->where('route', 'pengguna.index')
            ->update(['name' => 'Calon Pelanggan']);
    }

    public function down(): void
    {
        DB::table('routes')->where('route', 'pengguna.update-status')->delete();

        DB::table('menu_items')
            ->where('route', 'pengguna.index')
            ->update(['name' => 'All Members']);

        Schema::table('users', function (Blueprint $table) {
            foreach (['prospect_status_updated_at', 'prospect_status'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
