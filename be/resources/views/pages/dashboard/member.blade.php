{{-- Member dashboard (dilibatkan oleh pages.dashboard.index saat role user) --}}
<style>
    @media (min-width: 992px) {
        .member-dashboard {
            max-width: 1480px;
            margin: 0 auto;
            padding: 0 12px 32px;
        }

        .member-dashboard .member-hero {
            padding: 34px 38px;
            margin-bottom: 24px !important;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 22px;
            background: linear-gradient(120deg, #373477 0%, #5b52c8 55%, #7766df 100%);
            box-shadow: 0 18px 42px rgba(67, 56, 160, .18);
        }

        .member-dashboard .member-hero h1 {
            max-width: 760px;
            font-size: clamp(1.7rem, 2.2vw, 2.25rem);
            letter-spacing: -.035em;
        }

        .member-dashboard .member-hero p {
            max-width: 720px;
            font-size: .98rem;
            line-height: 1.65;
        }

        .member-dashboard .member-stat-card,
        .member-dashboard .member-section-card {
            border: 1px solid #e8eaf2 !important;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(30, 41, 59, .045) !important;
        }

        .member-dashboard .member-stat-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .member-dashboard .member-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(30, 41, 59, .08) !important;
        }

        .member-dashboard .member-stat-card .card-body {
            min-height: 104px;
            padding: 20px;
        }

        .member-dashboard .member-stats {
            margin-bottom: 24px !important;
        }

        .member-dashboard .member-dashboard-grid {
            align-items: flex-start;
        }

        .member-dashboard .member-section-card > .card-body {
            padding: 24px;
        }

        .member-dashboard .member-section-title {
            font-size: 1.05rem;
            letter-spacing: -.015em;
        }

        .member-dashboard .member-orders-table {
            min-width: 850px;
        }

        .member-dashboard .member-orders-table thead th {
            padding: 12px 14px;
            border-bottom: 1px solid #e8eaf2;
            color: #64748b;
            font-size: .69rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .member-dashboard .member-orders-table tbody td {
            padding: 15px 14px;
            border-color: #edf0f5;
            vertical-align: middle;
        }

        .member-dashboard .member-orders-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .member-dashboard .member-order-number {
            color: #29334a;
            font-variant-numeric: tabular-nums;
        }

        .member-dashboard .member-order-progress {
            width: 92px;
            height: 5px;
            border-radius: 999px;
        }

        .member-dashboard .member-actions-card {
            position: sticky;
            top: 24px;
        }

        .member-dashboard .member-actions-card .btn {
            padding: 11px 14px;
            text-align: left;
        }
    }

    .member-planning .planning-summary {
        background: linear-gradient(135deg, #f4f8f4, #fff);
        border: 1px solid #d8e6da;
        border-radius: 14px;
        padding: 20px;
        height: 100%;
    }

    .member-planning .planning-progress {
        height: 8px;
        border-radius: 999px;
    }

    .member-planning .planning-task {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f5;
    }

    .member-planning .planning-task:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .member-planning .planning-task:first-child {
        padding-top: 0;
    }

    .member-planning .planning-timeline {
        position: relative;
        padding-left: 22px;
    }

    .member-planning .planning-timeline::before {
        content: "";
        position: absolute;
        left: 6px;
        top: 7px;
        bottom: 8px;
        width: 2px;
        background: #e8e7f5;
    }

    .member-planning .planning-milestone {
        position: relative;
        padding: 0 0 18px 10px;
    }

    .member-planning .planning-milestone:last-child {
        padding-bottom: 0;
    }

    .member-planning .planning-milestone::before {
        content: "";
        position: absolute;
        left: -20px;
        top: 5px;
        width: 10px;
        height: 10px;
        border: 2px solid #4b6b52;
        border-radius: 50%;
        background: #fff;
    }

    .member-planning .planning-budget-grid .form-control {
        min-width: 130px;
    }

    .member-planning .planning-budget-summary {
        padding: 18px;
        border: 1px solid #d8e6da;
        border-radius: 14px;
        background: linear-gradient(135deg, #f4f8f4, #fff);
    }

    .member-planning .planning-budget-category {
        padding: 12px 0;
        border-bottom: 1px solid #edf0f5;
    }

    .member-planning .planning-budget-category:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .member-planning .planning-budget-category .input-group {
        max-width: 260px;
    }

    .hero-panel {
        background: var(--brand-gradient);
        border-radius: var(--bs-border-radius-xl);
        color: #fff;
        padding: 1.8rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-panel::after {
        content: "";
        position: absolute;
        right: -40px;
        top: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .hero-panel::before {
        content: "";
        position: absolute;
        right: 60px;
        bottom: -70px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
    }

    .membership-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
        background: linear-gradient(135deg,
                #ffffff 0%,
                #f4f8f4 100%);
        border: 1px solid #d8e6da;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.06);
        transition: all 0.3s ease;
    }

    .membership-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.12);
    }


    /* Header */

    .membership-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .membership-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #fff;
        font-size: 19px;
        background: linear-gradient(135deg,
                #4b6b52,
                #6b8f6f);
        box-shadow: 0 5px 12px rgba(79, 70, 229, 0.25);
    }

    .membership-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 50px;
        color: #15803d;
        background: #dcfce7;
        font-size: 12px;
        font-weight: 600;
    }


    /* Plan */

    .membership-plan {
        position: relative;
        overflow: hidden;
        padding: 16px;
        margin-bottom: 18px;
        border-radius: 15px;
        background: linear-gradient(135deg,
                #eef3ee,
                #f5f3ff);
    }

    .membership-plan-name {
        margin-top: 3px;
        color: #4b6b52;
        font-size: 20px;
        font-weight: 700;
    }

    .membership-stars {
        position: absolute;
        top: 12px;
        right: 16px;
        color: #6b8f6f;
        font-size: 42px;
        opacity: 0.12;
    }


    /* Credit */

    .membership-credit {
        margin-top: 5px;
    }

    .membership-progress {
        width: 100%;
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #e9e7ff;
    }

    .membership-progress-bar {
        width: 67%;
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg,
                #4b6b52,
                #8b5cf6);
    }


    /* Button */
    .membership-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 18px;
        padding: 11px 16px;
        border-radius: 12px;
        color: #fff;
        background: linear-gradient(135deg,
                #4b6b52,
                #6b8f6f);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .membership-icon i,
    .membership-button i {
        width: auto;
        height: auto;
    }

    .course-date-badge {
        width: 58px;
        height: 58px;
        border-radius: 14px;
        background: #eef6f1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .course-date-day {
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        color: var(--bs-success);
    }

    .course-date-month {
        font-size: 9px;
        text-transform: uppercase;
        color: var(--bs-secondary-color);
        margin-top: 4px;
    }

    .membership-button:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }
</style>

<div class="member-dashboard container-fluid pb-5">

    <!-- Hero -->
    <div class="hero-panel member-hero mb-4">
        <div class="row align-items-center position-relative">
            <div class="col-lg-9">
                <div class="badge bg-white bg-opacity-25 mb-2 px-3 py-2 rounded-pill">
                    {{ now()->translatedFormat('l, j F Y') }}
                </div>
                <h1 class="fw-bold text-white mb-1">Halo, {{ auth()->user()->name }}</h1>
                <p class="text-white-50 mb-0">
                    Kelola pesanan, pantau status pembayaran, dan temukan paket wedding terbaik untuk acara Anda.
                </p>
            </div>
            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0" style="position: relative;z-index: 1;">
                <a href="#package-recommendation" class="btn btn-light fw-bold px-4"><i
                        class="bi bi-search me-1"></i> Cari Paket Sesuai Budget</a>
            </div>
        </div>
    </div>

    @php
        $myOrderStats = $myOrderStats ?? ['total'=>0,'pending'=>0,'paid'=>0,'active_package'=>0];
        $myOrders = $myOrders ?? collect();
    @endphp

    <div class="row g-3 mb-4 member-stats">
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-receipt-cutoff"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['total'] }}</div><div class="small text-muted mt-1">Total Pesanan</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-hourglass-split"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['pending'] }}</div><div class="small text-muted mt-1">Menunggu Pembayaran</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-patch-check"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['paid'] }}</div><div class="small text-muted mt-1">Pesanan Lunas</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-box-seam"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['active_package'] }}</div><div class="small text-muted mt-1">Paket Aktif</div></div></div></div></div>
    </div>

    <section class="member-planning mb-4" aria-labelledby="wedding-preparation-title">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="small fw-semibold text-primary text-uppercase">Ringkasan akun dan pesanan</span>
                <h2 id="wedding-preparation-title" class="fw-bold mb-1">Ringkasan persiapan</h2>
                <p class="small text-muted mb-0">Tanggal, langkah persiapan, paket, dan pembayaran mengikuti pesanan Anda.</p>
            </div>
            <a href="#package-recommendation" class="btn btn-outline-primary d-none d-md-inline-flex">
                <i class="bi bi-search me-1"></i> Cari Sesuai Budget
            </a>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="planning-summary">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:44px;height:44px;font-size:1.2rem">
                            <i class="bi bi-calendar-heart"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Tanggal pernikahan</div>
                            <div class="fw-bold">{{ $planningEventDate ? $planningEventDate->translatedFormat('d F Y') : 'Belum ada tanggal pesanan' }}</div>
                        </div>
                    </div>
                    @if ($planningDaysRemaining !== null)
                        <div class="text-primary fw-bold fs-5 mb-1">
                            @if ($planningDaysRemaining > 0)
                                {{ number_format($planningDaysRemaining, 0, ',', '.') }} hari lagi
                            @elseif ($planningDaysRemaining === 0)
                                Hari ini
                            @else
                                Acara telah berlangsung
                            @endif
                        </div>
                    @else
                        <div class="small text-muted">Tanggal tampil otomatis dari pesanan paket aktif.</div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Langkah persiapan</span>
                        <span class="fw-semibold">{{ $completedPlanningSteps }}/{{ count($planningSteps) }} selesai</span>
                    </div>
                    <div class="progress planning-progress" role="progressbar" aria-label="Progress checklist" aria-valuenow="{{ $planningProgress }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: {{ $planningProgress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 member-section-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h3 class="fw-bold mb-1 member-section-title">Langkah persiapan</h3>
                                <div class="small text-muted">Status mengikuti paket, tanggal acara, dan pembayaran.</div>
                            </div>
                            <i class="bi bi-check2-square fs-4 text-primary"></i>
                        </div>
                        <div>
                            @foreach ($planningSteps as $step)
                                <div class="planning-task">
                                    <i class="bi {{ $step['done'] ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} mt-1" aria-hidden="true"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $step['label'] }}</div>
                                        <div class="small text-muted">{{ $step['description'] }}</div>
                                    </div>
                                    @if ($step['done'])
                                        <span class="badge bg-success-subtle text-success-emphasis">Selesai</span>
                                    @else
                                        <a href="{{ $step['url'] }}" class="btn btn-sm btn-outline-primary text-nowrap">{{ $step['action'] }}</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 member-section-card">
                    <div class="card-body">
                        <h3 class="fw-bold mb-1 member-section-title">Timeline persiapan</h3>
                        <p class="small text-muted mb-3">Tanggal tahap dihitung dari tanggal acara pada pesanan.</p>
                        <div class="planning-timeline">
                            @foreach ($planningTimeline as $milestone)
                                <div class="planning-milestone">
                                    <div class="fw-semibold">{{ $milestone['label'] }}</div>
                                    <div class="small text-muted">
                                        {{ $milestone['date'] ? $milestone['date']->translatedFormat('d F Y') : $milestone['offset'] }}
                                    </div>
                                </div>

                                @if ($dashboardChecklistOrder && $dashboardChecklistItems->isNotEmpty())
                                    <div class="card border-0 shadow-sm mt-3 member-section-card">
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                                <div>
                                                    <h3 class="fw-bold mb-1 member-section-title">Checklist pernikahan</h3>
                                                    <div class="small text-muted">Tugas untuk {{ $dashboardChecklistOrder->order_number }} · {{ $dashboardChecklistItems->where('status', 'completed')->count() }}/{{ $dashboardChecklistItems->count() }} selesai</div>
                                                </div>
                                                <a href="{{ route('orders.show', $dashboardChecklistOrder->uuid) }}" class="btn btn-sm btn-outline-primary">Buka detail pesanan</a>
                                            </div>
                                            @foreach ($dashboardChecklistItems->take(5) as $item)
                                                <div class="planning-task">
                                                    <form method="POST" action="{{ route('orders.checklist.update', [$dashboardChecklistOrder->uuid, $item->uuid]) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ $item->status === 'completed' ? 'pending' : 'completed' }}">
                                                        <button type="submit" class="btn btn-link p-0 border-0" aria-label="{{ $item->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                                            <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                                        </button>
                                                    </form>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</div>
                                                        <div class="small text-muted">{{ $item->description }}@if($item->due_at) · Target {{ $item->due_at->translatedFormat('d M Y') }}@endif</div>
                                                    </div>
                                                    @if ($item->status === 'completed')<span class="badge bg-success-subtle text-success-emphasis">Selesai</span>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @unless ($planningEventDate)
                            <a href="#package-recommendation" class="btn btn-sm btn-outline-primary mt-3">Pilih paket untuk mulai</a>
                        @endunless
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100 member-section-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h3 class="fw-bold mb-1 member-section-title">Anggaran & rekomendasi paket</h3>
                                <p class="small text-muted mb-0">Nilai pesanan dan pembayaran tersinkron otomatis.</p>
                            </div>
                            <i class="bi bi-wallet2 fs-4 text-primary"></i>
                        </div>
                        @if ($planningOrderTotal > 0)
                            <div class="planning-budget-summary mb-3">
                                <div class="fw-semibold">{{ $planningOrders->count() }} pesanan paket aktif tersinkron</div>
                                <div class="small text-muted">Total disetujui dan kekurangan dapat dilihat pada rekap pembayaran di bawah.</div>
                                <a href="#payment-recap" class="small fw-semibold text-decoration-none d-inline-block mt-2">Lihat rekap pembayaran</a>
                            </div>
                        @else
                            <div class="planning-budget-summary mb-3">
                                <div class="fw-semibold">Belum ada pesanan paket aktif</div>
                                <div class="small text-muted">Setelah memilih paket, total biaya dan pembayaran akan muncul di sini.</div>
                            </div>
                        @endif

                        @include('pages.dashboard.partials.package-recommendations')
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4 mb-4 member-dashboard-grid">
        <div class="col-lg-8">
            <div id="payment-recap" class="card border-0 shadow-sm h-100 member-section-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="fw-bold mb-1 member-section-title">Rekap Pembayaran dan Bukti Transfer</h2>
                            <div class="small text-muted">Rekap 5 pesanan paket terbaru. Pembayaran yang disetujui mengurangi sisa tagihan.</div>
                        </div>
                        <a href="{{ route('orders.index') }}" class="small fw-semibold text-decoration-none">Riwayat lengkap</a>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="planning-budget-summary h-100">
                                <div class="small text-muted">Total pesanan aktif</div>
                                <div class="fw-bold">Rp {{ number_format($paymentOrderTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="planning-budget-summary h-100">
                                <div class="small text-muted">Pembayaran disetujui</div>
                                <div class="fw-bold text-success">Rp {{ number_format($paymentPaidTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="planning-budget-summary h-100">
                                <div class="small text-muted">Kekurangan</div>
                                <div class="fw-bold text-danger">Rp {{ number_format($paymentRemainingTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 member-orders-table">
                            <thead class="table-light"><tr><th>Pesanan & paket</th><th class="text-end">Total</th><th class="text-end">Disetujui</th><th class="text-end">Kekurangan</th><th>Bukti pembayaran</th><th class="text-end">Aksi</th></tr></thead>
                            <tbody>
                                @forelse ($myOrders as $o)
                                    @php
                                        $total = (float) $o->full_amount;
                                        $dibayar = (float) $o->amount_paid_value;
                                        $sisa = (float) $o->remaining_amount_value;
                                        $proofPayments = $o->payments->filter(fn ($payment) => $payment->proof_image_path)->take(3);
                                        $proofStatusLabels = ['accepted' => 'Diterima', 'pending' => 'Pending', 'rejected' => 'Ditolak'];
                                        $proofStatusClasses = ['accepted' => 'bg-success-subtle text-success-emphasis', 'pending' => 'bg-warning-subtle text-warning-emphasis', 'rejected' => 'bg-danger-subtle text-danger-emphasis'];
                                        $legacyProofStatus = $o->status === 'paid' ? 'accepted' : ($o->status === 'failed' ? 'rejected' : 'pending');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold member-order-number">{{ $o->order_number }}</div>
                                            <div class="small text-muted">{{ $o->package?->name ?? 'Paket Wedding' }} {{ $o->packageOption?->name ? '· '.$o->packageOption->name : '' }}</div>
                                        </td>
                                        <td class="text-end text-nowrap">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap fw-semibold text-success">Rp {{ number_format($dibayar, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap fw-semibold {{ $sisa > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                        <td>
                                            @forelse ($proofPayments as $payment)
                                                @php
                                                    $proofStatus = $payment->proof_verification_status;
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-between gap-2 {{ $loop->first ? '' : 'mt-2' }}">
                                                    <span class="badge {{ $proofStatusClasses[$proofStatus] }}">{{ $proofStatusLabels[$proofStatus] }}</span>
                                                    <a href="{{ route('orders.payments.proof', [$o->uuid, $payment->uuid]) }}" class="small text-decoration-none">Lihat bukti</a>
                                                </div>
                                                <div class="small text-muted">Rp {{ number_format((float) ($payment->amount ?? $payment->gross_amount), 0, ',', '.') }}</div>
                                            @empty
                                                @if ($o->proof_image_path)
                                                    <span class="badge {{ $proofStatusClasses[$legacyProofStatus] }}">{{ $proofStatusLabels[$legacyProofStatus] }}</span>
                                                    <a href="{{ route('orders.proof.show', $o->uuid) }}" class="small text-decoration-none ms-1">Lihat bukti</a>
                                                @else
                                                    <span class="small text-muted">Belum ada bukti</span>
                                                @endif
                                            @endforelse
                                        </td>
                                        <td class="text-end"><a href="{{ route('orders.show', $o->uuid) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pembayaran. Cari paket sesuai anggaran untuk memulai.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 member-section-card member-actions-card">
                <div class="card-body">
                    <h2 class="fw-bold mb-1 member-section-title">Mulai dari sini</h2>
                    <p class="small text-muted mb-3">Akses cepat untuk mengelola rencana pernikahan Anda.</p>
                    <div class="d-grid gap-2">
                        <a href="#package-recommendation" class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari Paket Sesuai Budget</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary"><i class="bi bi-receipt me-1"></i> Pesanan & Pembayaran</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary"><i class="bi bi-person me-1"></i> Profil Saya</a>
                    </div>
                    @if($activePackage)
                    <hr>
                    <div class="small text-muted mb-1">Paket Aktif</div>
                    <div class="fw-semibold">{{ $activePackage->package?->name ?? 'Paket Wedding' }}</div>
                    <div class="small text-muted">Berlaku sampai {{ $activePackage->expired_at?->translatedFormat('d M Y') ?? '-' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>