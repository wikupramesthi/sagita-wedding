<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wedding_plans');
    }

    public function down(): void
    {
        if (Schema::hasTable('wedding_plans')) {
            return;
        }

        Schema::create('wedding_plans', function (Blueprint $table) {
            $table->uuid('user_uuid')->primary();
            $table->date('event_date')->nullable();
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->json('budget_breakdown')->nullable();
            $table->json('completed_tasks')->nullable();
            $table->timestamps();

            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
