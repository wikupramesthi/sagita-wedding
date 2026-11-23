<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'kind')) {
                $table->string('kind', 30)->default('full')->after('order_uuid');
            }
            if (! Schema::hasColumn('payments', 'proof_image_path')) {
                $table->string('proof_image_path')->nullable()->after('raw_response');
            }
            if (! Schema::hasColumn('payments', 'proof_uploaded_at')) {
                $table->timestamp('proof_uploaded_at')->nullable()->after('proof_image_path');
            }
        });

        // Backfill kind from linked order scheme, and attach existing order proof to paid payment.
        $payments = DB::table('payments')->select('uuid', 'order_uuid', 'status')->get();
        foreach ($payments as $payment) {
            $order = DB::table('orders')->where('uuid', $payment->order_uuid)->select('payment_scheme', 'status', 'proof_image_path', 'proof_uploaded_at', 'amount', 'total_amount')->first();
            if (! $order) {
                continue;
            }
            $scheme = (string) ($order->payment_scheme ?? 'full');
            $kind = 'full';
            if ($payment->status === 'paid' && in_array($scheme, ['dp30', 'dp50'], true)) {
                $orderFull = (float) ($order->total_amount ?? 0);
                $orderAmount = (float) ($order->amount ?? 0);
                // If order is fully paid, existing single payment represents final settlement for legacy; keep as balance/full based on scheme.
                $kind = $order->status === 'paid' && $orderFull > 0 && abs($orderAmount - $orderFull) < 0.01 ? 'full' : 'dp';
            }
            $update = ['kind' => $kind];
            if ($order->proof_image_path) {
                $update['proof_image_path'] = $order->proof_image_path;
                $update['proof_uploaded_at'] = $order->proof_uploaded_at;
            }
            DB::table('payments')->where('uuid', $payment->uuid)->update($update);
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['kind', 'proof_image_path', 'proof_uploaded_at'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
