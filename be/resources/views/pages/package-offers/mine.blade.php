@extends('layouts.app')

@section('title', 'Penawaran Saya')

@section('content')
<div class="container-fluid py-3 offer-module-page">
    <section class="analytics-hero mb-4" aria-labelledby="my-offers-title">
        <div class="analytics-hero__copy">
            <p class="analytics-hero__eyebrow"><i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i>Ruang pelanggan</p>
            <h1 id="my-offers-title">Penawaran Saya</h1>
            <p class="analytics-hero__subtitle">Tinjau rincian penawaran privat yang disiapkan khusus untuk Anda.</p>
        </div>
        <div class="offer-module-actions">
            <a href="{{ route('package-offer-requests.mine') }}" class="btn btn-light"><i class="bi bi-inboxes me-1" aria-hidden="true"></i>Permintaan Saya</a>
            <a href="{{ route('package-offer-requests.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Ajukan Paket Khusus</a>
        </div>
        <div class="analytics-hero__decoration" aria-hidden="true"><i class="bi bi-envelope-paper"></i></div>
    </section>

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
            <article class="card h-100 shadow-sm offer-list-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <span class="badge text-bg-{{ $statusTone }} mb-2">{{ $statusLabel }}</span>
                            <h2 class="h5 fw-bold offer-list-card__title">{{ $offer->title }}</h2>
                        </div>
                        <span class="offer-version-badge">Versi {{ $offer->version }}</span>
                    </div>
                    <div class="offer-list-card__meta"><i class="bi bi-calendar-event" aria-hidden="true"></i><span>Tanggal acara: {{ $offer->event_date?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</span></div>
                    <p class="offer-list-card__price mb-3">Rp {{ number_format($offer->total_amount, 0, ',', '.') }}</p>
                    @if ($offer->display_status === 'sent')
                    <p class="small text-muted">Berlaku sampai {{ $offer->expires_at?->translatedFormat('d F Y') }}.</p>
                    @endif
                    <a href="{{ route('package-offers.show', $offer->uuid) }}" class="btn btn-primary"><i class="bi bi-arrow-right-circle me-1" aria-hidden="true"></i>Lihat Penawaran</a>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-body text-center py-5 offer-module-empty">
                <i class="bi bi-file-earmark-text display-5 text-muted mb-3"></i>
                <h5>Belum ada penawaran khusus</h5>
                <p class="text-muted">Jika admin menyiapkan penawaran untuk Anda, penawaran akan muncul di sini dan Anda akan menerima notifikasi.</p>
                <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary align-self-center"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Ajukan Paket Khusus</a>
            </div>
        </div>
        @endforelse
    </div>

    @if ($offers->hasPages())
    <div class="mt-4">{{ $offers->links() }}</div>
    @endif
</div>
@endsection
