<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment\Order;
use App\Models\UserPackage;
use App\Models\Article;
use App\Models\Event;
use App\Models\Class\ClassModel;
use App\Models\Package\Package;
use App\Models\User;
use App\Models\Faq;
use App\Models\Kontak;
use App\Models\Poll;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\Class\ClassBooking;
use App\Models\Class\ClassSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $rules = [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rules['end_date'][] = 'after_or_equal:start_date';
        }
        $request->validate($rules);

        $user = Auth::user();
        $isMember = $user->hasRole('user');

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $applyPeriod = function ($query, string $column = 'created_at') use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate($column, '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate($column, '<=', $endDate);
            }
            return $query;
        };

        // =========================
        // DASHBOARD STATISTICS
        // =========================

        $jumlahInstruktur = $applyPeriod(User::role('instruktur'))->count();
        $jumlahMembers = $applyPeriod(User::role('user'))->count();

        $totalClasses = $applyPeriod(ClassModel::query())->count();
        $totalEvents = $applyPeriod(Event::query())->count();

        $data = [
            'user' => $user,
            'jumlahInstruktur' => $jumlahInstruktur,
            'jumlahMembers' => $jumlahMembers,
            'totalClasses' => $totalClasses,
            'totalEvents' => $totalEvents,
            'unreadCount' => $user->unreadNotifications()->count(),
        ];

        // =========================
        // ADMIN-ONLY STATISTICS
        // =========================
        // Member (role "user") tidak memakai data ini, jadi jangan dirender
        // untuk role lain agar halaman member tidak menambah query sia-sia.

        if (! $isMember) {
            $totalPackages = $applyPeriod(Package::query())->count();
            $totalArticles = $applyPeriod(Article::query())->count();
            $totalFaq = $applyPeriod(Faq::query())->count();
            $totalPolling = $applyPeriod(Poll::query())->count();
            $totalPesan = $applyPeriod(Kontak::query())->count();
            $totalTestimonial = $applyPeriod(Testimonial::query())->count();

            // =========================
            // ORDERS & MEMBERSHIP & REVENUE
            // =========================

            $newOrdersCount = $applyPeriod(Order::where('status', 'pending'))->count();
            $newMembershipCount = $applyPeriod(UserPackage::where('status', 'active'), 'started_at')->count();

            $revenuePaid = $applyPeriod(Order::where('status', 'paid'), 'paid_at');
            $revenueMonth = (clone $revenuePaid)->sum('amount');
            $revenueTotal = Order::where('status', 'paid')->sum('amount');

            $orderPaidByDay = Order::where('status', 'paid')
                ->whereNotNull('paid_at')
                ->selectRaw('DATE(paid_at) as day_date, SUM(amount) as total')
                ->groupBy('day_date')
                ->orderBy('day_date')
                ->pluck('total', 'day_date');

            $days = collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->format('Y-m-d'));
            $revenueDailyLabels = $days->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->all();
            $revenueDaily = $days->map(fn ($d) => (float) ($orderPaidByDay[$d] ?? 0))->all();

            $monthStart = now()->startOfMonth();
            $monthDays = collect(range(0, now()->daysInMonth - 1))->map(fn ($i) => $monthStart->copy()->addDays($i)->format('Y-m-d'));
            $revenueMonthLabels = $monthDays->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d'))->all();
            $revenueMonthDaily = $monthDays->map(fn ($d) => (float) ($orderPaidByDay[$d] ?? 0))->all();

            $orderPaidByMonth = Order::where('status', 'paid')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', now()->year)
                ->selectRaw('MONTH(paid_at) as m, SUM(amount) as total')
                ->groupBy('m')
                ->pluck('total', 'm');
            $revenueYearLabels = [];
            $revenueYear = [];
            for ($m = 1; $m <= 12; $m++) {
                $revenueYearLabels[] = now()->startOfYear()->addMonths($m - 1)->format('M');
                $revenueYear[] = (float) ($orderPaidByMonth[$m] ?? 0);
            }

            $revenueAllDaily = $orderPaidByDay->mapWithKeys(fn ($total, $day) => [$day => (float) $total]);

            $recentOrders = $applyPeriod(Order::with('user', 'package')->latest())
                ->take(5)
                ->get();

            $recentMemberships = $applyPeriod(UserPackage::with('user', 'package')->latest(), 'started_at')
                ->take(5)
                ->get();

            $data += [
                'totalPackages' => $totalPackages,
                'totalArticles' => $totalArticles,
                'totalFaq' => $totalFaq,
                'totalPolling' => $totalPolling,
                'totalPesan' => $totalPesan,
                'totalTestimonial' => $totalTestimonial,

                'newOrdersCount' => $newOrdersCount,
                'newMembershipCount' => $newMembershipCount,
                'revenueMonth' => $revenueMonth,
                'revenueTotal' => $revenueTotal,
                'recentOrders' => $recentOrders,
                'recentMemberships' => $recentMemberships,

                'revenueDailyLabels' => $revenueDailyLabels,
                'revenueDaily' => $revenueDaily,
                'revenueMonthLabels' => $revenueMonthLabels,
                'revenueMonthDaily' => $revenueMonthDaily,
                'revenueYearLabels' => $revenueYearLabels,
                'revenueYear' => $revenueYear,
                'revenueAllDaily' => $revenueAllDaily,
            ] + $this->siteAnalytics();
        }

        // =========================
        // ACTIVE MEMBERSHIP
        // =========================

        $activePackage = $user->userPackages()
            ->with('package')
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
            })
            ->latest('started_at')
            ->first();

        $data['activePackage'] = $activePackage;

        $attendedCount = ClassBooking::where('user_uuid', $user->uuid)
            ->where('status', 'attended')
            ->count();
        $data['attendedCount'] = $attendedCount;

        // =========================
        // ACTIVE BANNER
        // =========================

        $banner = Banner::where('status', 'active')
            ->where('posisi', 'slider')
            ->first();


        // =========================
        // UPCOMING EVENTS
        // =========================

        $events = Event::where('status', 'published')
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->take(5)
            ->get();

        // =========================
        // DAY CONFIGURATION
        // =========================

        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        $today = strtolower(now()->format('l'));
        $currentDayIndex = array_search($today, $days);
        $nextDay = $days[($currentDayIndex + 1) % count($days)];

        // =========================
        // TODAY'S SCHEDULE
        // =========================

        $todaySchedules = ClassSchedule::with([
            'class.instructor'
        ])
            ->withCount([
                'bookings as bookings_count' => function ($q) {
                    $q->whereIn('status', ['confirmed', 'attended']);
                },
            ])
            ->where('status', 'active')
            ->where('day', $today)
            ->orderBy('start_time')
            ->take(5)
            ->get();


        // =========================
        // NEXT DAY SCHEDULE
        // =========================

        $upcomingClasses = ClassSchedule::with([
            'class.instructor'
        ])
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


        // =========================
        // ART OF LIVING COURSES API
        // =========================

        $courses = $this->artOfLivingCourses();

        $data += [
            'banner' => $banner,
            'events' => $events,
            'todaySchedules' => $todaySchedules,
            'upcomingClasses' => $upcomingClasses,
            'courses' => $courses,
        ];

        // =========================
        // MEMBER: laptop/desktop = view desktop, HP = phone-frame mobile.
        // Bisa dioverride manual via session `member_view`.
        // =========================

        if ($isMember && \App\Support\MemberView::isMobile()) {

            $todayDate = now()->format('Y-m-d');
            $tomorrowDate = now()->addDay()->format('Y-m-d');
            $scheduleUuids = $todaySchedules->pluck('uuid')->merge($upcomingClasses->pluck('uuid'));

            $myBookings = ClassBooking::where('user_uuid', $user->uuid)
                ->whereIn('class_schedule_uuid', $scheduleUuids)
                ->where('status', '!=', 'cancelled')
                ->get()
                ->keyBy(fn ($b) => $b->class_schedule_uuid . '|' . $b->booking_date?->format('Y-m-d'));

            return view('pages.mobile.home', $data + [
                'courses' => array_slice($courses, 0, 6),
                'myBookings' => $myBookings,
                'todayDate' => $todayDate,
                'tomorrowDate' => $tomorrowDate,
            ]);
        }


        // =========================
        // DESKTOP
        // =========================

        return view('pages.dashboard.index', $data);
    }

    /**
     * Anonymous public-site traffic aggregates for the admin dashboard.
     */
    private function siteAnalytics(): array
    {
        $today = now()->startOfDay();
        $dailyStart = $today->copy()->subDays(30);
        $monthlyStart = now()->startOfMonth()->subMonths(11);

        $dailyRows = DB::table('site_visits')
            ->where('visited_at', '>=', $dailyStart)
            ->selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as unique_visitors')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');

        $monthExpression = match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', visited_at)",
            'pgsql' => "TO_CHAR(visited_at, 'YYYY-MM')",
            default => "DATE_FORMAT(visited_at, '%Y-%m')",
        };
        $monthlyRows = DB::table('site_visits')
            ->where('visited_at', '>=', $monthlyStart)
            ->selectRaw("$monthExpression as visit_month, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as unique_visitors")
            ->groupBy('visit_month')
            ->orderBy('visit_month')
            ->get()
            ->keyBy('visit_month');

        $visitorDailyLabels = [];
        $visitorDailyTotal = [];
        $visitorDailyUnique = [];
        for ($date = $dailyStart->copy(); $date <= $today; $date->addDay()) {
            $key = $date->format('Y-m-d');
            $row = $dailyRows->get($key);
            $visitorDailyLabels[] = $date->translatedFormat('d M');
            $visitorDailyTotal[] = (int) ($row->total ?? 0);
            $visitorDailyUnique[] = (int) ($row->unique_visitors ?? 0);
        }

        $visitorMonthLabels = [];
        $visitorMonthTotal = [];
        $visitorMonthUnique = [];
        for ($month = $monthlyStart->copy(); $month <= now()->startOfMonth(); $month->addMonth()) {
            $key = $month->format('Y-m');
            $row = $monthlyRows->get($key);
            $visitorMonthLabels[] = $month->translatedFormat('M Y');
            $visitorMonthTotal[] = (int) ($row->total ?? 0);
            $visitorMonthUnique[] = (int) ($row->unique_visitors ?? 0);
        }

        $contentRows = DB::table('site_visits')
            ->where('visited_at', '>=', $dailyStart)
            ->select('path')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $deviceRows = DB::table('site_visits')
            ->where('visited_at', '>=', $dailyStart)
            ->select('device')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('device')
            ->get()
            ->keyBy('device');

        return [
            'visitorDailyLabels' => $visitorDailyLabels,
            'visitorDailyTotal' => $visitorDailyTotal,
            'visitorDailyUnique' => $visitorDailyUnique,
            'visitorMonthLabels' => $visitorMonthLabels,
            'visitorMonthTotal' => $visitorMonthTotal,
            'visitorMonthUnique' => $visitorMonthUnique,
            'visitorDateRangeLabel' => $dailyStart->translatedFormat('d M Y') . ' - ' . now()->translatedFormat('d M Y') . ' · 31 hari',
            'activeVisitors' => DB::table('site_visits')
                ->where('visited_at', '>=', now()->subMinutes(5))
                ->distinct()
                ->count('visitor_hash'),
            'activeVisitorsUpdatedAt' => now()->format('H.i'),
            'analyticsContentLabels' => $contentRows->pluck('path')->all(),
            'analyticsContentCounts' => $contentRows->pluck('total')->map(fn ($total) => (int) $total)->all(),
            'analyticsDeviceLabels' => ['Desktop', 'Ponsel', 'Tablet'],
            'analyticsDeviceCounts' => [
                (int) ($deviceRows->get('desktop')->total ?? 0),
                (int) ($deviceRows->get('mobile')->total ?? 0),
                (int) ($deviceRows->get('tablet')->total ?? 0),
            ],
        ];
    }

    /**
     * Daftar course dari API Art of Living.
     *
     * Dipakai di dashboard (member & admin), jadi hasilnya di-cache agar
     * tidak memanggil API pihak ketiga setiap kali halaman dibuka.
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

                Log::info('Art of Living Courses synced', [
                    'total' => count($courses),
                ]);

                return $courses;
            }

            Log::warning('Art of Living API Error', [
                'status' => $response->status(),
            ]);
        } catch (\Throwable $e) {

            Log::error('Art of Living API Exception', [
                'message' => $e->getMessage(),
            ]);
        }

        // API sedang bermasalah: cache singkat supaya tidak mencoba terus
        // di setiap request, lalu coba lagi beberapa menit kemudian.
        Cache::put($key, [], now()->addMinutes(5));

        return [];
    }

    public function submitSumber(Request $request)
    {
        $request->validate([
            'no_hp' => [
                'required',
                'regex:/^[0-9]{8,15}$/',
                'unique:users,no_hp,' . Auth::user()->uuid . ',uuid',
            ],
            'sumber_informasi' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'no_hp.required' => 'Phone number is required.',
            'no_hp.regex'    => 'Invalid phone number format.',
            'no_hp.unique'   => 'This phone number is already in use.',

            'sumber_informasi.required' => 'Please select how you heard about us.',
            'sumber_informasi.string'   => 'Invalid information source.',
            'sumber_informasi.max'      => 'Information source is too long.',
        ]);

        $user = Auth::user();

        $no_hp = trim($request->no_hp);

        $user->update([
            'no_hp'             => $no_hp,
            'sumber_informasi'  => $request->sumber_informasi,
        ]);

        Auth::setUser($user->fresh());

        return redirect()
            ->route('dashboard.index')
            ->with(
                'success',
                __('flash.sumber_ok')
            );
    }
}
