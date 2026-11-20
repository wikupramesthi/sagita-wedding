{{-- Dashboard admin --}}
<div class="dashboard-home">

    @php
        $localNow = now('Asia/Jakarta');
        $hour = $localNow->hour;
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
    @endphp

    <section class="dashboard-welcome" aria-labelledby="dashboard-welcome-title">
        <div class="dashboard-welcome__copy">
            <p class="dashboard-eyebrow">{{ $greeting }}, <strong>{{ auth()->user()->name }}</strong></p>
            <h1 id="dashboard-welcome-title">Selamat datang di dashboard {{ $brandName }}</h1>
            <p class="dashboard-welcome__subtitle">Pantau operasional, verifikasi pembayaran, dan kelola konten dari satu tempat.</p>
            <span class="dashboard-date"><i class="bi bi-calendar3" aria-hidden="true"></i> {{ $localNow->translatedFormat('l, j F Y') }}</span>
        </div>
        <div class="dashboard-welcome__decoration" aria-hidden="true">
            <span class="dashboard-welcome__orbit dashboard-welcome__orbit--one"></span>
            <span class="dashboard-welcome__orbit dashboard-welcome__orbit--two"></span>
            <i class="bi bi-stars"></i>
        </div>
    </section>

    <section class="card" aria-labelledby="quickActionsTitle">
        <div class="card-body d-flex flex-column gap-3">
            <div>
                <h2 id="quickActionsTitle" class="h6 mb-1">Aksi cepat</h2>
                <p class="small text-muted mb-0">Pintasan ke tugas yang paling sering dilakukan.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-plus me-1"></i> Tambah artikel</a>
                <a href="{{ route('banner.create') }}" class="btn btn-sm btn-info"><i class="bi bi-image me-1"></i> Upload banner</a>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-success"><i class="bi bi-calendar-plus me-1"></i> Buat event</a>
                <a href="{{ route('class-bookings.scan') }}" class="btn btn-sm btn-warning"><i class="bi bi-qr-code-scan me-1"></i> Scan QR</a>
                @if ($canViewConsultations)
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-sm btn-primary"><i class="bi bi-diagram-3 me-1"></i> Konsultasi</a>
                @endif
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-dark">
                    <i class="bi bi-bell me-1"></i> Notifikasi
                    <span class="badge bg-light text-dark rounded-pill ms-1" data-notification-badge {{ $unreadCount > 0 ? '' : 'hidden' }}>{{ $unreadCount }}</span>
                </a>
            </div>
        </div>
    </section>

    @php
        $actionTotal = $actionQueue['orderCount'] + $actionQueue['messageCount'];
    @endphp

        <section id="dashboardActionQueue" aria-labelledby="actionQueueTitle" {{ $actionTotal > 0 ? '' : 'hidden' }}>
            <div class="dashboard-analytics-heading">
                <h2 id="actionQueueTitle">Perlu tindakan</h2>
                <p>Ada <span data-action-total>{{ $actionTotal }}</span> item yang menunggu tindak lanjut Anda.</p>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-hourglass-split me-2"></i> Pembayaran perlu verifikasi</h3>
                            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="small text-decoration-none fw-semibold"><span data-action-orders>{{ $actionQueue['orderCount'] }}</span> pesanan</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['orders'] as $order)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $order->order_number }}</div>
                                        <div class="small text-muted text-truncate">{{ $order->user->name ?? '-' }} · Rp {{ number_format($order->amount, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="{{ $order->proof_image_path ? route('orders.proof.show', $order->uuid) : route('orders.show', $order->uuid) }}" class="btn btn-sm btn-info" title="Lihat bukti pembayaran">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('orders.approve', $order->uuid) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui pembayaran" onclick="return confirm('Setujui pembayaran ini?')">Setujui</button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-check2-circle fs-3 d-block mb-2"></i>
                                    <p class="mb-0 small">Semua pembayaran sudah diverifikasi.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if ($canViewConsultations)
                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-clock-history me-2"></i> Follow-up konsultasi terlewat</h3>
                            <a href="{{ route('konsultasi.index', ['follow_up' => 'overdue']) }}" class="small text-decoration-none fw-semibold"><span data-action-messages>{{ $actionQueue['messageCount'] }}</span> lead</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['messages'] as $message)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $message->full_name }} <span class="text-muted fw-normal">· {{ $message->email ?: $message->whatsapp }}</span></div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="badge {{ $message->lead_status_badge_class }}">{{ $message->lead_status_label }}</span>
                                            <span class="text-truncate">Jatuh tempo {{ optional($message->follow_up_at)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('konsultasi.show', $message->uuid) }}" class="btn btn-sm btn-info"><i class="bi bi-arrow-right"></i></a>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-check2-circle fs-3 d-block mb-2"></i>
                                    <p class="mb-0 small">Semua tindak lanjut sudah terkini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </section>

    <details class="dashboard-details-fold" open>
        <summary class="dashboard-details-summary">
            <span class="min-w-0">
                <strong>Ringkasan aktivitas</strong>
                <small class="text-muted d-block text-truncate" data-period-label>Statistik, grafik, dan funnel mengikuti rentang {{ $periodLabel }}.</small>
            </span>
            <span class="dashboard-details-toggle"><i class="bi bi-chevron-up"></i> Lipat</span>
        </summary>

        <div class="dashboard-details d-grid gap-4">
        <div class="card dashboard-date-filter">
            <div class="card-body">
                <form action="{{ route('dashboard.index') }}" method="GET" class="row g-2 align-items-end" id="dashboardDateForm">
                    <div class="col-md-auto col-12">
                        <label class="form-label small fw-semibold mb-1" for="dashboardStartDate">Dari tanggal</label>
                        <input id="dashboardStartDate" type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-auto col-12">
                        <label class="form-label small fw-semibold mb-1" for="dashboardEndDate">Sampai tanggal</label>
                        <input id="dashboardEndDate" type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-auto col-12">
                        <span class="form-label small fw-semibold mb-1 d-block">Preset cepat</span>
                        <div class="d-flex flex-wrap gap-1" role="group" aria-label="Preset periode">
                            @foreach ([7, 30, 90] as $days)
                                @php
                                    $presetStart = now()->subDays($days - 1)->toDateString();
                                    $presetEnd = now()->toDateString();
                                    $presetActive = request('start_date') === $presetStart && request('end_date') === $presetEnd;
                                @endphp
                                <a href="{{ route('dashboard.index', ['start_date' => $presetStart, 'end_date' => $presetEnd]) }}" class="btn btn-sm {{ $presetActive ? 'btn-success' : 'btn-outline-success' }} dashboard-period-preset">{{ $days }} hari</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-auto col-12 d-flex flex-wrap gap-2 align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        <a href="{{ route('dashboard.export', request()->query()) }}" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                        <a href="{{ route('dashboard.export-pdf', request()->query()) }}" class="btn btn-sm btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                        <button type="button" id="dashboardRefreshBtn" class="btn btn-sm btn-warning"><i class="bi bi-arrow-clockwise me-1"></i> Segarkan</button>
                    </div>
                    <div class="col-12 d-flex align-items-center gap-2">
                        <span class="dashboard-updated" id="dashboardUpdatedAt"><i class="bi bi-clock-history me-1"></i> Data diperbarui {{ now()->format('H.i') }}</span>
                        <span class="small text-muted">Auto-refresh tiap 2 menit saat halaman aktif.</span>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 dashboard-stat-grid">
            @foreach ($stats as $stat)
                @php
                    $spark = $stat['spark'];
                    $sparkMax = !empty($spark) ? max($spark) : 0;
                    $sparkPoints = collect($spark)
                        ->map(fn ($value, $index) => (count($spark) > 1 ? ($index / (count($spark) - 1)) * 60 : 0) . ',' . (18 - ($sparkMax > 0 ? ($value / $sparkMax) * 16 : 0)))
                        ->implode(' ');
                @endphp
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card h-100 dashboard-stat-card" data-tone="{{ $stat['class'] }}" data-stat-label="{{ $stat['label'] }}">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stats-icon {{ $stat['class'] }}"><i class="{{ $stat['icon'] }}"></i></div>
                            <div class="min-w-0 flex-grow-1">
                                <h6 class="text-muted font-semibold mb-0 text-truncate">{{ $stat['label'] }}</h6>
                                <a href="{{ $stat['route'] }}" class="text-decoration-none">
                                    <h4 class="font-extrabold mb-0" data-stat-value>{{ number_format($stat['value'], 0, ',', '.') }}</h4>
                                </a>
                                @if ($stat['change'] !== null)
                                    <small class="dashboard-stat-trend d-inline-flex align-items-center gap-1 {{ $stat['change'] >= 0 ? 'text-success' : 'text-danger' }}" data-stat-change>
                                        <i class="bi {{ $stat['change'] >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                                        {{ abs($stat['change']) }}%
                                    </small>
                                @endif
                            </div>
                            @if (!empty($spark))
                                <svg viewBox="0 0 60 20" width="52" height="18" aria-hidden="true" class="flex-shrink-0 dashboard-stat-spark">
                                    <polyline points="{{ $sparkPoints }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary opacity-75" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($canViewConsultations)
        <div class="row g-4">
            <div class="col-12">
                <div class="card dashboard-consultation-card">
                    <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title h6 mb-0"><i class="bi bi-diagram-3 me-2"></i> Pipeline konsultasi</h5>
                            <small class="text-muted">Distribusi lead saat ini dan kapasitas WIP</small>
                        </div>
                        <a href="{{ route('konsultasi.kanban') }}" class="small text-decoration-none fw-semibold">Buka Kanban <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($consultationPipeline as $stage)
                                <div class="col-6 col-md-4 col-xl-2">
                                    <a href="{{ $stage['route'] }}" class="text-decoration-none">
                                        @php
                                            $wipPercent = $stage['limit'] > 0
                                                ? min(100, (int) round(($stage['count'] / $stage['limit']) * 100))
                                                : 0;
                                            $wipTone = $wipPercent >= 100 ? 'bg-danger' : ($wipPercent >= 80 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="dashboard-pipeline-tile h-100" data-stage="{{ $stage['key'] }}">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <span class="small fw-semibold text-muted">{{ $stage['label'] }}</span>
                                                <span class="badge {{ $stage['badge'] }}" data-stage-count>{{ $stage['count'] }}</span>
                                            </div>
                                            <div class="fw-extrabold h4 mb-0 mt-2" data-stage-value>{{ $stage['count'] }}</div>
                                            <div class="progress dashboard-wip-progress mt-2" style="height:5px" role="progressbar" aria-label="Kapasitas {{ $stage['label'] }}" aria-valuenow="{{ $wipPercent }}" aria-valuemin="0" aria-valuemax="100" data-wip-progress>
                                                <div class="progress-bar {{ $wipTone }}" style="width: {{ $wipPercent }}%" data-wip-bar></div>
                                            </div>
                                            <small class="text-muted" data-wip-label>{{ $stage['count'] }}/{{ $stage['limit'] }} WIP</small>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                            <span class="badge bg-light-success text-success"><i class="bi bi-graph-up-arrow me-1"></i> Konversi <span data-highlight="conversion_rate">{{ $consultationHighlights['conversion_rate'] }}</span>%</span>
                            <a href="{{ route('konsultasi.index', ['sla' => 'breach']) }}" class="badge bg-light-danger text-danger text-decoration-none"><i class="bi bi-alarm me-1"></i> SLA terlewat <span data-highlight="sla_breach">{{ $consultationHighlights['sla_breach'] }}</span></a>
                            <a href="{{ route('konsultasi.index', ['sla' => 'warning']) }}" class="badge bg-light-warning text-warning text-decoration-none"><i class="bi bi-stopwatch me-1"></i> SLA mendekat <span data-highlight="sla_warning">{{ $consultationHighlights['sla_warning'] }}</span></a>
                            <a href="{{ route('konsultasi.index', ['follow_up' => 'done']) }}" class="badge bg-light-primary text-primary text-decoration-none"><i class="bi bi-check2-circle me-1"></i> Terkonversi <span data-highlight="converted">{{ $consultationHighlights['converted'] }}</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon yellow"><i class="bi bi-bag-plus"></i></div>
                        <div class="min-w-0">
                            <h6 class="text-muted font-semibold mb-0">Pesanan baru</h6>
                            <a href="{{ route('orders.index') }}" class="text-decoration-none"><h4 class="font-extrabold mb-0" data-metric="newOrdersCount">{{ number_format($newOrdersCount, 0, ',', '.') }}</h4></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon green"><i class="bi bi-person-plus"></i></div>
                        <div class="min-w-0">
                            <h6 class="text-muted font-semibold mb-0">Keanggotaan baru</h6>
                            <a href="{{ route('memberships.index') }}" class="text-decoration-none"><h4 class="font-extrabold mb-0" data-metric="newMembershipCount">{{ number_format($newMembershipCount, 0, ',', '.') }}</h4></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon blue"><i class="bi bi-cash-stack"></i></div>
                        <div class="min-w-0">
                            <h6 class="text-muted font-semibold mb-0">Pendapatan</h6>
                            <h4 class="font-extrabold mb-0 text-success">Rp <span data-metric="revenueMonth">{{ number_format($revenueMonth, 0, ',', '.') }}</span></h4>
                            <small class="text-muted">Total keseluruhan: Rp <span data-metric="revenueTotal">{{ number_format($revenueTotal, 0, ',', '.') }}</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-0">Grafik pendapatan</h5>
                            <small class="text-muted" data-period-label>{{ $periodLabel }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="small text-muted mb-0" for="revenueRange">Tampilkan</label>
                            <select id="revenueRange" class="form-select form-select-sm" style="width:auto">
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="position:relative;height:300px;">
                            <canvas id="revenueChart" role="img" aria-label="Grafik pendapatan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent"><h5 class="card-title h6 mb-0">Funnel konversi</h5></div>
                    <div class="card-body">
                        @php $funnelMax = max(1, ...array_column($funnel, 'value')); @endphp
                        @foreach ($funnel as $step)
                            <div class="mb-3" data-funnel="{{ $step['label'] }}">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold">{{ $step['label'] }}</span>
                                    <span data-funnel-value>{{ number_format($step['value'], 0, ',', '.') }}</span>
                                </div>
                                <div class="progress" style="height:8px" role="progressbar" aria-label="{{ $step['label'] }}" aria-valuenow="{{ $step['value'] }}" aria-valuemin="0" aria-valuemax="{{ $funnelMax }}" data-funnel-progress>
                                    <div class="progress-bar" style="width: {{ ($step['value'] / $funnelMax) * 100 }}%" data-funnel-bar></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent"><h5 class="card-title h6 mb-0">Keamanan (24 jam terakhir)</h5></div>
                    <div class="card-body">
                        <a href="{{ route('security.failed-login.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none mb-3">
                            <span class="small"><i class="bi bi-shield-exclamation me-2"></i> Login gagal</span>
                            <span class="badge {{ $security['failedLogins'] > 0 ? 'bg-light-warning' : 'bg-light-success' }}" data-security="failedLogins">{{ $security['failedLogins'] }}</span>
                        </a>
                        <a href="{{ route('security.login-lockout.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none">
                            <span class="small"><i class="bi bi-lock me-2"></i> Akun terkunci</span>
                            <span class="badge {{ $security['lockouts'] > 0 ? 'bg-light-danger' : 'bg-light-success' }}" data-security="lockouts">{{ $security['lockouts'] }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <section class="card site-analytics-card" aria-labelledby="siteAnalyticsHeading">
                    <div class="site-analytics-tabs" role="tablist" aria-label="Statistik website">
                        <button class="site-analytics-tab active" id="analyticsVisitorsTab" type="button" role="tab"
                            aria-selected="true" aria-controls="siteAnalyticsPanel" data-analytics-tab="visitors">Pengunjung</button>
                        <button class="site-analytics-tab" id="analyticsContentTab" type="button" role="tab"
                            aria-selected="false" aria-controls="siteAnalyticsPanel" tabindex="-1" data-analytics-tab="content">Konten</button>
                        <button class="site-analytics-tab" id="analyticsDevicesTab" type="button" role="tab"
                            aria-selected="false" aria-controls="siteAnalyticsPanel" tabindex="-1" data-analytics-tab="devices">Perangkat</button>
                    </div>

                    <div class="card-body site-analytics-card__body" id="siteAnalyticsPanel" role="tabpanel"
                        aria-labelledby="analyticsVisitorsTab" tabindex="0">
                        <div class="site-analytics-heading">
                            <div>
                                <h5 id="siteAnalyticsHeading">Statistik pengunjung</h5>
                                <p id="siteAnalyticsPeriod" data-period-label>{{ $periodLabel }}</p>
                            </div>
                            <div class="site-analytics-range" id="visitorRangeControls" aria-label="Rentang statistik pengunjung">
                                <button type="button" class="btn btn-sm active" data-visitor-range="daily" aria-pressed="true"><i class="bi bi-calendar-date" aria-hidden="true"></i> Harian</button>
                                <button type="button" class="btn btn-sm" data-visitor-range="monthly" aria-pressed="false"><i class="bi bi-calendar-month" aria-hidden="true"></i> Bulanan</button>
                            </div>
                        </div>

                        <div class="site-analytics-chart">
                            <canvas id="siteAnalyticsChart" role="img" aria-label="Grafik statistik pengunjung website"></canvas>
                            <p class="site-analytics-empty" id="siteAnalyticsEmpty" role="status" hidden>Belum ada data untuk kategori ini pada rentang tanggal ini.</p>
                        </div>

                        <div class="site-analytics-active" id="activeVisitorsSummary">
                            <span class="site-analytics-active__indicator" aria-hidden="true"></span>
                            <div>
                                <strong>Pengunjung aktif sekarang</strong>
                                <span>Unik dalam 5 menit terakhir</span>
                            </div>
                            <div class="site-analytics-active__count">
                                <strong id="activeVisitorsCount">{{ $site['activeVisitors'] }}</strong>
                                <span>Diperbarui {{ $site['activeVisitorsUpdatedAt'] }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-{{ $canViewConsultations ? '7' : '12' }}">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title h6 mb-0">Pesanan terbaru</h5>
                        <a href="{{ route('orders.index') }}" class="small text-decoration-none text-primary fw-semibold">Lihat semua</a>
                    </div>
                    <div class="card-body table-responsive">
                        @if ($recentOrders->count())
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr><th>No. pesanan</th><th>Member</th><th>Total</th><th>Status</th></tr>
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
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada pesanan</p>
                                <p class="small mb-0">Pesanan baru akan muncul di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($canViewConsultations)
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title h6 mb-0">Calon Konsumen Baru</h5>
                        <a href="{{ route('konsultasi.index') }}" class="small text-decoration-none text-primary fw-semibold">Lihat semua</a>
                    </div>
                    <div class="card-body">
                        @forelse ($recentConsultations as $consultation)
                            <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                                <div class="avatar avatar-md2 dashboard-avatar">
                                    <div class="avatar-content"><i class="bi bi-person-check"></i></div>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <a href="{{ route('konsultasi.show', $consultation->uuid) }}" class="fw-semibold small text-truncate text-decoration-none d-block">{{ $consultation->full_name }}</a>
                                    <div class="small text-muted text-truncate">{{ trim(($consultation->email ?: '') . ' · ' . ($consultation->whatsapp ?: ''), ' · ') }} · {{ optional($consultation->created_at)->translatedFormat('d M Y') }}</div>
                                </div>
                                <span class="badge {{ $consultation->lead_status_badge_class }}">{{ $consultation->lead_status_label }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada calon konsumen</p>
                                <p class="small mb-0">Lead konsultasi baru akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title h6 mb-0">Jadwal hari ini</h5>
                        <a href="{{ route('class-bookings.index', ['status' => 'confirmed', 'date' => now()->toDateString()]) }}" class="small text-decoration-none text-primary fw-semibold">Check-in booking</a>
                    </div>
                    <div class="card-body table-responsive">
                        @if ($todaySchedules->count())
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr><th>Kelas</th><th>Waktu</th><th>Vendor</th><th>Level</th><th>Peserta</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($todaySchedules as $schedule)
                                        <tr>
                                            <td class="fw-semibold">{{ $schedule->class->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                            <td>{{ $schedule->class->vendor->name ?? '-' }}</td>
                                            <td><span class="badge bg-light-secondary">{{ $schedule->class->level ?? '-' }}</span></td>
                                            <td>
                                                @if (($schedule->bookings_count ?? 0) >= ($schedule->capacity ?? PHP_INT_MAX))
                                                    <span class="badge bg-light-danger">Penuh</span>
                                                @else
                                                    <span class="badge bg-light-success">{{ $schedule->bookings_count ?? 0 }} / {{ $schedule->capacity ?? '∞' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('class-bookings.index', ['status' => 'confirmed', 'date' => now()->toDateString()]) }}" class="btn btn-sm btn-outline-primary" title="Check-in peserta">
                                                    <i class="bi bi-qr-code-scan"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Tidak ada kelas hari ini</p>
                                <p class="small mb-0"><a href="{{ route('class-schedules.create') }}">Buat jadwal baru</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent"><h5 class="card-title h6 mb-0">Acara mendatang</h5></div>
                    <div class="card-body">
                        @forelse ($events as $event)
                            <div class="d-flex gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                                <div class="text-center" style="min-width:52px;">
                                    <div class="fw-extrabold fs-4 lh-1">{{ optional($event->tanggal)->format('d') }}</div>
                                    <div class="small text-muted text-uppercase">{{ optional($event->tanggal)->translatedFormat('M') }}</div>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $event->judul ?? $event->title ?? '-' }}</div>
                                    <div class="small text-muted">{{ $event->waktu_mulai ?? '' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada acara</p>
                                <p class="small mb-0"><a href="{{ route('events.create') }}">Buat acara baru</a></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </details>
</div>

@push('after-style')
<style>
    #main-content .dashboard-home {
        --dashboard-gap: 1rem;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: var(--dashboard-gap);
    }

    #main-content .dashboard-home > * {
        min-width: 0;
    }

    #main-content .dashboard-details > .row > [class*="col-"] > .card {
        margin-bottom: 0;
    }

    #main-content .dashboard-home .row.g-4,
    #main-content .dashboard-home .row.g-3 {
        --bs-gutter-x: var(--dashboard-gap);
        --bs-gutter-y: var(--dashboard-gap);
    }

    #main-content .dashboard-pipeline-tile {
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 12px;
        padding: .75rem;
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, .02));
        transition: border-color .15s ease, transform .15s ease;
    }

    #main-content .dashboard-pipeline-tile:hover {
        border-color: #6c5a4a;
        transform: translateY(-1px);
    }

    #main-content .dashboard-consultation-card .badge {
        font-size: .75rem;
        font-weight: 600;
    }

    #main-content .dashboard-wip-progress {
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, .06));
        border-radius: 999px;
        overflow: hidden;
    }

    #main-content .dashboard-wip-progress .progress-bar {
        border-radius: 999px;
    }

    #main-content .dashboard-avatar .avatar-content {
        background: #2f7d4f;
        color: #fff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .16);
    }

    #main-content .dashboard-avatar .avatar-content i {
        color: #fff;
        font-size: 1rem;
        height: auto;
    }

    #main-content .dashboard-stat-card {
        border-top: 3px solid transparent;
        border-radius: 12px;
        overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    #main-content .dashboard-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .65rem 1.5rem rgba(15, 23, 42, .08);
    }

    #main-content .dashboard-stat-card[data-tone="purple"] { border-top-color: #7357d8; }
    #main-content .dashboard-stat-card[data-tone="blue"] { border-top-color: #3b82f6; }
    #main-content .dashboard-stat-card[data-tone="green"] { border-top-color: #2f7d4f; }
    #main-content .dashboard-stat-card[data-tone="cyan"] { border-top-color: #06b6d4; }
    #main-content .dashboard-stat-card[data-tone="red"] { border-top-color: #ef4444; }
    #main-content .dashboard-stat-card[data-tone="yellow"] { border-top-color: #f59e0b; }
    #main-content .dashboard-stat-card[data-tone="pink"] { border-top-color: #ec4899; }

    #main-content .dashboard-stat-card .stats-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        min-height: 34px;
        border-radius: 10px;
        font-size: .85rem;
    }

    #main-content .dashboard-stat-card h6 {
        font-size: .72rem;
    }

    #main-content .dashboard-stat-card h4 {
        font-size: 1.3rem;
        line-height: 1.15;
    }

    #main-content .dashboard-stat-trend {
        margin-top: .2rem;
        border-radius: 999px;
        padding: .05rem .4rem;
        font-size: .68rem;
        font-weight: 600;
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, .04));
    }

    @media (min-width: 1200px) {
        #main-content .dashboard-stat-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: var(--dashboard-gap);
            margin: 0;
        }

        #main-content .dashboard-stat-grid > div {
            width: auto;
            padding: 0;
        }

        #main-content .dashboard-stat-grid .card-body {
            padding: .75rem .85rem;
        }

        #main-content .dashboard-stat-grid .stats-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            min-height: 36px;
        }
    }

    #main-content .dashboard-details-fold {
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 14px;
        background: var(--bs-body-bg, #fff);
        padding: 0;
    }

    #main-content .dashboard-details-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .85rem 1rem;
        cursor: pointer;
        list-style: none;
        border-radius: 14px;
    }

    #main-content .dashboard-details-summary::-webkit-details-marker {
        display: none;
    }

    #main-content .dashboard-details-toggle {
        flex-shrink: 0;
        font-size: .78rem;
        font-weight: 600;
        color: #6c5a4a;
    }

    #main-content .dashboard-details-fold:not([open]) .dashboard-details-toggle i {
        transform: rotate(180deg);
        display: inline-block;
    }

    #main-content .dashboard-details {
        padding: 0 1rem 1rem;
    }

    @media (min-width: 992px) {
        #main-content .dashboard-date-filter {
            position: sticky;
            top: 4.75rem;
            z-index: 20;
            box-shadow: 0 .5rem 1.25rem rgba(15, 23, 42, .06);
        }
    }

    #main-content .dashboard-updated {
        display: inline-flex;
        align-items: center;
        font-size: .75rem;
        font-weight: 600;
        color: #2f7d4f;
    }

    [data-bs-theme="dark"] #main-content .dashboard-pipeline-tile {
        background: rgba(255, 255, 255, .03);
        border-color: rgba(255, 255, 255, .12);
    }

    [data-bs-theme="dark"] #main-content .dashboard-details-fold {
        border-color: rgba(255, 255, 255, .12);
    }

    [data-bs-theme="dark"] #main-content .dashboard-details-toggle {
        color: #d8c3ae;
    }

    [data-bs-theme="dark"] #main-content .dashboard-updated {
        color: #6ee7a8;
    }

    [data-bs-theme="dark"] #main-content .dashboard-stat-trend {
        background: rgba(255, 255, 255, .06);
    }

    #main-content .dashboard-date-filter .btn {
        white-space: nowrap;
    }

    @media (max-width: 575.98px) {
        #main-content .dashboard-home {
            --dashboard-gap: .75rem;
            gap: var(--dashboard-gap);
        }

        #main-content .dashboard-welcome {
            padding: 1rem;
        }

        #main-content .dashboard-welcome__decoration {
            display: none;
        }

        #main-content .dashboard-welcome h1 {
            font-size: 1.15rem;
        }

        #main-content .dashboard-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: var(--dashboard-gap);
            margin: 0;
        }

        #main-content .dashboard-stat-grid > div {
            width: auto;
            padding: 0;
        }

        #main-content .dashboard-stat-card .card-body {
            padding: .65rem .7rem;
            gap: .55rem;
        }

        #main-content .dashboard-stat-card .stats-icon {
            width: 30px;
            height: 30px;
            min-width: 30px;
            min-height: 30px;
            font-size: .75rem;
            border-radius: 8px;
        }

        #main-content .dashboard-stat-card h6 {
            font-size: .65rem;
        }

        #main-content .dashboard-stat-card h4 {
            font-size: 1.05rem;
        }

        #main-content .dashboard-stat-spark {
            display: none;
        }

        #main-content .dashboard-date-filter .card-body {
            padding: .75rem;
        }

        #main-content .dashboard-date-filter .btn {
            flex: 1 1 auto;
        }

        #main-content .dashboard-details {
            padding: 0 .75rem .75rem;
        }

        #main-content .dashboard-details-summary {
            padding: .7rem .75rem;
        }

        #main-content .dashboard-analytics-heading h2 {
            font-size: 1.05rem;
        }

        #main-content .dashboard-consultation-card .card-body {
            padding: .75rem;
        }

        #main-content .dashboard-pipeline-tile {
            padding: .6rem;
        }

        #main-content .dashboard-pipeline-tile .h4 {
            font-size: 1.15rem;
        }

        #main-content .dashboard-pipeline-tile small {
            font-size: .65rem;
        }
    }
