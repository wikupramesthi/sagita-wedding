<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->uuid('assigned_admin_uuid')->nullable()->after('admin_note');
            $table->timestamp('sla_reminded_at')->nullable()->after('proof_uploaded_at');
            $table->json('approval_snapshot')->nullable()->after('sla_reminded_at');

            $table->index(['assigned_admin_uuid', 'status'], 'orders_admin_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('orders_admin_status_index');
            $table->dropColumn(['assigned_admin_uuid', 'sla_reminded_at', 'approval_snapshot']);
        });
    }
};
