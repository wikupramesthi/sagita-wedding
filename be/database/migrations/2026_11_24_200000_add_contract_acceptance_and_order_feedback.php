<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_documents', function (Blueprint $table): void {
            $table->timestamp('accepted_at')->nullable();
            $table->uuid('accepted_by_uuid')->nullable();
            $table->json('accepted_snapshot')->nullable();
            $table->foreign('accepted_by_uuid', 'order_documents_accepted_by_fk')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::create('order_feedback', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid')->unique();
            $table->uuid('user_uuid')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('user_uuid')->references('uuid')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_feedback');

        Schema::table('order_documents', function (Blueprint $table): void {
            $table->dropForeign('order_documents_accepted_by_fk');
            $table->dropColumn(['accepted_at', 'accepted_by_uuid', 'accepted_snapshot']);
        });
    }
};
