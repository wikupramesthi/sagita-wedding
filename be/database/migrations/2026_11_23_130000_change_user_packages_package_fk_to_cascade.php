<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_packages')) {
            return;
        }

        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropForeign(['package_uuid']);
            $table->foreign('package_uuid')
                ->references('uuid')
                ->on('packages')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('user_packages')) {
            return;
        }

        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropForeign(['package_uuid']);
            $table->foreign('package_uuid')
                ->references('uuid')
                ->on('packages')
                ->restrictOnDelete();
        });
    }
};
