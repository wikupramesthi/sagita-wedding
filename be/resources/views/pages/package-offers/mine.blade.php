@extends('layouts.app')

@section('title', 'Penawaran Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Penawaran Saya</h3>
            <p class="text-muted mb-0">Penawaran khusus yang ditujukan untuk Anda.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('package-offer-requests.mine') }}" class="btn btn-outline-primary">Permintaan Saya</a>
            <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajukan Paket Khusus</a>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($offers as $offer)
        @php
        $statusLabel = match ($offer->display_status) {
            'sent' => 'Menunggu tanggapan Anda',
            'revision_requested' => 'Sedang ditinjau admin',
            'accepted' => 'Disetujui',
            'rejected' => 'Ditolak',
            'expired' => 'Kedaluwarsa',
            'superseded' => 'Digantikan',
            default => 'Dalam proses',
        };
        $statusTone = match ($offer->display_status) {
            'sent' => 'warning',
            'revision_requested' => 'info',
            'accepted' => 'success',
            'rejected', 'expired' => 'secondary',
            default => 'light',
        };
        @endphp
        <div class="col-12 col-lg-6">
            <article class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <span class="badge text-bg-{{ $statusTone }} mb-2">{{ $statusLabel }}</span>
                            <h5 class="fw-bold">{{ $offer->title }}</h5>
                        </div>
                        <span class="small text-muted">Versi {{ $offer->version }}</span>
                    </div>
                    <p class="text-muted small mb-2">Tanggal acara: {{ $offer->event_date?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</p>
                    <p class="h5 fw-bold text-success mb-3">Rp {{ number_format($offer->total_amount, 0, ',', '.') }}</p>
                    @if ($offer->display_status === 'sent')
                    <p class="small text-muted">Berlaku sampai {{ $offer->expires_at?->translatedFormat('d F Y') }}.</p>
                    @endif
                    <a href="{{ route('package-offers.show', $offer->uuid) }}" class="btn btn-outline-primary">Lihat Penawaran</a>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-body text-center py-5">
                <i class="bi bi-file-earmark-text display-5 text-muted mb-3"></i>
                <h5>Belum ada penawaran khusus</h5>
                <p class="text-muted">Jika admin menyiapkan penawaran untuk Anda, penawaran akan muncul di sini dan Anda akan menerima notifikasi.</p>
                <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary align-self-center">Ajukan Paket Khusus</a>
            </div>
        </div>
        @endforelse
    </div>

    @if ($offers->hasPages())
    <div class="mt-4">{{ $offers->links() }}</div>
    @endif
</div>
@endsection
