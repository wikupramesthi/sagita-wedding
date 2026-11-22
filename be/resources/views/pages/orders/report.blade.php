@extends('layouts.app')

@section('title', 'Laporan Pesanan')

@section('content')

@php
    $statusLabels = [
        'paid' => 'Lunas',
        'pending' => 'Menunggu',
        'failed' => 'Gagal',
        'expired' => 'Kedaluwarsa',
        'cancelled' => 'Dibatalkan',
    ];
    $hasColumn = fn (string $key): bool => in_array($key, $columns, true);
    $visibleColumns = array_values(array_filter(
        ['date', 'order', 'customer', 'package', 'amount', 'status', 'proof'],
        function (string $key) use ($hasColumn, $isAdmin): bool {
            if ($key === 'customer') {
                return $isAdmin && $hasColumn('customer');
            }

            return $hasColumn($key);
        }
    ));
@endphp

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Laporan Pesanan</h4>
            <p class="mb-0 fw-semibold">
                Periode: <strong>{{ $periode }}</strong>
                <span class="mx-1">•</span> {{ number_format($total) }} pesanan
                <span class="mx-1">•</span> Dibuat {{ $waktuCetak }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('orders.index', request()->query()) }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('orders.exportCsv', request()->query()) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh CSV
            </a>
            <a href="{{ route('orders.exportPdf', request()->query()) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
        </div>
    </div>

    <div class="alert alert-light border small mb-4">
        <i class="bi bi-info-circle me-1"></i>
        Ekspor dibatasi maks. 10.000 baris untuk CSV dan 1.000 baris untuk PDF.
        Gunakan filter periode/status jika hasil terlalu besar.
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-3 order-report-stats">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 report-stat report-stat-total"><div class="card-body">
                <div class="text-muted small">Total Pesanan</div>
                <div class="fs-4 fw-bold">{{ number_format($total) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 report-stat report-stat-pending"><div class="card-body">
                <div class="text-muted small">Menunggu</div>
                <div class="fs-4 fw-bold text-warning">{{ number_format($pendingCount) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 report-stat report-stat-paid"><div class="card-body">
                <div class="text-muted small">Lunas</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($paidCount) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 report-stat report-stat-failed"><div class="card-body">
                <div class="text-muted small">Gagal / Kedaluwarsa</div>
                <div class="fs-4 fw-bold text-danger">{{ number_format($failedCount) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card order-report-revenue mb-4">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="order-report-revenue-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="order-report-revenue-label">Pendapatan Diterima</div>
                    <div class="order-report-revenue-value">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-4">
                <div>
                    <div class="text-muted small">Tingkat persetujuan</div>
                    <div class="fw-bold">{{ $approvalRate }}%</div>
                </div>
                <div>
                    <div class="text-muted small">Bukti terunggah</div>
                    <div class="fw-bold">{{ $proofUploadRate }}%</div>
                </div>
                <div>
                    <div class="text-muted small">Rata-rata verifikasi</div>
                    <div class="fw-bold">{{ $averageVerificationHours }} jam</div>
                </div>
                <div>
                    <div class="text-muted small">Melewati SLA 24 jam</div>
                    <div class="fw-bold text-danger">{{ number_format($slaBreachCount) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 d-flex flex-wrap align-items-center gap-3">
            <span class="d-inline-flex align-items-center gap-2 fw-bold text-dark">
                <i class="bi bi-funnel-fill text-primary"></i> Filter
            </span>
            <span>Status: <strong class="text-dark">{{ $filterStatus }}</strong></span>
            @if (!empty($search))
                <span>Pencarian: <strong class="text-dark">{{ $search }}</strong></span>
            @endif
            <span>Dicetak oleh <strong class="text-dark">{{ $dicetakOleh }}</strong></span>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:40px;">No</th>
                            @if ($hasColumn('date'))
                                <th>Tanggal</th>
                            @endif
                            @if ($hasColumn('order'))
                                <th>No. Pesanan</th>
                            @endif
                            @if ($hasColumn('customer') && $isAdmin)
                                <th>Pelanggan</th>
                            @endif
                            @if ($hasColumn('package'))
                                <th>Paket</th>
                            @endif
                            @if ($hasColumn('amount'))
                                <th class="text-end">Jumlah</th>
                            @endif
                            @if ($hasColumn('status'))
                                <th>Status</th>
                            @endif
                            @if ($hasColumn('proof'))
                                <th>Bukti</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $order)
                            <tr>
                                <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                @if ($hasColumn('date'))
                                    <td><small>{{ $order->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</small></td>
                                @endif
                                @if ($hasColumn('order'))
                                    <td class="fw-semibold">{{ $order->order_number }}</td>
                                @endif
                                @if ($hasColumn('customer') && $isAdmin)
                                    <td>
                                        <span class="d-block">{{ $order->user?->name ?? '-' }}</span>
                                        <small class="text-muted">{{ $order->user?->email ?? '-' }}</small>
                                    </td>
                                @endif
                                @if ($hasColumn('package'))
                                    <td>
                                        <span class="d-block">{{ $order->package?->name ?? '-' }}</span>
                                        <small class="text-muted">{{ $order->packageOption?->name ?? '' }}</small>
                                    </td>
                                @endif
                                @if ($hasColumn('amount'))
                                    <td class="text-end">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                @endif
                                @if ($hasColumn('status'))
                                    <td>
                                        @if ($order->status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif ($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif ($order->status === 'failed')
                                            <span class="badge bg-danger">Gagal</span>
                                        @elseif ($order->status === 'expired')
                                            <span class="badge bg-secondary">Kedaluwarsa</span>
                                        @else
                                            <span class="badge bg-secondary">Dibatalkan</span>
                                        @endif
                                    </td>
                                @endif
                                @if ($hasColumn('proof'))
                                    <td>
                                        @if ($order->proof_image_path)
                                            <span class="badge bg-info">Diunggah</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($visibleColumns) + 1 }}" class="text-center text-muted py-5">
                                    Tidak ada data untuk filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($orders->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="{{ count($visibleColumns) + 1 }}" class="ps-4 fw-bold text-end">
                                    Total Pendapatan Diterima: Rp {{ number_format($revenue, 0, ',', '.') }} | Outstanding DP: Rp {{ number_format($outstanding ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>

@push('after-style')
<style>
    .report-stat { position: relative; overflow: hidden; border-radius: 16px !important; }
    .report-stat::after { content: ''; position: absolute; inset: auto 0 0; height: 3px; background: var(--report-accent, #4b6b52); }
    .report-stat-total { --report-accent: #4b6b52; }
    .report-stat-pending { --report-accent: #f59e0b; }
    .report-stat-paid { --report-accent: #10b981; }
    .report-stat-failed { --report-accent: #ef4444; }
    .order-report-revenue { border-radius: 16px; border: 1px solid #dfe7ff; background: linear-gradient(110deg, #f7f9ff, #fff 58%, #f5fffb); }
    .order-report-revenue-icon { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; font-size: 22px; color: #fff; background: linear-gradient(135deg, #4b6b52, #6b8f6f); }
    .order-report-revenue-label { font-size: 12px; letter-spacing: .04em; text-transform: uppercase; color: #667085; font-weight: 600; }
    .order-report-revenue-value { font-size: 24px; font-weight: 700; color: #101828; }
</style>
@endpush

@endsection
