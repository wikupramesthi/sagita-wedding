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
            return;
        }

        DB::table('orders')
            ->where(function ($query): void {
                $query->where('type', '!=', 'package')
                    ->orWhereNull('type');
            })
            ->update(['pending_key' => null]);

        DB::table('orders')
            ->where('type', 'package')
            ->where('status', 'pending')
            ->update(['pending_key' => null]);

        $pendingOrders = DB::table('orders')
            ->where('type', 'package')
            ->where('status', 'pending')
            ->whereNotNull('user_uuid')
            ->whereNotNull('package_option_uuid')
            ->orderByDesc('created_at')
            ->get(['uuid', 'user_uuid', 'package_option_uuid']);

        $seenKeys = [];

        foreach ($pendingOrders as $order) {
            $key = $order->user_uuid.'|'.$order->package_option_uuid;

            if (isset($seenKeys[$key])) {
                continue;
            }

            $seenKeys[$key] = true;
            DB::table('orders')->where('uuid', $order->uuid)->update(['pending_key' => $key]);
        }

        if (! Schema::hasIndex('orders', 'orders_pending_key_unique', 'unique')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->unique('pending_key', 'orders_pending_key_unique');
            });
        }
    }

    public function down(): void {}
};
