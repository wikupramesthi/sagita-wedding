<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sita_analytics_events', function (Blueprint $table): void {
            $table->string('query_sample', 160)->nullable()->after('query_hash');
        });
    }

    public function down(): void
    {
        Schema::table('sita_analytics_events', function (Blueprint $table): void {
            $table->dropColumn('query_sample');
        });
    }
};
