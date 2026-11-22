<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    /**
     * List members who purchased a membership (user packages), with payment and event monitoring.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        $query = UserPackage::with(['user', 'package', 'order.packageOption']);

        if (!$isAdmin) {
            $query->where('user_uuid', $user->uuid);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search') && $isAdmin) {
            $search = $request->input('search');

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('expiring')) {
            $query->where('status', 'active')
                ->whereNotNull('expired_at')
                ->whereBetween('expired_at', [now(), now()->addDays(7)]);
        }

        $statsQuery = clone $query;

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('status', 'active')->count(),
            'expiring' => (clone $statsQuery)->where('status', 'active')
                ->whereNotNull('expired_at')
                ->whereBetween('expired_at', [now(), now()->addDays(7)])->count(),
            'expired' => (clone $statsQuery)->where('status', 'expired')->count(),
        ];

        $orderQuery = Order::whereIn(
            'uuid',
            (clone $statsQuery)->select('user_packages.order_uuid')
        );

        $orderColumns = [
            'uuid',
            'user_uuid',
            'package_uuid',
            'status',
            'amount',
            'total_amount',
            'amount_paid',
            'remaining_amount',
            'event_date',
            'balance_due_at',
            'payment_scheme',
        ];

        $orderRows = (clone $orderQuery)->get($orderColumns);

        $finance = [
            'total' => 0.0,
            'paid' => 0.0,
            'remaining' => 0.0,
            'fully_paid' => 0,
            'partial' => 0,
            'awaiting' => 0,
            'progress' => 0,
        ];

        foreach ($orderRows as $order) {
            $finance['total'] += $order->full_amount;
            $finance['paid'] += $order->amount_paid_value;
            $finance['remaining'] += $order->remaining_amount_value;

            if ($order->isFullyPaid()) {
                $finance['fully_paid']++;
            } elseif ($order->hasOutstandingBalance()) {
                $finance['partial']++;
            } else {
                $finance['awaiting']++;
            }
        }

        $finance['progress'] = $finance['total'] > 0
            ? (int) round(min(100, max(0, $finance['paid'] / $finance['total'] * 100)))
            : 0;

        $today = now()->startOfDay();
        $horizon = $today->copy()->addDays(30);

        $stats['events_soon'] = $orderRows->filter(
            fn (Order $order) => $order->event_date
                && $order->event_date->gte($today)
                && $order->event_date->lte($horizon)
        )->count();

        $upcomingEvents = (clone $orderQuery)
            ->whereNotNull('event_date')
            ->whereDate('event_date', '>=', $today->toDateString())
            ->with(['user', 'package'])
            ->orderBy('event_date')
            ->limit(5)
            ->get($orderColumns)
            ->map(fn (Order $order) => [
                'order' => $order,
                'customer' => $order->user?->name ?? '-',
                'package' => $order->package?->name ?? '-',
                'days' => (int) $today->diffInDays($order->event_date->copy()->startOfDay()),
            ])
            ->all();

        $memberships = $query
            ->latest('started_at')
            ->paginate(10)
            ->withQueryString();

        $viewData = [
            'memberships' => $memberships,
            'stats' => $stats,
            'finance' => $finance,
            'upcomingEvents' => $upcomingEvents,
            'isAdmin' => $isAdmin,
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'expiring' => $request->input('expiring'),
            ],
        ];

        // Member: laptop/desktop = view desktop, HP = phone-frame mobile.
        if (! $isAdmin && \App\Support\MemberView::isMobile()) {
            $viewData['unreadCount'] = $user->unreadNotifications()->count();

            return view('pages.mobile.memberships', $viewData);
        }

        return view('pages.memberships.index', $viewData);
    }

    /**
     * Member detail + payment monitoring.
     */
    public function show(string $uuid)
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        $membership = UserPackage::with([
            'user',
            'package',
            'order.packageOption',
            'order.payments',
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (!$isAdmin && $membership->user_uuid !== $user->uuid) {
            abort(403);
        }

        $bookings = collect();

        if (! $isAdmin && \App\Support\MemberView::isMobile()) {
            return view('pages.mobile.membership-show', compact('membership', 'bookings', 'isAdmin'));
        }

        return view('pages.memberships.show', compact('membership', 'bookings', 'isAdmin'));
    }
}
