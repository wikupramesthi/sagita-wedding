<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment\Order;
use App\Models\Payment\OrderCancellationRequest;
use App\Models\Payment\OrderChangeRequest;
use App\Models\Payment\OrderVendorAssignment;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\OrderWorkflowNotification;
use App\Services\EventDateReservationService;
use App\Services\TelegramAdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderCancellationController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $cancellationRequests = OrderCancellationRequest::with([
            'order.user',
            'order.package',
            'order.packageOption',
            'order.customPackageOffer',
            'order.payments',
            'requestedBy',
            'reviewedBy',
            'refundRecordedBy',
        ])->latest()->paginate(25);

        return view('pages.orders.cancellations', compact('cancellationRequests'));
    }

    public function store(Request $request, string $order)
    {
        $validated = $request->validate([
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);
        $user = Auth::user();

        $cancellation = DB::transaction(function () use ($order, $validated, $user): OrderCancellationRequest {
            $record = Order::packageOnly()->where('uuid', $order)->lockForUpdate()->firstOrFail();
            abort_unless($record->user_uuid === $user->uuid, 403);
            $this->ensureCancellable($record);

            if ($record->created_at && now()->greaterThan($record->created_at->copy()->addDays(7))) {
                throw ValidationException::withMessages([
                    'cancellation' => 'Permintaan pembatalan hanya dapat diajukan dalam 7 hari sejak pesanan dibuat.',
                ]);
            }

            if ($record->cancellationRequests()->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages([
                    'cancellation' => 'Permintaan pembatalan pesanan ini masih menunggu tinjauan admin.',
                ]);
            }

            return $record->cancellationRequests()->create([
                'requested_by_uuid' => $user->uuid,
                'customer_note' => $validated['customer_note'] ?? null,
            ]);
        });

        $record = $cancellation->order;
        $this->logOrderAction($record, 'cancellation_requested', [
            'cancellation_request_uuid' => $cancellation->uuid,
        ]);

        $admins = User::role(['super-admin', 'admin'])->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new OrderWorkflowNotification(
                $record->uuid,
                'Permintaan pembatalan pesanan',
                'Pelanggan mengajukan pembatalan untuk pesanan '.$record->order_number.'.',
                'bi-calendar2-x'
            ));
        }

        try {
            app(TelegramAdminNotifier::class)->cancellationRequested($record);
        } catch (Throwable $exception) {
            Log::error('Telegram cancellation request notification failed: '.$exception->getMessage());
        }

        return redirect()
            ->route('orders.show', $record->uuid)
            ->with('success', 'Permintaan pembatalan dikirim. Admin akan meninjau pengajuan dan nominal refund.');
    }

    public function review(Request $request, string $cancellationRequest, EventDateReservationService $reservations)
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'refund_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);
        $reviewer = Auth::user();

        $result = DB::transaction(function () use ($cancellationRequest, $validated, $reviewer, $reservations): array {
            $cancellation = OrderCancellationRequest::where('uuid', $cancellationRequest)
                ->lockForUpdate()
                ->firstOrFail();
            if ($cancellation->status !== 'pending') {
                throw ValidationException::withMessages([
                    'decision' => 'Permintaan pembatalan ini sudah ditinjau.',
                ]);
            }

            $order = Order::packageOnly()->where('uuid', $cancellation->order_uuid)->lockForUpdate()->firstOrFail();
            $refundAmount = 0.0;
            if ($validated['decision'] === 'approved') {
                $this->ensureCancellable($order);
                $refundAmount = round((float) ($validated['refund_amount'] ?? 0), 2);
                $verifiedAmount = $this->verifiedPaymentAmount($order);
                if ($refundAmount - $verifiedAmount > 0.01) {
                    throw ValidationException::withMessages([
                        'refund_amount' => 'Nominal refund tidak boleh melebihi total pembayaran yang telah diverifikasi (Rp '.number_format($verifiedAmount, 0, ',', '.').').',
                    ]);
                }

                $order->update([
                    'status' => 'cancelled',
                    'remaining_amount' => 0,
                    'balance_due_at' => null,
                ]);
                Payment::where('order_uuid', $order->uuid)
                    ->whereNotIn('status', ['paid', 'failed'])
                    ->where(function ($query): void {
                        $query->where('verification_status', 'pending')
                            ->orWhere('status', 'pending');
                    })
                    ->update([
                        'status' => 'failed',
                        'verification_status' => 'rejected',
                    ]);
                UserPackage::where('order_uuid', $order->uuid)
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled']);
                OrderChangeRequest::where('order_uuid', $order->uuid)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'admin_note' => 'Ditutup karena pembatalan pesanan disetujui.',
                        'reviewed_by_uuid' => $reviewer->uuid,
                        'reviewed_at' => now(),
                    ]);
                OrderVendorAssignment::where('order_uuid', $order->uuid)
                    ->whereNotIn('status', [
                        OrderVendorAssignment::STATUS_COMPLETED,
                        OrderVendorAssignment::STATUS_CANCELLED,
                    ])
                    ->update(['status' => OrderVendorAssignment::STATUS_CANCELLED]);
                $reservations->releaseForOrder($order->uuid);
            }

            $cancellation->update([
                'status' => $validated['decision'],
                'refund_amount' => $refundAmount,
                'refund_status' => $validated['decision'] === 'approved' && $refundAmount > 0
                    ? 'pending'
                    : 'not_applicable',
                'admin_note' => $validated['admin_note'] ?? null,
                'reviewed_by_uuid' => $reviewer->uuid,
                'reviewed_at' => now(),
            ]);

            return [
                'cancellation' => $cancellation->fresh(),
                'order' => $order->fresh(),
                'verified_amount' => $validated['decision'] === 'approved'
                    ? $verifiedAmount
                    : 0.0,
            ];
        });

        $cancellation = $result['cancellation'];
        $order = $result['order'];
        $this->logOrderAction($order, $validated['decision'] === 'approved' ? 'cancellation_approved' : 'cancellation_rejected', [
            'cancellation_request_uuid' => $cancellation->uuid,
            'refund_amount' => (float) $cancellation->refund_amount,
            'verified_payment_amount' => $result['verified_amount'],
        ]);
        $cancellation->requestedBy?->notify(new OrderWorkflowNotification(
            $order->uuid,
            $validated['decision'] === 'approved' ? 'Pembatalan pesanan disetujui' : 'Permintaan pembatalan ditolak',
            $validated['decision'] === 'approved'
                ? ($cancellation->refund_amount > 0
                    ? 'Pesanan '.$order->order_number.' dibatalkan. Refund Rp '.number_format((float) $cancellation->refund_amount, 0, ',', '.').' menunggu transfer manual admin.'
                    : 'Pesanan '.$order->order_number.' dibatalkan tanpa refund.')
                : 'Admin menolak pembatalan pesanan '.$order->order_number.'. Catatan: '.($cancellation->admin_note ?: 'Silakan hubungi admin untuk detail.'),
            $validated['decision'] === 'approved' ? 'bi-calendar2-x' : 'bi-info-circle'
        ));

        return back()->with('success', 'Permintaan pembatalan berhasil ditinjau.');
    }

    public function recordRefund(Request $request, string $cancellationRequest)
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $validated = $request->validate([
            'refund_reference' => ['required', 'string', 'min:2', 'max:255'],
        ]);
        $user = Auth::user();

        $cancellation = DB::transaction(function () use ($cancellationRequest, $validated, $user): OrderCancellationRequest {
            $record = OrderCancellationRequest::where('uuid', $cancellationRequest)
                ->lockForUpdate()
                ->firstOrFail();
            if ($record->status !== 'approved' || $record->refund_status !== 'pending' || (float) $record->refund_amount <= 0) {
                throw ValidationException::withMessages([
                    'refund_reference' => 'Refund ini tidak berada dalam status menunggu pencatatan.',
                ]);
            }

            $record->update([
                'refund_status' => 'refunded',
                'refund_reference' => trim($validated['refund_reference']),
                'refund_recorded_by_uuid' => $user->uuid,
                'refund_recorded_at' => now(),
            ]);

            return $record->fresh();
        });

        $this->logOrderAction($cancellation->order, 'refund_recorded', [
            'cancellation_request_uuid' => $cancellation->uuid,
            'refund_amount' => (float) $cancellation->refund_amount,
            'refund_reference' => $cancellation->refund_reference,
        ]);
        $cancellation->requestedBy?->notify(new OrderWorkflowNotification(
            $cancellation->order_uuid,
            'Refund pesanan telah dicatat',
            'Refund Rp '.number_format((float) $cancellation->refund_amount, 0, ',', '.').' untuk pesanan '.$cancellation->order?->order_number.' telah ditransfer. Referensi: '.$cancellation->refund_reference.'.',
            'bi-cash-coin'
        ));

        return back()->with('success', 'Transfer refund berhasil dicatat.');
    }

    private function ensureCancellable(Order $order): void
    {
        if (! in_array($order->status, ['pending', 'partially_paid', 'paid'], true)) {
            throw ValidationException::withMessages([
                'cancellation' => 'Pesanan tidak lagi aktif dan tidak dapat dibatalkan.',
            ]);
        }

        if ($order->status === 'pending' && $order->expired_at?->isPast()) {
            throw ValidationException::withMessages([
                'cancellation' => 'Pesanan yang sudah kedaluwarsa tidak dapat diajukan untuk pembatalan.',
            ]);
        }

        if ($order->event_date?->lt(today())) {
            throw ValidationException::withMessages([
                'cancellation' => 'Pembatalan tidak dapat diajukan setelah tanggal acara.',
            ]);
        }
    }

    private function verifiedPaymentAmount(Order $order): float
    {
        return round((float) Payment::where('order_uuid', $order->uuid)
            ->where(function ($query): void {
                $query->whereIn('verification_status', ['verified', 'approved'])
                    ->orWhere('status', 'paid');
            })
            ->sum(DB::raw('COALESCE(amount, gross_amount, 0)')), 2);
    }

    private function logOrderAction(Order $order, string $event, array $newValues): void
    {
        $user = Auth::user();

        try {
            AuditLog::create([
                'user_uuid' => $user?->uuid,
                'user_name' => $user?->name ?? 'Sistem',
                'user_email' => $user?->email,
                'event' => $event,
                'auditable_type' => Order::class,
                'auditable_id' => (string) $order->uuid,
                'auditable_label' => $order->order_number,
                'new_values' => $newValues,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Order cancellation audit log failed: '.$exception->getMessage(), [
                'order' => $order->uuid,
                'event' => $event,
            ]);
        }
    }
}
