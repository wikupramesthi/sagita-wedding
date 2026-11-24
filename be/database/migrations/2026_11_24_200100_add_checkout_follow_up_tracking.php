<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkout_sessions', function (Blueprint $table): void {
            $table->uuid('user_uuid')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->foreign('user_uuid', 'checkout_sessions_user_uuid_fk')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
            $table->index(
                ['status', 'started_at', 'reminder_sent_at'],
                'checkout_sessions_reminder_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('checkout_sessions', function (Blueprint $table): void {
            $table->dropIndex('checkout_sessions_reminder_idx');
            $table->dropForeign('checkout_sessions_user_uuid_fk');
            $table->dropColumn(['user_uuid', 'reminder_sent_at']);
        });
    }
};
