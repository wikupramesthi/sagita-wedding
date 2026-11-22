@extends('layouts.app')

@section('title', 'Detail Paket')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="Detail Paket" page="Memberships" active="Detail" route="{{ route('memberships.index') }}" />
@endsection

@php
$rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
$days = $membership->days_to_event;
@endphp

<section class="section ms-detail">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card ms-detail-card h-100">
                <div class="ms-detail-cover">
                    <div class="ms-detail-cover-icon">
                        @if ($membership->user?->avatar_url)
                            <img src="{{ $membership->user->avatar_url }}" alt="{{ $membership->user->name }}" class="ms-detail-avatar">
                        @else
                            <span class="ms-detail-avatar-initial">{{ strtoupper(substr($membership->user?->name ?? '?', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="ms-detail-cover-info">
                        <div class="ms-detail-eyebrow">Paket Pelanggan</div>
                        <h5 class="ms-detail-name mb-0" title="{{ $membership->user?->name ?? '-' }}">{{ $membership->user?->name ?? '-' }}</h5>
                        <div class="ms-detail-email" title="{{ $membership->user?->email ?? '-' }}">{{ $membership->user?->email ?? '-' }}</div>
                    </div>
                    @if ($membership->status === 'active')
                        <span class="ms-badge ms-badge-success ms-detail-status">Aktif</span>
                    @elseif ($membership->status === 'expired')
                        <span class="ms-badge ms-badge-muted ms-detail-status">Kedaluwarsa</span>
                    @else
                        <span class="ms-badge ms-badge-danger ms-detail-status">Dibatalkan</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <dl class="row ms-detail-list mb-0">
                        <dt>Paket</dt>
                        <dd class="fw-semibold">{{ $membership->package?->name ?? '-' }}</dd>

                        <dt>Pesanan</dt>
                        <dd>
                            @if ($membership->order)
                                <a href="{{ route('orders.show', $membership->order->uuid) }}" class="ms-link">
                                    #{{ $membership->order->order_number }}
                                </a>
                                <small class="ms-detail-note">{{ $membership->order->packageOption?->name ?? '' }}</small>
                            @else
                                <span class="ms-detail-note">Tanpa pesanan</span>
                            @endif
                        </dd>

                        <dt>Kuota</dt>
                        <dd>
                            @if (is_null($membership->quota))
                                <span class="ms-badge ms-badge-info">Tanpa batas</span>
                            @else
                                <strong>{{ $membership->quota }}</strong> sesi tersisa
                            @endif
                            <small class="ms-detail-note">
                                {{ $membership->started_at?->format('d M Y') ?? '-' }} →
                                {{ $membership->expired_at?->format('d M Y') ?? '-' }}
                            </small>
                        </dd>

                        <dt>Tanggal Acara</dt>
                        <dd>
                            @if ($membership->event_date)
                                <span class="fw-semibold">{{ $membership->event_date->format('d M Y') }}</span>
                                @if ($days === 0)
                                    <span class="ms-day ms-day-today">Hari ini</span>
                                @elseif ($days > 0)
                                    <span class="ms-day">H-{{ $days }}</span>
                                @else
                                    <span class="ms-day ms-day-past">Sudah lewat</span>
                                @endif
                                @if ($membership->order?->balance_due_at)
                                    <small class="ms-detail-note">Pelunasan sebelum {{ $membership->order->balance_due_at->format('d M Y') }}</small>
                                @endif
                            @else
                                <span class="ms-detail-note">Belum dijadwalkan</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card ms-pay-card mb-4">
                <div class="card-header ms-pay-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="ms-pay-icon"><i class="bi bi-wallet2"></i></span>
                        <div>
                            <h6 class="ms-pay-title mb-0">Ringkasan Pembayaran</h6>
                            <div class="ms-pay-subtitle">
                                {{ $membership->order?->scheme_label ?? 'Bayar Lunas' }}
                                · {{ $membership->payment_status_label }}
                            </div>
                        </div>
                    </div>
                    <span class="ms-pay-badge {{ $membership->payment_progress >= 100 ? 'is-paid' : '' }}">
                        {{ $membership->payment_progress }}% terbayar
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="ms-pay-grid">
                        <div class="ms-pay-metric">
                            <span>Total Nilai Paket</span>
                            <strong>{{ $rupiah($membership->total_amount_value) }}</strong>
                        </div>
                        <div class="ms-pay-metric is-paid">
                            <span>Sudah Dibayar</span>
                            <strong>{{ $rupiah($membership->amount_paid_value) }}</strong>
                        </div>
                        <div class="ms-pay-metric {{ $membership->remaining_amount_value > 0 ? 'is-due' : 'is-clear' }}">
                            <span>Sisa Tagihan</span>
                            <strong>{{ $rupiah($membership->remaining_amount_value) }}</strong>
                        </div>
                    </div>
                    <div class="progress ms-progress mt-3" role="progressbar"
                        aria-valuenow="{{ $membership->payment_progress }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $membership->payment_progress }}%"></div>
                    </div>

                    <div class="ms-pay-history mt-4">
                        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                            <div class="ms-pay-history-title">
                                Riwayat Pembayaran
                                @if ($membership->order?->payments?->isNotEmpty())
                                    <span class="ms-toolbar-badge">{{ $membership->order->payments->count() }}</span>
                                @endif
                            </div>
                            @if ($membership->order)
                                <a href="{{ route('orders.show', $membership->order->uuid) }}" class="ms-link small">Detail pesanan →</a>
                            @endif
                        </div>
                        @if ($membership->order?->payments?->isNotEmpty())
                            <div class="table-responsive mt-2">
                                <table class="table ms-history-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($membership->order->payments->sortByDesc('created_at') as $payment)
                                            <tr>
                                                <td class="text-capitalize fw-semibold">
                                                    {{ $payment->kind ?? 'pembayaran' }}
                                                    @if ($payment->note)
                                                        <small class="ms-detail-note">{{ $payment->note }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $rupiah($payment->amount ?: $payment->gross_amount) }}</td>
                                                <td>
                                                    @if (in_array($payment->verification_status, ['verified', 'approved'], true) || $payment->status === 'paid')
                                                        <span class="ms-badge ms-badge-success">Terverifikasi</span>
                                                    @elseif ($payment->verification_status === 'rejected')
                                                        <span class="ms-badge ms-badge-danger">Ditolak</span>
                                                    @else
                                                        <span class="ms-badge ms-badge-muted">Menunggu verifikasi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($payment->proof_image_path)
                                                        <a href="{{ route('orders.payments.proof', [$membership->order->uuid, $payment->uuid]) }}" target="_blank" rel="noopener" class="ms-link">Lihat</a>
                                                    @else
                                                        <span class="ms-detail-note">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ ($payment->paid_at ?? $payment->created_at)?->format('d M Y H:i') ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="ms-pay-empty mt-2">
                                <i class="bi bi-receipt"></i>
                                <span>Belum ada pembayaran.</span>
                                @if ($membership->order)
                                    <a href="{{ route('orders.show', $membership->order->uuid) }}" class="ms-link">Unggah bukti pembayaran →</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card ms-detail-card">
                <div class="card-header d-flex justify-content-between align-items-center ms-history-head">
                    <h6 class="ms-pay-title mb-0">Riwayat Layanan</h6>
                    <span class="ms-toolbar-badge">{{ $bookings->count() }} layanan</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table ms-history-table mb-0">
                            <thead>
                                <tr>
                                    <th>Layanan</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                    <th>Dipesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="fw-semibold">{{ $booking->schedule?->class?->name ?? '-' }}</td>
                                        <td>
                                            <span class="d-block fw-semibold">{{ $booking->booking_date?->format('d M Y') ?? '-' }}</span>
                                            <small class="ms-detail-note">{{ ucfirst($booking->schedule?->day ?? '-') }}</small>
                                        </td>
                                        <td>{{ ucfirst($booking->status ?? '-') }}</td>
                                        <td>{{ $booking->booked_at?->format('d M Y H:i') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada layanan dengan paket ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('after-style')
<style>
    .ms-detail { --ms-primary: #4b6b52; --ms-sage-soft: #eef3ee; --ms-ink: #101828; --ms-muted: #667085; --ms-line: #eef0f4; }
    .ms-detail-card, .ms-pay-card { border-radius: 16px; border-color: var(--ms-line); overflow: hidden; box-shadow: 0 8px 24px rgba(16,24,40,.04); }
    .ms-detail-cover { display: flex; align-items: center; gap: 14px; padding: 18px 20px; background: linear-gradient(110deg, #f6faf7, #fff 60%, #fdf8f4); border-bottom: 1px solid var(--ms-line); }
    .ms-detail-cover-icon { width: 52px; height: 52px; flex-shrink: 0; border-radius: 16px; display: grid; place-items: center; overflow: hidden; background: linear-gradient(135deg, #4b6b52, #6b8f6f); box-shadow: 0 8px 18px rgba(75,107,82,.25); }
    .ms-detail-avatar { width: 100%; height: 100%; object-fit: cover; }
    .ms-detail-avatar-initial { font-size: 20px; font-weight: 700; color: #fff; }
    .ms-detail-cover-info { flex: 1; min-width: 0; }
    .ms-detail-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: var(--ms-muted); }
    .ms-detail-name { font-size: 18px; font-weight: 700; color: var(--ms-ink); }
    .ms-detail-email { font-size: 12px; color: var(--ms-muted); }
    .ms-detail-name, .ms-detail-email { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ms-detail-status { flex-shrink: 0; align-self: flex-start; margin-left: auto; }
    .ms-detail-list dt { width: 38%; padding: 10px 0; font-size: 12px; font-weight: 600; color: var(--ms-muted); border-bottom: 1px solid var(--ms-line); }
    .ms-detail-list dd { width: 62%; padding: 10px 0; margin: 0; font-size: 13px; color: #344054; border-bottom: 1px solid var(--ms-line); }
    .ms-detail-note { display: block; font-size: 11px; color: #98a2b3; }
    .ms-link { color: var(--ms-primary); font-weight: 650; text-decoration: none; }
    .ms-link:hover { text-decoration: underline; }

    .ms-pay-head { padding: 15px 20px; background: linear-gradient(90deg, #fbfcff, #fff); border-bottom: 1px solid var(--ms-line); }
    .ms-pay-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; font-size: 18px; color: var(--ms-primary); background: var(--ms-sage-soft); }
    .ms-pay-title { font-size: 15px; font-weight: 700; color: var(--ms-ink); }
    .ms-pay-subtitle { font-size: 12px; color: #98a2b3; }
    .ms-pay-badge { font-size: 11px; font-weight: 650; color: #b54708; background: #fffaeb; border-radius: 999px; padding: 6px 12px; }
    .ms-pay-badge.is-paid { color: #027a48; background: #ecfdf3; }
    .ms-pay-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
    .ms-pay-metric { padding: 14px; border: 1px solid var(--ms-line); border-radius: 13px; background: #fbfcff; }
    .ms-pay-metric span { display: block; font-size: 11px; font-weight: 650; color: var(--ms-muted); }
    .ms-pay-metric strong { display: block; margin-top: 5px; font-size: 16px; color: var(--ms-ink); font-variant-numeric: tabular-nums; }
    .ms-pay-metric.is-paid strong { color: #027a48; }
    .ms-pay-metric.is-due strong { color: #b42318; }
    .ms-pay-metric.is-clear strong { color: #027a48; }
    .ms-progress { height: 9px; border-radius: 999px; background: #e9edf7; overflow: hidden; }
    .ms-progress .progress-bar { border-radius: 999px; background: linear-gradient(90deg, #4b6b52, #6b8f6f); }
    .ms-pay-history-title, .ms-history-head { font-size: 13px; font-weight: 700; color: #344054; }
    .ms-pay-history-title { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ms-pay-empty { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; padding: 14px; border: 1px dashed var(--ms-line); border-radius: 12px; background: #fbfcff; font-size: 12px; color: var(--ms-muted); }
    .ms-pay-empty i { font-size: 16px; color: var(--ms-primary); }
    .ms-history-head { padding: 15px 20px; border-bottom: 1px solid var(--ms-line); }
    .ms-history-table th { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #98a2b3; background: #fafbfc; white-space: nowrap; }
    .ms-history-table td { font-size: 12px; color: #475467; vertical-align: middle; white-space: nowrap; }
    .ms-badge { display: inline-block; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 650; white-space: nowrap; }
    .ms-badge-success { color: #027a48; background: #ecfdf3; }
    .ms-badge-muted { color: #475467; background: #f2f4f7; }
    .ms-badge-danger { color: #b42318; background: #fef3f2; }
    .ms-badge-info { color: #175cd3; background: #eff8ff; }
    .ms-day { display: inline-block; margin-top: 4px; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 650; color: var(--ms-primary); background: var(--ms-sage-soft); }
    .ms-day-today { color: #b54708; background: #fffaeb; }
    .ms-day-past { color: #667085; background: #f2f4f7; }
    .ms-toolbar-badge { font-size: 12px; font-weight: 650; color: var(--ms-primary); background: var(--ms-sage-soft); border-radius: 999px; padding: 6px 12px; }

    @media (max-width: 575.98px) {
        .ms-pay-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@endsection
