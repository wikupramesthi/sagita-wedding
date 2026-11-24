<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sita_analytics_daily', function (Blueprint $table): void {
            $table->id();
            $table->date('date');
            $table->string('name', 40);
            $table->string('topic', 20)->nullable();
            $table->string('locale', 2)->nullable();
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('helpful')->default(0);
            $table->unsignedInteger('unhelpful')->default(0);
            $table->unsignedInteger('fallback')->default(0);
            $table->unsignedInteger('clarify')->default(0);
            $table->unsignedInteger('low_confidence')->default(0);
            $table->unsignedBigInteger('response_ms_sum')->default(0);
            $table->unsignedInteger('response_ms_count')->default(0);
            $table->timestamps();

            $table->unique(['date', 'name', 'topic', 'locale'], 'sita_analytics_daily_unique');
            $table->index(['date', 'name'], 'sita_analytics_daily_date_name_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sita_analytics_daily');
    }
};
