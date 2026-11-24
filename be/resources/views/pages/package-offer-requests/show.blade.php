@extends('layouts.app')

@section('title', 'Detail Permintaan Paket')

@section('content')
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
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ $isAdmin ? route('package-offer-requests.index') : route('package-offer-requests.mine') }}" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>{{ $isAdmin ? 'Kembali ke antrean' : 'Kembali ke permintaan saya' }}
        </a>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
            <div>
                <h3 class="fw-bold mb-1">Detail Permintaan Paket</h3>
                <span class="badge text-bg-{{ $statusTone }}">{{ $statusLabel }}</span>
            </div>
            @if ($isAdmin)
            <div class="d-flex flex-wrap gap-2">
                @if (in_array($packageRequest->status, ['submitted', 'in_review'], true))
                <form method="POST" action="{{ route('package-offer-requests.convert', $packageRequest->uuid) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit"><i class="bi bi-file-earmark-plus me-1"></i>{{ $packageRequest->offer?->status === 'draft' ? 'Lanjutkan Draf' : 'Buat Draf Penawaran' }}</button>
                </form>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if ($isAdmin)
    <div class="alert alert-light border"><strong>Pelanggan:</strong> {{ $packageRequest->user?->name }} · {{ $packageRequest->user?->email }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Kebutuhan pelanggan</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><small class="text-muted d-block">Tanggal acara</small><strong>{{ $packageRequest->event_date?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</strong></div>
                        <div class="col-sm-6"><small class="text-muted d-block">Jumlah tamu</small><strong>{{ $packageRequest->guest_count ? number_format($packageRequest->guest_count, 0, ',', '.').' orang' : 'Belum ditentukan' }}</strong></div>
                        <div class="col-sm-6"><small class="text-muted d-block">Lokasi</small><strong>{{ $packageRequest->venue_note ?: 'Belum ditentukan' }}</strong></div>
                        <div class="col-sm-6"><small class="text-muted d-block">Anggaran</small><strong>
                            @if ($packageRequest->budget_min !== null || $packageRequest->budget_max !== null)
                                @if ($packageRequest->budget_min !== null)Rp {{ number_format($packageRequest->budget_min, 0, ',', '.') }}@endif
                                @if ($packageRequest->budget_min !== null && $packageRequest->budget_max !== null) – @endif
                                @if ($packageRequest->budget_max !== null)Rp {{ number_format($packageRequest->budget_max, 0, ',', '.') }}@endif
                            @else
                                Belum ditentukan
                            @endif
                        </strong></div>
                    </div>
                    <hr>
                    <div class="text-break">{{ $packageRequest->request_details }}</div>
                    <small class="d-block text-muted mt-3">Diajukan {{ $packageRequest->created_at->translatedFormat('d F Y, H:i') }}</small>
                    @if ($isAdmin && $packageRequest->admin_note)
                    <div class="alert alert-warning mt-3 mb-0"><strong>Catatan admin:</strong><br>{{ $packageRequest->admin_note }}</div>
                    @endif
                </div>
            </div>

            @if ($packageRequest->offer && ($isAdmin || $packageRequest->offer->status !== 'draft'))
            <div class="card mt-3">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div><strong>Penawaran terkait</strong><small class="d-block text-muted">{{ $packageRequest->offer->title }} · Versi {{ $packageRequest->offer->version }}</small></div>
                    <a href="{{ route('package-offers.show', $packageRequest->offer->uuid) }}" class="btn btn-outline-primary btn-sm">Buka Penawaran</a>
                </div>
            </div>
            @endif
        </div>

        @if ($isAdmin && in_array($packageRequest->status, ['submitted', 'in_review'], true))
        <div class="col-lg-4">
            <form method="POST" action="{{ route('package-offer-requests.status', $packageRequest->uuid) }}" class="card">
                @csrf
                <div class="card-header bg-white fw-semibold">Tindak lanjut</div>
                <div class="card-body">
                    <label for="status" class="form-label">Status permintaan</label>
                    <select id="status" name="status" class="form-select mb-3" required>
                        <option value="in_review" @selected($packageRequest->status === 'in_review')>Sedang ditinjau</option>
                        <option value="closed">Tutup permintaan</option>
                    </select>
                    <label for="admin_note" class="form-label">Catatan internal admin</label>
                    <textarea id="admin_note" name="admin_note" rows="5" maxlength="5000" class="form-control">{{ old('admin_note', $packageRequest->admin_note) }}</textarea>
                    <button type="submit" class="btn btn-outline-primary w-100 mt-3">Simpan Tindak Lanjut</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
