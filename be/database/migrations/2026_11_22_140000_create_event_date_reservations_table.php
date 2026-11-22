<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_date_reservations', function (Blueprint $table): void {
            $table->date('event_date')->primary();
            $table->uuid('order_uuid')->nullable()->unique();
            $table->string('status', 20)->default('available');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('order_uuid')
                ->references('uuid')
                ->on('orders')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_date_reservations');
    }
};
