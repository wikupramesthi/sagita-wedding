<?php

namespace App\Services\Dashboard;

use App\Models\Banner;
use App\Models\Payment\Order;
use App\Models\Payment\WeddingChecklistItem;
use App\Models\User;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MemberDashboardService
{
    public function __construct(private readonly PackageRecommendationService $recommendations)
    {
    }

    public function build(User $user, ?int $budgetMax): array
    {
        $activePackage = $this->activePackage($user);
        $myOrders = $this->myOrders($user);
        $planningOrders = $this->planningOrders($user);
        $planningEventOrder = $this->planningEventOrder($planningOrders);
        $checklistItems = $this->checklistItems($planningEventOrder);
        $planningEventDate = $planningEventOrder?->event_date;
        $paymentTotals = $this->paymentTotals($myOrders);
        $planningTotals = $this->planningTotals($planningOrders);
        $planningSteps = $this->planningSteps($planningOrders, $planningEventDate);
        $completedPlanningSteps = count(array_filter($planningSteps, fn (array $step) => $step['done']));
        $orderStats = $this->orderStats($user, $activePackage);

        return [
            'activePackage' => $activePackage,
            'myOrders' => $myOrders,
            'banner' => Banner::where('status', 'active')->where('posisi', 'slider')->first(),
            'recentPaymentProofs' => $this->recentPaymentProofs($myOrders),
            ...$paymentTotals,
            'planningEventDate' => $planningEventDate,
            'planningOrders' => $planningOrders,
            'dashboardChecklistOrder' => $planningEventOrder,
            'dashboardChecklistItems' => $checklistItems,
            ...$planningTotals,
            'planningDaysRemaining' => $planningEventDate
                ? now()->startOfDay()->diffInDays(Carbon::parse($planningEventDate)->startOfDay(), false)
                : null,
            'planningTimeline' => $this->planningTimeline($planningEventDate),
            'planningSteps' => $planningSteps,
            'completedPlanningSteps' => $completedPlanningSteps,
            'planningProgress' => (int) round($completedPlanningSteps / count($planningSteps) * 100),
            'budgetMax' => $budgetMax,
            'recommendedPackages' => $budgetMax === null
                ? collect()
                : $this->recommendations->forBudget($budgetMax),
            'myOrderStats' => $orderStats,
            'memberStats' => $this->memberStats($orderStats),
        ];
    }

    private function activePackage(User $user): ?UserPackage
    {
        return $user->userPackages()
            ->with('package')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expired_at')->orWhere('expired_at', '>=', now());
            })
            ->latest('started_at')
            ->first();
    }

    private function myOrders(User $user): Collection
    {
        return Order::where('user_uuid', $user->uuid)
            ->where('type', 'package')
            ->whereIn('status', ['pending', 'partially_paid', 'paid'])
            ->with([
                'package:uuid,name',
                'packageOption:uuid,name',
                'payments' => fn ($query) => $query->latest(),
            ])
            ->latest()
            ->take(5)
            ->get();
    }

    private function planningOrders(User $user): Collection
    {
        return Order::where('user_uuid', $user->uuid)
            ->where('type', 'package')
            ->whereIn('status', ['pending', 'partially_paid', 'paid'])
            ->whereHas('package', fn ($query) => $query->wedding())
            ->with([
                'package:uuid,name',
                'packageOption:uuid,name',
                'payments' => fn ($query) => $query->latest(),
            ])
            ->latest()
            ->orderBy('event_date')
            ->get();
    }

    private function planningEventOrder(Collection $planningOrders): ?Order
    {
        return $planningOrders->first(
            fn (Order $order) => $order->event_date?->greaterThanOrEqualTo(today())
        ) ?? $planningOrders->first(fn (Order $order) => $order->event_date !== null);
    }

    private function checklistItems(?Order $order): Collection
    {
        return $order
            ? WeddingChecklistItem::where('order_uuid', $order->uuid)->orderBy('due_at')->orderBy('created_at')->get()
            : collect();
    }

    private function recentPaymentProofs(Collection $myOrders): Collection
    {
        return $myOrders
            ->flatMap(fn (Order $order) => $order->payments
                ->filter(fn ($payment) => $payment->proof_image_path)
                ->map(fn ($payment) => ['order' => $order, 'payment' => $payment]))
            ->sortByDesc(fn (array $proof) => $proof['payment']->proof_uploaded_at ?? $proof['payment']->created_at)
            ->take(5)
            ->values();
    }

    private function paymentTotals(Collection $myOrders): array
    {
        $orderTotal = $myOrders->sum(fn (Order $order) => (float) $order->full_amount);
        $paidTotal = $myOrders->sum(fn (Order $order) => (float) $order->amount_paid_value);

        return [
            'paymentOrderTotal' => $orderTotal,
            'paymentPaidTotal' => $paidTotal,
            'paymentRemainingTotal' => max(0, $orderTotal - $paidTotal),
            'paymentProgress' => $orderTotal > 0
                ? (int) min(100, round($paidTotal / $orderTotal * 100))
                : 0,
        ];
    }

    private function planningTotals(Collection $planningOrders): array
    {
        $orderTotal = $planningOrders->sum(fn (Order $order) => (float) $order->full_amount);
        $paidTotal = $planningOrders->sum(fn (Order $order) => (float) $order->amount_paid_value);

        return [
            'planningOrderTotal' => $orderTotal,
            'planningPaidTotal' => $paidTotal,
            'planningRemainingTotal' => max(0, $orderTotal - $paidTotal),
            'planningBudgetProgress' => $orderTotal > 0
                ? (int) min(100, round($paidTotal / $orderTotal * 100))
                : 0,
        ];
    }

    private function planningTimeline($planningEventDate): Collection
    {
        return collect([
            ['label' => 'Tentukan konsep & anggaran', 'months_before' => 12],
            ['label' => 'Pilih venue dan vendor utama', 'months_before' => 9],
            ['label' => 'Finalisasi daftar tamu dan undangan', 'months_before' => 6],
            ['label' => 'Finalisasi dekorasi dan busana', 'months_before' => 3],
            ['label' => 'Konfirmasi vendor dan susunan acara', 'months_before' => 1],
            ['label' => 'Hari pernikahan', 'months_before' => 0],
        ])->map(fn (array $milestone) => [
            'label' => $milestone['label'],
            'offset' => $milestone['months_before'] > 0
                ? $milestone['months_before'].' bulan sebelum acara'
                : 'Hari pernikahan',
            'date' => $planningEventDate
                ? Carbon::parse($planningEventDate)->subMonthsNoOverflow($milestone['months_before'])
                : null,
        ]);
    }

    private function planningSteps(Collection $planningOrders, $planningEventDate): array
    {
        $hasPlanningOrders = $planningOrders->isNotEmpty();
        $allPlanningOrdersPaid = $hasPlanningOrders && $planningOrders->every(
            fn (Order $order) => $order->status === 'paid'
        );
        $orderRoute = $hasPlanningOrders ? route('orders.index') : '#package-recommendation';
        $orderAction = $hasPlanningOrders ? 'Lihat pesanan' : 'Cari paket';

        return [
            [
                'label' => 'Pilih paket wedding',
                'description' => $hasPlanningOrders
                    ? $planningOrders->count().' pesanan paket aktif'
                    : 'Pilih paket agar tanggal dan biaya acara terhubung di sini.',
                'done' => $hasPlanningOrders,
                'url' => $orderRoute,
                'action' => $orderAction,
            ],
            [
                'label' => 'Tentukan tanggal acara',
                'description' => $planningEventDate
                    ? Carbon::parse($planningEventDate)->translatedFormat('d F Y')
                    : 'Tanggal acara tercatat bersama pesanan paket.',
                'done' => $planningEventDate !== null,
                'url' => $orderRoute,
                'action' => $orderAction,
            ],
            [
                'label' => 'Selesaikan pembayaran',
                'description' => ! $hasPlanningOrders
                    ? 'Status pembayaran akan muncul setelah memilih paket.'
                    : ($allPlanningOrdersPaid ? 'Semua pesanan aktif sudah lunas.' : 'Masih ada pembayaran yang perlu diselesaikan.'),
                'done' => $allPlanningOrdersPaid,
                'url' => $orderRoute,
                'action' => $hasPlanningOrders ? 'Cek pembayaran' : 'Cari paket',
            ],
        ];
    }

    private function orderStats(User $user, ?UserPackage $activePackage): array
    {
        $row = Order::where('user_uuid', $user->uuid)
            ->reorder()
            ->selectRaw(
                'COUNT(*) as total, '.
                'SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as pending, '.
                "SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid",
                ['pending', 'partially_paid']
            )
            ->first();

        return [
            'total' => (int) $row->total,
            'pending' => (int) $row->pending,
            'paid' => (int) $row->paid,
            'active_package' => $activePackage ? 1 : 0,
        ];
    }

    private function memberStats(array $stats): array
    {
        return [
            ['label' => 'Total Pesanan', 'value' => $stats['total'], 'icon' => 'bi-receipt-cutoff', 'tone' => 'primary'],
            ['label' => 'Menunggu Pembayaran', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
            ['label' => 'Pesanan Lunas', 'value' => $stats['paid'], 'icon' => 'bi-patch-check', 'tone' => 'success'],
            ['label' => 'Paket Aktif', 'value' => $stats['active_package'], 'icon' => 'bi-box-seam', 'tone' => 'info'],
        ];
    }
}
