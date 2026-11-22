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
        background: linear-gradient(135deg, #f8f7ff, #fff);
        border: 1px solid #eceaff;
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
        border: 2px solid #6758d6;
        border-radius: 50%;
        background: #fff;
    }

    .member-planning .planning-budget-grid .form-control {
        min-width: 130px;
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
                #f8f7ff 100%);
        border: 1px solid #eceaff;
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
                #4f46e5,
                #7c3aed);
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
                #eef2ff,
                #f5f3ff);
    }

    .membership-plan-name {
        margin-top: 3px;
        color: #5b4ce1;
        font-size: 20px;
        font-weight: 700;
    }

    .membership-stars {
        position: absolute;
        top: 12px;
        right: 16px;
        color: #7c3aed;
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
                #4f46e5,
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
                #4f46e5,
                #7c3aed);
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
                <a href="{{ route('packages.member') }}" class="btn btn-light fw-bold px-4"><i
                        class="bi bi-box-seam me-1"></i> Lihat Paket</a>
            </div>
        </div>
    </div>

    @php($myOrderStats = $myOrderStats ?? ['total'=>0,'pending'=>0,'paid'=>0,'active_package'=>0])
    @php($myOrders = $myOrders ?? collect())

    <div class="row g-3 mb-4 member-stats">
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-receipt-cutoff"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['total'] }}</div><div class="small text-muted mt-1">Total Pesanan</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-hourglass-split"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['pending'] }}</div><div class="small text-muted mt-1">Menunggu Pembayaran</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-patch-check"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['paid'] }}</div><div class="small text-muted mt-1">Pesanan Lunas</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100 member-stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-box-seam"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $myOrderStats['active_package'] }}</div><div class="small text-muted mt-1">Paket Aktif</div></div></div></div></div>
    </div>

    @php
        $planningTasks = \App\Models\WeddingPlan::TASKS;
        $completedPlanningTasks = $weddingPlan?->completed_tasks ?? [];
        $completedTaskCount = count(array_intersect(array_keys($planningTasks), $completedPlanningTasks));
        $planningProgress = count($planningTasks) > 0 ? (int) round($completedTaskCount / count($planningTasks) * 100) : 0;
        $budgetCategories = \App\Models\WeddingPlan::BUDGET_CATEGORIES;
        $budgetBreakdown = $weddingPlan?->budget_breakdown ?? [];
        $allocatedBudget = collect($budgetCategories)->keys()->sum(fn ($category) => (float) ($budgetBreakdown[$category] ?? 0));
        $totalBudget = (float) ($weddingPlan?->total_budget ?? 0);
        $remainingBudget = $totalBudget - $allocatedBudget;
        $planningDaysRemaining = $planningEventDate
            ? now()->startOfDay()->diffInDays(\Illuminate\Support\Carbon::parse($planningEventDate)->startOfDay(), false)
            : null;
    @endphp

    <section class="member-planning mb-4" aria-labelledby="wedding-planning-title">
        @if (session('success'))
            <div class="alert alert-success py-2" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger py-2" role="alert">{{ $errors->first() }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="small fw-semibold text-primary text-uppercase">Pusat persiapan</span>
                <h2 id="wedding-planning-title" class="fw-bold mb-1">Wedding Planning Hub</h2>
                <p class="small text-muted mb-0">Atur tanggal, checklist, dan rencana anggaran pernikahan Anda.</p>
            </div>
            <a href="{{ route('packages.member') }}" class="btn btn-outline-primary d-none d-md-inline-flex">
                <i class="bi bi-search me-1"></i> Jelajahi Paket & Vendor
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
                            <div class="fw-bold">{{ $planningEventDate ? \Illuminate\Support\Carbon::parse($planningEventDate)->translatedFormat('d F Y') : 'Belum ditentukan' }}</div>
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
                        <div class="small text-muted">Tambahkan tanggal untuk melihat hitung mundur dan timeline.</div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Progress checklist</span>
                        <span class="fw-semibold">{{ $completedTaskCount }}/{{ count($planningTasks) }} selesai</span>
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
                                <h3 class="fw-bold mb-1 member-section-title">Checklist persiapan</h3>
                                <div class="small text-muted">Tandai langkah yang sudah selesai.</div>
                            </div>
                            <i class="bi bi-check2-square fs-4 text-primary"></i>
                        </div>
                        <div class="row g-0">
                            @foreach ($planningTasks as $taskKey => $taskLabel)
                                <div class="col-md-6 pe-md-3">
                                    <form method="POST" action="{{ route('wedding-planning.tasks.update', $taskKey) }}" class="planning-task">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="completed" value="0">
                                        <input
                                            class="form-check-input mt-1"
                                            type="checkbox"
                                            name="completed"
                                            value="1"
                                            aria-label="{{ $taskLabel }}"
                                            onchange="this.form.requestSubmit()"
                                            @checked(in_array($taskKey, $completedPlanningTasks, true))
                                        >
                                        <span class="{{ in_array($taskKey, $completedPlanningTasks, true) ? 'text-muted text-decoration-line-through' : 'fw-medium' }}">{{ $taskLabel }}</span>
                                    </form>
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
                        <p class="small text-muted mb-3">Tahapan rekomendasi dihitung dari tanggal acara.</p>
                        @if ($planningTimeline->isNotEmpty())
                            <div class="planning-timeline">
                                @foreach ($planningTimeline as $milestone)
                                    <div class="planning-milestone">
                                        <div class="fw-semibold">{{ $milestone['label'] }}</div>
                                        <div class="small text-muted">{{ $milestone['date']->translatedFormat('d F Y') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-3 bg-light p-3 small text-muted">
                                Simpan tanggal pernikahan untuk membuat timeline persiapan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100 member-section-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h3 class="fw-bold mb-1 member-section-title">Rencana anggaran</h3>
                                <p class="small text-muted mb-0">Tetapkan batas dan alokasikan anggaran per kategori.</p>
                            </div>
                            <i class="bi bi-wallet2 fs-4 text-primary"></i>
                        </div>
                        <form method="POST" action="{{ route('wedding-planning.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 mb-3">
                                <div class="col-md-5">
                                    <label for="planning-event-date" class="form-label small fw-semibold">Tanggal pernikahan</label>
                                    <input
                                        id="planning-event-date"
                                        class="form-control"
                                        type="date"
                                        name="event_date"
                                        value="{{ old('event_date', $weddingPlan?->event_date?->format('Y-m-d') ?? ($planningEventDate ? \Illuminate\Support\Carbon::parse($planningEventDate)->format('Y-m-d') : '')) }}"
                                    >
                                </div>
                                <div class="col-md-7">
                                    <label for="planning-total-budget" class="form-label small fw-semibold">Total anggaran (Rp)</label>
                                    <input
                                        id="planning-total-budget"
                                        class="form-control"
                                        type="number"
                                        name="total_budget"
                                        min="0"
                                        step="1000"
                                        value="{{ old('total_budget', $totalBudget) }}"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="row g-2 planning-budget-grid">
                                @foreach ($budgetCategories as $categoryKey => $categoryLabel)
                                    <div class="col-md-6">
                                        <label for="budget-{{ $categoryKey }}" class="form-label small fw-semibold mb-1">{{ $categoryLabel }}</label>
                                        <input
                                            id="budget-{{ $categoryKey }}"
                                            class="form-control"
                                            type="number"
                                            name="budget_breakdown[{{ $categoryKey }}]"
                                            min="0"
                                            step="1000"
                                            value="{{ old('budget_breakdown.'.$categoryKey, $budgetBreakdown[$categoryKey] ?? 0) }}"
                                            required
                                        >
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">
                                <div class="small">
                                    <span class="text-muted">Dialokasikan:</span>
                                    <strong>Rp {{ number_format($allocatedBudget, 0, ',', '.') }}</strong>
                                    <span class="text-muted ms-2">{{ $remainingBudget < 0 ? 'Melebihi anggaran:' : 'Sisa:' }}</span>
                                    <strong class="{{ $remainingBudget >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format(abs($remainingBudget), 0, ',', '.') }}</strong>
                                </div>
                                <button type="submit" class="btn btn-primary text-nowrap">
                                    <i class="bi bi-check2 me-1"></i> Simpan Rencana
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4 mb-4 member-dashboard-grid">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100 member-section-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="fw-bold mb-1 member-section-title">Pesanan Terbaru</h2>
                            <div class="small text-muted">Pantau status pesanan dan pembayaran wedding Anda.</div>
                        </div>
                        <a href="{{ route('orders.index') }}" class="small fw-semibold text-decoration-none">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 member-orders-table">
                            <thead class="table-light"><tr><th>Pesanan</th><th>Paket</th><th>Status</th><th class="text-end">Total</th><th class="text-end">Dibayar</th><th class="text-end">Sisa</th><th class="text-end">Aksi</th></tr></thead>
                            <tbody>
                                @forelse ($myOrders as $o)
                                @php($total = (float) ($o->total_amount ?: $o->amount))
                                @php($dibayar = (float) ($o->amount_paid_value ?? ($o->status === 'paid' ? $total : 0)))
                                @php($sisa = max(0, $total - $dibayar))
                                @php($progres = $total > 0 ? min(100, (int) round(($dibayar / $total) * 100)) : 0)
                                <tr>
                                    <td><div class="fw-semibold member-order-number">{{ $o->order_number }}</div><div class="small text-muted">{{ $o->created_at?->format('d M Y') }}</div></td>
                                    <td><div class="fw-semibold">{{ $o->package?->name ?? '-' }}</div><div class="small text-muted">{{ $o->packageOption?->name ?? '' }}</div></td>
                                    @php($statusLabels=['pending'=>'Menunggu Pembayaran','partially_paid'=>'Dibayar Sebagian','paid'=>'Lunas','cancelled'=>'Dibatalkan','expired'=>'Kedaluwarsa','failed'=>'Gagal'])
                                    <td><span class="badge {{ $o->status === 'paid' ? 'bg-success-subtle text-success-emphasis' : ($o->status === 'partially_paid' ? 'bg-warning-subtle text-warning-emphasis' : ($o->status === 'pending' ? 'bg-primary-subtle text-primary-emphasis' : 'bg-secondary-subtle text-secondary-emphasis')) }}">{{ $statusLabels[$o->status] ?? ucfirst(str_replace('_',' ',$o->status ?? '-')) }}</span></td>
                                    <td class="text-end fw-semibold text-nowrap">Rp {{ number_format($total,0,',','.') }}</td>
                                    <td class="text-end text-nowrap"><div class="fw-semibold text-success">Rp {{ number_format($dibayar,0,',','.') }}</div><div class="progress member-order-progress ms-auto mt-1"><div class="progress-bar bg-success" role="progressbar" style="width:{{ $progres }}%" aria-valuenow="{{ $progres }}" aria-valuemin="0" aria-valuemax="100"></div></div></td>
                                    <td class="text-end fw-semibold {{ $sisa>0?'text-danger':'text-success' }}">Rp {{ number_format($sisa,0,',','.') }}</td>
                                    <td class="text-end"><a href="{{ route('orders.show', $o->uuid) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada pesanan. <a href="{{ route('packages.member') }}" class="fw-semibold text-decoration-none">Pilih paket wedding</a>.</td></tr>
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
                        <a href="{{ route('packages.member') }}" class="btn btn-primary"><i class="bi bi-box-seam me-1"></i> Jelajahi Paket Wedding</a>
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