<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('konsultasi_saved_filters')) {
            return;
        }

        Schema::table('konsultasi_saved_filters', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('konsultasi_saved_filters', function (Blueprint $table) {
            $table->uuid('user_id')->change();
            $table->foreign('user_id')->references('uuid')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('konsultasi_saved_filters')) {
            return;
        }

        Schema::table('konsultasi_saved_filters', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
