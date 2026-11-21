<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_saved_filters', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('user_uuid');
            $table->string('name');
            $table->json('filters');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_uuid', 'name'], 'order_saved_filters_user_name_unique');
            $table->index(['user_uuid', 'is_default'], 'order_saved_filters_user_default_index');
            $table->foreign('user_uuid')->references('uuid')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_saved_filters');
    }
};
