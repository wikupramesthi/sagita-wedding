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
    <div class="alert alert-light border">
        <strong>Pelanggan:</strong> {{ $packageRequest->user?->name }} · {{ $packageRequest->user?->email }}
        <div class="small mt-1">
            <strong>Penanggung jawab:</strong> {{ $packageRequest->assignedAdmin?->name ?? 'Belum ditugaskan' }}
            @if ($packageRequest->review_due_at)
            · <strong>Tenggat tinjauan:</strong> {{ $packageRequest->review_due_at->translatedFormat('d F Y, H:i') }}
            @endif
        </div>
    </div>
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
                    @if ($packageRequest->attachments->isNotEmpty())
                    <hr>
                    <strong>Lampiran pendukung</strong>
                    <div class="list-group mt-2">
                        @foreach ($packageRequest->attachments as $attachment)
                        <a href="{{ route('package-offer-requests.attachments.download', [$packageRequest->uuid, $attachment->uuid]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-2">
                            <span class="text-break"><i class="bi bi-paperclip me-2"></i>{{ $attachment->original_name }}</span>
                            <small class="text-muted flex-shrink-0">{{ number_format($attachment->size_bytes / 1048576, 2, ',', '.') }} MB</small>
                        </a>
                        @endforeach
                    </div>
                    @endif
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
                    <label for="assigned_admin_uuid" class="form-label">Tugaskan kepada admin</label>
                    <select id="assigned_admin_uuid" name="assigned_admin_uuid" class="form-select mb-3">
                        <option value="">Belum ditugaskan</option>
                        @foreach ($admins as $admin)
                        <option value="{{ $admin->uuid }}" @selected(old('assigned_admin_uuid', $packageRequest->assigned_admin_uuid) === $admin->uuid)>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_admin_uuid')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                    <label for="admin_note" class="form-label">Catatan internal admin</label>
                    <textarea id="admin_note" name="admin_note" rows="5" maxlength="5000" class="form-control">{{ old('admin_note', $packageRequest->admin_note) }}</textarea>
                    <button type="submit" class="btn btn-outline-primary w-100 mt-3">Simpan Tindak Lanjut</button>
                </div>
            </form>
        </div>
        @endif

        @if ($isAdmin && $packageRequest->workflowEvents->isNotEmpty())
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Riwayat aktivitas</div>
                <div class="list-group list-group-flush">
                    @foreach ($packageRequest->workflowEvents as $event)
                    <div class="list-group-item d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <strong>{{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $event->event_key)) }}</strong>
                            @if ($event->from_status || $event->to_status)
                            <span class="text-muted">· {{ $event->from_status ?: 'baru' }} → {{ $event->to_status ?: 'dihapus' }}</span>
                            @endif
                            <small class="d-block text-muted">{{ $event->actor?->name ?? 'Sistem' }}</small>
                        </div>
                        <small class="text-muted">{{ $event->created_at?->translatedFormat('d F Y, H:i') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
