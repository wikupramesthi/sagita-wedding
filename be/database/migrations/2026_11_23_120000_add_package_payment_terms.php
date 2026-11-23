<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (! Schema::hasColumn('packages', 'dp_percentage')) {
                $table->unsignedInteger('dp_percentage')->nullable()->default(30);
            }

            if (! Schema::hasColumn('packages', 'payment_terms_note')) {
                $table->text('payment_terms_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'dp_percentage')) {
                $table->dropColumn('dp_percentage');
            }

            if (Schema::hasColumn('packages', 'payment_terms_note')) {
                $table->dropColumn('payment_terms_note');
            }
        });
    }
};
