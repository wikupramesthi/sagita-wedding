{{-- Admin dashboard (dilibatkan oleh pages.dashboard.index saat bukan role user) --}}
<div class="dashboard-home d-grid gap-4">

    @php
        $localNow = now('Asia/Jakarta');
        $hour = $localNow->hour;
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
    @endphp

    <section class="dashboard-welcome" aria-labelledby="dashboard-welcome-title">
        <div class="dashboard-welcome__copy">
            <p class="dashboard-eyebrow">{{ $greeting }}, <strong>{{ auth()->user()->name }}</strong></p>
            <h1 id="dashboard-welcome-title">Selamat datang di dashboard Sagita Wedding</h1>
            <p class="dashboard-welcome__subtitle">Kelola layanan dan temukan modul yang Anda butuhkan dengan cepat.</p>
            <span class="dashboard-date"><i class="bi bi-calendar3" aria-hidden="true"></i> {{ $localNow->translatedFormat('l, j F Y') }}</span>
        </div>
        <div class="dashboard-welcome__decoration" aria-hidden="true">
            <span class="dashboard-welcome__orbit dashboard-welcome__orbit--one"></span>
            <span class="dashboard-welcome__orbit dashboard-welcome__orbit--two"></span>
            <i class="bi bi-stars"></i>
        </div>
    </section>

    <div class="dashboard-details d-grid gap-4">
    <section class="dashboard-analytics-heading">
        <h2>Ringkasan aktivitas</h2>
        <p>Pantau performa dan aktivitas terbaru dari dashboard.</p>
    </section>

    <div class="card dashboard-date-filter">
        <div class="card-body">
            <form action="{{ route('dashboard.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-auto col-12">
                    <label class="form-label small fw-semibold mb-1" for="dashboardStartDate">Dari tanggal</label>
                    <input id="dashboardStartDate" type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-auto col-12">
                    <label class="form-label small fw-semibold mb-1" for="dashboardEndDate">Sampai tanggal</label>
                    <input id="dashboardEndDate" type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-auto col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- STAT CARDS --}}
    @php
        $stats = [
            ['label' => 'Members', 'value' => $jumlahMembers, 'icon' => 'bi bi-people', 'class' => 'purple', 'route' => route('pengguna.index')],
            ['label' => 'Instructors', 'value' => $jumlahInstruktur, 'icon' => 'bi bi-person-badge', 'class' => 'blue', 'route' => route('instruktur.index')],
            ['label' => 'Packages', 'value' => $totalPackages, 'icon' => 'bi bi-box-seam', 'class' => 'green', 'route' => route('packages.index')],
            ['label' => 'Classes', 'value' => $totalClasses, 'icon' => 'bi bi-book', 'class' => 'red', 'route' => route('classes.index')],
            ['label' => 'Events', 'value' => $totalEvents, 'icon' => 'bi bi-calendar-event', 'class' => 'cyan', 'route' => route('events.index')],
            ['label' => 'Articles', 'value' => $totalArticles, 'icon' => 'bi bi-file-earmark-text', 'class' => 'yellow', 'route' => route('articles.index')],
            ['label' => 'FAQ', 'value' => $totalFaq, 'icon' => 'bi bi-question-circle', 'class' => 'pink', 'route' => route('faq.index')],
            ['label' => 'Incoming Messages', 'value' => $totalPesan, 'icon' => 'bi bi-envelope', 'class' => 'turquoise', 'route' => route('layanan.kontak')],
        ];
    @endphp

    <div class="row g-4">
        @foreach ($stats as $s)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon {{ $s['class'] }}">
                            <i class="{{ $s['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <h6 class="text-muted font-semibold mb-0 text-truncate">{{ $s['label'] }}</h6>
                            <a href="{{ $s['route'] }}" class="text-decoration-none">
                                <h4 class="font-extrabold mb-0">{{ $s['value'] }}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ORDER, MEMBERSHIP & REVENUE --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon yellow"><i class="bi bi-bag-plus"></i></div>
                    <div class="min-w-0">
                        <h6 class="text-muted font-semibold mb-0">New Orders (Pending)</h6>
                        <a href="{{ route('orders.index') }}" class="text-decoration-none">
                            <h4 class="font-extrabold mb-0">{{ $newOrdersCount }}</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon green"><i class="bi bi-person-plus"></i></div>
                    <div class="min-w-0">
                        <h6 class="text-muted font-semibold mb-0">New Memberships</h6>
                        <a href="{{ route('memberships.index') }}" class="text-decoration-none">
                            <h4 class="font-extrabold mb-0">{{ $newMembershipCount }}</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon blue"><i class="bi bi-cash-stack"></i></div>
                    <div class="min-w-0">
                        <h6 class="text-muted font-semibold mb-0">Revenue</h6>
                        <h4 class="font-extrabold mb-0 text-success">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</h4>
                        <small class="text-muted">Total: Rp {{ number_format($revenueTotal, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- REVENUE CHART --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Revenue</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <input type="date" id="revenueFrom" class="form-control form-control-sm" style="width:auto" value="{{ now()->subDays(6)->format('Y-m-d') }}">
                        <input type="date" id="revenueTo" class="form-control form-control-sm" style="width:auto" value="{{ now()->format('Y-m-d') }}">
                        <select id="revenueChartRange" class="form-select form-select-sm" style="width:auto">
                            <option value="daily">Per Day</option>
                            <option value="monthly">This Month</option>
                            <option value="yearly">This Year</option>
                        </select>
                        <button id="revenueApply" class="btn btn-sm btn-primary" aria-label="Terapkan rentang tanggal" title="Terapkan rentang tanggal"><i class="bi bi-funnel"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik kunjungan website --}}
    <div class="row g-4">
        <div class="col-12">
            <section class="card site-analytics-card" aria-labelledby="siteAnalyticsHeading">
                <div class="site-analytics-tabs" role="tablist" aria-label="Statistik website">
                    <button class="site-analytics-tab active" id="analyticsVisitorsTab" type="button" role="tab"
                        aria-selected="true" aria-controls="siteAnalyticsPanel" data-analytics-tab="visitors">
                        Pengunjung
                    </button>
                    <button class="site-analytics-tab" id="analyticsContentTab" type="button" role="tab"
                        aria-selected="false" aria-controls="siteAnalyticsPanel" tabindex="-1" data-analytics-tab="content">
                        Konten
                    </button>
                    <button class="site-analytics-tab" id="analyticsDevicesTab" type="button" role="tab"
                        aria-selected="false" aria-controls="siteAnalyticsPanel" tabindex="-1" data-analytics-tab="devices">
                        Perangkat
                    </button>
                </div>

                <div class="card-body site-analytics-card__body" id="siteAnalyticsPanel" role="tabpanel"
                    aria-labelledby="analyticsVisitorsTab" tabindex="0">
                    <div class="site-analytics-heading">
                        <div>
                            <h5 id="siteAnalyticsHeading">Statistik Pengunjung</h5>
                            <p id="siteAnalyticsPeriod">{{ $visitorDateRangeLabel }}</p>
                        </div>
                        <div class="site-analytics-range" id="visitorRangeControls" aria-label="Rentang statistik pengunjung">
                            <button type="button" class="btn btn-sm active" data-visitor-range="daily" aria-pressed="true">
                                <i class="bi bi-calendar-date" aria-hidden="true"></i> Harian
                            </button>
                            <button type="button" class="btn btn-sm" data-visitor-range="monthly" aria-pressed="false">
                                <i class="bi bi-calendar-month" aria-hidden="true"></i> Bulanan
                            </button>
                        </div>
                    </div>

                    <div class="site-analytics-chart">
                        <canvas id="siteAnalyticsChart" role="img" aria-label="Grafik statistik pengunjung website"></canvas>
                        <p class="site-analytics-empty" id="siteAnalyticsEmpty" role="status" hidden>
                            Belum ada data untuk kategori ini.
                        </p>
                    </div>

                    <div class="site-analytics-active" id="activeVisitorsSummary">
                        <span class="site-analytics-active__indicator" aria-hidden="true"></span>
                        <div>
                            <strong>Pengunjung aktif sekarang</strong>
                            <span>Unik dalam 5 menit terakhir</span>
                        </div>
                        <div class="site-analytics-active__count">
                            <strong id="activeVisitorsCount">{{ $activeVisitors }}</strong>
                            <span>Diperbarui {{ $activeVisitorsUpdatedAt }}</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- ORDER & MEMBERSHIP TERBARU --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="{{ route('orders.index') }}" class="small text-decoration-none text-primary fw-semibold">View All</a>
                </div>
                <div class="card-body table-responsive">
                    @if ($recentOrders->count())
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Order No.</th>
                                    <th>Member</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $badge = match ($order->status) {
                                                    'paid' => 'bg-light-success',
                                                    'pending' => 'bg-light-warning',
                                                    default => 'bg-light-danger',
                                                };
                                            @endphp
                                            <span class="badge {{ $badge }}">{{ ucfirst($order->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-4">No orders yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Memberships</h5>
                    <a href="{{ route('memberships.index') }}" class="small text-decoration-none text-primary fw-semibold">View All</a>
                </div>
                <div class="card-body">
                    @forelse ($recentMemberships as $membership)
                        <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                            <div class="avatar avatar-md2">
                                <div class="avatar-content bg-light-success">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $membership->user->name ?? '-' }}</div>
                                <div class="small text-muted text-truncate">{{ $membership->package->name ?? '-' }} · {{ \Carbon\Carbon::parse($membership->started_at)->format('d M Y') }}</div>
                            </div>
                            <span class="badge {{ $membership->status === 'active' ? 'bg-light-success' : 'bg-light-secondary' }}">{{ ucfirst($membership->status) }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No memberships yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- JADWAL HARI INI & EVENT --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Today's Schedule</h5>
                    <a href="{{ route('class-schedules.index') }}" class="small text-decoration-none text-primary fw-semibold">View All</a>
                </div>
                <div class="card-body table-responsive">
                    @if ($todaySchedules->count())
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Time</th>
                                    <th>Instructor</th>
                                    <th>Level</th>
                                    <th>Attendees</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todaySchedules as $schedule)
                                    <tr>
                                        <td class="fw-semibold">{{ $schedule->class->name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                        <td>{{ $schedule->class->instructor->name ?? '-' }}</td>
                                        <td><span class="badge bg-light-secondary">{{ $schedule->class->level ?? '-' }}</span></td>
                                        <td>
                                            @if (($schedule->bookings_count ?? 0) >= ($schedule->capacity ?? PHP_INT_MAX))
                                                <span class="badge bg-light-danger">Full</span>
                                            @else
                                                <span class="badge bg-light-success">{{ $schedule->bookings_count ?? 0 }} / {{ $schedule->capacity ?? '∞' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                            No classes scheduled today.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @forelse ($events as $event)
                        <div class="d-flex gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                            <div class="text-center" style="min-width:52px;">
                                <div class="fw-extrabold fs-4 lh-1">{{ \Carbon\Carbon::parse($event->tanggal)->format('d') }}</div>
                                <div class="small text-muted text-uppercase">{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('M') }}</div>
                            </div>
                            <div class="min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $event->judul ?? $event->title ?? '-' }}</div>
                                <div class="small text-muted">{{ $event->waktu_mulai ?? '' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No upcoming events.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- KELAS BERIKUTNYA --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Upcoming Classes</h5>
                </div>
                <div class="card-body">
                    @forelse ($upcomingClasses as $kelas)
                        <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                            <div class="avatar avatar-md2">
                                <div class="avatar-content bg-light-primary">
                                    <i class="bi bi-activity"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $kelas->class->name ?? '-' }}</div>
                                <div class="small text-muted">{{ ucfirst($kelas->day) }} · {{ \Carbon\Carbon::parse($kelas->start_time)->format('H:i') }}</div>
                            </div>
                            <span class="badge bg-light-primary">{{ $kelas->class->level ?? '-' }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No upcoming classes.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
</div>

@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const dailyLabels = @json($revenueDailyLabels);
    const daily = @json($revenueDaily);
    const monthLabels = @json($revenueMonthLabels);
    const monthDaily = @json($revenueMonthDaily);
    const yearLabels = @json($revenueYearLabels);
    const year = @json($revenueYear);
    const allDaily = @json($revenueAllDaily);

    function dailyRangeSeries(from, to) {
        const keys = Object.keys(allDaily).sort().filter(d => d >= from && d <= to);
        if (keys.length === 0) return { labels: [], data: [] };
        const start = new Date(from);
        const end = new Date(to);
        const labels = [];
        const data = [];
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const key = d.toISOString().slice(0, 10);
            labels.push(d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
            data.push(Number(allDaily[key] || 0));
        }
        return { labels, data };
    }

    function currentSet() {
        const mode = document.getElementById('revenueChartRange').value;
        if (mode === 'monthly') return { labels: monthLabels, data: monthDaily };
        if (mode === 'yearly') return { labels: yearLabels, data: year };
        const from = document.getElementById('revenueFrom').value;
        const to = document.getElementById('revenueTo').value;
        if (from && to) return dailyRangeSeries(from, to);
        return { labels: dailyLabels, data: daily };
    }

    const ctx = document.getElementById('revenueChart');
    if (!ctx || typeof Chart === 'undefined') return;

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: daily,
                borderColor: '#2f7d4f',
                backgroundColor: 'rgba(47,125,79,0.12)',
                fill: true,
                tension: 0.3,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID'),
                    },
                },
            },
        },
    });

    function refresh() {
        const set = currentSet();
        chart.data.labels = set.labels;
        chart.data.datasets[0].data = set.data;
        chart.update();
    }

    document.getElementById('revenueChartRange').addEventListener('change', refresh);
    document.getElementById('revenueApply').addEventListener('click', refresh);

    const analyticsCanvas = document.getElementById('siteAnalyticsChart');
    const analyticsTabs = Array.from(document.querySelectorAll('[data-analytics-tab]'));
    const analyticsRangeButtons = Array.from(document.querySelectorAll('[data-visitor-range]'));
    const analyticsHeading = document.getElementById('siteAnalyticsHeading');
    const analyticsPeriod = document.getElementById('siteAnalyticsPeriod');
    const visitorRangeControls = document.getElementById('visitorRangeControls');
    const activeVisitorsSummary = document.getElementById('activeVisitorsSummary');
    const analyticsEmptyState = document.getElementById('siteAnalyticsEmpty');
    const dailyVisitorData = {
        labels: @json($visitorDailyLabels),
        total: @json($visitorDailyTotal),
        unique: @json($visitorDailyUnique),
    };
    const monthlyVisitorData = {
        labels: @json($visitorMonthLabels),
        total: @json($visitorMonthTotal),
        unique: @json($visitorMonthUnique),
    };
    const contentData = {
        labels: @json($analyticsContentLabels),
        values: @json($analyticsContentCounts),
    };
    const deviceData = {
        labels: @json($analyticsDeviceLabels),
        values: @json($analyticsDeviceCounts),
    };
    let activeAnalyticsTab = 'visitors';
    let activeVisitorRange = 'daily';
    let analyticsChart;

    function renderAnalyticsChart() {
        if (!analyticsCanvas) return;
        if (analyticsChart) analyticsChart.destroy();

        let config;
        const hasAnalyticsData = activeAnalyticsTab === 'visitors'
            || (activeAnalyticsTab === 'content' && contentData.values.some((value) => Number(value) > 0))
            || (activeAnalyticsTab === 'devices' && deviceData.values.some((value) => Number(value) > 0));
        analyticsCanvas.hidden = !hasAnalyticsData;
        analyticsEmptyState.hidden = hasAnalyticsData;
        if (!hasAnalyticsData) return;

        if (activeAnalyticsTab === 'visitors') {
            const dataset = activeVisitorRange === 'monthly' ? monthlyVisitorData : dailyVisitorData;
            config = {
                type: 'line',
                data: {
                    labels: dataset.labels,
                    datasets: [
                        {
                            label: 'Total Kunjungan',
                            data: dataset.total,
                            borderColor: '#5e72e4',
                            backgroundColor: 'rgba(94, 114, 228, 0.08)',
                            fill: false,
                            tension: 0.25,
                        },
                        {
                            label: 'Pengunjung Unik',
                            data: dataset.unique,
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32, 201, 151, 0.08)',
                            fill: false,
                            tension: 0.25,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                        },
                    },
                },
            };
        } else if (activeAnalyticsTab === 'content') {
            config = {
                type: 'bar',
                data: {
                    labels: contentData.labels,
                    datasets: [{
                        label: 'Kunjungan halaman',
                        data: contentData.values,
                        backgroundColor: 'rgba(94, 114, 228, 0.75)',
                        borderRadius: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            };
        } else {
            config = {
                type: 'doughnut',
                data: {
                    labels: deviceData.labels,
                    datasets: [{
                        data: deviceData.values,
                        backgroundColor: ['#5e72e4', '#20c997', '#f7b84b'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                },
            };
        }

        analyticsChart = new Chart(analyticsCanvas, config);
    }

    function updateAnalyticsTab(tab) {
        activeAnalyticsTab = tab;
        const tabCopy = {
            visitors: {
                title: 'Statistik Pengunjung',
                period: @json($visitorDateRangeLabel),
                showVisitors: true,
            },
            content: {
                title: 'Konten Terpopuler',
                period: 'Halaman paling banyak dikunjungi dalam 31 hari terakhir',
                showVisitors: false,
            },
            devices: {
                title: 'Perangkat Pengunjung',
                period: 'Jenis perangkat dalam 31 hari terakhir',
                showVisitors: false,
            },
        }[tab];

        analyticsTabs.forEach((button) => {
            const selected = button.dataset.analyticsTab === tab;
            button.classList.toggle('active', selected);
            button.setAttribute('aria-selected', String(selected));
            button.tabIndex = selected ? 0 : -1;
            if (selected) {
                document.getElementById('siteAnalyticsPanel').setAttribute('aria-labelledby', button.id);
            }
        });
        analyticsHeading.textContent = tabCopy.title;
        analyticsPeriod.textContent = tabCopy.period;
        visitorRangeControls.hidden = !tabCopy.showVisitors;
        activeVisitorsSummary.hidden = !tabCopy.showVisitors;
        renderAnalyticsChart();
    }

    analyticsTabs.forEach((button) => {
        button.addEventListener('click', () => updateAnalyticsTab(button.dataset.analyticsTab));
    });

    analyticsRangeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeVisitorRange = button.dataset.visitorRange;
            analyticsRangeButtons.forEach((rangeButton) => {
                const selected = rangeButton === button;
                rangeButton.classList.toggle('active', selected);
                rangeButton.setAttribute('aria-pressed', String(selected));
            });
            analyticsPeriod.textContent = activeVisitorRange === 'monthly'
                ? '12 bulan terakhir'
                : @json($visitorDateRangeLabel);
            renderAnalyticsChart();
        });
    });

    if (typeof Chart !== 'undefined') renderAnalyticsChart();
})();
</script>
@endpush