</style>
@endpush

@push('after-script')
<script src="{{ asset('dist/assets/extensions/chart.js/chart.umd.js') }}"></script>
<script>
(function () {
    const detailsFold = document.querySelector('.dashboard-details-fold');
    if (detailsFold && window.matchMedia('(max-width: 575.98px)').matches) {
        detailsFold.open = false;
    }

    let revenue = {
        daily: { labels: @json($revenueChart['daily']['labels']), data: @json($revenueChart['daily']['data']) },
        monthly: { labels: @json($revenueChart['monthly']['labels']), data: @json($revenueChart['monthly']['data']) },
        yearly: { labels: @json($revenueChart['yearly']['labels']), data: @json($revenueChart['yearly']['data']) },
    };

    const rangeSelect = document.getElementById('revenueRange');
    const revenueCanvas = document.getElementById('revenueChart');
    let revenueChart;

    function updateRevenueSeries() {
        if (!revenueChart) return;
        const series = revenue[rangeSelect && rangeSelect.value] || revenue.daily;
        revenueChart.data.labels = series.labels;
        revenueChart.data.datasets[0].data = series.data;
        revenueChart.update();
    }

    function initRevenueChart() {
        if (!revenueCanvas || typeof Chart === 'undefined' || revenueChart) return;

        revenueChart = new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: revenue.daily.labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenue.daily.data,
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
                onClick: (event, elements) => {
                    if (!elements.length) return;
                    const params = new URLSearchParams(window.location.search);
                    params.set('status', 'paid');
                    window.location.href = '{{ route('orders.index') }}?' + params.toString();
                },
                scales: {
                    y: { ticks: { callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID') } },
                },
            },
        });

        if (rangeSelect) {
            rangeSelect.addEventListener('change', updateRevenueSeries);
        }
    }

    const analyticsCanvas = document.getElementById('siteAnalyticsChart');
    const analyticsTabs = Array.from(document.querySelectorAll('[data-analytics-tab]'));
    const analyticsRangeButtons = Array.from(document.querySelectorAll('[data-visitor-range]'));
    const analyticsHeading = document.getElementById('siteAnalyticsHeading');
    const analyticsPeriod = document.getElementById('siteAnalyticsPeriod');
    const visitorRangeControls = document.getElementById('visitorRangeControls');
    const activeVisitorsSummary = document.getElementById('activeVisitorsSummary');
    const analyticsEmptyState = document.getElementById('siteAnalyticsEmpty');
    const periodLabel = @json($periodLabel);
    let dailyVisitorData = {
        labels: @json($site['visitorDailyLabels']),
        total: @json($site['visitorDailyTotal']),
        unique: @json($site['visitorDailyUnique']),
    };
    let monthlyVisitorData = {
        labels: @json($site['visitorMonthLabels']),
        total: @json($site['visitorMonthTotal']),
        unique: @json($site['visitorMonthUnique']),
    };
    let contentData = {
        labels: @json($site['analyticsContentLabels']),
        values: @json($site['analyticsContentCounts']),
    };
    let deviceData = {
        labels: @json($site['analyticsDeviceLabels']),
        values: @json($site['analyticsDeviceCounts']),
    };
    let activeAnalyticsTab = 'visitors';
    let activeVisitorRange = 'daily';
    let analyticsChart;
    let chartsReady = false;

    function renderAnalyticsChart() {
        if (!chartsReady || !analyticsCanvas || typeof Chart === 'undefined') return;
        if (analyticsChart) analyticsChart.destroy();

        let config;
        const hasData = activeAnalyticsTab === 'visitors'
            || (activeAnalyticsTab === 'content' && contentData.values.some((value) => Number(value) > 0))
            || (activeAnalyticsTab === 'devices' && deviceData.values.some((value) => Number(value) > 0));

        analyticsCanvas.hidden = !hasData;
        analyticsEmptyState.hidden = hasData;
        if (!hasData) return;

        if (activeAnalyticsTab === 'visitors') {
            const dataset = activeVisitorRange === 'monthly' ? monthlyVisitorData : dailyVisitorData;
            config = {
                type: 'line',
                data: {
                    labels: dataset.labels,
                    datasets: [
                        {
                            label: 'Total kunjungan',
                            data: dataset.total,
                            borderColor: '#5e72e4',
                            backgroundColor: 'rgba(94, 114, 228, 0.08)',
                            fill: false,
                            tension: 0.25,
                        },
                        {
                            label: 'Pengunjung unik',
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
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
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
        const copy = {
            visitors: { title: 'Statistik pengunjung', period: periodLabel, showVisitors: true },
            content: { title: 'Konten terpopuler', period: 'Halaman paling banyak dikunjungi pada rentang ini', showVisitors: false },
            devices: { title: 'Perangkat pengunjung', period: 'Jenis perangkat pada rentang ini', showVisitors: false },
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

        analyticsHeading.textContent = copy.title;
        analyticsPeriod.textContent = copy.period;
        visitorRangeControls.hidden = !copy.showVisitors;
        activeVisitorsSummary.hidden = !copy.showVisitors;
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
                ? 'Bulanan dalam rentang ini'
                : periodLabel;
            renderAnalyticsChart();
        });
    });

    function ensureCharts() {
        if (chartsReady) return;
        chartsReady = true;
        initRevenueChart();
        renderAnalyticsChart();
    }

    if (detailsFold && !detailsFold.open) {
        detailsFold.addEventListener('toggle', () => {
            if (detailsFold.open) ensureCharts();
        });
    } else {
        ensureCharts();
    }

    const refreshButton = document.getElementById('dashboardRefreshBtn');
    const updatedAt = document.getElementById('dashboardUpdatedAt');
    let refreshing = false;

    function formatCount(value) {
        return Number(value || 0).toLocaleString('id-ID');
    }

    function applySummary(payload) {
        document.querySelectorAll('[data-stat-label]').forEach((card) => {
            const stat = (payload.stats || []).find((item) => item.label === card.dataset.statLabel);
            if (!stat) return;

            const value = card.querySelector('[data-stat-value]');
            if (value) value.textContent = formatCount(stat.value);

            const change = card.querySelector('[data-stat-change]');
            if (change && stat.change !== null) {
                change.textContent = (stat.change >= 0 ? '+' : '') + Math.abs(stat.change) + '%';
                change.classList.toggle('text-success', stat.change >= 0);
                change.classList.toggle('text-danger', stat.change < 0);
                const icon = change.querySelector('i');
                if (icon) icon.className = 'bi ' + (stat.change >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right');
            }
        });

        document.querySelectorAll('[data-stage]').forEach((tile) => {
            const stage = (payload.consultationPipeline || []).find((item) => item.key === tile.dataset.stage);
            if (!stage) return;

            tile.querySelectorAll('[data-stage-count], [data-stage-value]').forEach((node) => {
                node.textContent = formatCount(stage.count);
            });

            const percent = stage.limit > 0 ? Math.min(100, Math.round((stage.count / stage.limit) * 100)) : 0;
            const tone = percent >= 100 ? 'bg-danger' : (percent >= 80 ? 'bg-warning' : 'bg-success');
            const bar = tile.querySelector('[data-wip-bar]');
            if (bar) {
                bar.className = 'progress-bar ' + tone;
                bar.style.width = percent + '%';
            }
            const progress = tile.querySelector('[data-wip-progress]');
            if (progress) progress.setAttribute('aria-valuenow', String(percent));
            const label = tile.querySelector('[data-wip-label]');
            if (label) label.textContent = formatCount(stage.count) + '/' + formatCount(stage.limit) + ' WIP';
        });

        document.querySelectorAll('[data-period-label]').forEach((node) => {
            if (payload.periodLabel) node.textContent = payload.periodLabel;
        });

        document.querySelectorAll('[data-metric]').forEach((node) => {
            const value = (payload.metrics || {})[node.dataset.metric];
            if (value !== undefined) node.textContent = formatCount(value);
        });

        const actionCounts = payload.actionCounts || {};
        const orderCount = document.querySelector('[data-action-orders]');
        if (orderCount && actionCounts.orders !== undefined) orderCount.textContent = formatCount(actionCounts.orders);
        const messageCount = document.querySelector('[data-action-messages]');
        if (messageCount && actionCounts.messages !== undefined) messageCount.textContent = formatCount(actionCounts.messages);

        const actionTotal = document.querySelector('[data-action-total]');
        if (actionTotal) actionTotal.textContent = formatCount(payload.actionTotal);
        const actionSection = document.getElementById('dashboardActionQueue');
        if (actionSection) actionSection.hidden = Number(payload.actionTotal || 0) === 0;

        document.querySelectorAll('[data-highlight]').forEach((node) => {
            const value = (payload.consultationHighlights || {})[node.dataset.highlight];
            if (value !== undefined) node.textContent = value;
        });

        const funnel = payload.funnel || [];
        const funnelMax = Math.max(1, ...funnel.map((item) => Number(item.value || 0)));
        document.querySelectorAll('[data-funnel]').forEach((row) => {
            const step = funnel.find((item) => item.label === row.dataset.funnel);
            if (!step) return;

            const value = row.querySelector('[data-funnel-value]');
            if (value) value.textContent = formatCount(step.value);
            const progress = row.querySelector('[data-funnel-progress]');
            if (progress) progress.setAttribute('aria-valuemax', String(funnelMax));
            const bar = row.querySelector('[data-funnel-bar]');
            if (bar) bar.style.width = ((Number(step.value || 0) / funnelMax) * 100) + '%';
        });

        Object.entries(payload.security || {}).forEach(([key, value]) => {
            const node = document.querySelector('[data-security="' + key + '"]');
            if (!node) return;
            node.textContent = formatCount(value);
            node.classList.remove('bg-light-warning', 'bg-light-danger', 'bg-light-success');
            const positiveClass = key === 'lockouts' ? 'bg-light-danger' : 'bg-light-warning';
            node.classList.add(Number(value || 0) > 0 ? positiveClass : 'bg-light-success');
        });

        const unread = Number(payload.unreadCount || 0);
        const badge = document.querySelector('[data-notification-badge]');
        if (badge) {
            badge.textContent = formatCount(unread);
            badge.hidden = unread === 0;
        }
        const globalBadge = document.getElementById('notif-count');
        if (globalBadge) {
            if (unread > 0) globalBadge.textContent = formatCount(unread);
            else globalBadge.remove();
        } else if (unread > 0) {
            const bell = document.getElementById('notifDropdown');
            if (bell) {
                const globalBadgeNode = document.createElement('span');
                globalBadgeNode.className = 'badge badge-notification bg-danger';
                globalBadgeNode.id = 'notif-count';
                globalBadgeNode.textContent = formatCount(unread);
                bell.appendChild(globalBadgeNode);
            }
        }

        if (payload.revenueChart) {
            revenue = payload.revenueChart;
            if (revenueChart) updateRevenueSeries();
        }

        if (payload.site) {
            dailyVisitorData = {
                labels: payload.site.visitorDailyLabels,
                total: payload.site.visitorDailyTotal,
                unique: payload.site.visitorDailyUnique,
            };
            monthlyVisitorData = {
                labels: payload.site.visitorMonthLabels,
                total: payload.site.visitorMonthTotal,
                unique: payload.site.visitorMonthUnique,
            };
            contentData = {
                labels: payload.site.analyticsContentLabels,
                values: payload.site.analyticsContentCounts,
            };
            deviceData = {
                labels: payload.site.analyticsDeviceLabels,
                values: payload.site.analyticsDeviceCounts,
            };
            if (chartsReady) renderAnalyticsChart();
        }

        if (updatedAt) {
            const time = new Date(payload.updatedAt).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(':', '.');
            updatedAt.innerHTML = '<i class="bi bi-clock-history me-1"></i> Data diperbarui ' + time;
        }
    }

    async function refreshDashboard(silent = false) {
        if (refreshing) return;
        refreshing = true;

        const originalHtml = refreshButton ? refreshButton.innerHTML : null;
        if (refreshButton && !silent) {
            refreshButton.disabled = true;
            refreshButton.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Memuat...';
        }

        try {
            const url = new URL('{{ route('dashboard.refresh') }}' + window.location.search, window.location.origin);
            if (!silent) url.searchParams.set('fresh', '1');
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!response.ok) throw new Error('Gagal memuat data dashboard');
            const payload = await response.json();
            if (!payload.ok) throw new Error('Respons dashboard tidak valid');
            applySummary(payload);
        } catch (error) {
            console.warn(error);
        } finally {
            refreshing = false;
            if (refreshButton && !silent) {
                refreshButton.disabled = false;
                refreshButton.innerHTML = originalHtml;
            }
        }
    }

    if (refreshButton) {
        refreshButton.addEventListener('click', () => refreshDashboard(false));
    }

    window.setInterval(() => {
        if (!document.hidden) refreshDashboard(true);
    }, 120000);
})();
</script>
@endpush
