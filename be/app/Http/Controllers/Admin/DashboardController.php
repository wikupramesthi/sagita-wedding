<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DashboardExport;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Consultation;
use App\Models\Event;
use App\Models\FailedLogin;
use App\Models\LoginLockout;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Venue;
use App\Models\WebsiteIdentity;
use App\Services\Dashboard\MemberDashboardService;
use App\Services\Dashboard\PackageRecommendationService;
use App\Support\DashboardCache;
use App\Support\MemberView;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function __construct(
        private readonly MemberDashboardService $memberDashboard,
        private readonly PackageRecommendationService $packageRecommendations,
    ) {}

    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget_max' => ['nullable', 'integer', 'min:100000'],
        ]);

        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);
        $canViewConsultations = $user->can('konsultasi.index');

        [$startDate, $endDate] = $this->resolveRange($validated);
        $bounds = $this->periodBounds($startDate, $endDate);
        $previousStart = $bounds['previousStart'];
        $previousEnd = $bounds['previousEnd'];

        $periodLabel = Carbon::parse($startDate)->translatedFormat('d M Y')
            .' – '.Carbon::parse($endDate)->translatedFormat('d M Y');

        $identity = WebsiteIdentity::singleton();

        $countBetween = function ($query, string $column, string $from, string $to): int {
            return (clone $query)
                ->whereBetween($column, [$from.' 00:00:00', $to.' 23:59:59'])
                ->count();
        };

        $sparkline = function ($query, string $column, string $from, string $to, int $points = 14): array {
            return $this->sparkline($query, $column, $from, $to, $points);
        };

        $makeStat = function (
            string $label,
            $query,
            string $column,
            string $icon,
            string $class,
            string $route,
            array $params = []
        ) use ($countBetween, $sparkline, $startDate, $endDate, $previousStart, $previousEnd): array {
            $current = $countBetween($query, $column, $startDate, $endDate);
            $previous = $countBetween($query, $column, $previousStart, $previousEnd);

            return [
                'label' => $label,
                'value' => $current,
                'previous' => $previous,
                'change' => $this->percentageChange($current, $previous),
                'spark' => $sparkline($query, $column, $startDate, $endDate),
                'icon' => $icon,
                'class' => $class,
                'route' => route($route, $params),
            ];
        };

        $makeSnapshot = function (
            string $label,
            int $value,
            string $icon,
            string $class,
            string $route,
            array $params = []
        ): array {
            return [
                'label' => $label,
                'value' => $value,
                'previous' => null,
                'change' => null,
                'spark' => [],
                'icon' => $icon,
                'class' => $class,
                'route' => route($route, $params),
            ];
        };

        $data = [
            'user' => $user,
            'brandName' => $identity->site_name ?: config('app.name', 'Sagita Wedding'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
            'canViewConsultations' => $canViewConsultations,
            'unreadCount' => $user->unreadNotifications()->count(),
        ];

        if ($isAdmin) {
            $adminData = Cache::remember(
                DashboardCache::key($startDate, $endDate, $canViewConsultations),
                300,
                fn () => $this->buildAdminSummary($startDate, $endDate, $canViewConsultations, $countBetween, $makeStat, $makeSnapshot)
            );

            $freshKey = DashboardCache::version().'.'.(int) $canViewConsultations;

            $adminViewData = [
                'actionQueue' => Cache::remember('dashboard.admin.action.'.$freshKey, 60, fn () => $this->actionQueue($canViewConsultations)),
                'recentOrders' => Cache::remember('dashboard.admin.recent-orders.'.$freshKey, 60, fn () => Order::with('user:uuid,name')->latest()->take(5)->get()),
            ];

            if ($isAdmin) {
                $adminViewData['upcomingWeddingOrders'] = Order::packageOnly()
                    ->whereIn('status', ['pending', 'partially_paid', 'paid'])
                    ->whereHas('package', fn ($query) => $query->wedding())
                    ->whereNotNull('event_date')
                    ->whereDate('event_date', '>=', now()->toDateString())
                    ->with(['user:uuid,name,email', 'package:uuid,name', 'packageOption:uuid,name'])
                    ->orderBy('event_date')
                    ->take(8)
                    ->get();
            }

            if ($canViewConsultations) {
                $adminViewData['recentConsultations'] = Cache::remember('dashboard.admin.recent-consultations.'.$freshKey, 60, fn () => Consultation::latest()->take(5)->get([
                    'uuid',
                    'full_name',
                    'email',
                    'whatsapp',
                    'lead_status',
                    'follow_up_at',
                    'created_at',
                ]));
            }

            $data = array_merge($data, $adminData, $adminViewData);
        }

        $data += [
            'events' => Event::where('status', 'published')
                ->whereDate('tanggal', '>=', now())
                ->orderBy('tanggal')
                ->orderBy('waktu_mulai')
                ->take(5)
                ->get(),
            'todaySchedules' => collect(),
        ];

        if (! $isAdmin) {
            $data = array_merge($data, $this->memberDashboard->build($user, $validated['budget_max'] ?? null));

            if (MemberView::isMobile()) {
                return view('pages.mobile.home', $data + [
                    'myBookings' => collect(),
                    'todayDate' => now()->format('Y-m-d'),
                    'tomorrowDate' => now()->addDay()->format('Y-m-d'),
                ]);
            }

            return view('pages.dashboard.index', $data);
        }

        return view('pages.dashboard.index', $data);
    }

    public function recommendations(Request $request): JsonResponse
    {
        abort_unless(Auth::user()?->hasRole('user'), 403);

        $validated = $request->validate([
            'budget_max' => ['required', 'integer', 'min:100000'],
        ]);
        $budgetMax = (int) $validated['budget_max'];

        $recommendations = $this->packageRecommendations->toPayload(
            $this->packageRecommendations->forBudget($budgetMax)
        );

        return response()->json([
            'budget_max' => $budgetMax,
            'formatted_budget' => number_format($budgetMax, 0, ',', '.'),
            'recommendations' => $recommendations,
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $summary = $this->dashboardSummary($request, $request->boolean('fresh'));
        $action = $this->actionQueue((bool) $summary['canViewConsultations']);

        return response()->json([
            'ok' => true,
            'updatedAt' => now()->format('c'),
            'periodLabel' => $summary['periodLabel'],
            'stats' => $summary['stats'],
            'consultationPipeline' => $summary['consultationPipeline'],
            'consultationHighlights' => $summary['consultationHighlights'],
            'funnel' => $summary['funnel'],
            'metrics' => [
                'newOrdersCount' => $summary['newOrdersCount'],
                'newMembershipCount' => $summary['newMembershipCount'],
                'revenueMonth' => $summary['revenueMonth'],
                'revenueTotal' => $summary['revenueTotal'],
            ],
            'security' => $summary['security'],
            'revenueChart' => $summary['revenueChart'],
            'site' => $summary['site'],
            'actionCounts' => [
                'orders' => (int) $action['orderCount'],
                'messages' => (int) $action['messageCount'],
            ],
            'actionTotal' => (int) $action['orderCount'] + (int) $action['messageCount'],
            'unreadCount' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    public function export(Request $request)
    {
        $summary = $this->dashboardSummary($request);

        return Excel::download(
            new DashboardExport($this->exportRows($summary), $summary['periodLabel']),
            'dashboard-'.$summary['startDate'].'-'.$summary['endDate'].'.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $summary = $this->dashboardSummary($request);

        return Pdf::loadView('pages.dashboard.laporan', $summary + [
            'actionQueue' => $this->actionQueue((bool) $summary['canViewConsultations']),
        ])
            ->setPaper('a4', 'landscape')
            ->download('dashboard-'.$summary['startDate'].'-'.$summary['endDate'].'.pdf');
    }

    private function dashboardSummary(Request $request, bool $fresh = false): array
    {
        $user = Auth::user();
        abort_unless($user && $user->hasAnyRole(['super-admin', 'admin']), 404);

        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        [$startDate, $endDate] = $this->resolveRange($validated);
        $bounds = $this->periodBounds($startDate, $endDate);
        $canViewConsultations = Auth::user()->can('konsultasi.index');

        $countBetween = function ($query, string $column, string $from, string $to): int {
            return (clone $query)
                ->whereBetween($column, [$from.' 00:00:00', $to.' 23:59:59'])
                ->count();
        };

        $sparkline = fn ($query, string $column, string $from, string $to, int $points = 14): array => $this->sparkline($query, $column, $from, $to, $points);

        $makeStat = function (
            string $label,
            $query,
            string $column,
            string $icon,
            string $class,
            string $route,
            array $params = []
        ) use ($countBetween, $sparkline, $startDate, $endDate, $bounds): array {
            $current = $countBetween($query, $column, $startDate, $endDate);
            $previous = $countBetween($query, $column, $bounds['previousStart'], $bounds['previousEnd']);

            return [
                'label' => $label,
                'value' => $current,
                'previous' => $previous,
                'change' => $this->percentageChange($current, $previous),
                'spark' => $sparkline($query, $column, $startDate, $endDate),
                'icon' => $icon,
                'class' => $class,
                'route' => route($route, $params),
            ];
        };

        $makeSnapshot = function (
            string $label,
            int $value,
            string $icon,
            string $class,
            string $route,
            array $params = []
        ): array {
            return [
                'label' => $label,
                'value' => $value,
                'previous' => null,
                'change' => null,
                'spark' => [],
                'icon' => $icon,
                'class' => $class,
                'route' => route($route, $params),
            ];
        };

        $summaryKey = DashboardCache::key($startDate, $endDate, $canViewConsultations);
        $freshKey = DashboardCache::version().'.'.(int) $canViewConsultations;

        if ($fresh) {
            Cache::forget($summaryKey);
            Cache::forget('dashboard.admin.action.'.$freshKey);
            Cache::forget('dashboard.admin.recent-orders.'.$freshKey);
            Cache::forget('dashboard.admin.recent-consultations.'.$freshKey);
        }

        $summary = Cache::remember(
            $summaryKey,
            300,
            fn () => $this->buildAdminSummary($startDate, $endDate, $canViewConsultations, $countBetween, $makeStat, $makeSnapshot)
        );

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => Carbon::parse($startDate)->translatedFormat('d M Y').' – '.Carbon::parse($endDate)->translatedFormat('d M Y'),
            'canViewConsultations' => $canViewConsultations,
            'brandName' => WebsiteIdentity::singleton()->site_name ?: config('app.name', 'Sagita Wedding'),
        ] + $summary;
    }

    private function buildAdminSummary(
        string $startDate,
        string $endDate,
        bool $canViewConsultations,
        Closure $countBetween,
        Closure $makeStat,
        Closure $makeSnapshot
    ): array {
        $newOrdersCount = $countBetween(Order::whereIn('status', ['pending', 'partially_paid']), 'created_at', $startDate, $endDate);
        $newMembershipCount = $countBetween(UserPackage::where('status', 'active'), 'started_at', $startDate, $endDate);
        $revenueMonth = (float) Order::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->sum(DB::raw('COALESCE(NULLIF(total_amount, 0), amount)'));

        $stats = [
            $makeStat('Member baru', User::role('user'), 'created_at', 'bi bi-people', 'purple', 'pengguna.index'),
            $makeStat('Vendor baru', User::role('vendor'), 'created_at', 'bi bi-person-badge', 'blue', 'vendor.index'),
            $makeSnapshot('Venue aktif', (int) Venue::where('status', 'active')->count(), 'bi bi-geo-alt', 'green', 'venues.index'),
        ];
        $funnel = [];
        $consultationPipeline = [];
        $consultationHighlights = null;

        if ($canViewConsultations) {
            $consultationStats = Consultation::stats([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            $leadCounts = Consultation::query()
                ->selectRaw('lead_status, COUNT(*) as total')
                ->groupBy('lead_status')
                ->pluck('total', 'lead_status');
            $consultationPipeline = collect(Consultation::LEAD_STATUS_LABELS)
                ->map(function ($label, $key) use ($leadCounts) {
                    return [
                        'key' => $key,
                        'label' => $label,
                        'count' => (int) ($leadCounts->get($key) ?? 0),
                        'limit' => (int) (Consultation::WIP_LIMITS[$key] ?? 0),
                        'badge' => (new Consultation(['lead_status' => $key]))->lead_status_badge_class,
                        'route' => route('konsultasi.index', ['lead' => $key]),
                    ];
                })
                ->values()
                ->all();
            $consultationHighlights = [
                'conversion_rate' => (int) $consultationStats['conversion_rate'],
                'sla_breach' => (int) $consultationStats['sla_breach'],
                'sla_warning' => (int) $consultationStats['sla_warning'],
                'converted' => (int) $consultationStats['converted'],
            ];
            $stats[] = $makeStat('Lead baru', Consultation::where('lead_status', 'New'), 'created_at', 'bi bi-person-plus', 'cyan', 'konsultasi.index', ['lead' => 'New']);
            $stats[] = $makeSnapshot('Follow-up terlewat', (int) $consultationStats['overdue'], 'bi bi-clock-history', 'red', 'konsultasi.index', ['follow_up' => 'overdue']);
            $funnel = [
                ['label' => 'Lead konsultasi', 'value' => $countBetween(Consultation::query(), 'created_at', $startDate, $endDate)],
                ['label' => 'Lead panas', 'value' => $countBetween(Consultation::whereIn('lead_status', ['Interested', 'Negotiation']), 'created_at', $startDate, $endDate)],
                ['label' => 'Terkonversi', 'value' => $countBetween(Consultation::where('lead_status', 'Converted'), 'created_at', $startDate, $endDate)],
            ];
        }

        $stats = array_merge($stats, [
            $makeStat('Pesanan baru', Order::query(), 'created_at', 'bi bi-bag-plus', 'yellow', 'orders.index'),
            $makeStat('Paket', Package::query(), 'created_at', 'bi bi-box-seam', 'pink', 'packages.index'),
            $makeStat('Event', Event::query(), 'created_at', 'bi bi-calendar-event', 'green', 'events.index'),
            $makeStat('Artikel', Article::query(), 'created_at', 'bi bi-file-earmark-text', 'blue', 'articles.index'),
        ]);
        $funnel = array_merge($funnel, [
            ['label' => 'Pesanan', 'value' => $countBetween(Order::query(), 'created_at', $startDate, $endDate)],
            ['label' => 'Lunas', 'value' => $countBetween(Order::where('status', 'paid'), 'paid_at', $startDate, $endDate)],
            ['label' => 'Paket pernikahan aktif', 'value' => $newMembershipCount],
        ]);

        return [
            'revenueChart' => $this->revenueSeries($startDate, $endDate),
            'site' => $this->siteAnalytics($startDate, $endDate),
            'stats' => $stats,
            'consultationPipeline' => $consultationPipeline,
            'consultationHighlights' => $consultationHighlights,
            'funnel' => $funnel,
            'newOrdersCount' => $newOrdersCount,
            'newMembershipCount' => $newMembershipCount,
            'revenueMonth' => $revenueMonth,
            'revenueTotal' => (float) Order::whereIn('status', ['paid', 'partially_paid'])
                ->sum(DB::raw("CASE WHEN status = 'paid' THEN COALESCE(NULLIF(total_amount, 0), amount) ELSE COALESCE(amount_paid, 0) END")),
            'security' => [
                'failedLogins' => (int) FailedLogin::where('created_at', '>=', now()->subDay())->count(),
                'lockouts' => (int) LoginLockout::active()->count(),
            ],
        ];
    }

    private function exportRows(array $summary): array
    {
        $rows = [];

        foreach ($summary['stats'] as $stat) {
            $rows[] = ['Ringkasan', $stat['label'], $stat['value'], $stat['change'] === null ? '-' : $stat['change'].'%'];
        }

        foreach ($summary['consultationPipeline'] as $stage) {
            $rows[] = ['Pipeline Konsultasi', $stage['label'], $stage['count'], 'WIP '.$stage['limit']];
        }

        $highlightLabels = [
            'conversion_rate' => 'Tingkat konversi',
            'sla_breach' => 'SLA terlewat',
            'sla_warning' => 'SLA mendekat',
            'converted' => 'Terkonversi',
        ];
        foreach ($summary['consultationHighlights'] ?? [] as $key => $value) {
            $rows[] = ['Konsultasi', $highlightLabels[$key] ?? $key, $value, ''];
        }

        foreach ($summary['funnel'] as $step) {
            $rows[] = ['Funnel', $step['label'], $step['value'], ''];
        }

        $rows[] = ['Metrik cepat', 'Pesanan baru', $summary['newOrdersCount'], $summary['periodLabel']];
        $rows[] = ['Metrik cepat', 'Paket pernikahan baru', $summary['newMembershipCount'], $summary['periodLabel']];
        $rows[] = ['Metrik cepat', 'Pendapatan periode', number_format($summary['revenueMonth'], 0, ',', '.'), 'Rp'];
        $rows[] = ['Metrik cepat', 'Pendapatan total', number_format($summary['revenueTotal'], 0, ',', '.'), 'Rp'];
        $rows[] = ['Keamanan 24 jam', 'Login gagal', $summary['security']['failedLogins'], ''];
        $rows[] = ['Keamanan 24 jam', 'Akun terkunci', $summary['security']['lockouts'], ''];

        $action = $this->actionQueue((bool) $summary['canViewConsultations']);
        $rows[] = ['Perlu tindakan', 'Pembayaran perlu verifikasi', $action['orderCount'], ''];
        $rows[] = ['Perlu tindakan', 'Follow-up terlewat', $action['messageCount'], ''];

        return $rows;
    }

    private function resolveRange(array $validated): array
    {
        $end = $validated['end_date'] ?? now()->toDateString();
        $start = $validated['start_date'] ?? Carbon::parse($end)->subDays(29)->toDateString();

        if (Carbon::parse($start)->gt(Carbon::parse($end))) {
            $start = Carbon::parse($end)->subDays(29)->toDateString();
        }

        return [$start, $end];
    }

    private function periodBounds(string $start, string $end): array
    {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->startOfDay();
        $days = $startDate->diffInDays($endDate) + 1;

        return [
            'previousStart' => $startDate->copy()->subDays($days)->toDateString(),
            'previousEnd' => $startDate->copy()->subDay()->toDateString(),
            'days' => $days,
        ];
    }

    private function percentageChange(int $current, int $previous): ?int
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : null;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function sparkline($query, string $column, string $from, string $to, int $points = 14): array
    {
        $rows = (clone $query)
            ->whereBetween($column, [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw('DATE('.$column.') as day_key, COUNT(*) as total')
            ->groupBy('day_key')
            ->pluck('total', 'day_key');

        $start = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->startOfDay();
        $totalDays = $start->diffInDays($end) + 1;
        $bucketSize = max(1, (int) ceil($totalDays / min($points, $totalDays)));

        $result = [];
        for ($offset = 0; $offset < $totalDays; $offset += $bucketSize) {
            $sum = 0;
            for ($day = $offset; $day < min($offset + $bucketSize, $totalDays); $day++) {
                $sum += (int) ($rows[$start->copy()->addDays($day)->toDateString()] ?? 0);
            }
            $result[] = $sum;
        }

        return $result;
    }

    private function revenueSeries(string $from, string $to): array
    {
        $rows = Order::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw("DATE(paid_at) as day, DATE_FORMAT(paid_at, '%Y-%m') as month, YEAR(paid_at) as year, SUM(COALESCE(NULLIF(total_amount, 0), amount)) as total")
            ->groupByRaw("DATE(paid_at), DATE_FORMAT(paid_at, '%Y-%m'), YEAR(paid_at)")
            ->get();

        $dailyRows = $rows->mapWithKeys(fn ($row) => [$row->day => (float) $row->total]);
        $monthlyRows = $rows->mapWithKeys(fn ($row) => [$row->month => (float) $row->total]);
        $yearlyRows = $rows->mapWithKeys(fn ($row) => [(string) $row->year => (float) $row->total]);

        $start = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->startOfDay();

        $daily = [];
        for ($date = $start->copy(); $date <= $end && count($daily) < 366; $date->addDay()) {
            $key = $date->toDateString();
            $daily['labels'][] = $date->translatedFormat('d M');
            $daily['data'][] = (float) ($dailyRows[$key] ?? 0);
        }

        $monthly = [];
        for ($month = $start->copy()->startOfMonth(); $month <= $end && count($monthly['labels'] ?? []) < 24; $month->addMonth()) {
            $key = $month->format('Y-m');
            $monthly['labels'][] = $month->translatedFormat('M Y');
            $monthly['data'][] = (float) ($monthlyRows[$key] ?? 0);
        }

        $yearly = [];
        for ($year = $start->copy()->startOfYear(); $year <= $end && count($yearly['labels'] ?? []) < 10; $year->addYear()) {
            $key = $year->format('Y');
            $yearly['labels'][] = $key;
            $yearly['data'][] = (float) ($yearlyRows[$key] ?? 0);
        }

        return [
            'daily' => $daily + ['labels' => [], 'data' => []],
            'monthly' => $monthly + ['labels' => [], 'data' => []],
            'yearly' => $yearly + ['labels' => [], 'data' => []],
        ];
    }

    private function actionQueue(bool $includeConsultations): array
    {
        $pendingOrders = Order::with('user:uuid,name')
            ->whereIn('status', ['pending', 'partially_paid'])
            ->where(function ($q) {
                $q->whereNotNull('proof_image_path')->orWhereHas('payments', fn ($p) => $p->where('verification_status', 'pending')->orWhere('status', 'pending'));
            })
            ->latest()
            ->take(5)
            ->get();

        $overdueFollowUps = collect();
        $overdueFollowUpCount = 0;
        if ($includeConsultations) {
            $overdueFollowUps = Consultation::whereNotNull('follow_up_at')
                ->where('follow_up_at', '<', now())
                ->whereNotIn('lead_status', ['Converted', 'Lost'])
                ->latest('follow_up_at')
                ->take(5)
                ->get(['uuid', 'full_name', 'email', 'whatsapp', 'lead_status', 'follow_up_at']);
            $overdueFollowUpCount = (int) Consultation::whereNotNull('follow_up_at')
                ->where('follow_up_at', '<', now())
                ->whereNotIn('lead_status', ['Converted', 'Lost'])
                ->count();
        }

        return [
            'orders' => $pendingOrders,
            'orderCount' => (int) Order::whereIn('status', ['pending', 'partially_paid'])->where(function ($q) {
                $q->whereNotNull('proof_image_path')->orWhereHas('payments', fn ($p) => $p->where('verification_status', 'pending')->orWhere('status', 'pending'));
            })->count(),
            'messages' => $overdueFollowUps,
            'messageCount' => $overdueFollowUpCount,
        ];
    }

    private function siteAnalytics(string $from, string $to): array
    {
        $start = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->startOfDay();
        $monthStart = $start->copy()->startOfMonth();
        $monthEnd = $end->copy()->startOfMonth();

        $dailyRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as unique_visitors')
            ->groupBy('visit_date')
            ->pluck('total', 'visit_date');

        $dailyUniqueRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw('DATE(visited_at) as visit_date, COUNT(DISTINCT visitor_hash) as unique_visitors')
            ->groupBy('visit_date')
            ->pluck('unique_visitors', 'visit_date');

        $monthExpression = match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', visited_at)",
            'pgsql' => "TO_CHAR(visited_at, 'YYYY-MM')",
            default => "DATE_FORMAT(visited_at, '%Y-%m')",
        };

        $monthlyRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw("$monthExpression as visit_month, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as unique_visitors")
            ->groupBy('visit_month')
            ->pluck('total', 'visit_month');

        $monthlyUniqueRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw("$monthExpression as visit_month, COUNT(DISTINCT visitor_hash) as unique_visitors")
            ->groupBy('visit_month')
            ->pluck('unique_visitors', 'visit_month');

        $visitorDailyLabels = [];
        $visitorDailyTotal = [];
        $visitorDailyUnique = [];
        for ($date = $start->copy(); $date <= $end && count($visitorDailyLabels) < 366; $date->addDay()) {
            $key = $date->toDateString();
            $visitorDailyLabels[] = $date->translatedFormat('d M');
            $visitorDailyTotal[] = (int) ($dailyRows[$key] ?? 0);
            $visitorDailyUnique[] = (int) ($dailyUniqueRows[$key] ?? 0);
        }

        $visitorMonthLabels = [];
        $visitorMonthTotal = [];
        $visitorMonthUnique = [];
        for ($month = $monthStart->copy(); $month <= $monthEnd && count($visitorMonthLabels) < 24; $month->addMonth()) {
            $key = $month->format('Y-m');
            $visitorMonthLabels[] = $month->translatedFormat('M Y');
            $visitorMonthTotal[] = (int) ($monthlyRows[$key] ?? 0);
            $visitorMonthUnique[] = (int) ($monthlyUniqueRows[$key] ?? 0);
        }

        $contentRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->select('path')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $deviceRows = DB::table('site_visits')
            ->whereBetween('visited_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->select('device')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('device')
            ->pluck('total', 'device');

        return [
            'visitorDailyLabels' => $visitorDailyLabels,
            'visitorDailyTotal' => $visitorDailyTotal,
            'visitorDailyUnique' => $visitorDailyUnique,
            'visitorMonthLabels' => $visitorMonthLabels,
            'visitorMonthTotal' => $visitorMonthTotal,
            'visitorMonthUnique' => $visitorMonthUnique,
            'activeVisitors' => (int) DB::table('site_visits')
                ->where('visited_at', '>=', now()->subMinutes(5))
                ->distinct()
                ->count('visitor_hash'),
            'activeVisitorsUpdatedAt' => now()->format('H.i'),
            'analyticsContentLabels' => $contentRows->pluck('path')->all(),
            'analyticsContentCounts' => $contentRows->pluck('total')->map(fn ($total) => (int) $total)->all(),
            'analyticsDeviceLabels' => ['Desktop', 'Ponsel', 'Tablet'],
            'analyticsDeviceCounts' => [
                (int) ($deviceRows['desktop'] ?? 0),
                (int) ($deviceRows['mobile'] ?? 0),
                (int) ($deviceRows['tablet'] ?? 0),
            ],
        ];
    }

    public function submitSumber(Request $request)
    {
        $request->validate([
            'no_hp' => [
                'required',
                'regex:/^[0-9]{8,15}$/',
                'unique:users,no_hp,'.Auth::user()->uuid.',uuid',
            ],
            'sumber_informasi' => ['required', 'string', 'max:255'],
        ], [
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.regex' => 'Format nomor telepon tidak valid.',
            'no_hp.unique' => 'Nomor telepon sudah terdaftar.',
            'sumber_informasi.required' => 'Pilih sumber informasi.',
            'sumber_informasi.string' => 'Sumber informasi tidak valid.',
            'sumber_informasi.max' => 'Sumber informasi terlalu panjang.',
        ]);

        $user = Auth::user();
        $user->update([
            'no_hp' => trim($request->no_hp),
            'sumber_informasi' => $request->sumber_informasi,
        ]);

        Auth::setUser($user->fresh());

        return redirect()
            ->route('dashboard.index')
            ->with('success', __('flash.sumber_ok'));
    }
}
