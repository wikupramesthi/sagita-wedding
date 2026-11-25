@extends('layouts.app')

@section('title', 'Penawaran Khusus')

@section('content')
<div class="container-fluid py-3 offer-module-page">
    <section class="offer-page-heading mb-4" aria-labelledby="offers-page-title">
        <div class="offer-page-heading__copy">
            <p class="offer-page-heading__eyebrow"><i class="bi bi-gem" aria-hidden="true"></i>Paket privat</p>
            <h1 id="offers-page-title">Penawaran Paket Khusus</h1>
            <p class="offer-page-heading__subtitle">Kelola penawaran privat dan pantau respons setiap pelanggan.</p>
        </div>
        <div class="offer-page-heading__actions">
            <a href="{{ route('package-offers.analytics') }}" class="btn btn-outline-primary"><i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i>Analitik</a>
            <a href="{{ route('package-offer-requests.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-inboxes me-1" aria-hidden="true"></i>Permintaan pelanggan
            </a>
            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke Paket</a>
            <a href="{{ route('package-offers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>Buat Penawaran
            </a>
        </div>
    </section>

    <form method="GET" class="card shadow-sm border-0 mb-4 offer-module-filter">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-3">
              <span class="analytics-section-icon"><i class="bi bi-funnel" aria-hidden="true"></i></span>
              <div><h2 class="h6 fw-bold mb-0">Filter penawaran</h2><p class="small text-muted mb-0">Persempit daftar berdasarkan status terkini.</p></div>
          </div>
          <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-4">
                <label for="offer-status" class="form-label">Status</label>
                <select id="offer-status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach (['draft' => 'Draf admin', 'sent' => 'Menunggu pelanggan', 'revision_requested' => 'Menunggu revisi', 'accepted' => 'Disetujui', 'rejected' => 'Ditolak', 'expired' => 'Kedaluwarsa'] as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Terapkan</button>
            </div>
          </div>
        </div>
    </form>

    <div class="card shadow-sm border-0 offer-module-table">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center gap-2">
            <h2 class="h6 fw-bold mb-0">Daftar penawaran</h2>
            <span class="badge rounded-pill text-bg-light">{{ number_format($offers->total(), 0, ',', '.') }} penawaran</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Penawaran</th>
                        <th>Pelanggan</th>
                        <th>Tanggal acara</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                        <th>Masa berlaku</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($offers as $offer)
                    @php
                    $statusLabel = match ($offer->display_status) {
                        'sent' => 'Menunggu pelanggan',
                        'revision_requested' => 'Menunggu revisi',
                        'accepted' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kedaluwarsa',
                        'superseded' => 'Digantikan',
                        default => 'Draf',
                    };
                    $statusTone = match ($offer->display_status) {
                        'sent' => 'warning',
                        'revision_requested' => 'info',
                        'accepted' => 'success',
                        'rejected', 'expired' => 'secondary',
                        default => 'light',
                    };
                    @endphp
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $offer->title }}</span>
                            <small class="d-block text-muted">Versi {{ $offer->version }}</small>
                            <small class="d-block text-muted">{{ $offer->options->count() }} pilihan</small>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $offer->user?->name ?? '-' }}</span>
                            <small class="d-block text-muted">{{ $offer->user?->email ?? '-' }}</small>
                        </td>
                        <td>{{ $offer->event_date?->translatedFormat('d F Y') ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($offer->total_amount, 0, ',', '.') }}</td>
                        <td><span class="badge text-bg-{{ $statusTone }}">{{ $statusLabel }}</span></td>
                        <td>{{ $offer->expires_at?->translatedFormat('d F Y') ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('package-offers.show', $offer->uuid) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1" aria-hidden="true"></i>Buka</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="offer-module-empty"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i><strong>Belum ada penawaran khusus</strong><span>Buat penawaran untuk mulai mengelola kebutuhan pelanggan.</span><a href="{{ route('package-offers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Buat Penawaran</a></div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($offers->hasPages())
        <div class="card-footer">{{ $offers->links() }}</div>
        @endif
    </div>
</div>
@endsection
