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
            if (! Schema::hasColumn('custom_package_offer_requests', 'fingerprint_hash')) {
                $table->string('fingerprint_hash', 64)->nullable()->index('cpor_fingerprint_idx');
            }
            if (! Schema::hasColumn('custom_package_offer_requests', 'review_due_at')) {
                $table->timestamp('review_due_at')->nullable()->index('cpor_review_due_idx');
            }
            if (! Schema::hasColumn('custom_package_offer_requests', 'sla_notified_at')) {
                $table->timestamp('sla_notified_at')->nullable();
            }
            if (! Schema::hasColumn('custom_package_offer_requests', 'quoted_at')) {
                $table->timestamp('quoted_at')->nullable();
            }
        });

        if (! Schema::hasTable('custom_package_offer_options')) {
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

                $table->foreign('custom_package_offer_uuid', 'cpoo_offer_fk')
                    ->references('uuid')
                    ->on('custom_package_offers')
                    ->cascadeOnDelete();
                $table->unique(['custom_package_offer_uuid', 'sort_order'], 'cpoo_offer_sort_unique');
            });
        }

        if (! Schema::hasTable('custom_package_offer_option_items')) {
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

                $table->foreign('custom_package_offer_option_uuid', 'cpooi_option_fk')
                    ->references('uuid')
                    ->on('custom_package_offer_options')
                    ->cascadeOnDelete();
                $table->index(['custom_package_offer_option_uuid', 'sort_order'], 'cpooi_option_sort_idx');
            });
        } else {
            $foreignKeys = Schema::getForeignKeys('custom_package_offer_option_items');
            if (! collect($foreignKeys)->contains(fn ($foreignKey) => in_array('custom_package_offer_option_uuid', $foreignKey['columns'], true))) {
                Schema::table('custom_package_offer_option_items', function (Blueprint $table) {
                    $table->foreign('custom_package_offer_option_uuid', 'cpooi_option_fk')
                        ->references('uuid')
                        ->on('custom_package_offer_options')
                        ->cascadeOnDelete();
                });
            }
            if (! Schema::hasIndex('custom_package_offer_option_items', 'cpooi_option_sort_idx')) {
                Schema::table('custom_package_offer_option_items', function (Blueprint $table) {
                    $table->index(['custom_package_offer_option_uuid', 'sort_order'], 'cpooi_option_sort_idx');
                });
            }
        }

        Schema::table('custom_package_offers', function (Blueprint $table) {
            if (! Schema::hasColumn('custom_package_offers', 'selected_option_uuid')) {
                $table->uuid('selected_option_uuid')->nullable()->index('cpo_selected_option_idx');
            }
            if (! Schema::hasColumn('custom_package_offers', 'response_reminded_at')) {
                $table->timestamp('response_reminded_at')->nullable();
            }
            if (! Schema::hasColumn('custom_package_offers', 'rejection_reason')) {
                $table->string('rejection_reason', 32)->nullable();
            }
            if (! Schema::hasColumn('custom_package_offers', 'rejection_note')) {
                $table->text('rejection_note')->nullable();
            }
        });

        if (! Schema::hasColumn('orders', 'custom_package_offer_option_uuid')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->uuid('custom_package_offer_option_uuid')->nullable()->after('custom_package_offer_uuid');
                $table->foreign('custom_package_offer_option_uuid', 'orders_custom_offer_option_fk')
                    ->references('uuid')
                    ->on('custom_package_offer_options')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasTable('custom_package_offer_request_attachments')) {
            Schema::create('custom_package_offer_request_attachments', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('request_uuid');
                $table->uuid('uploaded_by_uuid')->nullable();
                $table->string('storage_path', 500);
                $table->string('original_name', 255);
                $table->string('mime_type', 100);
                $table->unsignedInteger('size_bytes');
                $table->timestamps();

                $table->foreign('request_uuid', 'cpo_attach_request_fk')
                    ->references('uuid')
                    ->on('custom_package_offer_requests')
                    ->cascadeOnDelete();
                $table->foreign('uploaded_by_uuid', 'cpo_attach_user_fk')
                    ->references('uuid')
                    ->on('users')
                    ->nullOnDelete();
                $table->index(['request_uuid', 'created_at'], 'cpo_attachment_request_created_idx');
            });
        }

        if (! Schema::hasTable('custom_package_workflow_events')) {
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

                $table->foreign('actor_uuid', 'cpwe_actor_fk')->references('uuid')->on('users')->nullOnDelete();
                $table->foreign('request_uuid', 'cpwe_request_fk')->references('uuid')->on('custom_package_offer_requests')->nullOnDelete();
                $table->foreign('offer_uuid', 'cpwe_offer_fk')->references('uuid')->on('custom_package_offers')->nullOnDelete();
                $table->index(['request_uuid', 'created_at'], 'cpwe_request_created_idx');
                $table->index(['offer_uuid', 'created_at'], 'cpwe_offer_created_idx');
            });
        }

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
            $table->dropIndex('cpo_selected_option_idx');
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
            if (Schema::hasIndex('custom_package_offer_requests', 'cpor_fingerprint_idx')) {
                $table->dropIndex('cpor_fingerprint_idx');
            } elseif (Schema::hasIndex('custom_package_offer_requests', 'custom_package_offer_requests_fingerprint_hash_index')) {
                $table->dropIndex('custom_package_offer_requests_fingerprint_hash_index');
            }
            if (Schema::hasIndex('custom_package_offer_requests', 'cpor_review_due_idx')) {
                $table->dropIndex('cpor_review_due_idx');
            } elseif (Schema::hasIndex('custom_package_offer_requests', 'custom_package_offer_requests_review_due_at_index')) {
                $table->dropIndex('custom_package_offer_requests_review_due_at_index');
            }
            $table->dropColumn([
                'fingerprint_hash',
                'review_due_at',
                'sla_notified_at',
                'quoted_at',
            ]);
        });
    }
};
