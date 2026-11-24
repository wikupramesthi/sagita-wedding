<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = collect(DB::select('SHOW INDEX FROM sita_leads'))->pluck('Key_name')->all();

        Schema::table('sita_leads', function (Blueprint $table) use ($indexes): void {
            if (! Schema::hasColumn('sita_leads', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (! Schema::hasColumn('sita_leads', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('sita_leads', 'assigned_to')) {
                $table->string('assigned_to', 100)->nullable()->after('contacted_at');
            }
            if (! in_array('sita_leads_status_created_at_index', $indexes, true)) {
                $table->index(['status', 'created_at'], 'sita_leads_status_created_at_index');
            }
            if (! in_array('sita_leads_topic_created_at_index', $indexes, true)) {
                $table->index(['topic', 'created_at'], 'sita_leads_topic_created_at_index');
            }
            if (! in_array('sita_leads_score_created_at_index', $indexes, true)) {
                $table->index(['score', 'created_at'], 'sita_leads_score_created_at_index');
            }
        });

        if (! Schema::hasTable('sita_lead_activities')) {
            Schema::create('sita_lead_activities', function (Blueprint $table): void {
                $table->id();
                $table->uuid('lead_uuid');
                $table->uuid('user_id')->nullable()->index();
                $table->string('user_name')->nullable();
                $table->string('action', 40);
                $table->string('from_status', 20)->nullable();
                $table->string('to_status', 20)->nullable();
                $table->text('note')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->foreign('lead_uuid')->references('uuid')->on('sita_leads')->cascadeOnDelete();
                $table->index(['lead_uuid', 'created_at'], 'sita_lead_activities_lead_created_at_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sita_lead_activities');
        $indexes = collect(DB::select('SHOW INDEX FROM sita_leads'))->pluck('Key_name')->all();
        Schema::table('sita_leads', function (Blueprint $table) use ($indexes): void {
            foreach (['sita_leads_status_created_at_index', 'sita_leads_topic_created_at_index', 'sita_leads_score_created_at_index'] as $index) {
                if (in_array($index, $indexes, true)) {
                    $table->dropIndex($index);
                }
            }
            $cols = array_filter(['notes', 'contacted_at', 'assigned_to'], fn ($c) => Schema::hasColumn('sita_leads', $c));
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};
