@extends('layouts.app')

@section('title', 'Penawaran Khusus')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Penawaran Paket Khusus</h4>
            <p class="text-muted mb-0">Kelola penawaran privat untuk setiap pelanggan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('package-offer-requests.index') }}" class="btn btn-outline-primary">
                Permintaan pelanggan
            </a>
            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">Kembali ke Paket</a>
            <a href="{{ route('package-offers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Buat Penawaran
            </a>
        </div>
    </div>

    <form method="GET" class="card card-body mb-3">
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
                <button class="btn btn-outline-primary w-100" type="submit">Terapkan</button>
            </div>
        </div>
    </form>

    <div class="card">
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
                            <a href="{{ route('package-offers.show', $offer->uuid) }}" class="btn btn-sm btn-outline-primary">Buka</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada penawaran khusus.</td>
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
