<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_checklist_items', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid');
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->date('due_at')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->uuid('completed_by')->nullable();
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('completed_by')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['order_uuid', 'status', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_checklist_items');
    }
};
