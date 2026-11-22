<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_checklist_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('wedding_checklist_items', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('due_at');
            }

            if (! Schema::hasColumn('wedding_checklist_items', 'escalation_sent_at')) {
                $table->timestamp('escalation_sent_at')->nullable()->after('reminder_sent_at');
            }
        });
    }

    public function down(): void
    {
        $columns = collect(['reminder_sent_at', 'escalation_sent_at'])
            ->filter(fn (string $column): bool => Schema::hasColumn('wedding_checklist_items', $column))
            ->values()
            ->all();

        if ($columns === []) {
            return;
        }

        Schema::table('wedding_checklist_items', function (Blueprint $table) use ($columns): void {
            $table->dropColumn($columns);
        });
    }
};
