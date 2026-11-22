<?php

namespace App\Services;

use App\Models\Payment\Order;
use App\Models\Payment\OrderDocument;
use App\Models\Payment\OrderWorkflowSetting;
use App\Models\Payment\Payment;
use App\Models\Payment\WeddingChecklistItem;
use Illuminate\Support\Facades\DB;

class OrderWorkflowProvisioningService
{
    public function afterVerifiedPayment(Order $order, Payment $payment): void
    {
        if ($order->amount_paid_value <= 0) {
            return;
        }

        DB::transaction(function () use ($order, $payment): void {
            $lockedOrder = Order::packageOnly()
                ->where('uuid', $order->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            $settings = OrderWorkflowSetting::current();
            if (! OrderDocument::where('document_key', $lockedOrder->uuid.':contract')->exists()) {
                $this->createDocument(
                    $lockedOrder,
                    $payment,
                    'contract',
                    $lockedOrder->uuid.':contract',
                    'CTR-'.$lockedOrder->order_number,
                    $this->documentSnapshot($lockedOrder, $payment, $settings->contract_template)
                );
            }

            $invoice = OrderDocument::where('document_key', $lockedOrder->uuid.':invoice')->first();
            if (! $invoice) {
                $this->createDocument(
                    $lockedOrder,
                    $payment,
                    'invoice',
                    $lockedOrder->uuid.':invoice',
                    'INV-'.$lockedOrder->order_number,
                    $this->documentSnapshot($lockedOrder, $payment)
                );
            } elseif ($invoice->payment_uuid !== $payment->uuid
                && ! OrderDocument::where('document_key', $lockedOrder->uuid.':receipt:'.$payment->uuid)->exists()) {
                $this->createDocument(
                    $lockedOrder,
                    $payment,
                    'receipt',
                    $lockedOrder->uuid.':receipt:'.$payment->uuid,
                    'REC-'.$lockedOrder->order_number.'-'.strtoupper(substr(str_replace('-', '', $payment->uuid), -8)),
                    $this->documentSnapshot($lockedOrder, $payment)
                );
            }

            if ($lockedOrder->event_date && ! WeddingChecklistItem::where('order_uuid', $lockedOrder->uuid)->exists()) {
                foreach ($settings->checklist_template ?? [] as $item) {
                    $weeksBefore = max(0, min(104, (int) ($item['weeks_before_event'] ?? 0)));
                    WeddingChecklistItem::create([
                        'order_uuid' => $lockedOrder->uuid,
                        'title' => (string) ($item['title'] ?? ''),
                        'description' => $item['description'] ?? null,
                        'due_at' => $lockedOrder->event_date->copy()->subWeeks($weeksBefore)->toDateString(),
                        'status' => 'pending',
                    ]);
                }
            }
        });
    }

    private function createDocument(
        Order $order,
        Payment $payment,
        string $type,
        string $documentKey,
        string $documentNumber,
        array $snapshot
    ): void {
        OrderDocument::create([
            'order_uuid' => $order->uuid,
            'payment_uuid' => $payment->uuid,
            'document_key' => $documentKey,
            'document_number' => $documentNumber,
            'type' => $type,
            'snapshot' => $snapshot,
            'issued_at' => now(),
        ]);
    }

    private function documentSnapshot(Order $order, Payment $payment, ?string $contractTemplate = null): array
    {
        $order->loadMissing(['user', 'package', 'packageOption']);
        $snapshot = [
            'order_number' => $order->order_number,
            'customer_name' => $order->user?->name ?? '-',
            'package_name' => $order->package?->name ?? '-',
            'option_name' => $order->packageOption?->name ?? '-',
            'event_date' => $order->event_date?->translatedFormat('d F Y') ?? 'Belum ditentukan',
            'total_amount' => (float) $order->full_amount,
            'amount_paid' => (float) $order->amount_paid_value,
            'remaining_amount' => (float) $order->remaining_amount_value,
            'payment_amount' => (float) ($payment->amount ?: $payment->gross_amount),
            'payment_date' => ($payment->paid_at ?: $payment->verified_at ?: now())->translatedFormat('d F Y H:i'),
            'issued_at' => now()->translatedFormat('d F Y'),
        ];

        if ($contractTemplate !== null) {
            $snapshot['contract_template'] = $contractTemplate;
        }

        return $snapshot;
    }
}
