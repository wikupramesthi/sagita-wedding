<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'installment_schedule')) {
                $table->json('installment_schedule')->nullable()->after('balance_due_at');
            }

            if (! Schema::hasColumn('orders', 'installment_reminders_sent')) {
                $table->json('installment_reminders_sent')->nullable()->after('installment_schedule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            foreach (['installment_reminders_sent', 'installment_schedule'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
