@extends('layouts.app')

@section('title', 'Checkout Penawaran Khusus')

@section('content')
<div class="container-fluid py-3 offer-module-page offer-checkout-page">
    <section class="analytics-hero mb-4" aria-labelledby="offer-checkout-title">
        <div class="analytics-hero__copy">
            <p class="analytics-hero__eyebrow"><i class="bi bi-shield-check me-2" aria-hidden="true"></i>Langkah terakhir</p>
            <h1 id="offer-checkout-title">Konfirmasi pesanan</h1>
            <p class="analytics-hero__subtitle">{{ $offer->title }} · {{ $selectedOption->label }} · Versi {{ $offer->version }}</p>
        </div>
        <div class="offer-module-actions"><a href="{{ route('package-offers.show', $offer->uuid) }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke penawaran</a></div>
        <div class="analytics-hero__decoration" aria-hidden="true"><i class="bi bi-bag-check"></i></div>
    </section>
    <div class="card shadow-sm border-0 offer-detail-card">
        <div class="card-header bg-transparent">
            <h2 class="h6 fw-bold mb-0"><i class="bi bi-card-checklist me-2 text-primary" aria-hidden="true"></i>Ringkasan pilihan</h2>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="small text-muted">Tanggal acara</div>
                    <div class="fw-semibold">{{ $offer->event_date?->translatedFormat('d F Y') }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-muted">Lokasi</div>
                    <div class="fw-semibold">{{ $offer->venue_note ?: 'Akan dikonfirmasi' }}</div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr><th>Layanan</th><th class="text-end">Jumlah</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedOption->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="offer-checkout-total">
                <div class="d-flex justify-content-between"><span>Subtotal</span><span>Rp {{ number_format($selectedOption->subtotal_amount, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between"><span>Diskon khusus</span><span>− Rp {{ number_format($selectedOption->discount_amount, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between border-top mt-2 pt-3 h5 fw-bold"><span>Total</span><span>Rp {{ number_format($selectedOption->total_amount, 0, ',', '.') }}</span></div>
            </div>
            <div class="alert alert-info border-0 mt-3 mb-4 offer-checkout-notice">
                <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
                DP {{ $selectedOption->deposit_percentage }}% yang dibayar sekarang: <strong>Rp {{ number_format($dueNow, 0, ',', '.') }}</strong>.
                Tanggal acara ditahan sementara selama pesanan menunggu pembayaran dan baru dikonfirmasi setelah DP diverifikasi.
                @if ($selectedOption->payment_terms_note)<div class="mt-2">{{ $selectedOption->payment_terms_note }}</div>@endif
            </div>

            <form method="POST" action="{{ route('package-offers.checkout.store', $offer->uuid) }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100"><i class="bi bi-lock-fill me-2" aria-hidden="true"></i>Buat Pesanan dan Lanjutkan Pembayaran</button>
            </form>
        </div>
    </div>
</div>
@endsection
