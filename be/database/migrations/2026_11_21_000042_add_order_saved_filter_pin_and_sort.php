<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_saved_filters', function (Blueprint $table): void {
            $table->timestamp('pinned_at')->nullable()->after('is_default');
            $table->unsignedInteger('sort_order')->default(0)->after('pinned_at');

            $table->index(['user_uuid', 'pinned_at', 'sort_order'], 'order_saved_filters_user_pin_order_index');
        });
    }

    public function down(): void
    {
        Schema::table('order_saved_filters', function (Blueprint $table): void {
            $table->dropIndex('order_saved_filters_user_pin_order_index');
            $table->dropColumn(['pinned_at', 'sort_order']);
        });
    }
};
