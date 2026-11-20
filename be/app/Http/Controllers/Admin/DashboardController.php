<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Class\ClassBooking;
use App\Models\Class\ClassModel;
use App\Models\Class\ClassSchedule;
use App\Models\Consultation;
use App\Models\Event;
use App\Models\FailedLogin;
use App\Models\LoginLockout;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\VendorsCategory;
use App\Models\Venue;
use App\Models\WebsiteIdentity;
use App\Support\MemberView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        if (! empty($validated['start_date']) && ! empty($validated['end_date'])) {
            $request->validate([
                'end_date' => ['after_or_equal:start_date'],
            ]);
        }

        $user = Auth::user();
        $isMember = $user->hasRole('user');

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
            string $route
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
                'route' => route($route),
            ];
        };

        $makeSnapshot = function (
            string $label,
            int $value,
            string $icon,
            string $class,
            string $route
        ): array {
            return [
                'label' => $label,
                'value' => $value,
                'previous' => null,
                'change' => null,
                'spark' => [],
                'icon' => $icon,
                'class' => $class,
                'route' => route($route),
            ];
        };

        $data = [
            'user' => $user,
            'brandName' => $identity->site_name ?: config('app.name', 'Sagita Wedding'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
            'unreadCount' => $user->unreadNotifications()->count(),
            'jumlahMembers' => $countBetween(User::role('user'), 'created_at', $startDate, $endDate),
            'jumlahVendor' => $countBetween(User::role('vendor'), 'created_at', $startDate, $endDate),
            'totalClasses' => $countBetween(ClassModel::query(), 'created_at', $startDate, $endDate),
            'totalEvents' => $countBetween(Event::query(), 'created_at', $startDate, $endDate),
        ];

        if (! $isMember) {
            $adminData = Cache::remember('dashboard.admin.summary.'.sha1($startDate.'|'.$endDate), 300, function () use ($countBetween, $makeStat, $makeSnapshot, $startDate, $endDate) {
                $newOrdersCount = $countBetween(Order::where('status', 'pending'), 'created_at', $startDate, $endDate);
                $newMembershipCount = $countBetween(UserPackage::where('status', 'active'), 'started_at', $startDate, $endDate);
                $revenueMonth = (float) Order::where('status', 'paid')
                    ->whereBetween('paid_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
                    ->sum('amount');

                return [
                    'revenueChart' => $this->revenueSeries($startDate, $endDate),
                    'site' => $this->siteAnalytics($startDate, $endDate),
                    'stats' => [
                        $makeStat('Member baru', User::role('user'), 'created_at', 'bi bi-people', 'purple', 'pengguna.index'),
                        $makeStat('Vendor baru', User::role('vendor'), 'created_at', 'bi bi-person-badge', 'blue', 'vendor.index'),
                        $makeSnapshot('Venue aktif', (int) Venue::where('status', 'active')->count(), 'bi bi-geo-alt', 'green', 'venues.index'),
                        $makeSnapshot('Kategori vendor aktif', (int) VendorsCategory::where('is_active', 'active')->count(), 'bi bi-shop', 'cyan', 'vendors_categories.index'),
                        $makeStat('Pertanyaan masuk', Consultation::query(), 'created_at', 'bi bi-chat-left-text', 'turquoise', 'konsultasi.index'),
                        $makeStat('Pesanan baru', Order::query(), 'created_at', 'bi bi-bag-plus', 'yellow', 'orders.index'),
                        $makeStat('Paket', Package::query(), 'created_at', 'bi bi-box-seam', 'pink', 'packages.index'),
                        $makeStat('Kelas', ClassModel::query(), 'created_at', 'bi bi-book', 'red', 'classes.index'),
                        $makeStat('Event', Event::query(), 'created_at', 'bi bi-calendar-event', 'green', 'events.index'),
                        $makeStat('Artikel', Article::query(), 'created_at', 'bi bi-file-earmark-text', 'blue', 'articles.index'),
                    ],
                    'funnel' => [
                        ['label' => 'Pertanyaan', 'value' => $countBetween(Consultation::query(), 'created_at', $startDate, $endDate)],
                        ['label' => 'Pesanan', 'value' => $countBetween(Order::query(), 'created_at', $startDate, $endDate)],
                        ['label' => 'Lunas', 'value' => $countBetween(Order::where('status', 'paid'), 'paid_at', $startDate, $endDate)],
                        ['label' => 'Keanggotaan aktif', 'value' => $newMembershipCount],
                    ],
                    'newOrdersCount' => $newOrdersCount,
                    'newMembershipCount' => $newMembershipCount,
                    'revenueMonth' => $revenueMonth,
                    'revenueTotal' => (float) Order::where('status', 'paid')->sum('amount'),
                    'security' => [
                        'failedLogins' => (int) FailedLogin::where('created_at', '>=', now()->subDay())->count(),
                        'lockouts' => (int) LoginLockout::active()->count(),
                    ],
                ];
            });

            $data = array_merge($data, $adminData, [
                'actionQueue' => $this->actionQueue(),
                'recentOrders' => Order::with('user', 'package')->latest()->take(5)->get(),
                'recentMemberships' => UserPackage::with('user', 'package')->latest('started_at')->take(5)->get(),
            ]);
        }

        $activePackage = $user->userPackages()
            ->with('package')
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
            })
            ->latest('started_at')
            ->first();

        $data['activePackage'] = $activePackage;
        $data['attendedCount'] = ClassBooking::where('user_uuid', $user->uuid)
            ->where('status', 'attended')
            ->count();

        $todaySchedules = ClassSchedule::with('class.vendor')
            ->withCount([
                'bookings as bookings_count' => function ($q) {
                    $q->whereIn('status', ['confirmed', 'attended']);
                },
            ])
            ->where('status', 'active')
            ->where('day', strtolower(now()->format('l')))
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $today = strtolower(now()->format('l'));
        $nextDay = $days[((array_search($today, $days) + 1) % count($days))];

        $upcomingClasses = ClassSchedule::with('class.vendor')
            ->withCount([
                'bookings as bookings_count' => function ($q) {
                    $q->whereIn('status', ['confirmed', 'attended']);
                },
            ])
            ->where('status', 'active')
            ->where('day', $nextDay)
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $data += [
            'events' => Event::where('status', 'published')
                ->whereDate('tanggal', '>=', now())
                ->orderBy('tanggal')
                ->orderBy('waktu_mulai')
                ->take(5)
                ->get(),
            'todaySchedules' => $todaySchedules,
            'upcomingClasses' => $upcomingClasses,
        ];

        if ($isMember) {
            $data['banner'] = Banner::where('status', 'active')->where('posisi', 'slider')->first();
            $data['courses'] = $this->artOfLivingCourses();

            if (MemberView::isMobile()) {
                $scheduleUuids = $todaySchedules->pluck('uuid')->merge($upcomingClasses->pluck('uuid'));

                $myBookings = ClassBooking::where('user_uuid', $user->uuid)
                    ->whereIn('class_schedule_uuid', $scheduleUuids)
                    ->where('status', '!=', 'cancelled')
                    ->get()
                    ->keyBy(fn ($b) => $b->class_schedule_uuid.'|'.$b->booking_date?->format('Y-m-d'));

                return view('pages.mobile.home', $data + [
                    'courses' => array_slice($data['courses'], 0, 6),
                    'myBookings' => $myBookings,
                    'todayDate' => now()->format('Y-m-d'),
                    'tomorrowDate' => now()->addDay()->format('Y-m-d'),
                ]);
            }
        }

        return view('pages.dashboard.index', $data);
    }

    private function resolveRange(array $validated): array
    {
        $end = $validated['end_date'] ?? now()->toDateString();
        $start = $validated['start_date'] ?? Carbon::parse($end)->subDays(29)->toDateString();

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
            ->select('paid_at', 'amount')
            ->get();

        $dailyRows = $rows->groupBy(fn ($row) => $row->paid_at->toDateString())
            ->map(fn ($group) => (float) $group->sum('amount'));
        $monthlyRows = $rows->groupBy(fn ($row) => $row->paid_at->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('amount'));
        $yearlyRows = $rows->groupBy(fn ($row) => $row->paid_at->format('Y'))
            ->map(fn ($group) => (float) $group->sum('amount'));

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

    private function actionQueue(): array
    {
        $pendingOrders = Order::with('user', 'package')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $todayBookings = ClassBooking::with('user', 'schedule.class')
            ->where('status', 'confirmed')
            ->whereDate('booking_date', today())
            ->latest()
            ->take(5)
            ->get();

        $openMessages = Consultation::whereIn('consultation_status', ['Pending', 'Confirmed'])
            ->latest()
            ->take(5)
            ->get(['full_name', 'email', 'whatsapp', 'wedding_concept']);

        $expiring = UserPackage::with('user', 'package')
            ->where('status', 'active')
            ->whereBetween('expired_at', [now(), now()->addDays(7)])
            ->orderBy('expired_at')
            ->take(5)
            ->get();

        return [
            'orders' => $pendingOrders,
            'orderCount' => (int) Order::where('status', 'pending')->count(),
            'bookings' => $todayBookings,
            'bookingCount' => (int) ClassBooking::where('status', 'confirmed')
                ->whereDate('booking_date', today())
                ->count(),
            'messages' => $openMessages,
            'messageCount' => (int) Consultation::whereIn('consultation_status', ['Pending', 'Confirmed'])->count(),
            'expiring' => $expiring,
            'expiringCount' => (int) UserPackage::where('status', 'active')
                ->whereBetween('expired_at', [now(), now()->addDays(7)])
                ->count(),
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

    /**
     * Daftar course dari API Art of Living (hanya dipakai tampilan member).
     */
    protected function artOfLivingCourses(): array
    {
        $key = 'dashboard.artofliving.courses';

        if (Cache::has($key)) {
            return Cache::get($key, []);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0',
                ])
                ->get('https://unity.artofliving.org/csapi/courses', [
                    'type' => 'country',
                    'limit' => 100,
                    'distance' => 50,
                    'language' => 'id-id',
                    'country' => 'id',
                    'order_by' => 'start_date',
                ]);

            if ($response->successful()) {
                $courses = $response->json('courses', []);
                Cache::put($key, $courses, now()->addHours(6));
                Log::info('Art of Living Courses synced', ['total' => count($courses)]);

                return $courses;
            }

            Log::warning('Art of Living API Error', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            Log::error('Art of Living API Exception', ['message' => $e->getMessage()]);
        }

        Cache::put($key, [], now()->addMinutes(5));

        return [];
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
