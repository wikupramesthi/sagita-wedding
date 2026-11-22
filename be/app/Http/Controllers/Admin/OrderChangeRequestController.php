<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Payment\EventDateReservation;
use App\Models\Payment\Order;
use App\Models\Payment\OrderChangeRequest;
use App\Models\Payment\WeddingChecklistItem;
use App\Services\EventDateReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderChangeRequestController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $changeRequests = OrderChangeRequest::with([
            'order.user',
            'order.package',
            'order.packageOption',
            'addon',
            'requestedBy',
            'reviewedBy',
        ])->latest()->paginate(25);

        return view('pages.orders.change-requests', compact('changeRequests'));
    }

    public function store(Request $request, string $order)
    {
        $record = Order::packageOnly()->where('uuid', $order)->firstOrFail();
        abort_unless($record->user_uuid === Auth::user()->uuid, 403);
        abort_if(! $record->event_date || $record->event_date->lt(today()), 403);

        $validated = $request->validate([
            'change_type' => ['required', 'in:event_date,addon'],
            'requested_event_date' => ['exclude_unless:change_type,event_date', 'required', 'date', 'after_or_equal:tomorrow'],
            'addon_uuid' => ['exclude_unless:change_type,addon', 'required', 'uuid'],
            'addon_quantity' => ['exclude_unless:change_type,addon', 'required', 'integer', 'min:1', 'max:99'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if (OrderChangeRequest::where('order_uuid', $record->uuid)->where('status', 'pending')->exists()) {
            throw ValidationException::withMessages([
                'change_type' => 'Masih ada permintaan perubahan yang menunggu persetujuan admin.',
            ]);
        }

        $addon = null;
        $quantity = null;
        $requestedPrice = 0;
        if ($validated['change_type'] === 'addon') {
            $addon = PackageAddon::where('uuid', $validated['addon_uuid'])
                ->where('package_uuid', $record->package_uuid)
                ->where('is_active', true)
                ->firstOrFail();
            $quantity = (int) $validated['addon_quantity'];
            $min = max(1, (int) ($addon->min_quantity ?? 1));
            $max = $addon->max_quantity === null ? 99 : min(99, (int) $addon->max_quantity);
            $existingLine = collect($record->addons ?? [])->firstWhere('uuid', $addon->uuid);
            $alreadySelected = (int) ($existingLine['quantity'] ?? 0);
            if ($quantity < $min || $alreadySelected + $quantity > max($min, $max)) {
                throw ValidationException::withMessages([
                    'addon_quantity' => 'Jumlah add-on tidak boleh melebihi '.max($min, $max).'.',
                ]);
            }
            if ($addon->requires_addon_uuid && ! collect($record->addons ?? [])->contains(
                fn (array $line) => ($line['uuid'] ?? null) === $addon->requires_addon_uuid
            )) {
                throw ValidationException::withMessages([
                    'addon_uuid' => 'Add-on ini membutuhkan layanan tambahan lain yang belum ada di pesanan.',
                ]);
            }
            $requestedPrice = (int) $addon->final_price * $quantity;
        }

        OrderChangeRequest::create([
            'order_uuid' => $record->uuid,
            'requested_by_uuid' => Auth::user()->uuid,
            'change_type' => $validated['change_type'],
            'requested_event_date' => $validated['requested_event_date'] ?? null,
            'addon_uuid' => $addon?->uuid,
            'addon_quantity' => $quantity,
            'requested_price' => $requestedPrice,
            'customer_note' => $validated['customer_note'] ?? null,
        ]);

        return back()->with('success', 'Permintaan perubahan telah dikirim dan menunggu persetujuan admin.');
    }

    public function review(Request $request, string $changeRequest, EventDateReservationService $reservations)
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($changeRequest, $validated, $reservations): void {
            $change = OrderChangeRequest::where('uuid', $changeRequest)->lockForUpdate()->firstOrFail();
            if ($change->status !== 'pending') {
                throw ValidationException::withMessages(['decision' => 'Permintaan ini sudah ditinjau.']);
            }

            $order = Order::packageOnly()->where('uuid', $change->order_uuid)->lockForUpdate()->firstOrFail();
            if (! in_array($order->status, ['pending', 'partially_paid', 'paid'], true)) {
                throw ValidationException::withMessages(['decision' => 'Pesanan ini tidak lagi aktif.']);
            }

            if ($validated['decision'] === 'approved') {
                if ($change->change_type === 'event_date') {
                    $this->applyEventDateChange($change, $order, $reservations);
                } else {
                    $this->applyAddonChange($change, $order);
                }
            }

            $change->update([
                'status' => $validated['decision'],
                'admin_note' => $validated['admin_note'] ?? null,
                'reviewed_by_uuid' => Auth::user()->uuid,
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Permintaan perubahan berhasil ditinjau.');
    }

    private function applyEventDateChange(
        OrderChangeRequest $change,
        Order $order,
        EventDateReservationService $reservations
    ): void {
        $newDate = $change->requested_event_date;
        if (! $newDate || $newDate->lt(today())) {
            throw ValidationException::withMessages(['decision' => 'Tanggal acara yang diminta sudah lewat.']);
        }
        if (PackageBlackoutDate::where('package_uuid', $order->package_uuid)
            ->whereDate('date', $newDate->toDateString())
            ->exists()) {
            throw ValidationException::withMessages(['decision' => 'Tanggal tersebut merupakan tanggal yang tidak tersedia untuk paket ini.']);
        }

        $oldDate = $order->event_date;
        if ($oldDate?->toDateString() === $newDate->toDateString()) {
            return;
        }

        $newReservation = $reservations->lockDate($newDate, $order->uuid);
        $reservations->hold($newReservation, $order);
        if ($order->status !== 'pending') {
            $newReservation->forceFill([
                'status' => 'confirmed',
                'expires_at' => null,
            ])->save();
        }
        if ($oldDate) {
            EventDateReservation::whereDate('event_date', $oldDate->toDateString())
                ->where('order_uuid', $order->uuid)
                ->update([
                    'order_uuid' => null,
                    'status' => 'available',
                    'expires_at' => null,
                    'updated_at' => now(),
                ]);
        }

        $dayShift = $oldDate ? Carbon::parse($oldDate)->diffInDays($newDate, false) : 0;
        $order->event_date = $newDate->toDateString();
        $order->save();
        if ($dayShift !== 0) {
            WeddingChecklistItem::where('order_uuid', $order->uuid)
                ->where('status', 'pending')
                ->whereNotNull('due_at')
                ->get()
                ->each(fn (WeddingChecklistItem $item) => $item->update([
                    'due_at' => $item->due_at->copy()->addDays($dayShift)->toDateString(),
                ]));
        }
    }

    private function applyAddonChange(OrderChangeRequest $change, Order $order): void
    {
        $addon = PackageAddon::where('uuid', $change->addon_uuid)
            ->where('package_uuid', $order->package_uuid)
            ->where('is_active', true)
            ->first();
        if (! $addon) {
            throw ValidationException::withMessages(['decision' => 'Add-on ini tidak lagi tersedia.']);
        }

        $quantity = (int) $change->addon_quantity;
        $min = max(1, (int) ($addon->min_quantity ?? 1));
        $max = $addon->max_quantity === null ? 99 : min(99, (int) $addon->max_quantity);
        $existingLine = collect($order->addons ?? [])->firstWhere('uuid', $addon->uuid);
        $alreadySelected = (int) ($existingLine['quantity'] ?? 0);
        if ($quantity < $min || $alreadySelected + $quantity > max($min, $max)) {
            throw ValidationException::withMessages(['decision' => 'Jumlah add-on tidak lagi valid.']);
        }
        if ($addon->requires_addon_uuid && ! collect($order->addons ?? [])->contains(
            fn (array $line) => ($line['uuid'] ?? null) === $addon->requires_addon_uuid
        )) {
            throw ValidationException::withMessages(['decision' => 'Add-on prasyarat tidak lagi tersedia di pesanan.']);
        }

        $unitPrice = (int) $addon->final_price;
        $additionalPrice = $unitPrice * $quantity;
        $addons = collect($order->addons ?? [])->values();
        $existingIndex = $addons->search(fn (array $line) => ($line['uuid'] ?? null) === $addon->uuid);
        if ($existingIndex !== false) {
            $line = $addons[$existingIndex];
            $line['quantity'] = (int) ($line['quantity'] ?? 0) + $quantity;
            $line['line_total'] = (int) ($line['line_total'] ?? 0) + $additionalPrice;
            $addons[$existingIndex] = $line;
        } else {
            $addons->push([
                'uuid' => $addon->uuid,
                'name' => $addon->name,
                'price' => $unitPrice,
                'quantity' => $quantity,
                'line_total' => $additionalPrice,
            ]);
        }

        $newTotal = (float) $order->full_amount + $additionalPrice;
        $paid = (float) $order->amount_paid_value;
        $order->addons = $addons->all();
        $order->total_amount = $newTotal;
        $order->remaining_amount = max(0, $newTotal - $paid);
        $schedule = $order->installment_schedule ?? [];
        if (! empty($schedule['installments'])) {
            $lastInstallment = count($schedule['installments']) - 1;
            $schedule['installments'][$lastInstallment]['amount'] =
                (int) ($schedule['installments'][$lastInstallment]['amount'] ?? 0) + $additionalPrice;
            $order->installment_schedule = $schedule;
        }
        if ($order->status === 'paid' && $order->remaining_amount > 0) {
            $order->status = 'partially_paid';
            $order->paid_at = null;
            $order->balance_due_at = $order->event_date?->copy()->subDays(30)->startOfDay();
        }
        $snapshot = $order->promo_snapshot ?? [];
        $snapshot['full_amount'] = $newTotal;
        $order->promo_snapshot = $snapshot;
        $order->save();
    }
}
