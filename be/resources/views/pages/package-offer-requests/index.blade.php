@extends('layouts.app')

@section('title', 'Antrean Permintaan Paket')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Antrean Permintaan Paket</h4>
            <p class="text-muted mb-0">Tinjau kebutuhan pelanggan dan ubah menjadi penawaran privat.</p>
        </div>
        <a href="{{ route('package-offers.index') }}" class="btn btn-outline-secondary">Lihat Penawaran</a>
    </div>

    <form method="GET" class="card card-body mb-3">
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
            <div class="col-sm-6 col-md-2"><button class="btn btn-outline-primary w-100" type="submit">Terapkan</button></div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Pelanggan</th><th>Kebutuhan</th><th>Tanggal acara</th><th>Diajukan</th><th>Status</th><th></th></tr>
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
                        <td><strong>{{ $packageRequest->user?->name ?? '-' }}</strong><small class="d-block text-muted">{{ $packageRequest->user?->email }}</small></td>
                        <td>{{ \Illuminate\Support\Str::limit($packageRequest->request_details, 90) }}</td>
                        <td>{{ $packageRequest->event_date?->translatedFormat('d F Y') ?? '-' }}</td>
                        <td>{{ $packageRequest->created_at->translatedFormat('d M Y') }}</td>
                        <td><span class="badge text-bg-{{ $statusTone }}">{{ $statusLabel }}</span></td>
                        <td class="text-end"><a href="{{ route('package-offer-requests.show', $packageRequest->uuid) }}" class="btn btn-sm btn-outline-primary">Tinjau</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Belum ada permintaan paket khusus.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())<div class="card-footer">{{ $requests->links() }}</div>@endif
    </div>
</div>
@endsection
