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
                    @if ($offer->status === 'rejected' && $offer->rejection_reason)
                    <div class="alert alert-light border mt-3 mb-0">
                        <strong>Alasan penolakan:</strong>
                        {{ ['price' => 'Anggaran atau harga', 'services' => 'Layanan yang ditawarkan', 'date' => 'Tanggal atau jadwal', 'other' => 'Alasan lainnya'][$offer->rejection_reason] ?? 'Alasan lainnya' }}
                        @if ($offer->rejection_note)<div class="mt-1">{{ $offer->rejection_note }}</div>@endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="vstack gap-3">
                @foreach ($offer->options as $option)
                <div class="card {{ $offer->selected_option_uuid === $option->uuid ? 'border-success' : '' }}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="fw-bold mb-0">{{ $option->label }}</h5>
                            @if ($option->description)<div class="small text-muted mt-1">{{ $option->description }}</div>@endif
                        </div>
                        @if ($offer->selected_option_uuid === $option->uuid)
                        <span class="badge text-bg-success">Pilihan Anda</span>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Layanan</th><th class="text-end">Jumlah</th><th class="text-end">Harga satuan</th><th class="text-end">Subtotal</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($option->items as $item)
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
                        </table>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-between"><span>Subtotal</span><span>Rp {{ number_format($option->subtotal_amount, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between"><span>Diskon</span><span>− Rp {{ number_format($option->discount_amount, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between border-top mt-2 pt-2 fw-bold"><span>Total</span><span>Rp {{ number_format($option->total_amount, 0, ',', '.') }}</span></div>
                        <div class="small mt-2"><strong>DP awal:</strong> {{ $option->deposit_percentage }}%</div>
                        @if ($option->payment_terms_note)<div class="small mt-1"><strong>Ketentuan pembayaran:</strong> {{ $option->payment_terms_note }}</div>@endif
                        @if ($offer->status === 'sent')
                        <div class="small text-muted mt-2">Berlaku sampai {{ $offer->expires_at?->translatedFormat('d F Y, H:i') }}.</div>
                        @elseif ($offer->status === 'draft')
                        <div class="small text-muted mt-2">Belum dikirim. Pelanggan belum menerima notifikasi dan masa berlaku belum dimulai.</div>
                        @endif
                        @if (! $isAdmin && $isLatest && $offer->status === 'sent')
                        <form method="POST" action="{{ route('package-offers.respond', $offer->uuid) }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <input type="hidden" name="option_uuid" value="{{ $option->uuid }}">
                            <button type="submit" class="btn btn-primary w-100">Pilih {{ $option->label }} dan Lanjut</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
                @if ($isAdmin && $offer->status === 'draft')
                <div class="small text-muted">Draf hanya terlihat oleh admin. Pelanggan akan menerima notifikasi setelah penawaran dikirim.</div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            @if (! $isAdmin && $isLatest && $offer->status === 'sent')
            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h5 class="fw-bold">Bagaimana pendapat Anda?</h5>
                    <p class="small text-muted">Pilih salah satu opsi di samping untuk lanjut checkout, atau minta perubahan sebelum penawaran berakhir.</p>
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
                        <label for="rejection_reason" class="form-label small">Alasan penolakan</label>
                        <select id="rejection_reason" name="rejection_reason" class="form-select mb-2">
                            <option value="price">Anggaran atau harga</option>
                            <option value="services">Layanan yang ditawarkan</option>
                            <option value="date">Tanggal atau jadwal</option>
                            <option value="other">Alasan lainnya</option>
                        </select>
                        <textarea name="rejection_note" rows="2" class="form-control mb-2" maxlength="2000" placeholder="Catatan tambahan (opsional)"></textarea>
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
                <div class="card-header bg-white fw-semibold">Perbandingan versi penawaran</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Versi</th><th>Status</th><th>Pilihan dan harga</th><th>Layanan</th><th>Dibuat</th></tr>
                        </thead>
                        <tbody>
                    @foreach ($versions as $version)
                    <tr>
                        <td>
                            <a href="{{ route('package-offers.show', $version->uuid) }}" class="fw-semibold">Versi {{ $version->version }}</a>
                            <div class="small text-muted">{{ $version->title }}</div>
                        </td>
                        <td>{{ ['draft' => 'Draf', 'sent' => 'Terkirim', 'accepted' => 'Disetujui', 'rejected' => 'Ditolak', 'expired' => 'Kedaluwarsa', 'superseded' => 'Digantikan', 'revision_requested' => 'Menunggu revisi'][$version->status] ?? 'Dalam proses' }}</td>
                        <td>
                            @forelse ($version->options as $versionOption)
                                <div class="small {{ !$loop->last ? 'mb-2' : '' }}">
                                    <strong>{{ $versionOption->label }}</strong>
                                    <span class="d-block">Rp {{ number_format($versionOption->total_amount, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <span class="small">Rp {{ number_format($version->total_amount, 0, ',', '.') }}</span>
                            @endforelse
                        </td>
                        <td>
                            @forelse ($version->options as $versionOption)
                                <div class="small {{ !$loop->last ? 'mb-2' : '' }}">
                                    @if ($version->options->count() > 1)<strong>{{ $versionOption->label }}:</strong> @endif
                                    {{ $versionOption->items->pluck('name')->join(', ') ?: 'Belum ada rincian layanan' }}
                                </div>
                            @empty
                                <span class="small">{{ $version->items->pluck('name')->join(', ') ?: 'Belum ada rincian layanan' }}</span>
                            @endforelse
                        </td>
                        <td class="text-nowrap">{{ $version->created_at?->translatedFormat('d M Y') }}</td>
                    </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if ($isAdmin && $offer->workflowEvents->isNotEmpty())
            <div class="card mt-3">
                <div class="card-header bg-white fw-semibold">Riwayat aktivitas</div>
                <div class="list-group list-group-flush">
                    @foreach ($offer->workflowEvents as $event)
                    <div class="list-group-item">
                        <strong>{{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $event->event_key)) }}</strong>
                        <small class="d-block text-muted">{{ $event->actor?->name ?? 'Sistem' }} · {{ $event->created_at?->translatedFormat('d F Y, H:i') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
