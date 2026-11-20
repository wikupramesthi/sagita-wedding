<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultations') && ! Schema::hasColumn('consultations', 'email')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->string('email', 255)->nullable()->after('whatsapp');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('consultations') && Schema::hasColumn('consultations', 'email')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
