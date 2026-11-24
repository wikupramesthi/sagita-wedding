<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $eventIndexes = collect(DB::select('SHOW INDEX FROM sita_analytics_events'))->pluck('Key_name')->all();

        Schema::table('sita_analytics_events', function (Blueprint $table) use ($eventIndexes) {
            if (in_array('sita_analytics_events_occurred_at_index', $eventIndexes, true)) {
                $table->dropIndex('sita_analytics_events_occurred_at_index');
            }

            if (! in_array('sita_analytics_events_occurred_at_id_index', $eventIndexes, true)) {
                $table->index(['occurred_at', 'id'], 'sita_analytics_events_occurred_at_id_index');
            }
        });

        $synonymIndexes = collect(DB::select('SHOW INDEX FROM sita_synonyms'))->pluck('Key_name')->all();

        if (! in_array('sita_synonyms_locale_active_topic_index', $synonymIndexes, true)) {
            Schema::table('sita_synonyms', function (Blueprint $table) {
                $table->index(['locale', 'is_active', 'topic'], 'sita_synonyms_locale_active_topic_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sita_synonyms', function (Blueprint $table) {
            $table->dropIndex('sita_synonyms_locale_active_topic_index');
        });

        Schema::table('sita_analytics_events', function (Blueprint $table) {
            $table->dropIndex('sita_analytics_events_occurred_at_id_index');
            $table->index('occurred_at', 'sita_analytics_events_occurred_at_index');
        });
    }
};
