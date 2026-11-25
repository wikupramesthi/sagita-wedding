<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCalendarController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $month = Carbon::createFromFormat('!Y-m', $validated['month'] ?? now()->format('Y-m'));
        $firstDay = $month->copy()->startOfMonth();
        $gridStart = $firstDay->copy()->startOfWeek(CarbonInterface::MONDAY);
        $days = collect(range(0, 41))
            ->map(fn (int $offset) => $gridStart->copy()->addDays($offset));

        $ordersByDate = Order::packageOnly()
            ->with(['user', 'package', 'customPackageOffer'])
            ->withCount(['checklistItems', 'vendorAssignments'])
            ->whereBetween('event_date', [
                $firstDay->toDateString(),
                $firstDay->copy()->endOfMonth()->toDateString(),
            ])
            ->where(function ($query): void {
                $query->whereIn('status', ['partially_paid', 'paid'])
                    ->orWhere(function ($pendingQuery): void {
                        $pendingQuery->where('status', 'pending')
                            ->where(function ($expiryQuery): void {
                                $expiryQuery->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', now());
                            });
                    });
            })
            ->orderBy('event_date')
            ->orderBy('order_number')
            ->get()
            ->groupBy(fn (Order $order): string => $order->event_date->toDateString());

        return view('pages.orders.calendar', [
            'month' => $firstDay,
            'days' => $days,
            'ordersByDate' => $ordersByDate,
            'previousMonth' => $firstDay->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $firstDay->copy()->addMonth()->format('Y-m'),
        ]);
    }
}
