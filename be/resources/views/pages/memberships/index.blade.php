@extends('layouts.app')

@section('title', $isAdmin ? 'Monitoring Paket' : 'Paket Saya')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="{{ $isAdmin ? 'Monitoring Paket' : 'Paket Saya' }}" page="Paket" active="Daftar Paket"
    route="{{ route('memberships.index') }}" />
@endsection

@php
$rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
$statusLabels = [
    'active' => 'Aktif',
    'expired' => 'Kedaluwarsa',
    'cancelled' => 'Dibatalkan',
];
$statusClasses = [
    'active' => 'ms-badge-success',
    'expired' => 'ms-badge-muted',
    'cancelled' => 'ms-badge-danger',
];
$paymentClasses = [
    'Lunas' => 'ms-state-lunas',
    'Cicilan' => 'ms-state-cicilan',
    'Menunggu' => 'ms-state-menunggu',
    'Tanpa pesanan' => 'ms-state-tanpa-pesanan',
];
@endphp

<section class="section ms-page">
    <div class="ms-page-head mb-4">
        <div>
            <p class="ms-page-eyebrow">{{ $isAdmin ? 'Admin / Monitoring' : 'Area Member' }}</p>
            <h1 class="ms-page-title">{{ $isAdmin ? 'Monitoring Paket' : 'Paket Saya' }}</h1>
            <p class="ms-page-subtitle">Pantau kuota, pembayaran, dan jadwal acara dalam satu tampilan.</p>
        </div>
        <div class="ms-page-meta">
            <span class="ms-page-meta-dot"></span>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4 ms-stats">
        <div class="col-6 col-xl-3">
            <div class="card ms-stat ms-stat-total h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-package"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['total']) }}</div>
                            <div class="ml-stat-label">Total Paket</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card ms-stat ms-stat-active h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['active']) }}</div>
                            <div class="ml-stat-label">Sedang Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card ms-stat ms-stat-expiring h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-time-five"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['expiring']) }}</div>
                            <div class="ml-stat-label">Berakhir ≤ 7 Hari</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card ms-stat ms-stat-event h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-calendar-event"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['events_soon']) }}</div>
                            <div class="ml-stat-label">Acara ≤ 30 Hari</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms-finance mb-4">
        <div class="ms-finance-main">
            <div class="ms-finance-icon"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="ms-finance-eyebrow">Ringkasan Pembayaran</div>
                <div class="ms-finance-value">{{ $rupiah($finance['paid']) }}</div>
                <div class="ms-finance-caption">Total dibayar dari {{ $rupiah($finance['total']) }} nilai paket</div>
            </div>
        </div>
        <div class="ms-finance-progress">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="ms-finance-label">Progres Pelunasan</span>
                <strong class="ms-finance-percent">{{ $finance['progress'] }}%</strong>
            </div>
            <div class="progress ms-progress" role="progressbar" aria-valuenow="{{ $finance['progress'] }}"
                aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: {{ $finance['progress'] }}%"></div>
            </div>
            <div class="ms-finance-remaining">
                Sisa tagihan <strong>{{ $rupiah($finance['remaining']) }}</strong>
            </div>
        </div>
        <div class="ms-finance-counts">
            <div class="ms-finance-count">
                <span class="ms-count-dot ms-dot-success"></span>
                <div><strong>{{ $finance['fully_paid'] }}</strong><small>Lunas</small></div>
            </div>
            <div class="ms-finance-count">
                <span class="ms-count-dot ms-dot-warning"></span>
                <div><strong>{{ $finance['partial'] }}</strong><small>Cicilan</small></div>
            </div>
            <div class="ms-finance-count">
                <span class="ms-count-dot ms-dot-danger"></span>
                <div><strong>{{ $finance['awaiting'] }}</strong><small>Menunggu</small></div>
            </div>
        </div>
    </div>

    @if (! empty($upcomingEvents))
        <div class="card ms-reminder mb-4">
            <div class="card-header ms-reminder-head">
                <div class="d-flex align-items-center gap-3">
                    <div class="ms-reminder-icon"><i class="bi bi-calendar2-week"></i></div>
                    <div>
                        <h6 class="ms-reminder-title mb-0">Pengingat Acara</h6>
                        <div class="ms-reminder-subtitle">
                            {{ count($upcomingEvents) }} acara terdekat · {{ $stats['events_soon'] }} acara dalam 30 hari
                        </div>
                    </div>
                </div>
                <span class="ms-reminder-badge"><i class="bi bi-bell me-1"></i>Jangan sampai terlewat</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table ms-reminder-table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal Acara</th>
                                <th>Hitung Mundur</th>
                                @if ($isAdmin)
                                    <th>Pelanggan</th>
                                @endif
                                <th>Paket</th>
                                <th>Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcomingEvents as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event['order']->event_date->format('d M Y') }}</td>
                                    <td>
                                        @if ($event['days'] === 0)
                                            <span class="ms-day ms-day-today">Hari ini</span>
                                        @elseif ($event['days'] === 1)
                                            <span class="ms-day ms-day-near">Besok</span>
                                        @else
                                            <span class="ms-day">H-{{ $event['days'] }}</span>
                                        @endif
                                    </td>
                                    @if ($isAdmin)
                                        <td>{{ $event['customer'] }}</td>
                                    @endif
                                    <td>{{ $event['package'] }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $event['order']->uuid) }}"
                                            class="ms-link">#{{ $event['order']->order_number }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card ms-toolbar mb-4">
        <div class="ms-toolbar-head">
            <div class="d-flex align-items-center gap-3">
                <span class="ms-toolbar-icon"><i class="bi bi-sliders2"></i></span>
                <div>
                    <h6 class="ms-toolbar-title mb-0">Filter Paket</h6>
                    <div class="ms-toolbar-subtitle">Sesuaikan daftar paket berdasarkan status dan masa aktif.</div>
                </div>
            </div>
            <span class="ms-toolbar-badge"><i class="bi bi-list-check me-1"></i>{{ number_format($memberships->total()) }} paket</span>
        </div>
        <div class="ms-toolbar-body">
            <form method="GET" action="{{ route('memberships.index') }}" class="ms-filter-grid {{ $isAdmin ? 'is-admin' : 'is-member' }}">
                @if ($isAdmin)
                    <div class="ms-filter-field ms-field-search">
                        <label class="ms-filter-label" for="filterSearch">Cari pelanggan</label>
                        <input type="text" name="search" id="filterSearch" class="form-control"
                            placeholder="Nama atau email..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                @endif
                <div class="ms-filter-field">
                    <label class="ms-filter-label" for="filterStatus">Status</label>
                    <select name="status" id="filterStatus" class="form-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ms-filter-field">
                    <label class="ms-filter-label" for="filterExpiring">Masa aktif</label>
                    <select name="expiring" id="filterExpiring" class="form-select">
                        <option value="">Semua</option>
                        <option value="7days" @selected(($filters['expiring'] ?? '') === '7days')>Berakhir ≤ 7 hari</option>
                    </select>
                </div>
                <div class="ms-filter-actions">
                    <button type="submit" class="btn btn-success"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('memberships.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card ms-table-card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 ms-table-head">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">{{ $isAdmin ? 'Semua Paket' : 'Paket Saya' }}</h5>
                <span class="ms-toolbar-badge">{{ number_format($memberships->total()) }} paket</span>
            </div>
            <div class="ms-table-hint">Pembayaran, kuota, dan jadwal acara dalam satu tampilan.</div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table ms-table">
                    <thead>
                        <tr>
                            @if ($isAdmin)
                                <th>Pelanggan</th>
                            @endif
                            <th>Paket & Pesanan</th>
                            <th>Tanggal Acara</th>
                            <th>Pembayaran</th>
                            <th class="text-center">Kuota</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($memberships as $membership)
                            <tr>
                                @if ($isAdmin)
                                    <td>
                                        <span class="d-block fw-semibold">{{ $membership->user?->name ?? '-' }}</span>
                                        <small class="log-muted">{{ $membership->user?->email ?? '-' }}</small>
                                    </td>
                                @endif
                                <td>
                                    <span class="d-block fw-semibold">{{ $membership->package?->name ?? '-' }}</span>
                                    <small class="log-muted">
                                        {{ $membership->order?->order_number ?? 'Tanpa pesanan' }}
                                        @if ($membership->order?->scheme_label)
                                            · {{ $membership->order->scheme_label }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if ($membership->event_date)
                                        <span class="d-block fw-semibold">{{ $membership->event_date->format('d M Y') }}</span>
                                        @if ($membership->days_to_event === 0)
                                            <span class="ms-day ms-day-today">Hari ini</span>
                                        @elseif ($membership->days_to_event > 0)
                                            <span class="ms-day">H-{{ $membership->days_to_event }}</span>
                                        @else
                                            <span class="ms-day ms-day-past">Sudah lewat</span>
                                        @endif
                                    @else
                                        <span class="log-muted">Belum dijadwalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="ms-pay">
                                        <span class="ms-pay-paid">{{ $rupiah($membership->amount_paid_value) }}</span>
                                        <small class="ms-pay-total">dari {{ $rupiah($membership->total_amount_value) }}</small>
                                        <div class="ms-mini-progress" aria-hidden="true">
                                            <span class="{{ $membership->payment_progress >= 100 ? 'is-complete' : '' }}"
                                                style="width: {{ $membership->payment_progress }}%"></span>
                                        </div>
                                        @if ($membership->remaining_amount_value > 0)
                                            <small class="ms-pay-due">Sisa {{ $rupiah($membership->remaining_amount_value) }}</small>
                                        @else
                                            <small class="ms-pay-clear"><i class="bi bi-check2"></i> Lunas</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if (is_null($membership->quota))
                                        <span class="ms-badge ms-badge-info">Tanpa batas</span>
                                    @elseif ($membership->quota > 0)
                                        <strong>{{ $membership->quota }}</strong><small class="ms-quota-unit"> sisa</small>
                                    @else
                                        <span class="ms-badge ms-badge-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="ms-badge {{ $statusClasses[$membership->status] ?? 'ms-badge-muted' }}">
                                        {{ $statusLabels[$membership->status] ?? ucfirst($membership->status) }}
                                    </span>
                                    <span class="ms-pay-state {{ $paymentClasses[$membership->payment_status_label] ?? '' }}">
                                        {{ $membership->payment_status_label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('memberships.show', $membership->uuid) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada paket yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($memberships->hasPages())
                <div class="ms-pagination">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $memberships->firstItem() }}</strong>–<strong>{{ $memberships->lastItem() }}</strong>
                        dari <strong>{{ $memberships->total() }}</strong> paket
                    </div>
                    <div>{{ $memberships->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

@push('after-style')
<style>
    .ms-page { --ms-primary: #5b7cfa; --ms-ink: #101828; --ms-muted: #667085; --ms-line: #eef0f4; }
    .ms-stats .ms-stat { position: relative; overflow: hidden; border-radius: 16px; transition: transform .18s ease, box-shadow .18s ease; }
    .ms-stats .ms-stat:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,.1); }
    .ms-stats .ms-stat::after { content: ''; position: absolute; inset: auto 0 0; height: 3px; background: var(--ms-accent); }
    .ms-stats .ms-stat-total { --ms-accent: #5b7cfa; }
    .ms-stats .ms-stat-active { --ms-accent: #10b981; }
    .ms-stats .ms-stat-expiring { --ms-accent: #f59e0b; }
    .ms-stats .ms-stat-event { --ms-accent: #7c5cfa; }

    .ms-finance {
        display: grid; grid-template-columns: minmax(260px, 1.2fr) minmax(260px, 1fr) auto;
        gap: 24px; align-items: center; padding: 22px 24px; border-radius: 18px;
        border: 1px solid #dfe7ff; background: linear-gradient(110deg, #f7f9ff 0%, #fff 55%, #f4fdf8 100%);
        box-shadow: 0 10px 28px rgba(16,24,40,.05);
    }
    .ms-finance-main { display: flex; align-items: center; gap: 16px; min-width: 0; }
    .ms-finance-icon {
        width: 52px; height: 52px; flex-shrink: 0; border-radius: 16px; display: grid; place-items: center;
        font-size: 24px; color: #fff; background: linear-gradient(135deg, #5b7cfa, #7c5cfa);
        box-shadow: 0 8px 18px rgba(91,124,250,.28);
    }
    .ms-finance-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ms-muted); }
    .ms-finance-value { font-size: 27px; font-weight: 750; letter-spacing: -.02em; color: var(--ms-ink); font-variant-numeric: tabular-nums; }
    .ms-finance-caption { font-size: 12px; color: var(--ms-muted); }
    .ms-finance-label { font-size: 12px; font-weight: 650; color: var(--ms-muted); }
    .ms-finance-percent { font-size: 15px; color: #3538cd; font-variant-numeric: tabular-nums; }
    .ms-progress { height: 9px; border-radius: 999px; background: #e9edf7; overflow: hidden; }
    .ms-progress .progress-bar { border-radius: 999px; background: linear-gradient(90deg, #5b7cfa, #7c5cfa); }
    .ms-finance-remaining { margin-top: 8px; font-size: 12px; color: var(--ms-muted); }
    .ms-finance-remaining strong { color: #b42318; font-size: 13px; }
    .ms-finance-counts { display: flex; gap: 18px; padding-left: 22px; border-left: 1px solid #e4e7ec; }
    .ms-finance-count { display: flex; align-items: center; gap: 9px; }
    .ms-count-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .ms-dot-success { background: #10b981; } .ms-dot-warning { background: #f59e0b; } .ms-dot-danger { background: #ef4444; }
    .ms-finance-count strong { display: block; font-size: 17px; line-height: 1.1; color: var(--ms-ink); }
    .ms-finance-count small { font-size: 11px; color: var(--ms-muted); }

    .ms-reminder { border-radius: 16px; overflow: hidden; border-color: #e7e3ff; }
    .ms-reminder-head { padding: 14px 20px; background: linear-gradient(90deg, #faf9ff, #fff); border-bottom: 1px solid var(--ms-line); }
    .ms-reminder-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; font-size: 18px; color: #6941c6; background: #f4f3ff; }
    .ms-reminder-title { font-size: 15px; font-weight: 700; color: var(--ms-ink); }
    .ms-reminder-subtitle { font-size: 12px; color: #98a2b3; }
    .ms-reminder-badge { font-size: 11px; font-weight: 650; color: #6941c6; background: #f4f3ff; border-radius: 999px; padding: 6px 12px; }
    .ms-reminder-table th { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #98a2b3; background: #fcfcfe; white-space: nowrap; }
    .ms-reminder-table td { font-size: 13px; vertical-align: middle; white-space: nowrap; color: #475467; }
    .ms-link { color: #3538cd; font-weight: 650; text-decoration: none; }
    .ms-link:hover { text-decoration: underline; }

    .ms-day { display: inline-block; margin-top: 4px; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 650; color: #3538cd; background: #eef2ff; }
    .ms-day-today { color: #b54708; background: #fffaeb; }
    .ms-day-near { color: #b54708; background: #fff7e6; }
    .ms-day-past { color: #667085; background: #f2f4f7; }

    .ms-toolbar, .ms-table-card { border-radius: 16px; border-color: var(--ms-line); box-shadow: 0 8px 24px rgba(16,24,40,.04); overflow: hidden; }
    .ms-toolbar-head { padding: 15px 20px; border-bottom: 1px solid var(--ms-line); background: linear-gradient(90deg, #fbfcff, #fff); }
    .ms-toolbar-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; color: var(--ms-primary); background: #eef2ff; font-size: 18px; }
    .ms-toolbar-title { font-size: 15px; font-weight: 700; color: var(--ms-ink); }
    .ms-toolbar-subtitle { font-size: 12px; color: #98a2b3; }
    .ms-toolbar-badge { font-size: 12px; font-weight: 650; color: #3538cd; background: #eef2ff; border-radius: 999px; padding: 6px 12px; white-space: nowrap; }
    .ms-toolbar-body { padding: 18px 20px; }
    .ms-filter-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 12px 14px; align-items: end; padding: 16px; border: 1px solid var(--ms-line); border-radius: 14px; background: #fbfcff; }
    .ms-field-search { grid-column: span 5; }
    .ms-filter-field { grid-column: span 3; min-width: 0; }
    .ms-filter-actions { grid-column: span 4; display: flex; align-items: end; gap: 8px; }
    .ms-filter-label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 650; color: var(--ms-muted); }
    .ms-filter-grid .form-control, .ms-filter-grid .form-select { height: 38px; font-size: 13px; border-color: #e4e7ec; border-radius: 10px; }

    .ms-table-head { padding: 16px 20px; border-bottom: 1px solid var(--ms-line); }
    .ms-table-hint { font-size: 12px; color: #98a2b3; }
    .ms-table { min-width: 940px; margin-bottom: 0; }
    .ms-table th { padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #98a2b3; background: #f8f9fb; white-space: nowrap; }
    .ms-table td { padding: 14px 16px; vertical-align: middle; font-size: 13px; color: #475467; }
    .ms-pay { min-width: 170px; }
    .ms-pay-paid { display: block; font-size: 14px; font-weight: 700; color: #027a48; font-variant-numeric: tabular-nums; }
    .ms-pay-total { display: block; font-size: 11px; color: #98a2b3; }
    .ms-mini-progress { height: 5px; margin: 7px 0 5px; border-radius: 999px; background: #eef0f4; overflow: hidden; }
    .ms-mini-progress span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, #10b981, #5b7cfa); }
    .ms-mini-progress span.is-complete { background: #10b981; }
    .ms-pay-due { display: block; font-size: 11px; font-weight: 650; color: #b42318; }
    .ms-pay-clear { display: block; font-size: 11px; font-weight: 650; color: #027a48; }
    .ms-quota-unit { font-size: 11px; color: #98a2b3; }
    .ms-badge { display: inline-block; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 650; white-space: nowrap; }
    .ms-badge-success { color: #027a48; background: #ecfdf3; }
    .ms-badge-muted { color: #475467; background: #f2f4f7; }
    .ms-badge-danger { color: #b42318; background: #fef3f2; }
    .ms-badge-info { color: #175cd3; background: #eff8ff; }
    .ms-pay-state { display: block; margin-top: 6px; font-size: 11px; color: #667085; }
    .ms-state-lunas { color: #027a48; }
    .ms-state-cicilan { color: #b54708; }
    .ms-state-menunggu { color: #b42318; }
    .ms-state-tanpa-pesanan { color: #98a2b3; }
    .ms-pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; }

    @media (max-width: 1199.98px) {
        .ms-finance { grid-template-columns: 1fr 1fr; }
        .ms-finance-counts { grid-column: 1 / -1; padding: 16px 0 0; border-left: 0; border-top: 1px solid #e4e7ec; }
        .ms-filter-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .ms-field-search { grid-column: span 6; }
        .ms-filter-field { grid-column: span 3; }
        .ms-filter-actions { grid-column: span 6; }
    }

    @media (max-width: 575.98px) {
        .ms-finance { grid-template-columns: 1fr; padding: 18px; }
        .ms-finance-counts { flex-wrap: wrap; gap: 14px; }
        .ms-filter-grid { grid-template-columns: 1fr; }
        .ms-field-search, .ms-filter-field, .ms-filter-actions { grid-column: 1 / -1; }
    }
</style>
@endpush

@endsection
