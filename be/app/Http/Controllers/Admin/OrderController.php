<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\OrderSavedFilter;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\MemberActiveNotification;
use App\Notifications\OrderBaruNotification;
use App\Notifications\OrderRejectedNotification;
use App\Notifications\OrderSlaReminderNotification;
use App\Notifications\ProofUploadedNotification;
use App\Services\MembershipService;
use App\Support\ImageShrinker;
use App\Support\MemberView;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Throwable;

class OrderController extends Controller
{
    public function __construct()
    {
        app()->setLocale('id');
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        $savedFilters = OrderSavedFilter::where('user_uuid', $user->uuid)
            ->orderByDesc('pinned_at')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        if (
            ! $request->hasAny(['search', 'status', 'proof', 'start_date', 'end_date', 'per_page', 'sort', 'all'])
            && $defaultFilter = $savedFilters->firstWhere('is_default', true)
        ) {
            $request->merge($defaultFilter->filters ?? []);
        }

        $query = $this->filteredOrders($request, $user);

        /*
        |--------------------------------------------------------------------------
        | Stats (respect current scope + filters)
        |--------------------------------------------------------------------------
        */

        $stats = (clone $query)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN status IN ('failed', 'expired', 'cancelled') THEN 1 ELSE 0 END) as others,
            COALESCE(SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END), 0) as revenue
        ")->first();

        $queue = Order::packageOnly()->where('status', 'pending');
        if (! $isAdmin) {
            $queue->where('user_uuid', $user->uuid);
        }

        $queueCounts = [
            'all' => (clone $queue)->count(),
            'uploaded' => (clone $queue)->whereNotNull('proof_image_path')->count(),
            'missing' => (clone $queue)->whereNull('proof_image_path')->count(),
            'sla' => (clone $queue)->where('created_at', '<=', now()->subHours(24))->count(),
            'claimed' => (clone $queue)->whereNotNull('assigned_admin_uuid')->count(),
        ];

        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $sort = (string) $request->input('sort', 'latest');
        $sort = in_array($sort, ['latest', 'oldest', 'amount_desc', 'amount_asc'], true) ? $sort : 'latest';

        /*
        |--------------------------------------------------------------------------
        | Get Orders
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->with([
                'user',
                'package',
                'packageOption',
                'payment',
            ]);

        match ($sort) {
            'oldest' => $orders->orderBy('created_at', 'asc'),
            'amount_desc' => $orders->orderBy('amount', 'desc'),
            'amount_asc' => $orders->orderBy('amount', 'asc'),
            default => $orders->orderBy('created_at', 'desc'),
        };

        $orders = $orders->paginate($perPage)->withQueryString();

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'proof' => $request->input('proof'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'per_page' => $perPage,
            'sort' => $sort,
        ];

        $searchTerm = trim((string) ($filters['search'] ?? ''));
        $searchTooShort = $searchTerm !== '' && Str::length($searchTerm) < 2;

        // Member: laptop/desktop = view desktop, HP = phone-frame mobile.
        if (! $isAdmin && MemberView::isMobile()) {
            return view('pages.mobile.orders', [
                'orders' => $orders,
                'stats' => $stats,
                'queueCounts' => $queueCounts,
                'filters' => $filters,
                'savedFilters' => $savedFilters,
            ]);
        }

        return view('pages.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'isAdmin' => $isAdmin,
            'queueCounts' => $queueCounts,
            'filters' => $filters,
            'savedFilters' => $savedFilters,
            'searchTooShort' => $searchTooShort,
        ]);
    }

    public function savedFilters()
    {
        abort_unless($this->canManageSavedFilters(), 403);

        return response()->json(
            OrderSavedFilter::where('user_uuid', Auth::user()->uuid)
                ->orderByDesc('pinned_at')
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->get(['uuid', 'name', 'filters', 'is_default', 'pinned_at', 'sort_order'])
        );
    }

    public function storeSavedFilter(Request $request)
    {
        abort_unless($this->canManageSavedFilters(), 403);

        $filters = $request->input('filters');

        if (is_string($filters)) {
            $filters = json_decode($filters, true) ?: [];
        }

        $request->merge(['filters' => is_array($filters) ? $this->normalizeOrderFilters($filters) : []]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'filters' => ['array'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $existing = OrderSavedFilter::where('user_uuid', Auth::user()->uuid)
            ->where('name', $validated['name'])
            ->first();

        $isDefault = array_key_exists('is_default', $validated)
            ? (bool) $validated['is_default']
            : (bool) ($existing?->is_default ?? false);

        $nextSortOrder = (int) OrderSavedFilter::where('user_uuid', Auth::user()->uuid)->max('sort_order') + 1;

        DB::transaction(function () use ($validated, $isDefault, $existing, $nextSortOrder): void {
            if ($isDefault) {
                OrderSavedFilter::where('user_uuid', Auth::user()->uuid)
                    ->update(['is_default' => false]);
            }

            OrderSavedFilter::updateOrCreate(
                [
                    'user_uuid' => Auth::user()->uuid,
                    'name' => $validated['name'],
                ],
                [
                    'filters' => $validated['filters'] ?? [],
                    'is_default' => $isDefault,
                    'sort_order' => $existing?->sort_order ?? $nextSortOrder,
                ]
            );
        });

        return back()->with('success', __('flash.order_saved_filter_saved'));
    }

    public function setDefaultSavedFilter(Request $request, OrderSavedFilter $savedFilter)
    {
        abort_unless($this->canManageSavedFilters(), 403);
        abort_unless($savedFilter->user_uuid === Auth::user()->uuid, 404);

        $isDefault = $request->has('is_default') ? $request->boolean('is_default') : true;

        DB::transaction(function () use ($savedFilter, $isDefault): void {
            if ($isDefault) {
                OrderSavedFilter::where('user_uuid', Auth::user()->uuid)
                    ->update(['is_default' => false]);
            }

            $savedFilter->update(['is_default' => $isDefault]);
        });

        return back()->with('success', __(
            $isDefault ? 'flash.order_saved_filter_default' : 'flash.order_saved_filter_default_off'
        ));
    }

    public function togglePinSavedFilter(OrderSavedFilter $savedFilter)
    {
        abort_unless($this->canManageSavedFilters(), 403);
        abort_unless($savedFilter->user_uuid === Auth::user()->uuid, 404);

        $pinned = ! $savedFilter->pinned_at;
        $savedFilter->update(['pinned_at' => $pinned ? now() : null]);

        return back()->with('success', __(
            $pinned ? 'flash.order_saved_filter_pinned' : 'flash.order_saved_filter_unpinned'
        ));
    }

    public function moveSavedFilter(Request $request, OrderSavedFilter $savedFilter)
    {
        abort_unless($this->canManageSavedFilters(), 403);
        abort_unless($savedFilter->user_uuid === Auth::user()->uuid, 404);

        $validated = $request->validate([
            'direction' => ['required', Rule::in(['up', 'down'])],
        ]);

        $items = OrderSavedFilter::where('user_uuid', Auth::user()->uuid)
            ->orderByDesc('pinned_at')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->values();

        $index = $items->search(fn (OrderSavedFilter $item) => $item->uuid === $savedFilter->uuid);
        $target = $validated['direction'] === 'up' ? $index - 1 : $index + 1;

        if ($index === false || ! isset($items[$target])) {
            return back()->with('error', 'Filter sudah berada di posisi tersebut.');
        }

        if ((bool) $items[$index]->pinned_at !== (bool) $items[$target]->pinned_at) {
            return back()->with('error', 'Urutan hanya bisa diubah di dalam grup yang sama.');
        }

        DB::transaction(function () use ($items, $index, $target): void {
            foreach ($items as $position => $item) {
                $item->update(['sort_order' => $position + 1]);
            }

            $current = $items[$index];
            $neighbor = $items[$target];
            $currentOrder = $current->sort_order;

            $current->update(['sort_order' => $neighbor->sort_order]);
            $neighbor->update(['sort_order' => $currentOrder]);
        });

        return back()->with('success', __('flash.order_saved_filter_moved'));
    }

    public function applySavedFilter(OrderSavedFilter $savedFilter)
    {
        abort_unless($this->canManageSavedFilters(), 403);
        abort_unless($savedFilter->user_uuid === Auth::user()->uuid, 404);

        return redirect()->route('orders.index', $this->normalizeOrderFilters($savedFilter->filters ?? []));
    }

    public function deleteSavedFilter(OrderSavedFilter $savedFilter)
    {
        abort_unless($this->canManageSavedFilters(), 403);
        abort_unless($savedFilter->user_uuid === Auth::user()->uuid, 404);

        $savedFilter->delete();

        return back()->with('success', __('flash.order_saved_filter_deleted'));
    }

    private function normalizeOrderFilters(array $filters): array
    {
        $normalized = [];

        foreach (['search'] as $key) {
            if (isset($filters[$key]) && is_scalar($filters[$key]) && (string) $filters[$key] !== '') {
                $normalized[$key] = (string) $filters[$key];
            }
        }

        if (in_array($filters['status'] ?? null, ['pending', 'paid', 'failed', 'expired', 'cancelled'], true)) {
            $normalized['status'] = (string) $filters['status'];
        }

        if (in_array($filters['proof'] ?? null, ['uploaded', 'missing'], true)) {
            $normalized['proof'] = (string) $filters['proof'];
        }

        foreach (['start_date', 'end_date'] as $key) {
            $value = $filters[$key] ?? null;

            if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                $normalized[$key] = $value;
            }
        }

        $perPage = (int) ($filters['per_page'] ?? 10);
        $normalized['per_page'] = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $sort = (string) ($filters['sort'] ?? 'latest');
        $normalized['sort'] = in_array($sort, ['latest', 'oldest', 'amount_desc', 'amount_asc'], true) ? $sort : 'latest';

        return $normalized;
    }

    /**
     * Display the specified order.
     */
    public function show(string $uuid)
    {
        $user = Auth::user();

        $order = Order::packageOnly()->with([
            'user',
            'package',
            'packageOption',
            'payment',
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | User Authorization
        |--------------------------------------------------------------------------
        */

        if (
            ! $user->hasAnyRole(['super-admin', 'admin']) &&
            $order->user_uuid !== $user->uuid
        ) {
            abort(403);
        }

        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        $userPackage = UserPackage::where('order_uuid', $order->uuid)
            ->with('package')
            ->first();

        $auditLogs = $isAdmin
            ? AuditLog::where('auditable_type', Order::class)
                ->where('auditable_id', (string) $order->uuid)
                ->latest()
                ->limit(8)
                ->get()
            : collect();

        $isMobile = MemberView::isMobile();

        if (! $isAdmin && $isMobile) {
            return view(
                'pages.mobile.order-show',
                compact('order', 'isAdmin', 'userPackage', 'auditLogs')
            );
        }

        return view(
            'pages.orders.show',
            compact('order', 'isAdmin', 'userPackage', 'auditLogs')
        );
    }

    public function quickView(string $uuid)
    {
        abort_unless($this->canQuickViewOrders(), 403);

        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        $order = Order::packageOnly()->with(['user', 'package', 'packageOption', 'payment'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (! $isAdmin && $order->user_uuid !== $user->uuid) {
            abort(404);
        }

        return view('pages.orders.partials.quick-view', compact('order', 'isAdmin'));
    }

    public function activity(string $uuid)
    {
        abort_unless($this->canQuickViewOrders(), 403);

        $order = Order::packageOnly()
            ->with(['user', 'package', 'packageOption', 'payment', 'assignedAdmin'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $audits = AuditLog::where('auditable_type', Order::class)
            ->where('auditable_id', (string) $order->uuid)
            ->latest()
            ->limit(15)
            ->get();

        return view('pages.orders.partials.activity-drawer', compact('order', 'audits'));
    }

    /**
     * Mark a pending order as paid and activate the membership.
     * Admin only (manual transfer verification).
     */
    public function approve(Request $request, string $order)
    {
        abort_unless($this->canApproveOrders(), 403);

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'idempotency_key' => ['sometimes', 'string', 'max:120'],
        ]);

        $idempotencyKey = $this->idempotencyKey($request, 'approve', (string) $order);

        if ($cachedOrder = $this->cachedIdempotencyResult($idempotencyKey)) {
            return redirect()
                ->route('orders.show', $cachedOrder)
                ->with('success', __('flash.order_approve_ok'));
        }

        if (! $this->beginIdempotency($idempotencyKey)) {
            return back()->with('error', __('flash.order_action_in_progress'));
        }

        try {
            [$order, $userPackage, $stage] = $this->activatePendingOrder(
                $order,
                $validated['admin_note'] ?? null
            );
        } catch (ValidationException $e) {
            $this->forgetIdempotency($idempotencyKey);

            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            $this->forgetIdempotency($idempotencyKey);

            throw $e;
        }

        $this->finishIdempotency($idempotencyKey, $order->uuid);

        try {
            if ($stage === 'dp') {
                $order->user?->notify(new \App\Notifications\OrderPaymentVerifiedNotification(
                    $order->uuid, $order->order_number,
                    'DP diterima. Sisa pelunasan: Rp ' . number_format((float) $order->remaining_amount_value, 0, ',', '.'),
                    'bi-cash-coin'
                ));
            } elseif ($userPackage) {
                $order->user?->notify(new MemberActiveNotification($order->uuid, $order->order_number, $userPackage->expired_at));
            }
        } catch (Throwable $e) {
            Log::error('Membership activation notification failed: '.$e->getMessage());
        }

        $success = $stage === 'dp' ? 'DP berhasil diverifikasi. Menunggu pelunasan.' : __('flash.order_approve_ok');
        return redirect()->route('orders.show', $order->uuid)->with('success', $success);
    }

    /**
     * Approve a pending/partially paid order inside a single locked transaction.
     * DP: pending -> partially_paid (no membership activation). Balance/full: -> paid + activate membership.
     *
     * @return array{0: Order, 1: ?UserPackage, 2: string}
     */
    private function activatePendingOrder(string $orderUuid, ?string $note): array
    {
        return DB::transaction(function () use ($orderUuid, $note) {
            $order = Order::packageOnly()->with(['packageOption', 'user', 'payments'])
                ->where('uuid', $orderUuid)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($order->status, ['pending', 'partially_paid'], true)) {
                throw ValidationException::withMessages(['order' => __('flash.order_only_pending_approve')]);
            }
            if ($order->status === 'pending' && $order->expired_at?->isPast()) {
                throw ValidationException::withMessages(['order' => __('flash.order_expired_cannot_process')]);
            }
            if (! $order->package_option_uuid || ! $order->packageOption) {
                throw ValidationException::withMessages(['order' => __('flash.order_no_option')]);
            }

            $snapshot = [
                'order' => [
                    'status' => $order->status,
                    'paid_at' => $order->paid_at?->toDateTimeString(),
                    'admin_note' => $order->admin_note,
                    'assigned_admin_uuid' => $order->assigned_admin_uuid,
                    'pending_key' => $order->pending_key,
                    'amount_paid' => (float) ($order->amount_paid ?? 0),
                    'remaining_amount' => (float) ($order->remaining_amount ?? 0),
                    'balance_due_at' => $order->balance_due_at?->toDateTimeString(),
                ],
                'payment_uuid' => null,
                'user_package' => null,
                'stage' => null,
            ];

            $fullAmount = (float) ($order->full_amount ?: $order->amount);
            $dueNow = (float) ($order->amount ?: 0);
            $scheme = $order->payment_scheme;
            $isDpInitial = $order->status === 'pending' && in_array($scheme, ['dp30', 'dp50'], true);

            if ($isDpInitial) {
                $payment = Payment::create([
                    'order_uuid' => $order->uuid,
                    'kind' => 'dp',
                    'payment_gateway' => 'manual_transfer',
                    'payment_type' => 'Transfer Manual DP',
                    'gross_amount' => $dueNow,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'proof_image_path' => $order->proof_image_path,
                    'proof_uploaded_at' => $order->proof_uploaded_at,
                ]);
                $remaining = max(0, $fullAmount - $dueNow);
                $order->update([
                    'status' => 'partially_paid',
                    'paid_at' => null,
                    'amount_paid' => $dueNow,
                    'remaining_amount' => $remaining,
                    'total_amount' => $fullAmount,
                    'balance_due_at' => $order->event_date ? $order->event_date->copy()->startOfDay() : $order->balance_due_at,
                    'admin_note' => $note,
                    'assigned_admin_uuid' => $order->assigned_admin_uuid ?: Auth::user()->uuid,
                    'proof_image_path' => null,
                    'proof_uploaded_at' => null,
                ]);
                $snapshot['payment_uuid'] = $payment->uuid;
                $snapshot['stage'] = 'dp';
                $order->update(['approval_snapshot' => $snapshot]);
                $this->logOrderAction($order, 'approved_dp', ['admin_note' => $note, 'amount_paid' => $dueNow, 'remaining_amount' => $remaining]);
                return [$order->fresh(), null, 'dp'];
            }

            $stage = $order->status === 'partially_paid' ? 'balance' : 'full';
            $settleAmount = $stage === 'balance' ? max(0, $fullAmount - (float) $order->amount_paid_value) : $dueNow;
            if ($settleAmount <= 0) { $settleAmount = $stage === 'balance' ? max(0, (float) $order->remaining_amount_value) : $dueNow; }

            $payment = Payment::create([
                'order_uuid' => $order->uuid,
                'kind' => $stage,
                'payment_gateway' => 'manual_transfer',
                'payment_type' => $stage === 'balance' ? 'Pelunasan Manual' : 'Transfer Manual',
                'gross_amount' => $settleAmount,
                'status' => 'paid',
                'paid_at' => now(),
                'proof_image_path' => $order->proof_image_path,
                'proof_uploaded_at' => $order->proof_uploaded_at,
            ]);
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'amount_paid' => $fullAmount,
                'remaining_amount' => 0,
                'total_amount' => $fullAmount,
                'admin_note' => $note,
                'assigned_admin_uuid' => $order->assigned_admin_uuid ?: Auth::user()->uuid,
            ]);

            $snapshot['payment_uuid'] = $payment->uuid;
            $snapshot['stage'] = $stage;
            $option = $order->packageOption;
            $started = now();
            $existing = UserPackage::where('user_uuid', $order->user_uuid)
                ->where('package_uuid', $order->package_uuid)
                ->where('status', 'active')
                ->where(function ($q) { $q->whereNull('expired_at')->orWhere('expired_at', '>', now()); })
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $snapshot['user_package'] = ['created' => false, 'uuid' => $existing->uuid, 'expired_at' => $existing->expired_at?->toDateTimeString(), 'quota' => $existing->quota, 'order_uuid' => $existing->order_uuid];
                $base = $existing->expired_at && $existing->expired_at->isFuture() ? $existing->expired_at->copy() : $started->copy();
                $expired = match ($option->duration_unit) { 'day' => $base->addDays($option->duration), 'week' => $base->addWeeks($option->duration), 'year' => $base->addYears($option->duration), default => $base->addMonths($option->duration) };
                $quota = $existing->quota;
                if (! is_null($quota) && ! is_null($option->quota)) { $quota += $option->quota; }
                elseif (is_null($existing->quota)) { $quota = null; } else { $quota = $option->quota; }
                $existing->update(['order_uuid' => $order->uuid, 'expired_at' => $expired, 'quota' => $quota]);
                $userPackage = $existing->fresh();
            } else {
                $expired = match ($option->duration_unit) { 'day' => (clone $started)->addDays($option->duration), 'week' => (clone $started)->addWeeks($option->duration), 'year' => (clone $started)->addYears($option->duration), default => (clone $started)->addMonths($option->duration) };
                $userPackage = UserPackage::create(['user_uuid' => $order->user_uuid, 'package_uuid' => $order->package_uuid, 'order_uuid' => $order->uuid, 'quota' => $option->quota, 'started_at' => $started, 'expired_at' => $expired, 'status' => 'active']);
                $snapshot['user_package'] = ['created' => true, 'uuid' => $userPackage->uuid];
            }

            $order->update(['approval_snapshot' => $snapshot]);
            $this->logOrderAction($order, $stage === 'balance' ? 'approved_balance' : 'approved', ['admin_note' => $note, 'user_package_uuid' => $userPackage->uuid, 'amount_paid' => $fullAmount]);
            return [$order->fresh(), $userPackage, $stage];
        });
    }

    /**
     * Reject a pending order (manual transfer verification failed).
     * Admin only.
     */
    public function reject(Request $request, string $order)
    {
        abort_unless($this->canRejectOrders(), 403);

        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
            'idempotency_key' => ['sometimes', 'string', 'max:120'],
        ], $this->orderValidationMessages());

        $idempotencyKey = $this->idempotencyKey($request, 'reject', (string) $order);

        if ($this->cachedIdempotencyResult($idempotencyKey)) {
            return back()->with('success', __('flash.order_rejected'));
        }

        if (! $this->beginIdempotency($idempotencyKey)) {
            return back()->with('error', __('flash.order_action_in_progress'));
        }

        try {
            $rejected = $this->rejectPendingOrder($order, $validated['admin_note']);
        } catch (ValidationException $e) {
            $this->forgetIdempotency($idempotencyKey);

            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            $this->forgetIdempotency($idempotencyKey);

            throw $e;
        }

        $this->finishIdempotency($idempotencyKey, $rejected->uuid);

        try {
            $rejected->user?->notify(new OrderRejectedNotification(
                $rejected->uuid,
                $rejected->order_number,
                $validated['admin_note'],
            ));
        } catch (Throwable $e) {
            Log::error('Order rejection notification failed: '.$e->getMessage());
        }

        return back()->with('success', __('flash.order_rejected'));
    }

    /**
     * Reject a pending order inside a single transaction.
     */
    private function rejectPendingOrder(string $orderUuid, string $note): Order
    {
        return DB::transaction(function () use ($orderUuid, $note) {
            $order = Order::packageOnly()->where('uuid', $orderUuid)->lockForUpdate()->firstOrFail();
            if (! in_array($order->status, ['pending', 'partially_paid'], true)) {
                throw ValidationException::withMessages(['order' => __('flash.order_only_pending_reject')]);
            }
            if ($order->status === 'partially_paid' || (float) $order->amount_paid_value > 0) {
                throw ValidationException::withMessages(['order' => 'Pesanan yang sudah memiliki pembayaran terverifikasi tidak bisa ditolak otomatis.']);
            }
            $order->update(['status' => 'failed', 'admin_note' => $note, 'assigned_admin_uuid' => $order->assigned_admin_uuid ?: Auth::user()->uuid]);
            $order->payments()->where(function($q){$q->where('verification_status','pending')->orWhere('status','pending');})->update(['status' => 'failed', 'verification_status' => 'rejected']);
            $this->logOrderAction($order, 'rejected', ['admin_note' => $note]);
            return $order;
        });
    }

    public function submitPayment(Request $request, string $order)
    {
        $record = Order::packageOnly()->with('package')->where('uuid', $order)->firstOrFail();
        $user = Auth::user();
        abort_unless($record->user_uuid === $user->uuid || $user->hasAnyRole(['admin','admin-venue','super-admin']), 403);
        abort_unless($record->status === 'partially_paid', 403, 'Pembayaran cicilan hanya untuk pesanan DP yang sudah diverifikasi.');
        if ($record->payments()->where(function($q){$q->where('verification_status','pending')->orWhere('status','pending');})->exists()) {
            return back()->with('error', 'Masih ada pembayaran yang menunggu verifikasi admin.');
        }

        $remaining = (float) $record->remaining_amount_value;
        $minInstallment = min($remaining, round((float) $record->full_amount * 0.20, 2));
        $validated = $request->validate([
            'payment_choice' => ['required', Rule::in(['installment20','full'])],
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.config('manual_payment.proof_max_kb', 3072)],
        ]);
        $amount = $validated['payment_choice'] === 'full' ? $remaining : $minInstallment;
        if ($amount <= 0 || $amount - $remaining > 0.01) {
            return back()->with('error', 'Nominal pembayaran tidak valid.');
        }

        $proofPath = $request->file('proof')->store('orders/proofs', 'public');
        $payment = Payment::create([
            'order_uuid' => $record->uuid,
            'kind' => $amount + 0.01 >= $remaining ? 'balance' : 'installment',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => $amount + 0.01 >= $remaining ? 'Pelunasan Manual' : 'Cicilan 20%',
            'amount' => $amount,
            'gross_amount' => $amount,
            'status' => 'pending',
            'verification_status' => 'pending',
            'proof_image_path' => $proofPath,
            'proof_uploaded_at' => now(),
        ]);
        $this->logOrderAction($record, 'payment_submitted', ['payment_uuid'=>$payment->uuid,'amount'=>$amount,'kind'=>$payment->kind]);

        try {
            $admins = User::role(['admin', 'super-admin'])->where('is_active', true)->get();
            $notif = new ProofUploadedNotification($record->uuid, $record->order_number, $user->name);
            foreach ($admins as $admin) { $admin->notify($notif); }
        } catch (Throwable $e) { Log::error('Payment submission notification failed: '.$e->getMessage()); }

        return back()->with('success', 'Bukti pembayaran cicilan berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function approvePayment(Request $request, string $order, string $payment)
    {
        abort_unless($this->canApproveOrders(), 403);
        $validated = $request->validate(['admin_note' => ['nullable','string','max:1000'], 'idempotency_key' => ['sometimes','string','max:120']]);
        $paymentModel = Payment::where('uuid', $payment)->where('order_uuid', $order)->firstOrFail();
        try {
            [$approved, $userPackage, $stage] = $this->activatePendingOrder($order, $validated['admin_note'] ?? null, $paymentModel);
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        }
        try {
            $user = $approved->user;
            if ($user) {
                if (in_array($stage, ['dp','installment'], true)) {
                    $msg = ($stage === 'dp' ? 'DP' : 'Cicilan') . ' diterima. Sisa pembayaran: Rp ' . number_format((float) $approved->remaining_amount_value, 0, ',', '.');
                    $user->notify(new \App\Notifications\OrderPaymentVerifiedNotification($approved->uuid, $approved->order_number, $msg, 'bi-cash-coin'));
                } elseif ($userPackage) {
                    $user->notify(new MemberActiveNotification($approved->uuid, $approved->order_number, $userPackage->expired_at));
                }
            }
        } catch (Throwable $e) { Log::error('Payment approval notification failed: '.$e->getMessage()); }

        $msg = match($stage) { 'dp'=>'DP berhasil diverifikasi.', 'installment'=>'Cicilan berhasil diverifikasi.', default=>__('flash.order_approved') };
        return redirect()->route('orders.show', $approved->uuid)->with('success', $msg)->with('payment_module_focus', true);
    }

    public function rejectPayment(Request $request, string $order, string $payment)
    {
        abort_unless($this->canRejectOrders(), 403);
        $validated = $request->validate(['admin_note' => ['required','string','max:1000']]);
        $record = Order::packageOnly()->where('uuid', $order)->firstOrFail();
        $paymentModel = Payment::where('uuid', $payment)->where('order_uuid', $order)->lockForUpdate()->firstOrFail();
        if (! in_array($paymentModel->verification_status ?: $paymentModel->status, ['pending'], true)) {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }
        $paymentModel->update(['status'=>'failed','verification_status'=>'rejected','note'=>$validated['admin_note'],'verified_by'=>Auth::user()->uuid,'verified_at'=>now()]);
        $this->logOrderAction($record, 'payment_rejected', ['payment_uuid'=>$paymentModel->uuid,'admin_note'=>$validated['admin_note']]);
        try { $record->user?->notify(new OrderRejectedNotification($record->uuid, $record->order_number, 'Pembayaran ditolak: '.$validated['admin_note'])); } catch (Throwable $e) { Log::error('Payment rejection notification failed: '.$e->getMessage()); }
        return redirect()->route('orders.show', $record->uuid)->with('success', 'Pembayaran ditolak.')->with('payment_module_focus', true);
    }

    /**
     * Bulk approve/reject pending orders (admin only).
     */
    public function bulkAction(Request $request)
    {
        abort_unless($this->canBulkOrders(), 403);

        $validated = $request->validate([
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'ids' => ['required', 'array', 'min:1', 'max:50'],
            'ids.*' => ['required', 'string'],
            'admin_note' => [
                Rule::requiredIf($request->input('action') === 'reject'),
                'nullable',
                'string',
                'max:1000',
            ],
            'idempotency_key' => ['sometimes', 'string', 'max:120'],
        ], $this->orderValidationMessages());

        $orderIds = array_values(array_unique($validated['ids']));
        sort($orderIds);

        $idempotencyKey = $this->idempotencyKey(
            $request,
            'bulk-'.$validated['action'],
            implode(',', $orderIds)
        );

        if ($cachedResult = $this->cachedIdempotencyResult($idempotencyKey)) {
            $response = back()->with('success', __('flash.order_action_already_done'));

            if ($cachedResult !== 'done') {
                $response->with('undo_token', $cachedResult)
                    ->with('undo_message', 'Tindakan massal dapat dibatalkan dalam 10 menit.');
            }

            return $response;
        }

        if (! $this->beginIdempotency($idempotencyKey)) {
            return back()->with('error', __('flash.order_action_in_progress'));
        }

        $berhasil = 0;
        $gagal = 0;
        $gagalRincian = [];
        $undoSnapshot = [];
        $undoToken = Str::random(24);

        foreach ($orderIds as $orderUuid) {
            try {
                $before = Order::packageOnly()->where('uuid', $orderUuid)->first()?->getAttributes() ?? [];

                if ($validated['action'] === 'approve') {
                    [$order, $userPackage, $stage, $payment] = $this->activatePendingOrder(
                        $orderUuid,
                        $validated['admin_note'] ?? null
                    );

                    try {
                        if (in_array($stage, ['dp','installment'], true)) {
                            $msg = $stage === 'dp'
                                ? 'DP diterima. Sisa pembayaran: Rp ' . number_format((float) $order->remaining_amount_value, 0, ',', '.')
                                : 'Cicilan diterima. Sisa pembayaran: Rp ' . number_format((float) $order->remaining_amount_value, 0, ',', '.');
                            $order->user?->notify(new \App\Notifications\OrderPaymentVerifiedNotification($order->uuid, $order->order_number, $msg, 'bi-cash-coin'));
                        } elseif ($userPackage) {
                            $order->user?->notify(new MemberActiveNotification($order->uuid, $order->order_number, $userPackage->expired_at));
                        }
                    } catch (Throwable $e) {
                        Log::error('Membership activation notification failed: '.$e->getMessage());
                    }

                    $undoSnapshot[] = [
                        'type' => 'approve',
                        'uuid' => $order->uuid,
                        'snapshot' => $order->approval_snapshot,
                    ];
                } else {
                    $paymentBefore = Payment::where('order_uuid', $orderUuid)
                        ->first(['uuid', 'status'])
                        ?->toArray();

                    $rejected = $this->rejectPendingOrder($orderUuid, $validated['admin_note']);

                    try {
                        $rejected->user?->notify(new OrderRejectedNotification(
                            $rejected->uuid,
                            $rejected->order_number,
                            $validated['admin_note'],
                        ));
                    } catch (Throwable $e) {
                        Log::error('Order rejection notification failed: '.$e->getMessage());
                    }

                    $undoSnapshot[] = [
                        'type' => 'reject',
                        'uuid' => $rejected->uuid,
                        'status' => $before['status'] ?? 'pending',
                        'admin_note' => $before['admin_note'] ?? null,
                        'paid_at' => $before['paid_at'] ?? null,
                        'assigned_admin_uuid' => $before['assigned_admin_uuid'] ?? null,
                        'pending_key' => $before['pending_key'] ?? null,
                        'payment_uuid' => $paymentBefore['uuid'] ?? null,
                        'payment_status' => $paymentBefore['status'] ?? null,
                    ];
                }

                $berhasil++;
            } catch (Throwable $e) {
                $gagal++;
                $gagalRincian[] = $orderUuid.': '.$e->getMessage();
                Log::warning('Bulk order action failed: '.$e->getMessage(), ['order' => $orderUuid]);
            }
        }

        $pesan = $berhasil.' pesanan berhasil '.($validated['action'] === 'approve' ? 'disetujui' : 'ditolak').'.';
        if ($gagal > 0) {
            $pesan .= ' '.$gagal.' pesanan gagal diproses.';
            $pesan .= ' Rincian: '.implode(' | ', array_slice($gagalRincian, 0, 3)).'.';
        }

        if ($undoSnapshot !== []) {
            Cache::put(
                'orders.bulk_undo.'.Auth::user()->uuid.'.'.$undoToken,
                $undoSnapshot,
                now()->addMinutes(10)
            );

            $this->finishIdempotency($idempotencyKey, $undoToken);

            return back()
                ->with($gagal > 0 ? 'error' : 'success', $pesan)
                ->with('undo_token', $undoToken)
                ->with('undo_message', 'Tindakan massal dapat dibatalkan dalam 10 menit.');
        }

        $this->finishIdempotency($idempotencyKey, 'done');

        return back()->with($gagal > 0 ? 'error' : 'success', $pesan);
    }

    public function bulkUndo(Request $request)
    {
        abort_unless($this->canBulkUndoOrders(), 403);

        $validated = $request->validate([
            'undo_token' => ['required', 'string'],
        ]);

        $key = 'orders.bulk_undo.'.Auth::user()->uuid.'.'.$validated['undo_token'];
        $snapshot = Cache::get($key);

        if (! $snapshot) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => __('flash.order_bulk_undo_expired')], 422)
                : back()->with('error', __('flash.order_bulk_undo_expired'));
        }

        try {
            DB::transaction(function () use ($snapshot): void {
                foreach ($snapshot as $item) {
                    $order = Order::packageOnly()->where('uuid', $item['uuid'])->lockForUpdate()->firstOrFail();

                    if (($item['type'] ?? 'reject') === 'reject') {
                        $order->update([
                            'status' => $item['status'],
                            'admin_note' => $item['admin_note'],
                            'paid_at' => $item['paid_at'],
                            'assigned_admin_uuid' => $item['assigned_admin_uuid'] ?? null,
                            'pending_key' => $item['pending_key'] ?? null,
                        ]);

                        $paymentStatus = $item['payment_status'] ?? 'pending';

                        if (! empty($item['payment_uuid'])) {
                            Payment::where('uuid', $item['payment_uuid'])
                                ->update(['status' => $paymentStatus]);
                        } else {
                            Payment::where('order_uuid', $order->uuid)
                                ->update(['status' => $paymentStatus]);
                        }

                        $this->logOrderAction($order, 'rejection_reverted');

                        continue;
                    }

                    $approval = $item['snapshot'] ?? [];
                    $beforeOrder = $approval['order'] ?? [];
                    $userPackageBefore = $approval['user_package'] ?? [];

                    $order->update([
                        'status' => $beforeOrder['status'] ?? 'pending',
                        'paid_at' => $beforeOrder['paid_at'] ?? null,
                        'admin_note' => $beforeOrder['admin_note'] ?? null,
                        'assigned_admin_uuid' => $beforeOrder['assigned_admin_uuid'] ?? null,
                        'pending_key' => $beforeOrder['pending_key'] ?? null,
                        'approval_snapshot' => null,
                    ]);

                    if (! empty($approval['payment_uuid'])) {
                        Payment::where('uuid', $approval['payment_uuid'])->delete();
                    }

                    if (! empty($userPackageBefore['created'])) {
                        UserPackage::where('uuid', $userPackageBefore['uuid'])->delete();
                    } elseif (! empty($userPackageBefore['uuid'])) {
                        UserPackage::where('uuid', $userPackageBefore['uuid'])->update([
                            'expired_at' => $userPackageBefore['expired_at'] ?? null,
                            'quota' => $userPackageBefore['quota'] ?? null,
                            'order_uuid' => $userPackageBefore['order_uuid'] ?? null,
                        ]);
                    }

                    $this->logOrderAction($order, 'approval_reverted');
                }
            });
        } catch (Throwable $e) {
            Log::warning('Order bulk undo failed: '.$e->getMessage());

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => __('flash.order_bulk_undo_failed')], 500)
                : back()->with('error', __('flash.order_bulk_undo_failed'));
        }

        Cache::forget($key);

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => __('flash.order_bulk_undo_ok'),
                'restored' => count($snapshot),
            ])
            : back()->with('success', __('flash.order_bulk_undo_ok'));
    }

    public function remindSla(Request $request)
    {
        abort_unless($this->canBulkOrders(), 403);

        $validated = $request->validate([
            'ids' => ['sometimes', 'array', 'max:200'],
            'ids.*' => ['required', 'string'],
        ]);

        $ids = array_values(array_unique($validated['ids'] ?? []));
        sort($ids);

        $query = Order::packageOnly()
            ->where('status', 'pending')
            ->whereNotNull('created_at')
            ->where('created_at', '<=', now()->subHours(24))
            ->with(['user', 'assignedAdmin']);

        if ($ids !== []) {
            $query->whereIn('uuid', $ids);
        }

        $orders = $query->limit(200)->get();
        $roles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('name');
        $fallbackRecipients = $roles->isNotEmpty() ? User::role($roles->all())->get() : collect();

        $terkirim = 0;
        $dilewati = 0;

        foreach ($orders as $order) {
            if ($order->sla_reminded_at && $order->sla_reminded_at->gt(now()->subHours(24))) {
                $dilewati++;

                continue;
            }

            $recipients = $order->assignedAdmin
                ? collect([$order->assignedAdmin])
                : $fallbackRecipients;

            if ($recipients->isEmpty()) {
                $dilewati++;

                continue;
            }

            $usiaJam = (int) $order->created_at->diffInHours();
            $terkirimPesanan = 0;

            foreach ($recipients as $recipient) {
                try {
                    Notification::send($recipient, new OrderSlaReminderNotification(
                        $order->uuid,
                        $order->order_number,
                        $order->user?->name ?? '-',
                        $usiaJam,
                    ));
                    $terkirimPesanan++;
                } catch (Throwable $e) {
                    Log::warning('orders.sla.manual_failed', [
                        'order' => $order->uuid,
                        'recipient' => $recipient->uuid ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($terkirimPesanan === 0) {
                $dilewati++;

                continue;
            }

            $order->update(['sla_reminded_at' => now()]);
            $this->logOrderAction($order, 'sla_reminded', [
                'hours' => $usiaJam,
                'manual' => true,
            ]);
            $terkirim++;
        }

        $pesan = $terkirim > 0
            ? $terkirim.' pengingat SLA berhasil dikirim.'
            : __('flash.order_sla_reminded');

        if ($dilewati > 0) {
            $pesan .= ' '.$dilewati.' pesanan dilewati.';
        }

        return back()->with('success', $pesan);
    }

    public function claim(string $order)
    {
        abort_unless(Gate::forUser(Auth::user())->allows('assign', Order::class), 403);

        try {
            DB::transaction(function () use ($order): void {
                $model = Order::packageOnly()->where('uuid', $order)->lockForUpdate()->firstOrFail();

                if ($model->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'order' => __('flash.order_only_pending_claim'),
                    ]);
                }

                if ($model->expired_at?->isPast()) {
                    throw ValidationException::withMessages([
                        'order' => __('flash.order_expired_claim'),
                    ]);
                }

                if ($model->assigned_admin_uuid && $model->assigned_admin_uuid !== Auth::user()->uuid) {
                    throw ValidationException::withMessages([
                        'order' => __('flash.order_already_claimed'),
                    ]);
                }

                $model->update(['assigned_admin_uuid' => Auth::user()->uuid]);
                $this->logOrderAction($model, 'assigned');
            });
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', __('flash.order_claimed'));
    }

    public function exportAudit(Request $request)
    {
        abort_unless(Gate::forUser(Auth::user())->allows('audit', Order::class), 403);

        $query = AuditLog::query()
            ->where('auditable_type', Order::class)
            ->whereIn('event', [
                'approved',
                'rejected',
                'proof_uploaded',
                'whatsapp_opened',
                'assigned',
                'approval_reverted',
                'rejection_reverted',
                'sla_reminded',
            ])
            ->latest();

        if ($request->filled('order')) {
            $query->where('auditable_id', $request->input('order'));
        }

        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        $logs = $query->limit(5000)->get();

        $fileName = 'order-audit-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($logs): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Waktu', 'No. Pesanan', 'Aktivitas', 'Admin', 'Catatan', 'Alamat IP']);

            foreach ($logs as $log) {
                $values = $log->new_values ?? [];
                $note = $values['admin_note'] ?? ($values['note'] ?? '');

                fputcsv($handle, [
                    optional($log->created_at)->format('d/m/Y H:i'),
                    $log->auditable_label,
                    $log->event_label,
                    $log->user_name ?: '-',
                    is_scalar($note) ? (string) $note : '',
                    $log->ip_address ?: '-',
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function whatsapp(Order $order)
    {
        abort_unless(Gate::forUser(Auth::user())->allows('whatsapp', Order::class), 403);

        $order->loadMissing('user');

        $phone = preg_replace('/\D+/', '', (string) ($order->user?->no_hp ?? '')) ?: '';
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        if (! Str::length($phone) || $phone === '62') {
            return back()->with('error', __('flash.order_whatsapp_number_missing'));
        }

        $message = 'Halo '.($order->user?->name ?? 'Pelanggan').', kami dari Sagita Wedding Organizer ingin mengonfirmasi pesanan '
            .$order->order_number.'. Mohon ditindaklanjuti, terima kasih.';

        $this->logOrderAction($order, 'whatsapp_opened', [
            'phone' => preg_replace('/\d/', 'x', substr($phone, -4)),
        ]);

        return redirect()->away('https://wa.me/'.$phone.'?text='.rawurlencode($message));
    }

    /**
     * Export the currently filtered orders as a UTF-8 CSV file.
     */
    public function exportCsv(Request $request)
    {
        abort_unless($this->canExportOrders(), 403);

        $user = Auth::user();

        $orders = $this->filteredOrders($request, $user)
            ->with(['user', 'package', 'packageOption', 'payment'])
            ->latest()
            ->limit(10000)
            ->get();

        $statusLabels = [
            'paid' => 'Lunas',
            'partially_paid' => 'DP Terverifikasi',
            'pending' => 'Menunggu',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'cancelled' => 'Dibatalkan',
        ];

        $columns = $this->exportColumns($request);
        $truncated = $orders->count() >= 10000;
        $columnMap = [
            'date' => [
                'label' => 'Tanggal',
                'value' => fn ($order) => $order->created_at?->format('d/m/Y H:i') ?? '-',
            ],
            'order' => [
                'label' => 'No. Pesanan',
                'value' => fn ($order) => $order->order_number,
            ],
            'customer' => [
                'label' => 'Pelanggan',
                'value' => fn ($order) => $order->user?->name ?? '-',
            ],
            'email' => [
                'label' => 'Email',
                'value' => fn ($order) => $order->user?->email ?? '-',
            ],
            'package' => [
                'label' => 'Paket',
                'value' => fn ($order) => $order->package?->name ?? '-',
            ],
            'option' => [
                'label' => 'Opsi Paket',
                'value' => fn ($order) => $order->packageOption?->name ?? '-',
            ],
            'amount' => [
                'label' => 'Tagihan Saat Ini (Rp)',
                'value' => fn ($order) => $order->amount,
            ],
            'total' => [
                'label' => 'Total Paket (Rp)',
                'value' => fn ($order) => (float) ($order->total_amount ?: $order->amount),
            ],
            'paid_amount' => [
                'label' => 'Dibayar (Rp)',
                'value' => fn ($order) => (float) ($order->amount_paid_value ?? 0),
            ],
            'remaining' => [
                'label' => 'Sisa (Rp)',
                'value' => fn ($order) => (float) ($order->remaining_amount_value ?? 0),
            ],
            'scheme' => [
                'label' => 'Skema Pembayaran',
                'value' => fn ($order) => $order->scheme_label ?? 'Bayar Lunas',
            ],
            'status' => [
                'label' => 'Status',
                'value' => fn ($order) => $statusLabels[$order->status] ?? ucfirst($order->status),
            ],
            'proof' => [
                'label' => 'Bukti Transfer',
                'value' => fn ($order) => $order->proof_image_path ? 'Sudah diunggah' : 'Belum diunggah',
            ],
            'note' => [
                'label' => 'Catatan Admin',
                'value' => fn ($order) => $order->admin_note ?? '',
            ],
        ];

        return response()->stream(function () use ($orders, $columns, $columnMap, $truncated) {
            $handle = fopen('php://output', 'w');

            $labels = ['No'];
            $resolvers = [];

            foreach ($columnMap as $key => $column) {
                if (! in_array($key, $columns, true)) {
                    continue;
                }

                $labels[] = $column['label'];
                $resolvers[] = $column['value'];
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $labels);

            foreach ($orders as $i => $order) {
                $row = [$i + 1];

                foreach ($resolvers as $resolver) {
                    $row[] = $resolver($order);
                }

                fputcsv($handle, $row);
            }

            if ($truncated) {
                fputcsv($handle, [
                    'Catatan',
                    'Data dibatasi 10.000 pesanan terbaru. Persempit filter periode/status untuk ekspor lengkap.',
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-pesanan-'.now()->format('Ymd-His').'.csv"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Member uploads manual transfer proof (owner only, pending only).
     */
    public function uploadProof(Request $request, string $order)
    {
        $order = Order::packageOnly()->where('uuid', $order)->firstOrFail();

        if ($order->user_uuid !== Auth::user()->uuid) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', __('flash.proof_pending_only'));
        }

        $validated = $request->validate([
            'proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.config('manual_payment.proof_max_kb', 3072),
            ],
        ]);

        $disk = config('manual_payment.proof_disk', 'public');
        $dir = config('manual_payment.proof_dir', 'proofs');

        $path = $request->file('proof')->store($dir, $disk);

        ImageShrinker::shrink(Storage::disk($disk)->path($path), 1280, 82);

        if ($order->proof_image_path) {
            Storage::disk($disk)->delete($order->proof_image_path);
        }

        $order->update([
            'proof_image_path' => $path,
            'proof_uploaded_at' => now(),
        ]);

        $this->logOrderAction($order, 'proof_uploaded', ['proof_image_path' => $path]);

        try {
            $order->loadMissing('user');

            $admins = User::role(['super-admin', 'admin'])
                ->where('uuid', '!=', $order->user_uuid)
                ->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ProofUploadedNotification(
                    $order->uuid,
                    $order->order_number,
                    $order->user?->name ?? '-',
                    (float) $order->amount,
                ));
            }
        } catch (Throwable $e) {
            Log::error('Proof upload notification failed: '.$e->getMessage());
        }

        return back()->with('success', __('flash.proof_ok'));
    }

    /**
     * Serve the transfer proof file (owner or admin only).
     * Never exposed as a guessable public URL.
     */
    public function showProof(Request $request, string $order)
    {
        $order = Order::packageOnly()->where('uuid', $order)->firstOrFail();

        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        if (! $isAdmin && $order->user_uuid !== $user->uuid) {
            abort(403);
        }

        if (! $order->proof_image_path) {
            abort(404);
        }

        $diskName = config('manual_payment.proof_disk', 'local');
        $disk = Storage::disk($diskName);

        // Backward compatibility with proofs stored on the public disk.
        if (! $disk->exists($order->proof_image_path) && $diskName !== 'public') {
            $disk = Storage::disk('public');
        }

        if (! $disk->exists($order->proof_image_path)) {
            abort(404);
        }

        return response()->file($disk->path($order->proof_image_path));
    }

    /**
     * Store a newly created order.
     *
     * This is called when the user clicks
     * "Continue to Payment".
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'type' => ['required','in:package'],

            'package_option_uuid' => [
                'required',
                'uuid',
                'exists:package_options,uuid',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'addon_uuids' => [
                'nullable',
                'array',
            ],

            'addon_uuids.*' => [
                'uuid',
                Rule::exists('package_addons', 'uuid')
                    ->where('is_active', true),
            ],

            'addon_quantities' => [
                'nullable',
                'array',
            ],

            'addon_quantities.*' => [
                'integer',
                'min:1',
                'max:99',
            ],

            'promo_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'payment_scheme' => [
                'nullable',
                Rule::in(['dp30', 'dp50', 'full']),
            ],
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get Package Option
        |--------------------------------------------------------------------------
        */

        $option = PackageOption::with('package')
            ->where('uuid', $validated['package_option_uuid'])
            ->where('is_active', true)
            ->first();

        if (! $option) {
            return back()
                ->withErrors([
                    'package_option_uuid' => __('flash.option_unavailable'),
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Check Package
        |--------------------------------------------------------------------------
        */

        if (
            ! $option->package ||
            $option->package->is_active !== 'active' ||
            ! $option->package->is_published
        ) {
            return back()
                ->withErrors([
                    'package_option_uuid' => __('flash.package_unavailable'),
                ])
                ->withInput();
        }

        // Reject buying a different package while one is still active.
        $activePackage = MembershipService::activePackage($user);

        if ($activePackage && $activePackage->package_uuid !== $option->package_uuid) {
            return back()
                ->withErrors([
                    'package_option_uuid' => __('flash.active_package_block'),
                ])
                ->withInput();
        }

        $eventDate = Carbon::parse($validated['event_date'])->startOfDay();
        $earliestEventDate = now()->startOfDay()->addDays((int) ($option->package->lead_time_days ?? 0));

        if ($eventDate->lt($earliestEventDate)) {
            return back()
                ->withErrors([
                    'event_date' => __('flash.event_date_too_soon', [
                        'count' => (int) ($option->package->lead_time_days ?? 0),
                    ]),
                ])
                ->withInput();
        }

        if (
            PackageBlackoutDate::where('package_uuid', $option->package_uuid)
                ->whereDate('date', $eventDate->toDateString())
                ->exists()
        ) {
            return back()
                ->withErrors([
                    'event_date' => __('flash.event_date_blackout'),
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Get Selected Addons
        |--------------------------------------------------------------------------
        */

        $addonUuids = array_values(array_unique(array_filter(
            $validated['addon_uuids'] ?? [],
            fn ($uuid) => ! blank($uuid)
        )));

        $addons = $addonUuids === []
            ? collect()
            : PackageAddon::whereIn('uuid', $addonUuids)
                ->where('package_uuid', $option->package_uuid)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

        if ($addons->count() !== count($addonUuids)) {
            return back()
                ->withErrors([
                    'addon_uuids' => __('validation.exists', [
                        'attribute' => __('validation.attributes.addon_uuids'),
                    ]),
                ])
                ->withInput();
        }

        $addonQuantities = $validated['addon_quantities'] ?? [];
        $selectedAddonUuids = $addons->pluck('uuid')->all();

        $addonLines = $addons->map(function ($addon) use ($addonQuantities) {
            $min = max(1, (int) ($addon->min_quantity ?? 1));
            $max = $addon->max_quantity !== null
                ? min(99, (int) $addon->max_quantity)
                : 99;

            return [
                'addon' => $addon,
                'quantity' => (int) ($addonQuantities[$addon->uuid] ?? $min),
                'min' => $min,
                'max' => max($min, $max),
            ];
        });

        foreach ($addonLines as $line) {
            if ($line['quantity'] < $line['min'] || $line['quantity'] > $line['max']) {
                return back()
                    ->withErrors([
                        'addon_quantities' => __('flash.addon_quantity_invalid'),
                    ])
                    ->withInput();
            }
        }

        foreach ($addons as $addon) {
            if (
                $addon->requires_addon_uuid &&
                ! in_array($addon->requires_addon_uuid, $selectedAddonUuids, true)
            ) {
                return back()
                    ->withErrors([
                        'addon_uuids' => __('flash.addon_dependency_missing'),
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Final Price
        |--------------------------------------------------------------------------
        */

        $subtotal =
            $option->discount_price !== null &&
            $option->discount_price < $option->price
            ? (int) $option->discount_price
            : (int) $option->price;

        $addonSnapshot = [];

        foreach ($addonLines as $line) {
            $addon = $line['addon'];
            $lineTotal = (int) $addon->final_price * $line['quantity'];

            $subtotal += $lineTotal;

            $addonSnapshot[] = [
                'uuid' => $addon->uuid,
                'name' => $addon->name,
                'price' => (int) $addon->final_price,
                'quantity' => $line['quantity'],
                'line_total' => $lineTotal,
            ];
        }

        $promo = null;
        $discount = 0;
        $promoCodeInput = trim((string) ($validated['promo_code'] ?? ''));

        if ($promoCodeInput !== '') {
            $promo = PackagePromo::whereRaw('UPPER(code)=UPPER(?)', [strtoupper($promoCodeInput)])
                ->usable()
                ->first();

            if (
                ! $promo ||
                ($promo->package_uuid !== null && $promo->package_uuid !== $option->package_uuid)
            ) {
                return back()
                    ->withErrors([
                        'promo_code' => __('flash.promo_invalid'),
                    ])
                    ->withInput();
            }

            if ($subtotal < (int) $promo->min_purchase) {
                return back()
                    ->withErrors([
                        'promo_code' => __('flash.promo_min_purchase'),
                    ])
                    ->withInput();
            }

            $discount = $promo->discountFor((int) $subtotal);
        }

        $fullAmount = max(0, $subtotal - $discount);

        $paymentSchemes = [
            'dp30' => ['label' => 'DP 30%', 'factor' => 0.30],
            'dp50' => ['label' => 'DP 50%', 'factor' => 0.50],
            'full' => ['label' => 'Bayar Lunas', 'factor' => 1.00],
        ];

        $paymentScheme = $validated['payment_scheme'] ?? 'full';
        $schemeLabel = $paymentSchemes[$paymentScheme]['label'];
        $amount = (int) round($fullAmount * $paymentSchemes[$paymentScheme]['factor']);

        /*
        |--------------------------------------------------------------------------
        | Reuse Recent Pending Order (anti spam + anti notif flood)
        |--------------------------------------------------------------------------
        */

        $existingPending = Order::where('user_uuid', $user->uuid)
            ->where('type', 'package')
            ->where('package_option_uuid', $option->uuid)
            ->where('status', 'pending')
            ->where('created_at', '>', now()->subHour())
            ->latest()
            ->first();

        if ($existingPending) {
            return redirect()
                ->route(
                    'orders.show',
                    $existingPending->uuid
                )
                ->with(
                    'success',
                    __('flash.order_pending_exists')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Order
        |--------------------------------------------------------------------------
        */

        try {
            $order = DB::transaction(function () use (
                $user,
                $option,
                $amount,
                $addonSnapshot,
                $promo,
                $discount,
                $eventDate,
                $paymentScheme,
                $schemeLabel,
                $fullAmount
            ) {
                if ($promo) {
                    $claimed = PackagePromo::where('uuid', $promo->uuid)
                        ->usable()
                        ->increment('used_count');

                    if (! $claimed) {
                        throw ValidationException::withMessages([
                            'promo_code' => __('flash.promo_invalid'),
                        ]);
                    }
                }

                $remainingAmount = max(0, (int) $fullAmount - (int) $amount);

                return Order::create([

                    'uuid' => (string) Str::uuid(),

                    'user_uuid' => $user->uuid,

                    'order_number' => 'ORD-'.strtoupper(Str::random(10)),

                    'type' => 'package',

                    'package_uuid' => $option->package_uuid,

                    'package_option_uuid' => $option->uuid,

                    'event_date' => $eventDate->toDateString(),

        
                    'amount' => $amount,

                    'payment_scheme' => $paymentScheme,

                    'total_amount' => $fullAmount,

                    'amount_paid' => 0,

                    'remaining_amount' => $remainingAmount,

                    'balance_due_at' => $paymentScheme !== 'full' ? $eventDate->copy()->startOfDay() : null,

                    'status' => 'pending',

                    'expired_at' => now()->addHours(24),

                    'paid_at' => null,

                    'addons' => $addonSnapshot,

                    'promo_code' => $promo?->code,

                    'promo_discount' => $discount,

                    'promo_snapshot' => [
                        'code' => $promo?->code,
                        'type' => $promo?->type,
                        'value' => $promo ? (int) $promo->value : null,
                        'discount' => $discount,
                        'payment_scheme' => $paymentScheme,
                        'scheme_label' => $schemeLabel,
                        'full_amount' => $fullAmount,
                        'due_amount' => $amount,
                    ],
                ]);
            });
        } catch (QueryException $e) {
            return back()->with('error', __('flash.order_pending_exists'));
        }

        /*
        |--------------------------------------------------------------------------
        | Notify Admins
        |--------------------------------------------------------------------------
        |
        | Manual transfer: admins verify the order and
        | activate the membership from the order detail.
        |
        */

        $this->notifyAdmins($order);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'orders.show',
                $order->uuid
            )
            ->with(
                'success',
                __('flash.order_created')
            );
    }

    /**
     * Order again from a failed/expired/cancelled order.
     * Creates a fresh pending order with the same package option
     * (re-validated), reusing a recent pending order if one exists.
     */
    public function reorder(Request $request, string $order)
    {
        $old = Order::packageOnly()->where('uuid', $order)->firstOrFail();

        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        if (! $isAdmin && $old->user_uuid !== $user->uuid) {
            abort(403);
        }

        if (! in_array($old->status, ['failed', 'expired', 'cancelled'], true)) {
            return back()->with('error', __('flash.reorder_only_failed'));
        }

        if ($old->type !== 'package' || ! $old->package_option_uuid) {
            return back()->with('error', __('flash.reorder_impossible'));
        }

        $option = PackageOption::with('package')
            ->where('uuid', $old->package_option_uuid)
            ->where('is_active', true)
            ->first();

        if (! $option || ! $option->package || $option->package->is_active !== 'active') {
            return back()->with('error', __('flash.package_unavailable_now'));
        }

        $owner = $isAdmin ? $old->user : $user;
        if (! $owner) {
            return back()->with('error', __('flash.order_owner_missing'));
        }

        $activePackage = MembershipService::activePackage($owner);

        if ($activePackage && $activePackage->package_uuid !== $option->package_uuid) {
            return back()->with('error', __('flash.member_active_package_block'));
        }

        $amount = $option->discount_price !== null && $option->discount_price < $option->price
            ? $option->discount_price
            : $option->price;

        $existingPending = Order::where('user_uuid', $owner->uuid)
            ->where('type', 'package')
            ->where('package_option_uuid', $option->uuid)
            ->where('status', 'pending')
            ->where('created_at', '>', now()->subHour())
            ->latest()
            ->first();

        if ($existingPending) {
            return redirect()
                ->route('orders.show', $existingPending->uuid)
                ->with('success', __('flash.order_pending_exists'));
        }

        try {
            $newOrder = DB::transaction(function () use ($owner, $option, $amount, $old) {
                $paymentScheme = in_array($old->payment_scheme, ['dp30','dp50','full'], true) ? $old->payment_scheme : 'full';
                $fullAmount = (float) ($old->total_amount ?: $old->amount ?: $amount);
                $schemeFactor = match($paymentScheme){ 'dp30'=>0.30, 'dp50'=>0.50, default=>1.00 };
                $newAmount = (int) round($fullAmount * $schemeFactor);
                $remainingAmount = max(0, (int) round($fullAmount - $newAmount));
                return Order::create([
                    'uuid' => (string) Str::uuid(),
                    'user_uuid' => $owner->uuid,
                    'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                    'type' => 'package',
                    'package_uuid' => $option->package_uuid,
                    'package_option_uuid' => $option->uuid,
                    'event_date' => $old->event_date?->toDateString(),
                            'amount' => $newAmount,
                    'payment_scheme' => $paymentScheme,
                    'total_amount' => (int) round($fullAmount),
                    'amount_paid' => 0,
                    'remaining_amount' => $remainingAmount,
                    'balance_due_at' => $paymentScheme !== 'full' ? ($old->event_date?->copy()->startOfDay()) : null,
                    'status' => 'pending',
                    'expired_at' => now()->addHours(24),
                    'paid_at' => null,
                    'addons' => $old->addons,
                    'promo_code' => $old->promo_code,
                    'promo_discount' => $old->promo_discount ?? 0,
                    'promo_snapshot' => array_merge((array) ($old->promo_snapshot ?? []), [
                        'payment_scheme' => $paymentScheme,
                        'scheme_label' => match($paymentScheme){'dp30'=>'DP 30%','dp50'=>'DP 50%',default=>'Bayar Lunas'},
                        'full_amount' => (int) round($fullAmount),
                        'due_amount' => $newAmount,
                    ]),
                ]);
            });
        } catch (QueryException $e) {
            return back()->with('error', __('flash.order_pending_exists'));
        }

        $this->notifyAdmins($newOrder);

        return redirect()
            ->route('orders.show', $newOrder->uuid)
            ->with('success', __('flash.reorder_created'));
    }

    /**
     * Professional order report (screen preview + print).
     */
    public function report(Request $request)
    {
        abort_unless($this->canExportOrders(), 403);

        $user = Auth::user();

        $orders = $this->filteredOrders($request, $user)
            ->with([
                'user',
                'package',
                'packageOption',
                'payment',
            ])
            ->latest()
            ->limit(1000)
            ->get();

        return view('pages.orders.report', array_merge(
            $this->reportData($orders, $request, $user),
            ['isScreen' => true]
        ));
    }

    /**
     * Download order report as PDF.
     */
    public function exportPdf(Request $request)
    {
        abort_unless($this->canExportOrders(), 403);

        $user = Auth::user();

        $orders = $this->filteredOrders($request, $user)
            ->with([
                'user',
                'package',
                'packageOption',
                'payment',
            ])
            ->latest()
            ->limit(1000)
            ->get();

        $pdf = Pdf::loadView(
            'pages.orders.laporan',
            $this->reportData($orders, $request, $user)
        )->setPaper('a4', 'landscape');

        return $pdf->download('order-report-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * Shared scoped + filtered orders query (no eager loads, no ordering).
     */
    private function filteredOrders(Request $request, $user)
    {
        $query = Order::packageOnly();

        /*
        |--------------------------------------------------------------------------
        | User Scope
        |--------------------------------------------------------------------------
        */

        if (! $user->hasAnyRole(['super-admin', 'admin'])) {
            $query->where('user_uuid', $user->uuid);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('proof') === 'uploaded') {
            $query->whereNotNull('proof_image_path');
        } elseif ($request->input('proof') === 'missing') {
            $query->whereNull('proof_image_path');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('search') &&
            $user->hasAnyRole(['super-admin', 'admin'])
        ) {
            $search = trim((string) $request->input('search'));

            if (Str::length($search) < 2) {
                return $query->whereRaw('1 = 0');
            }

            $query->where(function ($query) use ($search) {

                $query->where(
                    'order_number',
                    'like',
                    "%{$search}%"
                )

                    ->orWhereHas('user', function ($query) use ($search) {

                        $query->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    })

                    ->orWhereHas('package', function ($query) use ($search) {

                        $query->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        return $query;
    }

    /**
     * Whitelisted report/export columns requested by the column chooser.
     */
    private function exportColumns(Request $request): array
    {
        $available = ['date', 'order', 'customer', 'email', 'package', 'option', 'amount', 'status', 'proof', 'note'];
        $raw = $request->input('columns');

        if (! is_string($raw) || trim($raw) === '') {
            return $available;
        }

        $columns = array_values(array_unique(array_filter(
            array_map('trim', explode(',', $raw)),
            fn ($column) => in_array($column, $available, true)
        )));

        return $columns ?: $available;
    }

    /**
     * Shared report summary + meta.
     */
    private function reportData($orders, Request $request, $user): array
    {
        $paid = $orders->where('status', 'paid');
        $decidedCount = $paid->count() + $orders->whereIn('status', ['failed', 'expired', 'cancelled'])->count();
        $withProofCount = $orders->whereNotNull('proof_image_path')->count();
        $slaBreachCount = $orders->filter(function ($order) {
            return $order->status === 'pending'
                && $order->created_at
                && $order->created_at->lt(now()->subHours(24));
        })->count();

        $averageVerificationHours = $paid
            ->filter(fn ($order) => $order->paid_at && $order->created_at)
            ->avg(fn ($order) => $order->created_at->diffInHours($order->paid_at));

        return [
            'orders' => $orders,
            'columns' => $this->exportColumns($request),
            'total' => $orders->count(),
            'pendingCount' => $orders->where('status', 'pending')->count(),
            'paidCount' => $paid->count(),
            'failedCount' => $orders->whereIn('status', ['failed', 'expired', 'cancelled'])->count(),
            'revenue' => $paid->sum('amount'),
            'proofUploadRate' => $orders->count() ? (int) round($withProofCount / $orders->count() * 100) : 0,
            'approvalRate' => $decidedCount ? (int) round($paid->count() / $decidedCount * 100) : 0,
            'slaBreachCount' => $slaBreachCount,
            'averageVerificationHours' => $averageVerificationHours === null ? '-' : number_format((float) $averageVerificationHours, 1),
            'periode' => $this->reportPeriod($request),
            'filterStatus' => $request->input('status') ?: 'Semua',
            'search' => $request->input('search'),
            'isAdmin' => $user->hasAnyRole(['super-admin', 'admin']),
            'dicetakOleh' => $user->name ?? 'Sistem',
            'waktuCetak' => now()->translatedFormat('d F Y H:i'),
        ];
    }

    /**
     * Human readable report period label.
     */
    private function reportPeriod(Request $request): string
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            return $request->input('start_date').' sampai '.$request->input('end_date');
        }

        if ($request->filled('start_date')) {
            return 'Sejak '.$request->input('start_date');
        }

        if ($request->filled('end_date')) {
            return 'Hingga '.$request->input('end_date');
        }

        return 'Semua Periode';
    }

    /**
     * Notify all admins about a new order (never breaks checkout).
     */
    private function notifyAdmins(Order $order): void
    {
        try {
            $order->loadMissing(['user', 'package', 'packageOption']);

            $admins = User::role(['super-admin', 'admin'])
                ->where('uuid', '!=', $order->user_uuid)
                ->get();

            if ($admins->isEmpty()) {
                return;
            }

            $packageName = $order->package?->name ?? '-';
            if ($order->packageOption?->name) {
                $packageName .= ' — '.$order->packageOption->name;
            }

            Notification::send($admins, new OrderBaruNotification(
                $order->uuid,
                $order->order_number,
                $order->user?->name ?? '-',
                $packageName,
                (float) $order->amount,
            ));
        } catch (Throwable $e) {
            Log::error('Order admin notification failed: '.$e->getMessage());
        }
    }

    private function idempotencyKey(Request $request, string $action, string $subject): ?string
    {
        $provided = $request->header('Idempotency-Key') ?: $request->input('idempotency_key');

        if (! is_string($provided) || trim($provided) === '') {
            return null;
        }

        return 'orders.idempotency.'.Auth::user()->uuid.'.'.$action.'.'.sha1($subject.'|'.$provided);
    }

    private function cachedIdempotencyResult(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        $value = Cache::get($key);

        if (is_array($value)) {
            return ($value['status'] ?? null) === 'done'
                ? (string) ($value['result'] ?? 'done')
                : null;
        }

        return is_string($value) && $value !== 'processing' ? $value : null;
    }

    private function beginIdempotency(?string $key): bool
    {
        if (! $key) {
            return true;
        }

        $existing = Cache::get($key);

        if (is_array($existing) && ($existing['status'] ?? null) === 'processing') {
            if (($existing['started_at'] ?? 0) > now()->subMinutes(2)->getTimestamp()) {
                return false;
            }

            Cache::forget($key);
        } elseif ($existing === 'processing') {
            Cache::forget($key);
        }

        return Cache::add($key, [
            'status' => 'processing',
            'started_at' => now()->getTimestamp(),
        ], now()->addMinutes(10));
    }

    private function finishIdempotency(?string $key, string $result): void
    {
        if ($key) {
            Cache::put($key, [
                'status' => 'done',
                'result' => $result,
            ], now()->addMinutes(10));
        }
    }

    private function forgetIdempotency(?string $key): void
    {
        if ($key) {
            Cache::forget($key);
        }
    }

    private function canApproveOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('approve', Order::class);
    }

    private function canRejectOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('reject', Order::class);
    }

    private function canBulkOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('bulk', Order::class);
    }

    private function canBulkUndoOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('bulkUndo', Order::class);
    }

    private function canManageSavedFilters(): bool
    {
        return Gate::forUser(Auth::user())->allows('savedFilter', Order::class);
    }

    private function canQuickViewOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('quickView', Order::class);
    }

    private function canExportOrders(): bool
    {
        return Gate::forUser(Auth::user())->allows('export', Order::class);
    }

    private function orderValidationMessages(): array
    {
        return [
            'admin_note.required' => 'Alasan penolakan wajib diisi.',
            'admin_note.required_if' => 'Alasan penolakan wajib diisi.',
        ];
    }

    private function logOrderAction(Order $order, string $event, array $newValues = []): void
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
                'new_values' => $this->maskAuditValues($newValues),
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Order audit log failed: '.$e->getMessage(), ['order' => $order->uuid]);
        }
    }

    private function maskAuditValues(array $values): array
    {
        if (! empty($values['admin_note'])) {
            $values['admin_note'] = preg_replace(
                ['/[\w.+-]+@[\w.-]+\.\w+/', '/(08|\+?628)\d{6,}/'],
                ['[email]', '[nomor]'],
                (string) $values['admin_note']
            );
        }

        return $values;
    }
}
