<div class="order-activity-drawer">
    <div class="order-activity-summary">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <div class="text-muted small">Nomor pesanan</div>
                <div class="fw-bold fs-5">{{ $order->order_number }}</div>
            </div>
            <span class="badge bg-{{ $order->status === 'paid' ? 'success' : (in_array($order->status,['pending','partially_paid']) ? 'warning' : 'secondary') }}-subtle text-{{ $order->status === 'paid' ? 'success' : (in_array($order->status,['pending','partially_paid']) ? 'warning' : 'secondary') }}">
                {{ ['pending' => 'Menunggu', 'partially_paid' => 'DP Terverifikasi', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan'][$order->status] ?? $order->status }}
            </span>
        </div>
        <div class="row g-2 small">
            <div class="col-6">
                <div class="text-muted">Pelanggan</div>
                <div class="fw-semibold">{{ $order->user?->name ?? '-' }}</div>
            </div>
            <div class="col-6">
                <div class="text-muted">Paket</div>
                <div class="fw-semibold">{{ $order->package?->name ?? '-' }}</div>
            </div>
            <div class="col-6">
                <div class="text-muted">Dibuat</div>
                <div class="fw-semibold">{{ $order->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-6">
                <div class="text-muted">Ditangani</div>
                <div class="fw-semibold">{{ $order->assignedAdmin?->name ?? 'Belum diambil alih' }}</div>
            </div>
            @if ($order->sla_reminded_at)
            <div class="col-12">
                <span class="order-flag is-sla"><i class="bi bi-alarm me-1"></i>Pengingat SLA terakhir {{ $order->sla_reminded_at->translatedFormat('d M Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    @if ($order->proof_image_path)
    <div class="order-activity-proof mt-3">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <div class="small fw-semibold"><i class="bi bi-file-earmark-image me-1"></i>Bukti transfer</div>
            <button type="button" class="btn btn-sm btn-outline-primary js-proof-preview"
                data-proof-src="{{ route('orders.proof.show', $order->uuid) }}"
                data-proof-title="Bukti transfer {{ $order->order_number }}">
                <i class="bi bi-zoom-in me-1"></i>Perbesar
            </button>
        </div>
        <img src="{{ route('orders.proof.show', $order->uuid) }}" alt="Bukti transfer {{ $order->order_number }}"
            class="order-activity-proof-img">
    </div>
    @endif

    <div class="order-activity-timeline mt-3">
        <div class="small fw-semibold mb-2"><i class="bi bi-clock-history me-1"></i>Riwayat aktivitas</div>
        @forelse ($audits as $audit)
        @php
        $eventLabels = [
        'created' => 'Pesanan dibuat',
        'approved' => 'Pesanan disetujui',
        'rejected' => 'Pesanan ditolak',
        'proof_uploaded' => 'Bukti transfer diunggah',
        'whatsapp_opened' => 'WhatsApp dibuka',
        'assigned' => 'Pesanan diambil alih',
        'approval_reverted' => 'Persetujuan dibatalkan',
        'rejection_reverted' => 'Penolakan dibatalkan',
        'sla_reminded' => 'Pengingat SLA dikirim',
        ];
        $detail = $audit->new_values['admin_note'] ?? $audit->new_values['hours'] ?? null;
        @endphp
        <div class="order-activity-item">
            <span class="order-activity-dot"></span>
            <div class="flex-grow-1">
                <div class="small fw-semibold">{{ $eventLabels[$audit->event] ?? ucfirst(str_replace('_', ' ', $audit->event)) }}</div>
                <div class="text-muted small">{{ $audit->user_name ?: 'Sistem' }} &middot; {{ $audit->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
                @if ($detail)
                <div class="small fst-italic">{{ is_string($detail) ? $detail : 'SLA ' . $detail . ' jam' }}</div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-muted small">Belum ada aktivitas tambahan untuk pesanan ini.</div>
        @endforelse
    </div>
</div>
