<?php

namespace App\Services;

use App\Models\Payment\EventDateReservation;
use App\Models\Payment\Order;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventDateReservationService
{
    public function lockDate(CarbonInterface|string $date, ?string $excludeOrderUuid = null): EventDateReservation
    {
        $dateValue = $date instanceof CarbonInterface ? $date->toDateString() : $date;
        $now = now();

        DB::table('event_date_reservations')->insertOrIgnore([
            'event_date' => $dateValue,
            'status' => 'available',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $reservation = EventDateReservation::whereDate('event_date', $dateValue)
            ->lockForUpdate()
            ->firstOrFail();

        $reservationActive = $reservation->order_uuid !== null
            && ($reservation->status === 'confirmed'
                || ($reservation->status === 'held' && $reservation->expires_at?->isFuture()));

        if ($reservationActive && $reservation->order_uuid !== $excludeOrderUuid) {
            throw $this->unavailableDateException();
        }

        $conflictingOrder = $this->activeOrdersForDate($dateValue, $excludeOrderUuid)->first();
        if ($conflictingOrder) {
            $reservation->forceFill([
                'order_uuid' => $conflictingOrder->uuid,
                'status' => $conflictingOrder->status === 'pending' ? 'held' : 'confirmed',
                'expires_at' => $conflictingOrder->status === 'pending' ? $conflictingOrder->expired_at : null,
            ])->save();

            throw $this->unavailableDateException();
        }

        if ($reservation->order_uuid !== null) {
            $reservation->forceFill([
                'order_uuid' => null,
                'status' => 'available',
                'expires_at' => null,
            ])->save();
        }

        return $reservation;
    }

    public function hold(EventDateReservation $reservation, Order $order): void
    {
        $reservation->forceFill([
            'order_uuid' => $order->uuid,
            'status' => 'held',
            'expires_at' => $order->expired_at,
        ])->save();
    }

    public function releaseForOrder(string $orderUuid): void
    {
        EventDateReservation::where('order_uuid', $orderUuid)->update([
            'order_uuid' => null,
            'status' => 'available',
            'expires_at' => null,
            'updated_at' => now(),
        ]);
    }

    public function unavailableDates(?string $from = null): Collection
    {
        $reservedDates = EventDateReservation::query()
            ->whereNotNull('order_uuid')
            ->where(function ($query) {
                $query->where('status', 'confirmed')
                    ->orWhere(function ($heldQuery) {
                        $heldQuery->where('status', 'held')
                            ->where('expires_at', '>', now());
                    });
            })
            ->when($from, fn ($query) => $query->whereDate('event_date', '>=', $from))
            ->pluck('event_date');

        $activeOrderDates = Order::packageOnly()
            ->whereNotNull('event_date')
            ->where(function ($query) {
                $query->whereIn('status', ['partially_paid', 'paid'])
                    ->orWhere(function ($pendingQuery) {
                        $pendingQuery->where('status', 'pending')
                            ->where(function ($expiryQuery) {
                                $expiryQuery->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', now());
                            });
                    });
            })
            ->when($from, fn ($query) => $query->whereDate('event_date', '>=', $from))
            ->pluck('event_date');

        return $reservedDates
            ->merge($activeOrderDates)
            ->map(fn ($date) => $date instanceof CarbonInterface ? $date->toDateString() : substr((string) $date, 0, 10))
            ->unique()
            ->sort()
            ->values();
    }

    private function activeOrdersForDate(string $date, ?string $excludeOrderUuid = null)
    {
        return Order::packageOnly()
            ->whereDate('event_date', $date)
            ->where(function ($query) {
                $query->whereIn('status', ['partially_paid', 'paid'])
                    ->orWhere(function ($pendingQuery) {
                        $pendingQuery->where('status', 'pending')
                            ->where(function ($expiryQuery) {
                                $expiryQuery->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', now());
                            });
                    });
            })
            ->when($excludeOrderUuid, fn ($query) => $query->where('uuid', '!=', $excludeOrderUuid))
            ->latest('created_at');
    }

    private function unavailableDateException(): ValidationException
    {
        return ValidationException::withMessages([
            'event_date' => 'Tanggal acara sudah dipesan atau sedang ditahan. Silakan pilih tanggal lain.',
        ]);
    }
}
