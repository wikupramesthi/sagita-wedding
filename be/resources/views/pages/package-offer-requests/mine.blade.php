@extends('layouts.app')

@section('title', 'Permintaan Paket Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Permintaan Paket Saya</h3>
            <p class="text-muted mb-0">Pantau proses peninjauan dan penawaran khusus untuk acara Anda.</p>
        </div>
        <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajukan Paket Khusus</a>
    </div>

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
            <a href="{{ route('package-offer-requests.show', $packageRequest->uuid) }}" class="card text-decoration-none text-reset">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <span class="badge text-bg-{{ $statusTone }} mb-2">{{ $statusLabel }}</span>
                        <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($packageRequest->request_details, 110) }}</div>
                        <small class="text-muted">
                            Diajukan {{ $packageRequest->created_at->translatedFormat('d F Y') }}
                            @if ($packageRequest->event_date) · Acara {{ $packageRequest->event_date->translatedFormat('d F Y') }}@endif
                        </small>
                    </div>
                    <span class="btn btn-sm btn-outline-primary">Lihat perkembangan</span>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-body text-center py-5">
                <i class="bi bi-chat-square-text text-primary fs-2"></i>
                <h5 class="fw-bold mt-3">Belum ada permintaan paket</h5>
                <p class="text-muted">Ceritakan kebutuhan acara Anda agar tim kami dapat menyiapkan penawaran khusus.</p>
                <a href="{{ route('package-offer-requests.create') }}" class="btn btn-primary align-self-center">Ajukan Paket Khusus</a>
            </div>
        </div>
        @endforelse
    </div>
    @if ($requests->hasPages())<div class="mt-3">{{ $requests->links() }}</div>@endif
</div>
@endsection
