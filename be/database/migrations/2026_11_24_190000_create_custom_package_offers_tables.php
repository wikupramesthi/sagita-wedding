<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_package_offers', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('thread_uuid')->index();
            $table->uuid('user_uuid');
            $table->uuid('created_by_uuid')->nullable();
            $table->uuid('based_package_uuid')->nullable();
            $table->uuid('based_package_option_uuid')->nullable();
            $table->uuid('previous_offer_uuid')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->unsignedInteger('guest_count')->nullable();
            $table->string('venue_note', 1000)->nullable();
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('revision_note')->nullable();
            $table->unsignedSmallInteger('version')->default(1);
            $table->string('status', 32)->default('sent')->index();
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedTinyInteger('deposit_percentage')->default(30);
            $table->text('payment_terms_note')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->foreign('user_uuid')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('created_by_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->foreign('based_package_uuid')->references('uuid')->on('packages')->nullOnDelete();
            $table->foreign('based_package_option_uuid')->references('uuid')->on('package_options')->nullOnDelete();
            $table->foreign('previous_offer_uuid')->references('uuid')->on('custom_package_offers')->nullOnDelete();
            $table->unique(['thread_uuid', 'version']);
            $table->index(['user_uuid', 'status', 'expires_at']);
        });

        Schema::create('custom_package_offer_items', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('custom_package_offer_uuid');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('line_total')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('custom_package_offer_uuid')
                ->references('uuid')
                ->on('custom_package_offers')
                ->cascadeOnDelete();
            $table->index(['custom_package_offer_uuid', 'sort_order'], 'cpoi_offer_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_package_offer_items');
        Schema::dropIfExists('custom_package_offers');
    }
};
