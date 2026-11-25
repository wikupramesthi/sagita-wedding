@extends('layouts.app')

@section('title', 'Antrean Permintaan Paket')

@section('content')
<div class="container-fluid py-3 offer-module-page">
    <section class="offer-page-heading mb-4" aria-labelledby="requests-page-title">
        <div class="offer-page-heading__copy">
            <p class="offer-page-heading__eyebrow"><i class="bi bi-inboxes" aria-hidden="true"></i>Antrean pelanggan</p>
            <h1 id="requests-page-title">Permintaan Paket</h1>
            <p class="offer-page-heading__subtitle">Tinjau kebutuhan pelanggan dan ubah menjadi penawaran privat.</p>
        </div>
        <div class="offer-page-heading__actions">
            <a href="{{ route('package-offers.analytics') }}" class="btn btn-outline-primary"><i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i>Lihat Analitik</a>
            <a href="{{ route('package-offers.index') }}" class="btn btn-outline-primary"><i class="bi bi-file-earmark-text me-1" aria-hidden="true"></i>Lihat Penawaran</a>
        </div>
    </section>

    <form method="GET" class="card shadow-sm border-0 mb-4 offer-module-filter">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-3">
              <span class="analytics-section-icon"><i class="bi bi-funnel" aria-hidden="true"></i></span>
              <div><h2 class="h6 fw-bold mb-0">Filter permintaan</h2><p class="small text-muted mb-0">Cari permintaan berdasarkan status tindak lanjut.</p></div>
          </div>
          <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-4">
                <label for="request-status" class="form-label">Status</label>
                <select id="request-status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach (['submitted' => 'Menunggu ditinjau', 'in_review' => 'Sedang ditinjau', 'quoted' => 'Penawaran tersedia', 'closed' => 'Ditutup'] as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-2"><button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Terapkan</button></div>
          </div>
        </div>
    </form>

    <div class="card shadow-sm border-0 offer-module-table">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center gap-2">
            <h2 class="h6 fw-bold mb-0">Antrean tinjauan</h2>
            <span class="badge rounded-pill text-bg-light">{{ number_format($requests->total(), 0, ',', '.') }} permintaan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Pelanggan</th><th>Kebutuhan</th><th>Tanggal acara</th><th>Penanggung jawab</th><th>Tenggat tinjauan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
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
                    <tr>
                        <td>
                            <strong>{{ $packageRequest->user?->name ?? '-' }}</strong>
                            <small class="d-block text-muted">{{ $packageRequest->user?->email }}</small>
                            @if ($packageRequest->unread_messages_count > 0)
                                <span class="badge rounded-pill text-bg-success mt-1">{{ $packageRequest->unread_messages_count }} pesan baru</span>
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($packageRequest->request_details, 90) }}</td>
                        <td>{{ $packageRequest->event_date?->translatedFormat('d F Y') ?? '-' }}</td>
                        <td>{{ $packageRequest->assignedAdmin?->name ?? 'Belum ditugaskan' }}</td>
                        <td>
                            @if ($packageRequest->review_due_at)
                            <span class="{{ $packageRequest->review_due_at->isPast() && in_array($packageRequest->status, ['submitted', 'in_review'], true) ? 'text-danger fw-semibold' : '' }}">
                                {{ $packageRequest->review_due_at->translatedFormat('d M Y, H:i') }}
                                @if ($packageRequest->review_due_at->isPast() && in_array($packageRequest->status, ['submitted', 'in_review'], true))
                                <small class="d-block">Terlambat</small>
                                @endif
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge text-bg-{{ $statusTone }}">{{ $statusLabel }}</span></td>
                        <td class="text-end"><a href="{{ route('package-offer-requests.show', $packageRequest->uuid) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-right-circle me-1" aria-hidden="true"></i>Tinjau</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="offer-module-empty"><i class="bi bi-inboxes" aria-hidden="true"></i><strong>Belum ada permintaan paket</strong><span>Permintaan baru dari pelanggan akan muncul di antrean ini.</span></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())<div class="card-footer">{{ $requests->links() }}</div>@endif
    </div>
</div>
@endsection
