<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_vendor_assignments', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid');
            $table->uuid('vendor_uuid');
            $table->string('scope', 80);
            $table->string('status', 20)->default('assigned');
            $table->text('notes')->nullable();
            $table->uuid('assigned_by')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('vendor_uuid')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_by')->references('uuid')->on('users')->nullOnDelete();
            $table->unique(['order_uuid', 'vendor_uuid', 'scope'], 'order_vendor_scope_unique');
            $table->index(['order_uuid', 'status']);
            $table->index('vendor_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_vendor_assignments');
    }
};
