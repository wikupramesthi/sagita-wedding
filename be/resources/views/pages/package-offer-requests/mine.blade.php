@extends('layouts.app')

@section('title', 'Permintaan Paket Saya')

@section('content')
<div class="container-fluid py-3 offer-module-page">
    <section class="analytics-hero mb-4" aria-labelledby="my-requests-title">
        <div class="analytics-hero__copy">
            <p class="analytics-hero__eyebrow"><i class="bi bi-inboxes me-2" aria-hidden="true"></i>Ruang pelanggan</p>
            <h1 id="my-requests-title">Permintaan Paket Saya</h1>
            <p class="analytics-hero__subtitle">Pantau proses peninjauan dan penawaran khusus untuk acara Anda.</p>
        </div>
        <div class="offer-module-actions"><a href="{{ route('package-offer-requests.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Ajukan Paket Khusus</a></div>
        <div class="analytics-hero__decoration" aria-hidden="true"><i class="bi bi-journal-check"></i></div>
    </section>

    <div class="row g-3">
        @forelse ($requests as $packageRequest)
        @php
            $statusLabel = match ($packageRequest->status) {
                'submitted' => 'Menunggu ditinjau',
                'in_review' => 'Sedang ditinjau',
                'quoted' => 'Penawaran tersedia',
                'closed' => 'Ditutup',
                default => 'Dalam proses',
            };
            $statusTone = match ($packageRequest->status) {
                'submitted' => 'warning',
                'in_review' => 'info',
                'quoted' => 'success',
                default => 'secondary',
            };
        @endphp
        <div class="col-12">
            <a href="{{ route('package-offer-requests.show', $packageRequest->uuid) }}" class="card text-decoration-none text-reset offer-list-card">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <span class="badge text-bg-{{ $statusTone }} mb-2">{{ $statusLabel }}</span>
                        <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($packageRequest->request_details, 110) }}</div>
                        <small class="text-muted">
                            Diajukan {{ $packageRequest->created_at->translatedFormat('d F Y') }}
                            @if ($packageRequest->event_date) · Acara {{ $packageRequest->event_date->translatedFormat('d F Y') }}@endif
                        </small>
                        @if ($packageRequest->unread_messages_count > 0)
                            <span class="badge rounded-pill text-bg-success d-block mt-2 align-self-start">
                                {{ $packageRequest->unread_messages_count }} pesan baru
                            </span>
                        @endif
                    </div>
                    <span class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-right-circle me-1" aria-hidden="true"></i>Lihat perkembangan</span>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-body text-center py-5 offer-module-empty">
                <i class="bi bi-chat-square-text text-primary fs-2"></i>
                <h5 class="fw-bold mt-3">Belum ada permintaan paket</h5>
                <p class="text-muted">Ceritakan kebutuhan acara Anda agar tim kami dapat menyiapkan penawaran khusus.</p>
                <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary align-self-center"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Ajukan Paket Khusus</a>
            </div>
        </div>
        @endforelse
    </div>
    @if ($requests->hasPages())<div class="mt-3">{{ $requests->links() }}</div>@endif
</div>
@endsection
