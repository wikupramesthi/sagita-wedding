<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_sessions', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('visitor_key')->unique();
            $table->uuid('package_uuid');
            $table->uuid('order_uuid')->nullable();
            $table->string('status', 20)->default('started');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('package_uuid')->references('uuid')->on('packages')->cascadeOnDelete();
            $table->foreign('order_uuid')->references('uuid')->on('orders')->nullOnDelete();
            $table->index(['status', 'started_at']);
            $table->index(['package_uuid', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_sessions');
    }
};
