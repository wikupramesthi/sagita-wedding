<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('orders', 'consultation_uuid')) {
                    $table->string('consultation_uuid')->nullable()->index();
                }

                if (! Schema::hasColumn('orders', 'venue_uuid')) {
                    $table->string('venue_uuid')->nullable()->index();
                }

                if (! Schema::hasColumn('orders', 'venue_booking_status')) {
                    $table->string('venue_booking_status')->default('none');
                }

                if (! Schema::hasColumn('orders', 'venue_booking_at')) {
                    $table->timestamp('venue_booking_at')->nullable();
                }

                if (! Schema::hasColumn('orders', 'venue_booking_amount')) {
                    $table->decimal('venue_booking_amount', 14, 2)->nullable();
                }

                if (! Schema::hasColumn('orders', 'venue_booking_note')) {
                    $table->text('venue_booking_note')->nullable();
                }
            });
        }

        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table): void {
                if (! Schema::hasColumn('consultations', 'converted_order_uuid')) {
                    $table->string('converted_order_uuid')->nullable()->index();
                }

                if (! Schema::hasColumn('consultations', 'user_uuid')) {
                    $table->string('user_uuid')->nullable()->index();
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'consultation_uuid')) {
                    $table->string('consultation_uuid')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            $columns = collect([
                'consultation_uuid',
                'venue_uuid',
                'venue_booking_status',
                'venue_booking_at',
                'venue_booking_amount',
                'venue_booking_note',
            ])
                ->filter(fn (string $column): bool => Schema::hasColumn('orders', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                Schema::table('orders', function (Blueprint $table) use ($columns): void {
                    $table->dropColumn($columns);
                });
            }
        }

        if (Schema::hasTable('consultations')) {
            $columns = collect(['converted_order_uuid', 'user_uuid'])
                ->filter(fn (string $column): bool => Schema::hasColumn('consultations', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                Schema::table('consultations', function (Blueprint $table) use ($columns): void {
                    $table->dropColumn($columns);
                });
            }
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'consultation_uuid')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('consultation_uuid');
            });
        }
    }
};
