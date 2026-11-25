@extends('layouts.app')

@section('title', 'Analitik Penawaran Privat')

@section('content')
<div class="container-fluid py-3 analytics-page">
    <section class="offer-page-heading mb-4" aria-labelledby="offer-analytics-title">
        <div class="offer-page-heading__copy">
            <p class="offer-page-heading__eyebrow"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i>Analitik paket privat</p>
            <h1 id="offer-analytics-title">Kinerja penawaran</h1>
            <p class="offer-page-heading__subtitle">Pantau permintaan, keputusan pelanggan, dan pembayaran dalam satu ringkasan.</p>
            <span class="offer-page-heading__meta"><i class="bi bi-calendar3" aria-hidden="true"></i>{{ $monthLabel }}</span>
        </div>
        <div class="offer-page-heading__actions">
            <a href="{{ route('package-offers.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke penawaran</a>
        </div>
    </section>

    <form method="GET" class="card shadow-sm border-0 mb-4 analytics-filter">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-3">
              <span class="analytics-section-icon"><i class="bi bi-sliders2" aria-hidden="true"></i></span>
              <div><h2 class="h6 fw-bold mb-0">Periode laporan</h2><p class="small text-muted mb-0">Pilih bulan untuk memperbarui seluruh metrik.</p></div>
          </div>
          <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-3">
                <label for="analytics-month" class="form-label">Bulan laporan</label>
                <input id="analytics-month" type="month" name="month" value="{{ $month }}" class="form-control">
            </div>
            <div class="col-sm-6 col-md-2"><button type="submit" class="btn btn-primary w-100">Tampilkan</button></div>
          </div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        @foreach ([
            ['Permintaan masuk', $requestCount, 'bi-inbox', 'purple', 'Permintaan pelanggan'],
            ['Penawaran terkirim', $sentCount, 'bi-send', 'blue', 'Dikirim bulan ini'],
            ['Disetujui', $acceptedCount, 'bi-check-circle', 'green', 'Respons pelanggan'],
            ['Ditolak', $rejectedCount, 'bi-x-circle', 'red', 'Respons pelanggan'],
            ['Pesanan dibuat', $orderCount, 'bi-bag-check', 'cyan', 'Dari penawaran'],
            ['Pesanan dengan pembayaran', $paidOrderCount, 'bi-cash-coin', 'turquoise', 'Status saat ini'],
        ] as [$label, $value, $icon, $tone, $hint])
        <div class="col-12 col-md-4">
            <div class="card h-100 analytics-stat-card" data-tone="{{ $tone }}">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stats-icon {{ $tone }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
                    <div class="min-w-0">
                        <div class="analytics-stat-label">{{ $label }}</div>
                        <div class="analytics-stat-value">{{ number_format($value, 0, ',', '.') }}</div>
                        <div class="analytics-stat-hint">{{ $hint }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 analytics-panels">
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="analytics-section-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                        <div><h2 class="h6 fw-bold mb-0">Hasil dan tindak lanjut</h2><p class="small text-muted mb-0">Konversi serta kecepatan respons pelanggan.</p></div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ([
                        ['Tingkat persetujuan', $conversionRate, 'Penawaran yang disetujui'],
                        ['Penawaran menjadi pesanan', $acceptedToOrderRate, 'Penawaran disetujui yang berlanjut ke pesanan'],
                        ['Pesanan dengan pembayaran terverifikasi', $orderPaymentRate, 'Pesanan yang sudah menerima pembayaran'],
                    ] as [$label, $rate, $hint])
                    <div class="analytics-rate-row">
                        <div class="d-flex justify-content-between gap-3">
                            <span class="fw-semibold">{{ $label }}</span>
                            <strong>{{ number_format($rate, 1, ',', '.') }}%</strong>
                        </div>
                        <div class="progress analytics-progress" role="progressbar" aria-label="{{ $label }}" aria-valuenow="{{ min(100, max(0, $rate)) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ min(100, max(0, $rate)) }}%"></div>
                        </div>
                        <div class="small text-muted">{{ $hint }}</div>
                    </div>
                    @endforeach

                    <div class="analytics-insights">
                        <div class="analytics-insight">
                            <span class="analytics-section-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span>
                            <div><span class="small text-muted d-block">Pembayaran yang sudah diterima</span><strong>Rp {{ number_format($receivedAmount, 0, ',', '.') }}</strong></div>
                        </div>
                        <div class="analytics-insight">
                            <span class="analytics-section-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
                            <div><span class="small text-muted d-block">Rata-rata waktu hingga penawaran</span><strong>{{ $averageQuoteHours !== null ? number_format($averageQuoteHours, 1, ',', '.').' jam' : 'Belum ada data' }}</strong></div>
                        </div>
                        <div class="analytics-insight">
                            <span class="analytics-section-icon"><i class="bi bi-reply" aria-hidden="true"></i></span>
                            <div><span class="small text-muted d-block">Rata-rata waktu respons persetujuan</span><strong>{{ $averageResponseHours !== null ? number_format($averageResponseHours, 1, ',', '.').' jam' : 'Belum ada data' }}</strong></div>
                        </div>
                    </div>

                    <div class="analytics-subsection">
                        <h3 class="small fw-bold mb-2">Status permintaan masuk</h3>
                    @foreach (['submitted' => 'Menunggu ditinjau', 'in_review' => 'Sedang ditinjau', 'quoted' => 'Penawaran tersedia', 'closed' => 'Ditutup'] as $status => $label)
                    <div class="analytics-status-row"><span>{{ $label }}</span><strong>{{ number_format($requestStatuses->get($status, 0), 0, ',', '.') }}</strong></div>
                    @endforeach
                    </div>
                    <div class="small text-muted mt-3"><i class="bi bi-info-circle me-1" aria-hidden="true"></i>Pesanan dihitung dari penawaran yang dikirim pada bulan laporan. Status pesanan dan pembayaran mencerminkan kondisi terkini, bukan transaksi yang hanya terjadi pada bulan tersebut.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="analytics-section-icon analytics-section-icon--warm"><i class="bi bi-chat-square-text" aria-hidden="true"></i></span>
                        <div><h2 class="h6 fw-bold mb-0">Alasan penolakan</h2><p class="small text-muted mb-0">Masukan pelanggan untuk evaluasi penawaran.</p></div>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($rejectionReasons as $reason => $count)
                    <div class="list-group-item analytics-reason-row">
                        <span>{{ $reasonLabels[$reason] ?? 'Alasan lainnya' }}</span>
                        <strong class="analytics-reason-count">{{ number_format($count, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="analytics-empty"><i class="bi bi-emoji-smile" aria-hidden="true"></i><strong>Belum ada penolakan</strong><span>Belum ada masukan penolakan pada periode ini.</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
