<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'pending_payment_reminded_at')) {
                $table->timestamp('pending_payment_reminded_at')->nullable()->after('expired_at');
                $table->index(
                    ['status', 'expired_at', 'pending_payment_reminded_at'],
                    'orders_payment_expiry_reminder_idx'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'pending_payment_reminded_at')) {
                $table->dropIndex('orders_payment_expiry_reminder_idx');
                $table->dropColumn('pending_payment_reminded_at');
            }
        });
    }
};
