@extends('layouts.app')
@section('title', 'Analitik Checkout')
@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 mb-1">Analitik checkout</h1>
            <p class="text-muted mb-0">Ringkasan checkout 30 hari terakhir. Checkout dihitung ditinggalkan setelah 24 jam tanpa pesanan.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali ke Orders</a>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Checkout dimulai</div><div class="fs-3 fw-bold">{{ number_format($started) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Pesanan selesai</div><div class="fs-3 fw-bold text-success">{{ number_format($completed) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Checkout ditinggalkan</div><div class="fs-3 fw-bold text-warning">{{ number_format($abandoned) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted">Konversi</div><div class="fs-3 fw-bold">{{ number_format($conversionRate, 1, ',', '.') }}%</div></div></div></div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Performa per paket</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Paket</th><th>Dimulai</th><th>Selesai</th><th>Ditinggalkan*</th></tr></thead>
                <tbody>
                    @forelse ($packageStats as $stats)
                        <tr><td class="fw-semibold">{{ $stats['name'] }}</td><td>{{ $stats['started'] }}</td><td>{{ $stats['completed'] }}</td><td>{{ $stats['abandoned'] }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data checkout dalam 30 hari terakhir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white small text-muted">* Dihitung setelah sesi berumur lebih dari 24 jam dan belum menghasilkan pesanan. Data tidak menampilkan identitas pengunjung.</div>
    </div>
</div>
@endsection
