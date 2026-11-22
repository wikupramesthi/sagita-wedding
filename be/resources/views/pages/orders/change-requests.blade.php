@extends('layouts.app')
@section('title', 'Permintaan Perubahan Pesanan')
@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h1 class="h4 mb-1">Permintaan perubahan pesanan</h1><p class="text-muted mb-0">Tinjau perubahan tanggal acara dan layanan tambahan dari pelanggan.</p></div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali ke Orders</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="d-grid gap-3">
        @forelse ($changeRequests as $change)
            <article class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">{{ $change->order?->order_number }} · {{ $change->requestedBy?->name ?? 'Pelanggan' }}</div>
                            <div class="small text-muted">{{ $change->order?->package?->name }} · {{ $change->order?->packageOption?->name }}</div>
                        </div>
                        <span class="badge {{ $change->status === 'pending' ? 'text-bg-warning' : ($change->status === 'approved' ? 'text-bg-success' : 'text-bg-secondary') }}">{{ ucfirst($change->status) }}</span>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <div class="small text-muted">Permintaan</div>
                            @if ($change->change_type === 'event_date')
                                <div class="fw-semibold">Ubah tanggal menjadi {{ $change->requested_event_date?->translatedFormat('d F Y') }}</div>
                            @else
                                <div class="fw-semibold">Tambah {{ $change->addon?->name ?? 'Add-on' }} ×{{ $change->addon_quantity }}</div>
                                <div class="small">Estimasi saat diajukan: Rp {{ number_format($change->requested_price, 0, ',', '.') }}</div>
                            @endif
                            @if ($change->customer_note)<div class="small text-muted mt-1">{{ $change->customer_note }}</div>@endif
                            <div class="small text-muted mt-1">Diajukan {{ $change->created_at?->translatedFormat('d M Y H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            @if ($change->status === 'pending')
                                <form method="POST" action="{{ route('orders.change-requests.review', $change->uuid) }}" class="d-flex flex-column gap-2">
                                    @csrf
                                    <label class="visually-hidden" for="review-note-{{ $change->uuid }}">Catatan admin</label>
                                    <input id="review-note-{{ $change->uuid }}" class="form-control form-control-sm" name="admin_note" maxlength="1000" placeholder="Catatan admin (opsional)">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success flex-fill" name="decision" value="approved" onclick="return confirm('Setujui perubahan ini?')"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                                        <button class="btn btn-sm btn-outline-danger flex-fill" name="decision" value="rejected" onclick="return confirm('Tolak perubahan ini?')"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                                    </div>
                                </form>
                            @else
                                <div class="small text-muted">Ditinjau {{ $change->reviewed_at?->translatedFormat('d M Y H:i') }} oleh {{ $change->reviewedBy?->name ?? 'Admin' }}</div>
                                @if ($change->admin_note)<div class="small mt-1">{{ $change->admin_note }}</div>@endif
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">Belum ada permintaan perubahan pesanan.</div></div>
        @endforelse
    </div>
    <div class="mt-3">{{ $changeRequests->links() }}</div>
</div>
@endsection
