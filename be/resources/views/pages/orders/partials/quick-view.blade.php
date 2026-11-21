<div class="order-quick-view">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
        <div>
            <div class="text-muted small">Nomor pesanan</div>
            <div class="fw-bold fs-5">{{ $order->order_number }}</div>
        </div>
        <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}-subtle text-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
            {{ ['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan'][$order->status] ?? $order->status }}
        </span>
    </div>

    <div class="row g-3 mb-3">
        @if ($isAdmin)
        <div class="col-sm-6">
            <div class="order-quick-label">Pelanggan</div>
            <div class="fw-semibold">{{ $order->user?->name ?? '-' }}</div>
            <div class="text-muted small">{{ $order->user?->email ?? '-' }}</div>
        </div>
        @endif
        <div class="col-sm-6">
            <div class="order-quick-label">Paket</div>
            <div class="fw-semibold">{{ $order->package?->name ?? '-' }}</div>
            @if ($order->packageOption?->name)
            <div class="text-muted small">{{ $order->packageOption->name }}</div>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="order-quick-label">Jumlah</div>
            <div class="fw-semibold">Rp {{ number_format($order->amount, 0, ',', '.') }}</div>
        </div>
        <div class="col-sm-6">
            <div class="order-quick-label">Dibuat</div>
            <div class="fw-semibold">{{ $order->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</div>
        </div>
        @if ($order->admin_note)
        <div class="col-12">
            <div class="order-quick-label">Catatan admin</div>
            <div class="small">{{ $order->admin_note }}</div>
        </div>
        @endif
        @if ($order->proof_image_path)
        <div class="col-12">
            <div class="order-quick-label">Bukti transfer</div>
            <a href="{{ route('orders.proof.show', $order->uuid) }}" target="_blank" rel="noopener">
                <img src="{{ route('orders.proof.show', $order->uuid) }}" alt="Bukti transfer"
                    class="order-quick-proof">
            </a>
        </div>
        @endif
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-eye me-1"></i>Detail lengkap
        </a>
        @if ($isAdmin && $order->status === 'pending')
        <form method="POST" action="{{ route('orders.approve', $order->uuid) }}" data-orders-decision>
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ \Illuminate\Support\Str::uuid() }}">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-check-lg me-1"></i>Setujui
            </button>
        </form>
        @endif
    </div>
</div>
