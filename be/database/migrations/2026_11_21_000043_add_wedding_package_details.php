<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packages')) {
            if (! Schema::hasColumn('packages', 'guest_capacity')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->unsignedInteger('guest_capacity')->nullable()->after('description');
                });
            }

            if (! Schema::hasColumn('packages', 'event_duration_hours')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->unsignedInteger('event_duration_hours')->nullable()->after('guest_capacity');
                });
            }

            if (! Schema::hasColumn('packages', 'venue_note')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->text('venue_note')->nullable()->after('event_duration_hours');
                });
            }

            if (! Schema::hasColumn('packages', 'cover_url')) {
                Schema::table('packages', function (Blueprint $table) {
                    $table->string('cover_url')->nullable()->after('venue_note');
                });
            }
        }

        if (! Schema::hasTable('package_addons')) {
            Schema::create('package_addons', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('package_uuid');
                $table->string('name');
                $table->string('description')->nullable();
                $table->unsignedBigInteger('price');
                $table->unsignedBigInteger('discount_price')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('package_uuid')
                    ->references('uuid')
                    ->on('packages')
                    ->cascadeOnDelete();

                $table->index('package_uuid');
            });
        }

        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'addons')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->json('addons')->nullable()->after('approval_snapshot');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'addons')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('addons');
            });
        }

        Schema::dropIfExists('package_addons');

        if (Schema::hasTable('packages')) {
            $columns = array_values(array_filter(
                ['guest_capacity', 'event_duration_hours', 'venue_note', 'cover_url'],
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
