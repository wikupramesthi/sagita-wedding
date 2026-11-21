<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'pending_key')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->string('pending_key')->nullable()->after('status');
            });
        }

        $pendingOrders = DB::table('orders')
            ->where('type', 'package')
            ->where('status', 'pending')
            ->whereNotNull('user_uuid')
            ->whereNotNull('package_option_uuid')
            ->get(['uuid', 'user_uuid', 'package_option_uuid']);

        $seenKeys = [];

        foreach ($pendingOrders as $order) {
            $key = $order->user_uuid . '|' . $order->package_option_uuid;

            if (isset($seenKeys[$key])) {
                DB::table('orders')->where('uuid', $order->uuid)->update(['pending_key' => null]);

                continue;
            }

            $seenKeys[$key] = $order->uuid;
            DB::table('orders')->where('uuid', $order->uuid)->update(['pending_key' => $key]);
        }

        Schema::table('orders', function (Blueprint $table): void {
            $table->unique('pending_key', 'orders_pending_key_unique');
        });

        $this->safely(function (): void {
            Schema::table('orders', function (Blueprint $table): void {
                $table->index('order_number', 'orders_order_number_index');
            });
        });

        $this->safely(function (): void {
            Schema::table('orders', function (Blueprint $table): void {
                $table->index(['user_uuid', 'package_option_uuid', 'status'], 'orders_user_option_status_index');
            });
        });

        $this->safely(function (): void {
            Schema::table('users', function (Blueprint $table): void {
                $table->index(['name', 'email'], 'users_name_email_index');
            });
        });
    }

    public function down(): void
    {
        $this->safely(function (): void {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropIndex('users_name_email_index');
            });
        });

        $this->safely(function (): void {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropIndex('orders_user_option_status_index');
                $table->dropIndex('orders_order_number_index');
                $table->dropUnique('orders_pending_key_unique');
            });
        });

        if (Schema::hasColumn('orders', 'pending_key')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn('pending_key');
            });
        }
    }

    private function safely(callable $callback): void
    {
        try {
            $callback();
        } catch (\Throwable $e) {
        }
    }
};
