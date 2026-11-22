<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_change_requests', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid');
            $table->uuid('requested_by_uuid');
            $table->string('change_type', 30);
            $table->date('requested_event_date')->nullable();
            $table->uuid('addon_uuid')->nullable();
            $table->unsignedSmallInteger('addon_quantity')->nullable();
            $table->unsignedBigInteger('requested_price')->default(0);
            $table->text('customer_note')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_note')->nullable();
            $table->uuid('reviewed_by_uuid')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('requested_by_uuid')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('addon_uuid')->references('uuid')->on('package_addons')->nullOnDelete();
            $table->foreign('reviewed_by_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['status', 'created_at']);
            $table->index(['order_uuid', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_change_requests');
    }
};
