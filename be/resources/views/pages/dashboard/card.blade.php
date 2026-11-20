{{-- Dashboard admin --}}
<div class="dashboard-home d-grid gap-4">

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
                <a href="{{ route('banner.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-image me-1"></i> Upload banner</a>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-calendar-plus me-1"></i> Buat event</a>
                <a href="{{ route('class-bookings.scan') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-qr-code-scan me-1"></i> Scan QR</a>
                <a href="{{ route('konsultasi.index') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-envelope me-1"></i> Pesan masuk</a>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-bell me-1"></i> Notifikasi
                    @if ($unreadCount > 0)
                        <span class="badge bg-primary rounded-pill ms-1">{{ $unreadCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </section>

    @php
        $actionTotal = $actionQueue['orderCount'] + $actionQueue['bookingCount'] + $actionQueue['messageCount'] + $actionQueue['expiringCount'];
    @endphp

    @if ($actionTotal > 0)
        <section aria-labelledby="actionQueueTitle">
            <div class="dashboard-analytics-heading">
                <h2 id="actionQueueTitle">Perlu tindakan</h2>
                <p>Ada {{ $actionTotal }} item yang menunggu tindak lanjut Anda.</p>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-hourglass-split me-2"></i> Pembayaran perlu verifikasi</h3>
                            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="small text-decoration-none fw-semibold">{{ $actionQueue['orderCount'] }} pesanan</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['orders'] as $order)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $order->order_number }}</div>
                                        <div class="small text-muted text-truncate">{{ $order->user->name ?? '-' }} · Rp {{ number_format($order->amount, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="{{ $order->proof_image_path ? route('orders.proof.show', $order->uuid) : route('orders.show', $order->uuid) }}" class="btn btn-sm btn-outline-secondary" title="Lihat bukti pembayaran">
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

                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-person-check me-2"></i> Check-in menunggu</h3>
                            <a href="{{ route('class-bookings.index', ['status' => 'confirmed', 'date' => now()->toDateString()]) }}" class="small text-decoration-none fw-semibold">{{ $actionQueue['bookingCount'] }} booking</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['bookings'] as $booking)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $booking->user->name ?? '-' }}</div>
                                        <div class="small text-muted text-truncate">{{ $booking->schedule->class->name ?? '-' }} · {{ optional($booking->booking_date)->format('d M') }}</div>
                                    </div>
                                    <form action="{{ route('class-bookings.checkin', $booking->uuid) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Check-in</button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-check2-circle fs-3 d-block mb-2"></i>
                                    <p class="mb-0 small">Tidak ada booking yang menunggu check-in hari ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-envelope me-2"></i> Pesan belum ditangani</h3>
                            <a href="{{ route('konsultasi.index') }}" class="small text-decoration-none fw-semibold">{{ $actionQueue['messageCount'] }} pesan</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['messages'] as $message)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $message->full_name }} <span class="text-muted fw-normal">· {{ $message->email ?: $message->whatsapp }}</span></div>
                                        <div class="small text-muted text-truncate">{{ \Illuminate\Support\Str::limit($message->wedding_concept, 80) }}</div>
                                    </div>
                                    <a href="{{ route('konsultasi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-right"></i></a>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-check2-circle fs-3 d-block mb-2"></i>
                                    <p class="mb-0 small">Semua pesan sudah ditangani.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="card-title h6 mb-0"><i class="bi bi-clock-history me-2"></i> Keanggotaan segera habis</h3>
                            <a href="{{ route('memberships.index') }}" class="small text-decoration-none fw-semibold">{{ $actionQueue['expiringCount'] }} member</a>
                        </div>
                        <div class="card-body">
                            @forelse ($actionQueue['expiring'] as $membership)
                                <div class="d-flex align-items-center gap-2 {{ $loop->last ? '' : 'border-bottom pb-2 mb-2' }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small text-truncate">{{ $membership->user->name ?? '-' }}</div>
                                        <div class="small text-muted text-truncate">{{ $membership->package->name ?? '-' }} · habis {{ optional($membership->expired_at)->translatedFormat('d M Y') }}</div>
                                    </div>
                                    <a href="{{ route('memberships.show', $membership->uuid) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-right"></i></a>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-check2-circle fs-3 d-block mb-2"></i>
                                    <p class="mb-0 small">Tidak ada keanggotaan yang habis dalam 7 hari ke depan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="dashboard-details d-grid gap-4">
        <section class="dashboard-analytics-heading">
            <h2>Ringkasan aktivitas</h2>
            <p>Statistik, grafik, dan funnel mengikuti rentang tanggal di bawah ({{ $periodLabel }}). Antrean tindakan dan daftar terbaru selalu menampilkan data terkini.</p>
        </section>

        <div class="card dashboard-date-filter">
            <div class="card-body">
                <form action="{{ route('dashboard.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-auto col-12">
                        <label class="form-label small fw-semibold mb-1" for="dashboardStartDate">Dari tanggal</label>
                        <input id="dashboardStartDate" type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-auto col-12">
                        <label class="form-label small fw-semibold mb-1" for="dashboardEndDate">Sampai tanggal</label>
                        <input id="dashboardEndDate" type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-auto col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($stats as $stat)
                @php
                    $spark = $stat['spark'];
                    $sparkMax = !empty($spark) ? max($spark) : 0;
                    $sparkPoints = collect($spark)
                        ->map(fn ($value, $index) => (count($spark) > 1 ? ($index / (count($spark) - 1)) * 60 : 0) . ',' . (18 - ($sparkMax > 0 ? ($value / $sparkMax) * 16 : 0)))
                        ->implode(' ');
                @endphp
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stats-icon {{ $stat['class'] }}"><i class="{{ $stat['icon'] }}"></i></div>
                            <div class="min-w-0 flex-grow-1">
                                <h6 class="text-muted font-semibold mb-0 text-truncate">{{ $stat['label'] }}</h6>
                                <a href="{{ $stat['route'] }}" class="text-decoration-none">
                                    <h4 class="font-extrabold mb-0">{{ $stat['value'] }}</h4>
                                </a>
                                @if ($stat['change'] !== null)
                                    <small class="{{ $stat['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="bi {{ $stat['change'] >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                                        {{ abs($stat['change']) }}% vs periode sebelumnya
                                    </small>
                                @endif
                            </div>
                            @if (!empty($spark))
                                <svg viewBox="0 0 60 20" width="60" height="20" aria-hidden="true" class="flex-shrink-0">
                                    <polyline points="{{ $sparkPoints }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary opacity-75" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon yellow"><i class="bi bi-bag-plus"></i></div>
                        <div class="min-w-0">
                            <h6 class="text-muted font-semibold mb-0">Pesanan baru</h6>
                            <a href="{{ route('orders.index') }}" class="text-decoration-none"><h4 class="font-extrabold mb-0">{{ $newOrdersCount }}</h4></a>
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
                            <a href="{{ route('memberships.index') }}" class="text-decoration-none"><h4 class="font-extrabold mb-0">{{ $newMembershipCount }}</h4></a>
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
                            <h4 class="font-extrabold mb-0 text-success">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</h4>
                            <small class="text-muted">Total keseluruhan: Rp {{ number_format($revenueTotal, 0, ',', '.') }}</small>
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
                            <small class="text-muted">{{ $periodLabel }}</small>
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
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold">{{ $step['label'] }}</span>
                                    <span>{{ $step['value'] }}</span>
                                </div>
                                <div class="progress" style="height:8px" role="progressbar" aria-label="{{ $step['label'] }}" aria-valuenow="{{ $step['value'] }}" aria-valuemin="0" aria-valuemax="{{ $funnelMax }}">
                                    <div class="progress-bar" style="width: {{ ($step['value'] / $funnelMax) * 100 }}%"></div>
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
                            <span class="badge {{ $security['failedLogins'] > 0 ? 'bg-light-warning' : 'bg-light-success' }}">{{ $security['failedLogins'] }}</span>
                        </a>
                        <a href="{{ route('security.login-lockout.index') }}" class="d-flex align-items-center justify-content-between text-decoration-none">
                            <span class="small"><i class="bi bi-lock me-2"></i> Akun terkunci</span>
                            <span class="badge {{ $security['lockouts'] > 0 ? 'bg-light-danger' : 'bg-light-success' }}">{{ $security['lockouts'] }}</span>
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
                                <p id="siteAnalyticsPeriod">{{ $periodLabel }}</p>
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
            <div class="col-lg-7">
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

            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title h6 mb-0">Keanggotaan terbaru</h5>
                        <a href="{{ route('memberships.index') }}" class="small text-decoration-none text-primary fw-semibold">Lihat semua</a>
                    </div>
                    <div class="card-body">
                        @forelse ($recentMemberships as $membership)
                            <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                                <div class="avatar avatar-md2">
                                    <div class="avatar-content bg-light-success"><i class="bi bi-person-badge"></i></div>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $membership->user->name ?? '-' }}</div>
                                    <div class="small text-muted text-truncate">{{ $membership->package->name ?? '-' }} · {{ optional($membership->started_at)->format('d M Y') }}</div>
                                </div>
                                <span class="badge {{ $membership->status === 'active' ? 'bg-light-success' : 'bg-light-secondary' }}">{{ ucfirst($membership->status) }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada keanggotaan</p>
                                <p class="small mb-0">Keanggotaan baru akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
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

        <div class="row g-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header bg-transparent"><h5 class="card-title h6 mb-0">Kelas mendatang</h5></div>
                    <div class="card-body">
                        @forelse ($upcomingClasses as $kelas)
                            <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                                <div class="avatar avatar-md2">
                                    <div class="avatar-content bg-light-primary"><i class="bi bi-activity"></i></div>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $kelas->class->name ?? '-' }}</div>
                                    <div class="small text-muted">{{ ucfirst($kelas->day) }} · {{ \Carbon\Carbon::parse($kelas->start_time)->format('H:i') }}</div>
                                </div>
                                <span class="badge bg-light-primary">{{ $kelas->class->level ?? '-' }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada kelas mendatang</p>
                                <p class="small mb-0"><a href="{{ route('class-schedules.create') }}">Buat jadwal baru</a></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script src="{{ asset('dist/assets/extensions/chart.js/chart.umd.js') }}"></script>
<script>
(function () {
    const revenue = {
        daily: { labels: @json($revenueChart['daily']['labels']), data: @json($revenueChart['daily']['data']) },
        monthly: { labels: @json($revenueChart['monthly']['labels']), data: @json($revenueChart['monthly']['data']) },
        yearly: { labels: @json($revenueChart['yearly']['labels']), data: @json($revenueChart['yearly']['data']) },
    };

    const rangeSelect = document.getElementById('revenueRange');
    const revenueCanvas = document.getElementById('revenueChart');
    let revenueChart;

    if (revenueCanvas && typeof Chart !== 'undefined') {
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
                scales: {
                    y: { ticks: { callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID') } },
                },
            },
        });

        rangeSelect.addEventListener('change', () => {
            const series = revenue[rangeSelect.value] || revenue.daily;
            revenueChart.data.labels = series.labels;
            revenueChart.data.datasets[0].data = series.data;
            revenueChart.update();
        });
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
    const dailyVisitorData = {
        labels: @json($site['visitorDailyLabels']),
        total: @json($site['visitorDailyTotal']),
        unique: @json($site['visitorDailyUnique']),
    };
    const monthlyVisitorData = {
        labels: @json($site['visitorMonthLabels']),
        total: @json($site['visitorMonthTotal']),
        unique: @json($site['visitorMonthUnique']),
    };
    const contentData = {
        labels: @json($site['analyticsContentLabels']),
        values: @json($site['analyticsContentCounts']),
    };
    const deviceData = {
        labels: @json($site['analyticsDeviceLabels']),
        values: @json($site['analyticsDeviceCounts']),
    };
    let activeAnalyticsTab = 'visitors';
    let activeVisitorRange = 'daily';
    let analyticsChart;

    function renderAnalyticsChart() {
        if (!analyticsCanvas || typeof Chart === 'undefined') return;
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

    renderAnalyticsChart();
})();
</script>
@endpush
