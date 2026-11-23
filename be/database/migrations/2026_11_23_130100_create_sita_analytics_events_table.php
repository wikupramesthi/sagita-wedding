<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sita_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('topic', 20)->nullable();
            $table->string('query_hash', 32)->nullable();
            $table->string('turn_id', 80)->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->boolean('fallback')->nullable();
            $table->boolean('clarify')->nullable();
            $table->boolean('helpful')->nullable();
            $table->unsignedInteger('response_ms')->nullable();
            $table->string('path')->default('/');
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['name', 'occurred_at']);
            $table->index(['topic', 'occurred_at']);
            $table->index(['query_hash', 'occurred_at']);
            $table->index(['name', 'turn_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sita_analytics_events');
    }
};
