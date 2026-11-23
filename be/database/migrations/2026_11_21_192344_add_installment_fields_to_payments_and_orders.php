<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 15, 2)->nullable()->after('payment_type');
            }
            if (! Schema::hasColumn('payments', 'verification_status')) {
                $table->string('verification_status', 30)->default('pending')->after('status');
            }
            if (! Schema::hasColumn('payments', 'verified_by')) {
                $table->uuid('verified_by')->nullable()->after('verification_status');
            }
            if (! Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
            if (! Schema::hasColumn('payments', 'note')) {
                $table->text('note')->nullable()->after('proof_uploaded_at');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'balance_reminder_sent_at')) {
                $table->timestamp('balance_reminder_sent_at')->nullable()->after('balance_due_at');
            }
        });

        // backfill
        DB::table('payments')->whereNull('amount')->update(['amount' => DB::raw('gross_amount')]);
        DB::table('payments')->where('status', 'paid')->update(['verification_status' => 'verified', 'verified_at' => DB::raw('paid_at')]);
        DB::table('payments')->where('status', 'failed')->update(['verification_status' => 'rejected']);
        DB::table('payments')->whereNotIn('status', ['paid', 'failed'])->update(['verification_status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'balance_reminder_sent_at')) {
                $table->dropColumn('balance_reminder_sent_at');
            }
        });
        Schema::table('payments', function (Blueprint $table) {
            foreach (['amount', 'verification_status', 'verified_by', 'verified_at', 'note'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
