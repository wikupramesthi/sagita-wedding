<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sita_analytics_events', function (Blueprint $table) {
            $table->index('occurred_at', 'sita_analytics_events_occurred_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('sita_analytics_events', function (Blueprint $table) {
            $table->dropIndex('sita_analytics_events_occurred_at_index');
        });
    }
};
