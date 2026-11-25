<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_cancellation_requests', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid');
            $table->uuid('requested_by_uuid');
            $table->text('customer_note')->nullable();
            $table->string('status', 20)->default('pending');
            $table->decimal('refund_amount', 14, 2)->default(0);
            $table->string('refund_status', 20)->default('not_applicable');
            $table->string('refund_reference')->nullable();
            $table->uuid('refund_recorded_by_uuid')->nullable();
            $table->timestamp('refund_recorded_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->uuid('reviewed_by_uuid')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('requested_by_uuid')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('refund_recorded_by_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->foreign('reviewed_by_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['status', 'created_at']);
            $table->index(['order_uuid', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_cancellation_requests');
    }
};
