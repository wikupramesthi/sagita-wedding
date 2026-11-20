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

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">Total Pesanan</div>
                <div class="fs-4 fw-bold">{{ number_format($total) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">Menunggu</div>
                <div class="fs-4 fw-bold text-warning">{{ number_format($pendingCount) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">Lunas</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($paidCount) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">Pendapatan (Lunas)</div>
                <div class="fs-5 fw-bold text-primary">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 d-flex flex-wrap align-items-center gap-3">
            <span class="d-inline-flex align-items-center gap-2 fw-bold text-dark">
                <i class="bi bi-funnel-fill text-primary"></i> Filter
            </span>
            <span>Status: <strong class="text-dark">{{ $filterStatus }}</strong></span>
            <span>Tipe: <strong class="text-dark">{{ $filterType }}</strong></span>
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
                            <th>Tanggal</th>
                            <th>No. Pesanan</th>
                            @if ($isAdmin)
                                <th>Pelanggan</th>
                            @endif
                            <th>Paket</th>
                            <th class="text-end">Jumlah</th>
                            <th>Status</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $order)
                            <tr>
                                <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                <td><small>{{ $order->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</small></td>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                @if ($isAdmin)
                                    <td>
                                        <span class="d-block">{{ $order->user?->name ?? '-' }}</span>
                                        <small class="text-muted">{{ $order->user?->email ?? '-' }}</small>
                                    </td>
                                @endif
                                <td>
                                    <span class="d-block">{{ $order->package?->name ?? 'Kelas Tunggal' }}</span>
                                    <small class="text-muted">{{ $order->packageOption?->name ?? '' }}</small>
                                </td>
                                <td class="text-end">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
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
                                <td>
                                    @if ($order->proof_image_path)
                                        <span class="badge bg-info">Diunggah</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center text-muted py-5">
                                    Tidak ada data untuk filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($orders->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="{{ $isAdmin ? 6 : 5 }}" class="ps-4 fw-bold text-end">Total Pendapatan (Lunas)</td>
                                <td class="text-end fw-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
