<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sita_leads', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name', 150);
            $table->string('phone', 30);
            $table->string('topic', 20)->nullable();
            $table->json('criteria')->nullable();
            $table->unsignedTinyInteger('score');
            $table->text('message')->nullable();
            $table->string('status', 30)->default('new');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sita_leads');
    }
};
