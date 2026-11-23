<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasColumn('orders', 'assigned_admin_uuid')) {
            return;
        }

        $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND CONSTRAINT_NAME = 'orders_assigned_admin_uuid_foreign' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

        if ($exists !== []) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('assigned_admin_uuid', 'orders_assigned_admin_uuid_foreign')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        $exists = DB::select("SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND CONSTRAINT_NAME = 'orders_assigned_admin_uuid_foreign' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

        if ($exists === []) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_assigned_admin_uuid_foreign');
        });
    }
};
