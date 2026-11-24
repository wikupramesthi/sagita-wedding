@extends('layouts.app')
@section('title', 'Analitik Pemesanan')
@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 mb-1">Analitik pemesanan</h1>
            <p class="text-muted mb-0">Ringkasan pemesanan 30 hari terakhir. Pemesanan dihitung belum selesai setelah 24 jam tanpa pesanan.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali ke Orders</a>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Pemesanan dimulai</div><div class="fs-3 fw-bold">{{ number_format($started) }}</div></div></div></div>
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Pesanan selesai</div><div class="fs-3 fw-bold text-success">{{ number_format($completed) }}</div></div></div></div>
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Pemesanan belum selesai</div><div class="fs-3 fw-bold text-warning">{{ number_format($abandoned) }}</div></div></div></div>
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Pengingat terkirim</div><div class="fs-3 fw-bold text-info">{{ number_format($reminded) }}</div></div></div></div>
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Konversi</div><div class="fs-3 fw-bold">{{ number_format($conversionRate, 1, ',', '.') }}%</div></div></div></div>
        <div class="col-6 col-xl"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Kepuasan pelanggan · 30 hari</div><div class="fs-3 fw-bold">{{ $averageFeedbackRating !== null ? number_format($averageFeedbackRating, 1, ',', '.').' / 5' : 'Belum ada' }}</div><div class="small text-muted">{{ number_format($feedbackCount) }} tanggapan</div></div></div></div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Performa per paket</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Paket</th><th>Dimulai</th><th>Selesai</th><th>Ditinggalkan*</th><th>Diingatkan</th></tr></thead>
                <tbody>
                    @forelse ($packageStats as $stats)
                        <tr><td class="fw-semibold">{{ $stats['name'] }}</td><td>{{ $stats['started'] }}</td><td>{{ $stats['completed'] }}</td><td>{{ $stats['abandoned'] }}</td><td>{{ $stats['reminded'] }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data checkout dalam 30 hari terakhir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white small text-muted">* Dihitung setelah sesi berumur lebih dari 24 jam dan belum menghasilkan pesanan. Data tidak menampilkan identitas pengunjung.</div>
    </div>
</div>
@endsection
