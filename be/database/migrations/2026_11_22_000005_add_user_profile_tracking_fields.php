<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'pengalaman')) {
                $table->string('pengalaman')->nullable();
            }

            if (! Schema::hasColumn('users', 'sumber_informasi')) {
                $table->enum('sumber_informasi', [
                    'google',
                    'sosmed',
                    'friend',
                    'community',
                    'event',
                    'website',
                    'other',
                ])->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pengalaman', 'sumber_informasi']);
        });
    }
};
