<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_package_offer_requests', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('user_uuid');
            $table->date('event_date')->nullable();
            $table->unsignedInteger('guest_count')->nullable();
            $table->string('venue_note', 1000)->nullable();
            $table->unsignedBigInteger('budget_min')->nullable();
            $table->unsignedBigInteger('budget_max')->nullable();
            $table->text('request_details');
            $table->string('status', 32)->default('submitted')->index();
            $table->text('admin_note')->nullable();
            $table->uuid('assigned_admin_uuid')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_uuid')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_admin_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['user_uuid', 'status', 'created_at'], 'cpor_user_status_created_idx');
        });

        Schema::table('custom_package_offers', function (Blueprint $table) {
            $table->uuid('source_request_uuid')->nullable()->after('previous_offer_uuid');
            $table->timestamp('expiry_reminded_at')->nullable()->after('expires_at');
            $table->foreign('source_request_uuid', 'cpo_source_request_fk')
                ->references('uuid')
                ->on('custom_package_offer_requests')
                ->nullOnDelete();
            $table->index('source_request_uuid', 'cpo_source_request_idx');
        });
    }

    public function down(): void
    {
        Schema::table('custom_package_offers', function (Blueprint $table) {
            $table->dropForeign('cpo_source_request_fk');
            $table->dropIndex('cpo_source_request_idx');
            $table->dropColumn(['source_request_uuid', 'expiry_reminded_at']);
        });

        Schema::dropIfExists('custom_package_offer_requests');
    }
};
