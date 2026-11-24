@extends('layouts.app')

@section('title', 'Analitik Penawaran Privat')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Analitik Penawaran Privat</h4>
            <p class="text-muted mb-0">Ringkasan permintaan, penawaran, dan respons pelanggan.</p>
        </div>
        <a href="{{ route('package-offers.index') }}" class="btn btn-outline-secondary">Kembali ke Penawaran</a>
    </div>

    <form method="GET" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-3">
                <label for="analytics-month" class="form-label">Bulan laporan</label>
                <input id="analytics-month" type="month" name="month" value="{{ $month }}" class="form-control">
            </div>
            <div class="col-sm-6 col-md-2"><button type="submit" class="btn btn-primary w-100">Tampilkan</button></div>
        </div>
    </form>

    <h5 class="fw-semibold mb-3">{{ $monthLabel }}</h5>
    <div class="row g-3 mb-4">
        @foreach ([
            ['Permintaan masuk', $requestCount, 'bi-inbox'],
            ['Penawaran terkirim', $sentCount, 'bi-send'],
            ['Disetujui', $acceptedCount, 'bi-check-circle'],
            ['Ditolak', $rejectedCount, 'bi-x-circle'],
            ['Pesanan dibuat', $orderCount, 'bi-bag-check'],
            ['Pesanan dengan pembayaran', $paidOrderCount, 'bi-cash-coin'],
        ] as [$label, $value, $icon])
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><div class="small text-muted">{{ $label }}</div><div class="fs-3 fw-bold">{{ number_format($value, 0, ',', '.') }}</div></div>
                    <i class="bi {{ $icon }} fs-2 text-primary"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold">Hasil dan waktu tindak lanjut</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Tingkat persetujuan</span><strong>{{ number_format($conversionRate, 1, ',', '.') }}%</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Penawaran menjadi pesanan</span><strong>{{ number_format($acceptedToOrderRate, 1, ',', '.') }}%</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Pesanan dengan pembayaran terverifikasi</span><strong>{{ number_format($orderPaymentRate, 1, ',', '.') }}%</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Pembayaran yang sudah diterima</span><strong>Rp {{ number_format($receivedAmount, 0, ',', '.') }}</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Rata-rata waktu hingga penawaran</span><strong>{{ $averageQuoteHours !== null ? number_format($averageQuoteHours, 1, ',', '.').' jam' : 'Belum ada data' }}</strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Rata-rata waktu respons persetujuan</span><strong>{{ $averageResponseHours !== null ? number_format($averageResponseHours, 1, ',', '.').' jam' : 'Belum ada data' }}</strong></div>
                    <hr>
                    <div class="fw-semibold mb-2">Status permintaan masuk</div>
                    @foreach (['submitted' => 'Menunggu ditinjau', 'in_review' => 'Sedang ditinjau', 'quoted' => 'Penawaran tersedia', 'closed' => 'Ditutup'] as $status => $label)
                    <div class="d-flex justify-content-between py-1"><span>{{ $label }}</span><span>{{ number_format($requestStatuses->get($status, 0), 0, ',', '.') }}</span></div>
                    @endforeach
                    <div class="small text-muted mt-3">Hasil pesanan dihitung dari penawaran yang dikirim pada bulan laporan.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold">Alasan penolakan</div>
                <div class="list-group list-group-flush">
                    @forelse ($rejectionReasons as $reason => $count)
                    <div class="list-group-item d-flex justify-content-between">
                        <span>{{ $reasonLabels[$reason] ?? 'Alasan lainnya' }}</span>
                        <strong>{{ number_format($count, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="list-group-item text-muted">Belum ada penolakan pada bulan ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
