<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\UserPackage;
use App\Support\MemberView;
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

        $query = UserPackage::with(['user', 'package', 'order.packageOption', 'order.payments']);

        $this->applyFilters($request, $query, $user, $isAdmin);

        $statsQuery = UserPackage::query();
        $this->applyFilters($request, $statsQuery, $user, $isAdmin);

        $statsRow = (clone $statsQuery)
            ->selectRaw(
                'COUNT(*) as total, '.
                "SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active, ".
                "SUM(CASE WHEN status = 'active' AND expired_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as expiring, ".
                "SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired",
                [now(), now()->addDays(7)]
            )
            ->first();

        $stats = [
            'total' => (int) $statsRow->total,
            'active' => (int) $statsRow->active,
            'expiring' => (int) $statsRow->expiring,
            'expired' => (int) $statsRow->expired,
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

        $finance = [
            'total' => 0.0,
            'paid' => 0.0,
            'remaining' => 0.0,
            'fully_paid' => 0,
            'partial' => 0,
            'awaiting' => 0,
            'progress' => 0,
        ];

        (clone $orderQuery)
            ->select($orderColumns)
            ->chunkById(500, function ($orders) use (&$finance): void {
                foreach ($orders as $order) {
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
            }, 'orders.uuid', 'uuid');

        $finance['progress'] = $finance['total'] > 0
            ? (int) round(min(100, max(0, $finance['paid'] / $finance['total'] * 100)))
            : 0;

        $today = now()->startOfDay();
        $horizon = $today->copy()->addDays(30);

        $stats['events_soon'] = (clone $orderQuery)
            ->whereBetween('event_date', [$today->toDateString(), $horizon->toDateString()])
            ->count();

        $upcomingEvents = (clone $orderQuery)
            ->where('event_date', '>=', $today->toDateString())
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

        $followUps = (clone $query)
            ->whereHas('order', fn ($q) => $q
                ->whereIn('status', ['pending', 'partially_paid'])
                ->where(function ($q) {
                    $q->where('remaining_amount', '>', 0.01)
                        ->orWhereNull('remaining_amount');
                }))
            ->limit(20)
            ->get()
            ->filter(fn (UserPackage $membership) => $membership->order?->hasOutstandingBalance())
            ->sortBy(fn (UserPackage $membership) => $membership->event_date?->timestamp ?? PHP_INT_MAX)
            ->take(5)
            ->map(fn (UserPackage $membership) => [
                'membership' => $membership,
                'customer' => $membership->user?->name ?? '-',
                'package' => $membership->package?->name ?? '-',
                'remaining' => $membership->remaining_amount_value,
                'days' => $membership->days_to_event,
                'last_payment' => $membership->order->payments->sortByDesc('created_at')->first(),
            ])
            ->values()
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
            'followUps' => $followUps,
            'isAdmin' => $isAdmin,
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'expiring' => $request->input('expiring'),
                'payment' => $request->input('payment'),
                'event' => $request->input('event'),
            ],
        ];

        // Member: laptop/desktop = view desktop, HP = phone-frame mobile.
        if (! $isAdmin && MemberView::isMobile()) {
            $viewData['unreadCount'] = $user->unreadNotifications()->count();

            return view('pages.mobile.memberships', $viewData);
        }

        return view('pages.memberships.index', $viewData);
    }

    /**
     * Export the filtered membership list (admin only).
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if (! $user->hasAnyRole(['super-admin', 'admin'])) {
            abort(403);
        }

        $query = UserPackage::with(['user', 'package', 'order']);

        $this->applyFilters($request, $query, $user, true);

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                throw new \RuntimeException('Tidak dapat membuka aliran ekspor CSV.');
            }

            fputcsv($handle, [
                'Pelanggan', 'Email', 'Paket', 'No. Pesanan', 'Status Paket',
                'Status Pembayaran', 'Total', 'Dibayar', 'Sisa', 'Tanggal Acara',
                'Mulai', 'Berakhir',
            ]);

            $query->latest('started_at')->chunk(500, function ($memberships) use ($handle): void {
                foreach ($memberships as $membership) {
                    fputcsv($handle, array_map(
                        [$this, 'csvSafe'],
                        [
                            $membership->user?->name ?? '-',
                            $membership->user?->email ?? '-',
                            $membership->package?->name ?? '-',
                            $membership->order?->order_number ?? '-',
                            $membership->status,
                            $membership->payment_status_label,
                            $membership->total_amount_value,
                            $membership->amount_paid_value,
                            $membership->remaining_amount_value,
                            $membership->event_date?->format('Y-m-d') ?? '-',
                            $membership->started_at?->format('Y-m-d H:i') ?? '-',
                            $membership->expired_at?->format('Y-m-d H:i') ?? '-',
                        ]
                    ));
                }
            });

            fclose($handle);
        }, 'memberships-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Shared filters for the listing and the CSV export.
     */
    private function applyFilters(Request $request, $query, $user, bool $isAdmin): void
    {
        if (! $isAdmin) {
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

        if ($request->input('event') === '30days') {
            $query->whereHas('order', fn ($q) => $q
                ->whereNotNull('event_date')
                ->whereBetween('event_date', [now()->toDateString(), now()->addDays(30)->toDateString()]));
        }

        $payment = $request->input('payment');

        if ($payment === 'tanpa') {
            $query->whereNull('order_uuid');
        } elseif (in_array($payment, ['lunas', 'cicilan', 'menunggu'], true)) {
            $status = [
                'lunas' => 'paid',
                'cicilan' => 'partially_paid',
                'menunggu' => 'pending',
            ][$payment];

            $query->whereHas('order', fn ($q) => $q->where('status', $status));
        }
    }

    /**
     * Neutralise spreadsheet formula injection in exported cells.
     */
    private function csvSafe($value): string
    {
        $value = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', (string) $value));

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'{$value}" : $value;
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

        if (! $isAdmin && $membership->user_uuid !== $user->uuid) {
            abort(403);
        }

        $bookings = collect();

        if (! $isAdmin && MemberView::isMobile()) {
            return view('pages.mobile.membership-show', compact('membership', 'bookings', 'isAdmin'));
        }

        return view('pages.memberships.show', compact('membership', 'bookings', 'isAdmin'));
    }
}
