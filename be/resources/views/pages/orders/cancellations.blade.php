@extends('layouts.app')

@section('title', 'Pembatalan Pesanan')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Permintaan pembatalan</h1>
            <p class="text-muted mb-0">Tinjau permintaan pelanggan dan catat transfer refund manual.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali ke pesanan</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="d-grid gap-3">
        @forelse ($cancellationRequests as $cancellation)
            @php
                $order = $cancellation->order;
                $verifiedAmount = $order?->payments
                    ? $order->payments
                        ->filter(fn ($payment) => in_array($payment->verification_status, ['verified', 'approved'], true) || $payment->status === 'paid')
                        ->sum(fn ($payment) => (float) ($payment->amount ?: $payment->gross_amount))
                    : 0;
                $requestStatus = [
                    'pending' => 'Menunggu tinjauan',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ][$cancellation->status] ?? ucfirst($cancellation->status);
            @endphp
            <article class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <div class="fw-semibold">
                                {{ $order?->order_number }} · {{ $cancellation->requestedBy?->name ?? 'Pelanggan' }}
                            </div>
                            <div class="small text-muted">
                                {{ $order?->customPackageOffer?->title ?? $order?->package?->name ?? 'Paket' }}
                                · {{ $order?->event_date?->translatedFormat('d F Y') ?? 'Tanggal acara belum ditentukan' }}
                            </div>
                        </div>
                        <span class="badge {{ $cancellation->status === 'pending' ? 'text-bg-warning' : ($cancellation->status === 'approved' ? 'text-bg-success' : 'text-bg-secondary') }}">
                            {{ $requestStatus }}
                        </span>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-lg-5">
                            <div class="small text-muted">Alasan pelanggan</div>
                            <div>{{ $cancellation->customer_note ?: 'Tidak ada catatan.' }}</div>
                            <div class="small text-muted mt-2">
                                Diajukan {{ $cancellation->created_at?->translatedFormat('d M Y, H:i') }}
                            </div>
                            <div class="small mt-2">
                                Pembayaran terverifikasi: <strong>Rp {{ number_format($verifiedAmount, 0, ',', '.') }}</strong>
                            </div>
                            @if ($cancellation->admin_note)
                                <div class="small text-muted mt-2">Catatan admin: {{ $cancellation->admin_note }}</div>
                            @endif
                        </div>
                        <div class="col-lg-7">
                            @if ($cancellation->status === 'pending')
                                <form method="POST" action="{{ route('orders.cancellations.review', $cancellation->uuid) }}" class="d-grid gap-2">
                                    @csrf
                                    <label class="form-label small fw-semibold mb-0" for="refund-amount-{{ $cancellation->uuid }}">
                                        Nominal refund (maks. Rp {{ number_format($verifiedAmount, 0, ',', '.') }})
                                    </label>
                                    <input
                                        id="refund-amount-{{ $cancellation->uuid }}"
                                        class="form-control form-control-sm"
                                        type="number"
                                        name="refund_amount"
                                        min="0"
                                        max="{{ number_format($verifiedAmount, 2, '.', '') }}"
                                        step="0.01"
                                        value="0"
                                    >
                                    <div class="form-text">Nominal hanya dicatat. Transfer dilakukan admin di luar aplikasi lalu dicatat dengan nomor referensi.</div>
                                    <input class="form-control form-control-sm" name="admin_note" maxlength="1000" placeholder="Catatan keputusan (opsional)">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success flex-fill" name="decision" value="approved" onclick="return confirm('Setujui pembatalan dan batalkan pesanan?')">
                                            Setujui pembatalan
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger flex-fill" name="decision" value="rejected" onclick="return confirm('Tolak permintaan pembatalan ini?')">
                                            Tolak
                                        </button>
                                    </div>
                                </form>
                            @elseif ($cancellation->status === 'approved' && $cancellation->refund_status === 'pending')
                                <div class="alert alert-warning small">
                                    Refund Rp {{ number_format((float) $cancellation->refund_amount, 0, ',', '.') }} menunggu transfer manual.
                                </div>
                                <form method="POST" action="{{ route('orders.cancellations.refund', $cancellation->uuid) }}" class="d-flex flex-wrap gap-2">
                                    @csrf
                                    <input class="form-control form-control-sm flex-grow-1" name="refund_reference" minlength="2" maxlength="255" placeholder="Nomor referensi transfer" required>
                                    <button class="btn btn-sm btn-primary" onclick="return confirm('Catat refund sebagai sudah ditransfer?')">Catat transfer selesai</button>
                                </form>
                            @elseif ($cancellation->refund_status === 'refunded')
                                <div class="alert alert-success small mb-0">
                                    Refund Rp {{ number_format((float) $cancellation->refund_amount, 0, ',', '.') }} telah dicatat ditransfer.
                                    Referensi: {{ $cancellation->refund_reference }}.
                                    @if ($cancellation->refundRecordedBy)
                                        <span class="d-block text-muted">Dicatat {{ $cancellation->refund_recorded_at?->translatedFormat('d M Y, H:i') }} oleh {{ $cancellation->refundRecordedBy->name }}.</span>
                                    @endif
                                </div>
                            @else
                                <div class="small text-muted">
                                    Ditinjau {{ $cancellation->reviewed_at?->translatedFormat('d M Y, H:i') }}
                                    oleh {{ $cancellation->reviewedBy?->name ?? 'Admin' }}.
                                    @if ($cancellation->status === 'approved')
                                        Refund: Rp {{ number_format((float) $cancellation->refund_amount, 0, ',', '.') }}.
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">Belum ada permintaan pembatalan.</div>
            </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $cancellationRequests->links() }}</div>
</div>
@endsection
