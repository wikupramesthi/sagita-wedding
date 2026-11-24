@extends('layouts.app')

@section('title', 'Detail Penawaran Khusus')

@section('content')
@php
    $statusLabel = match ($offer->display_status) {
        'draft' => 'Draf — hanya terlihat admin',
        'sent' => 'Menunggu tanggapan pelanggan',
        'revision_requested' => 'Pelanggan meminta revisi',
        'accepted' => 'Disetujui',
        'rejected' => 'Ditolak',
        'expired' => 'Kedaluwarsa',
        'superseded' => 'Digantikan versi baru',
        default => 'Dalam proses',
    };
    $statusTone = match ($offer->display_status) {
        'sent' => 'warning',
        'draft' => 'light',
        'revision_requested' => 'info',
        'accepted' => 'success',
        'rejected', 'expired' => 'secondary',
        default => 'light',
    };
@endphp
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="{{ $isAdmin ? route('package-offers.index') : route('package-offers.mine') }}" class="text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>{{ $isAdmin ? 'Kembali ke penawaran' : 'Kembali ke Penawaran Saya' }}
            </a>
            <h3 class="fw-bold mt-2 mb-1">{{ $offer->title }}</h3>
            <span class="badge text-bg-{{ $statusTone }}">{{ $statusLabel }}</span>
            <span class="text-muted small ms-2">Versi {{ $offer->version }}</span>
        </div>
        @if ($isAdmin)
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('package-offers.pdf', $offer->uuid) }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF
            </a>
            @if ($offer->status === 'draft')
            <form method="POST" action="{{ route('package-offers.discard', $offer->uuid) }}" onsubmit="return confirm('Hapus draf ini? Penawaran yang sebelumnya dikirim tidak akan berubah.')">
                @csrf
                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus Draf</button>
            </form>
            <a href="{{ route('package-offers.edit', $offer->uuid) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-1"></i>Ubah Draf
            </a>
            <form method="POST" action="{{ route('package-offers.send', $offer->uuid) }}" onsubmit="return confirm('Kirim penawaran ini kepada pelanggan dan mulai masa berlaku 7 hari?')">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i>Kirim Penawaran
                </button>
            </form>
            @elseif ($isLatest && ! in_array($offer->status, ['accepted', 'rejected', 'superseded'], true))
            <a href="{{ route('package-offers.create', ['revision_of' => $offer->uuid]) }}" class="btn btn-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>Buat Revisi
            </a>
            @endif
        </div>
        @endif
    </div>

    @if ($isAdmin)
    <div class="alert alert-light border">
        <strong>Penerima:</strong> {{ $offer->user?->name ?? '-' }} · {{ $offer->user?->email ?? '-' }}
        @if ($offer->source_request_uuid)
        <a href="{{ route('package-offer-requests.show', $offer->source_request_uuid) }}" class="ms-2">Lihat permintaan asal</a>
        @endif
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white fw-semibold">Ringkasan acara</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="small text-muted">Tanggal acara</div>
                            <div class="fw-semibold">{{ $offer->event_date?->translatedFormat('d F Y') ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small text-muted">Jumlah tamu</div>
                            <div class="fw-semibold">{{ $offer->guest_count ? number_format($offer->guest_count, 0, ',', '.').' orang' : 'Belum ditentukan' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="small text-muted">Lokasi acara</div>
                            <div class="fw-semibold">{{ $offer->venue_note ?: 'Akan dikonfirmasi' }}</div>
                        </div>
                    </div>
                    @if ($offer->description)
                    <hr>
                    <div class="small text-muted mb-1">Rincian dan kebutuhan</div>
                    <div class="text-break">{{ $offer->description }}</div>
                    @endif
                    @if ($offer->customer_note)
                    <div class="alert alert-light border mt-3 mb-0"><strong>Catatan untuk Anda:</strong><br>{{ $offer->customer_note }}</div>
                    @endif
                    @if ($isAdmin && $offer->admin_note)
                    <div class="alert alert-warning mt-3 mb-0"><strong>Catatan internal:</strong><br>{{ $offer->admin_note }}</div>
                    @endif
                    @if ($offer->revision_note)
                    <div class="alert alert-info mt-3 mb-0"><strong>Permintaan revisi:</strong><br>{{ $offer->revision_note }}</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white fw-semibold">Rincian layanan</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Layanan</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Harga satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offer->items as $item)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $item->name }}</span>
                                    @if ($item->description)<small class="d-block text-muted">{{ $item->description }}</small>@endif
                                </td>
                                <td class="text-end">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="3" class="text-end">Subtotal</th><td class="text-end">Rp {{ number_format($offer->subtotal_amount, 0, ',', '.') }}</td></tr>
                            <tr><th colspan="3" class="text-end">Diskon khusus</th><td class="text-end">− Rp {{ number_format($offer->discount_amount, 0, ',', '.') }}</td></tr>
                            <tr class="table-success"><th colspan="3" class="text-end">Total penawaran</th><th class="text-end">Rp {{ number_format($offer->total_amount, 0, ',', '.') }}</th></tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-body border-top">
                    <div><strong>DP awal:</strong> {{ $offer->deposit_percentage }}%</div>
                    @if ($offer->payment_terms_note)
                    <div class="mt-1"><strong>Ketentuan pembayaran:</strong> {{ $offer->payment_terms_note }}</div>
                    @endif
                    @if ($offer->status === 'sent')
                    <div class="small text-muted mt-2">Berlaku sampai {{ $offer->expires_at?->translatedFormat('d F Y, H:i') }}.</div>
                    @elseif ($offer->status === 'draft')
                    <div class="small text-muted mt-2">Belum dikirim. Pelanggan belum menerima notifikasi dan masa berlaku belum dimulai.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if (! $isAdmin && $isLatest && $offer->status === 'sent')
            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h5 class="fw-bold">Bagaimana pendapat Anda?</h5>
                    <p class="small text-muted">Setujui untuk lanjut checkout, atau minta perubahan sebelum penawaran berakhir.</p>
                    <form method="POST" action="{{ route('package-offers.respond', $offer->uuid) }}" class="mb-2">
                        @csrf
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="btn btn-primary w-100">Setujui dan Lanjut</button>
                    </form>
                    <form method="POST" action="{{ route('package-offers.respond', $offer->uuid) }}" class="mb-2">
                        @csrf
                        <input type="hidden" name="action" value="request_revision">
                        <label for="revision_note" class="form-label small">Apa yang ingin diubah?</label>
                        <textarea id="revision_note" name="revision_note" rows="3" class="form-control @error('revision_note') is-invalid @enderror" maxlength="3000" required>{{ old('revision_note') }}</textarea>
                        @error('revision_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <button type="submit" class="btn btn-outline-primary w-100 mt-2">Minta Revisi</button>
                    </form>
                    <form method="POST" action="{{ route('package-offers.respond', $offer->uuid) }}" onsubmit="return confirm('Tolak penawaran ini?')">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn btn-link text-danger w-100">Tolak Penawaran</button>
                    </form>
                </div>
            </div>
            @elseif (! $isAdmin && $offer->status === 'accepted')
            <div class="card border-success mb-3">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill text-success fs-2"></i>
                    <h5 class="fw-bold mt-2">Penawaran disetujui</h5>
                    @if ($activeOrder)
                    <a href="{{ route('orders.show', $activeOrder->uuid) }}" class="btn btn-success w-100">Lihat Pesanan</a>
                    @else
                    <a href="{{ route('package-offers.checkout', $offer->uuid) }}" class="btn btn-success w-100">Lanjutkan ke Checkout</a>
                    @endif
                </div>
            </div>
            @elseif (! $isAdmin && $offer->display_status === 'expired')
            <div class="alert alert-secondary">Penawaran ini sudah kedaluwarsa. Hubungi admin untuk meminta penawaran baru.</div>
            @endif

            @if ($versions->count() > 1)
            <div class="card">
                <div class="card-header bg-white fw-semibold">Riwayat versi</div>
                <div class="list-group list-group-flush">
                    @foreach ($versions as $version)
                    <a href="{{ route('package-offers.show', $version->uuid) }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                        <span>Versi {{ $version->version }}</span>
                        <span class="text-muted">{{ $version->created_at?->format('d M Y') }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
