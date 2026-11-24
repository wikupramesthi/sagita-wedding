@extends('layouts.app')

@section('title', 'Checkout Penawaran Khusus')

@section('content')
<div class="container py-4" style="max-width: 860px;">
    <a href="{{ route('package-offers.show', $offer->uuid) }}" class="text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke penawaran
    </a>
    <div class="card mt-3">
        <div class="card-header bg-white">
            <h4 class="fw-bold mb-1">Konfirmasi pesanan</h4>
            <div class="text-muted">{{ $offer->title }} · {{ $selectedOption->label }} · Versi {{ $offer->version }}</div>
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

            <div class="d-flex justify-content-between"><span>Subtotal</span><span>Rp {{ number_format($selectedOption->subtotal_amount, 0, ',', '.') }}</span></div>
            <div class="d-flex justify-content-between"><span>Diskon khusus</span><span>− Rp {{ number_format($selectedOption->discount_amount, 0, ',', '.') }}</span></div>
            <div class="d-flex justify-content-between border-top mt-2 pt-3 h5 fw-bold"><span>Total</span><span>Rp {{ number_format($selectedOption->total_amount, 0, ',', '.') }}</span></div>
            <div class="alert alert-info mt-3 mb-4">
                DP {{ $selectedOption->deposit_percentage }}% yang dibayar sekarang: <strong>Rp {{ number_format($dueNow, 0, ',', '.') }}</strong>.
                Tanggal acara ditahan sementara selama pesanan menunggu pembayaran dan baru dikonfirmasi setelah DP diverifikasi.
                @if ($selectedOption->payment_terms_note)<div class="mt-2">{{ $selectedOption->payment_terms_note }}</div>@endif
            </div>

            <form method="POST" action="{{ route('package-offers.checkout.store', $offer->uuid) }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg w-100">Buat Pesanan dan Lanjutkan Pembayaran</button>
            </form>
        </div>
    </div>
</div>
@endsection
