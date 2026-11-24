<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_package_offer_requests', function (Blueprint $table) {
            $table->string('fingerprint_hash', 64)->nullable()->index();
            $table->timestamp('review_due_at')->nullable()->index();
            $table->timestamp('sla_notified_at')->nullable();
            $table->timestamp('quoted_at')->nullable();
        });

        Schema::create('custom_package_offer_options', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('custom_package_offer_uuid');
            $table->string('label', 100);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedTinyInteger('deposit_percentage')->default(30);
            $table->text('payment_terms_note')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(1);
            $table->timestamps();

            $table->foreign('custom_package_offer_uuid')
                ->references('uuid')
                ->on('custom_package_offers')
                ->cascadeOnDelete();
            $table->unique(['custom_package_offer_uuid', 'sort_order'], 'cpoo_offer_sort_unique');
        });

        Schema::create('custom_package_offer_option_items', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('custom_package_offer_option_uuid');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('line_total')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('custom_package_offer_option_uuid')
                ->references('uuid')
                ->on('custom_package_offer_options')
                ->cascadeOnDelete();
            $table->index(['custom_package_offer_option_uuid', 'sort_order'], 'cpooi_option_sort_idx');
        });

        Schema::table('custom_package_offers', function (Blueprint $table) {
            $table->uuid('selected_option_uuid')->nullable()->index();
            $table->timestamp('response_reminded_at')->nullable();
            $table->string('rejection_reason', 32)->nullable();
            $table->text('rejection_note')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('custom_package_offer_option_uuid')->nullable()->after('custom_package_offer_uuid');
            $table->foreign('custom_package_offer_option_uuid', 'orders_custom_offer_option_fk')
                ->references('uuid')
                ->on('custom_package_offer_options')
                ->nullOnDelete();
        });

        Schema::create('custom_package_offer_request_attachments', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('request_uuid');
            $table->uuid('uploaded_by_uuid')->nullable();
            $table->string('storage_path', 500);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->unsignedInteger('size_bytes');
            $table->timestamps();

            $table->foreign('request_uuid')
                ->references('uuid')
                ->on('custom_package_offer_requests')
                ->cascadeOnDelete();
            $table->foreign('uploaded_by_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['request_uuid', 'created_at'], 'cpo_attachment_request_created_idx');
        });

        Schema::create('custom_package_workflow_events', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('actor_uuid')->nullable();
            $table->uuid('request_uuid')->nullable();
            $table->uuid('offer_uuid')->nullable();
            $table->string('event_key', 64);
            $table->string('from_status', 32)->nullable();
            $table->string('to_status', 32)->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('actor_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->foreign('request_uuid')->references('uuid')->on('custom_package_offer_requests')->nullOnDelete();
            $table->foreign('offer_uuid')->references('uuid')->on('custom_package_offers')->nullOnDelete();
            $table->index(['request_uuid', 'created_at'], 'cpwe_request_created_idx');
            $table->index(['offer_uuid', 'created_at'], 'cpwe_offer_created_idx');
        });

        DB::table('custom_package_offers')->orderBy('uuid')->chunk(100, function ($offers) {
            foreach ($offers as $offer) {
                $optionUuid = (string) Str::uuid();
                DB::table('custom_package_offer_options')->insert([
                    'uuid' => $optionUuid,
                    'custom_package_offer_uuid' => $offer->uuid,
                    'label' => 'Pilihan 1',
                    'description' => $offer->description,
                    'subtotal_amount' => $offer->subtotal_amount,
                    'discount_amount' => $offer->discount_amount,
                    'total_amount' => $offer->total_amount,
                    'deposit_percentage' => $offer->deposit_percentage,
                    'payment_terms_note' => $offer->payment_terms_note,
                    'sort_order' => 1,
                    'created_at' => $offer->created_at,
                    'updated_at' => $offer->updated_at,
                ]);

                $items = DB::table('custom_package_offer_items')
                    ->where('custom_package_offer_uuid', $offer->uuid)
                    ->get();
                foreach ($items as $item) {
                    DB::table('custom_package_offer_option_items')->insert([
                        'uuid' => (string) Str::uuid(),
                        'custom_package_offer_option_uuid' => $optionUuid,
                        'name' => $item->name,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'line_total' => $item->line_total,
                        'sort_order' => $item->sort_order,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ]);
                }

                if ($offer->status === 'accepted') {
                    DB::table('custom_package_offers')
                        ->where('uuid', $offer->uuid)
                        ->update(['selected_option_uuid' => $optionUuid]);
                }

                DB::table('orders')
                    ->where('custom_package_offer_uuid', $offer->uuid)
                    ->update(['custom_package_offer_option_uuid' => $optionUuid]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_package_workflow_events');
        Schema::dropIfExists('custom_package_offer_request_attachments');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_custom_offer_option_fk');
            $table->dropColumn('custom_package_offer_option_uuid');
        });

        Schema::table('custom_package_offers', function (Blueprint $table) {
            $table->dropIndex(['selected_option_uuid']);
            $table->dropColumn([
                'selected_option_uuid',
                'response_reminded_at',
                'rejection_reason',
                'rejection_note',
            ]);
        });

        Schema::dropIfExists('custom_package_offer_option_items');
        Schema::dropIfExists('custom_package_offer_options');

        Schema::table('custom_package_offer_requests', function (Blueprint $table) {
            $table->dropIndex(['fingerprint_hash']);
            $table->dropIndex(['review_due_at']);
            $table->dropColumn([
                'fingerprint_hash',
                'review_due_at',
                'sla_notified_at',
                'quoted_at',
            ]);
        });
    }
};
