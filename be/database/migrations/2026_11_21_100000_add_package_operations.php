<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packages')) {
            Schema::table('packages', function (Blueprint $table) {
                if (! Schema::hasColumn('packages', 'is_published')) {
                    $table->boolean('is_published')->default(true)->after('is_active');
                }

                if (! Schema::hasColumn('packages', 'published_at')) {
                    $table->timestamp('published_at')->nullable()->after('is_published');
                }

                if (! Schema::hasColumn('packages', 'lead_time_days')) {
                    $table->unsignedSmallInteger('lead_time_days')->default(0)->after('published_at');
                }
            });
        }

        if (Schema::hasTable('package_addons')) {
            Schema::table('package_addons', function (Blueprint $table) {
                if (! Schema::hasColumn('package_addons', 'min_quantity')) {
                    $table->unsignedSmallInteger('min_quantity')->default(1)->after('discount_price');
                }

                if (! Schema::hasColumn('package_addons', 'max_quantity')) {
                    $table->unsignedSmallInteger('max_quantity')->nullable()->after('min_quantity');
                }

                if (! Schema::hasColumn('package_addons', 'requires_addon_uuid')) {
                    $table->uuid('requires_addon_uuid')->nullable()->after('max_quantity');
                }
            });

            Schema::table('package_addons', function (Blueprint $table) {
                $table->foreign('requires_addon_uuid')
                    ->references('uuid')
                    ->on('package_addons')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasTable('package_blackout_dates')) {
            Schema::create('package_blackout_dates', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('package_uuid');
                $table->date('date');
                $table->string('reason')->nullable();
                $table->timestamps();

                $table->foreign('package_uuid')
                    ->references('uuid')
                    ->on('packages')
                    ->cascadeOnDelete();

                $table->unique(['package_uuid', 'date']);
            });
        }

        if (! Schema::hasTable('package_promos')) {
            Schema::create('package_promos', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('package_uuid')->nullable();
                $table->string('code');
                $table->string('type')->default('percent');
                $table->unsignedBigInteger('value');
                $table->unsignedBigInteger('min_purchase')->default(0);
                $table->unsignedBigInteger('max_discount')->nullable();
                $table->unsignedInteger('max_uses')->nullable();
                $table->unsignedInteger('used_count')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('package_uuid')
                    ->references('uuid')
                    ->on('packages')
                    ->cascadeOnDelete();

                $table->unique('code');
                $table->index(['package_uuid', 'is_active']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'event_date')) {
                    $table->date('event_date')->nullable()->after('package_option_uuid');
                }

                if (! Schema::hasColumn('orders', 'promo_code')) {
                    $table->string('promo_code')->nullable()->after('addons');
                }

                if (! Schema::hasColumn('orders', 'promo_discount')) {
                    $table->unsignedBigInteger('promo_discount')->default(0)->after('promo_code');
                }

                if (! Schema::hasColumn('orders', 'promo_snapshot')) {
                    $table->json('promo_snapshot')->nullable()->after('promo_discount');
                }
            });
        }

        if (! Schema::hasTable('package_wishlists')) {
            Schema::create('package_wishlists', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('user_uuid');
                $table->uuid('package_uuid');
                $table->timestamps();

                $table->foreign('user_uuid')
                    ->references('uuid')
                    ->on('users')
                    ->cascadeOnDelete();

                $table->foreign('package_uuid')
                    ->references('uuid')
                    ->on('packages')
                    ->cascadeOnDelete();

                $table->unique(['user_uuid', 'package_uuid']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('package_wishlists');

        if (Schema::hasTable('orders')) {
            $columns = array_values(array_filter(
                ['event_date', 'promo_code', 'promo_discount', 'promo_snapshot'],
                fn ($column) => Schema::hasColumn('orders', $column)
            ));

            if (! empty($columns)) {
                Schema::table('orders', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }

        Schema::dropIfExists('package_promos');
        Schema::dropIfExists('package_blackout_dates');

        if (Schema::hasTable('package_addons')) {
            Schema::table('package_addons', function (Blueprint $table) {
                if (Schema::hasColumn('package_addons', 'requires_addon_uuid')) {
                    $table->dropForeign(['requires_addon_uuid']);
                    $table->dropColumn('requires_addon_uuid');
                }

                $columns = array_values(array_filter(
                    ['min_quantity', 'max_quantity'],
                    fn ($column) => Schema::hasColumn('package_addons', $column)
                ));

                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('packages')) {
            $columns = array_values(array_filter(
                ['is_published', 'published_at', 'lead_time_days'],
                fn ($column) => Schema::hasColumn('packages', $column)
            ));

            if (! empty($columns)) {
                Schema::table('packages', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
};
