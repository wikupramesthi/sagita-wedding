<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_scheme')) {
                $table->string('payment_scheme', 20)->default('full')->after('amount');
            }
            if (! Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->nullable()->after('payment_scheme');
            }
            if (! Schema::hasColumn('orders', 'amount_paid')) {
                $table->decimal('amount_paid', 15, 2)->default(0)->after('total_amount');
            }
            if (! Schema::hasColumn('orders', 'remaining_amount')) {
                $table->decimal('remaining_amount', 15, 2)->default(0)->after('amount_paid');
            }
            if (! Schema::hasColumn('orders', 'balance_due_at')) {
                $table->timestamp('balance_due_at')->nullable()->after('remaining_amount');
            }
        });

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','paid','partially_paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending'");
        }

        $orders = DB::table('orders')->select('uuid', 'amount', 'status', 'promo_snapshot')->get();
        foreach ($orders as $order) {
            $snapshot = $order->promo_snapshot;
            if (is_string($snapshot) && $snapshot !== '') {
                $decoded = json_decode($snapshot, true);
                $snapshot = is_array($decoded) ? $decoded : [];
            } elseif (! is_array($snapshot)) {
                $snapshot = [];
            }

            $amount = (float) ($order->amount ?? 0);
            $scheme = (string) ($snapshot['payment_scheme'] ?? 'full');
            if (! in_array($scheme, ['dp30', 'dp50', 'full'], true)) {
                $scheme = 'full';
            }
            $fullAmount = (float) ($snapshot['full_amount'] ?? $amount);
            if ($fullAmount <= 0) {
                $fullAmount = $amount;
            }

            $isPaid = $order->status === 'paid';
            $isPartiallyPaid = $order->status === 'partially_paid';
            $amountPaid = $isPaid ? $fullAmount : ($isPartiallyPaid ? $amount : 0.0);
            $remaining = max(0, $fullAmount - $amountPaid);

            DB::table('orders')->where('uuid', $order->uuid)->update([
                'payment_scheme' => $scheme,
                'total_amount' => $fullAmount,
                'amount_paid' => $amountPaid,
                'remaining_amount' => $remaining,
            ]);
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("UPDATE orders SET status='pending' WHERE status='partially_paid'");
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('orders', function (Blueprint $table) {
            $cols = ['payment_scheme', 'total_amount', 'amount_paid', 'remaining_amount', 'balance_due_at'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
