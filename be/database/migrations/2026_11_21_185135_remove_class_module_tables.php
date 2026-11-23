<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'class_schedule_uuid')) {
            try {
                DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_class_schedule_uuid_foreign');
            } catch (Throwable $e) {
            }
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropIndex(['class_schedule_uuid']);
                });
            } catch (Throwable $e) {
            }
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('class_schedule_uuid');
                });
            } catch (Throwable $e) {
            }
        }
        Schema::dropIfExists('class_bookings');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('classes');
    }

    public function down(): void
    {
        // Class module intentionally removed.
    }
};
