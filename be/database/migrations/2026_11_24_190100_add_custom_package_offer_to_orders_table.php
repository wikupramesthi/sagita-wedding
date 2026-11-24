<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('custom_package_offer_uuid')->nullable()->after('package_option_uuid');
            $table->foreign('custom_package_offer_uuid')
                ->references('uuid')
                ->on('custom_package_offers')
                ->nullOnDelete();
            $table->index('custom_package_offer_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['custom_package_offer_uuid']);
            $table->dropIndex(['custom_package_offer_uuid']);
            $table->dropColumn('custom_package_offer_uuid');
        });
    }
};
