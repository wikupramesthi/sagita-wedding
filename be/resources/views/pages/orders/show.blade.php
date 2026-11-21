@extends('layouts.app')

@section('title', 'Bukti Pembayaran')

@section('content')

@php
    $statusLabels = [
        'pending' => 'Menunggu Verifikasi',
        'partially_paid' => 'DP Terverifikasi',
        'paid' => 'Lunas',
        'failed' => 'Gagal',
        'expired' => 'Kedaluwarsa',
        'cancelled' => 'Dibatalkan',
    ];
    $schemeLabel = $order->scheme_label ?? 'Bayar Lunas';
    $fullAmount = (float) ($order->total_amount ?: $order->amount);
    $amountPaid = (float) ($order->amount_paid_value ?? ($order->status === 'paid' ? $fullAmount : 0));
    $remainingAmount = (float) ($order->remaining_amount_value ?? max(0, $fullAmount - $amountPaid));
    $isDp = in_array($order->payment_scheme, ['dp30','dp50'], true);
@endphp

<div class="receipt-page">
    <div class="receipt-wrapper">

        {{-- Top Navigation --}}
        <div class="receipt-topbar">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('orders.index') }}" class="back-link" aria-label="Kembali ke pesanan">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="order-detail-eyebrow">Detail Pesanan</div>
                    <div class="order-detail-title">{{ $order->order_number }}</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="order-status-pill status-{{ $order->status }}">
                    <i class="bi bi-circle-fill me-1"></i>{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </span>
                <button type="button" class="print-button js-copy-order" data-copy="{{ $order->order_number }}"
                    title="Salin nomor pesanan">
                    <i class="bi bi-copy"></i>
                    <span>Salin</span>
                </button>
                @if ($isAdmin)
                    <a href="{{ route('orders.whatsapp', $order->uuid) }}" class="print-button text-decoration-none">
                        <i class="bi bi-whatsapp"></i>
                        <span>Hubungi WhatsApp</span>
                    </a>
                @endif
                <button type="button" onclick="window.print()" class="print-button print-button-primary">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Bukti</span>
                </button>
            </div>
        </div>

        <div class="order-detail-layout">
            <aside class="order-detail-side">

        {{-- Admin verification (manual transfer) --}}
        @if ($isAdmin && $order->status === 'pending')
            <div class="card border-warning mb-4">
                <div class="card-header bg-warning bg-opacity-10 fw-bold">
                    <i class="bi bi-shield-check me-1"></i> Verifikasi Admin
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Verifikasi transfer bank manual untuk <strong>{{ $order->order_number }}</strong>.
                        Menyetujui akan menandai pesanan lunas dan mengaktifkan keanggotaan secara otomatis.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <form action="{{ route('orders.approve', $order->uuid) }}" method="POST" data-orders-decision>
                                @csrf
                                <input type="hidden" name="idempotency_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                                <label class="form-label small fw-semibold" for="approveNote">Catatan (opsional)</label>
                                <textarea name="admin_note" id="approveNote" rows="2" class="form-control mb-2"
                                    placeholder="mis. Transfer terverifikasi, BCA ...1234"></textarea>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-lg me-1"></i> Setujui — Tandai Lunas
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('orders.reject', $order->uuid) }}" method="POST" data-orders-decision>
                                @csrf
                                <input type="hidden" name="idempotency_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                                <label class="form-label small fw-semibold" for="rejectNote">Alasan penolakan <span class="text-danger">*</span></label>
                                <textarea name="admin_note" id="rejectNote" rows="2" class="form-control mb-2" required
                                    placeholder="mis. Transfer tidak diterima"></textarea>
                                <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Tolak pesanan ini?')">
                                    <i class="bi bi-x-lg me-1"></i> Tolak Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isAdmin)
            <div class="card border-light mb-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-info-square me-1"></i> Ringkasan Admin
                </div>
                <div class="card-body">
                    <div class="row g-3 small">
                        <div class="col-md-4">
                            <div class="text-muted">Nomor pesanan</div>
                            <div class="fw-semibold font-monospace">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Paket</div>
                            <div class="fw-semibold">{{ $order->package?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Dibuat</div>
                            <div class="fw-semibold">{{ $order->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Kedaluwarsa</div>
                            <div class="fw-semibold {{ $order->status === 'pending' && $order->expired_at?->isPast() ? 'text-danger' : '' }}">
                                {{ $order->expired_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Metode pembayaran</div>
                            <div class="fw-semibold">{{ $order->payment?->payment_type ?? 'Transfer Manual' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted">Bukti transfer</div>
                            <div class="fw-semibold">
                                @if ($order->proof_image_path)
                                    <button type="button" class="btn btn-sm btn-outline-primary js-proof-lightbox"
                                        data-proof-src="{{ $order->proofUrl() }}">
                                        <i class="bi bi-file-earmark-image me-1"></i>Lihat bukti
                                    </button>
                                @else
                                    <span class="text-warning">Belum diunggah</span>
                                @endif
                            </div>
                        </div>
                        @if ($order->user?->phone || $order->user?->whatsapp)
                            <div class="col-md-4">
                                <div class="text-muted">Kontak pelanggan</div>
                                <div class="fw-semibold">{{ $order->user->phone ?? $order->user->whatsapp }}</div>
                            </div>
                        @endif
                        @if ($order->event_date)
                            <div class="col-md-4">
                                <div class="text-muted">Tanggal Acara</div>
                                <div class="fw-semibold">{{ $order->event_date->translatedFormat('d F Y') }}</div>
                            </div>
                        @endif
                        @if ($order->promo_code)
                            <div class="col-md-4">
                                <div class="text-muted">Promo</div>
                                <div class="fw-semibold">
                                    {{ $order->promo_code }}
                                    (Hemat Rp {{ number_format((int) $order->promo_discount, 0, ',', '.') }})
                                </div>
                            </div>
                        @endif
                        @if (!empty($order->addons))
                            <div class="col-12">
                                <div class="text-muted mb-1">Layanan tambahan</div>
                                @foreach ($order->addons as $addon)
                                    <div class="d-flex justify-content-between gap-3 border-bottom py-1">
                                        <span class="fw-semibold">
                                            {{ $addon['name'] ?? '-' }} x{{ (int) ($addon['quantity'] ?? 1) }}
                                        </span>
                                        <span>Rp {{ number_format((int) ($addon['line_total'] ?? $addon['price'] ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if ($userPackage)
                            <div class="col-md-4">
                                <div class="text-muted">Keanggotaan terkait</div>
                                <div class="fw-semibold">
                                    {{ $userPackage->package?->name ?? '-' }} —
                                    {{ $userPackage->quota === null ? 'tanpa batas' : 'sisa ' . $userPackage->quota . ' kelas' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($auditLogs->count())
                <div class="card border-light mb-4">
                    <div class="card-header bg-light fw-bold">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Verifikasi
                    </div>
                    <div class="card-body">
                        <div class="timeline-admin">
                            @foreach ($auditLogs as $log)
                                <div class="timeline-item-admin">
                                    <div class="timeline-dot-admin {{ $log->event === 'approved' ? 'bg-success' : ($log->event === 'rejected' ? 'bg-danger' : 'bg-info') }}"></div>
                                    <div>
                                        <div class="small fw-semibold">{{ $log->event_label }}</div>
                                        <div class="text-muted small">
                                            {{ $log->user_name ?? 'Sistem' }} —
                                            {{ $log->created_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                        </div>
                                        @if ($log->new_values && is_array($log->new_values) && !empty($log->new_values['admin_note']))
                                            <div class="small fst-italic mt-1">"{{ $log->new_values['admin_note'] }}"</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if ($isAdmin && $order->admin_note)
            <div class="alert alert-info">
                <strong>Catatan admin:</strong> {{ $order->admin_note }}
            </div>
        @endif

        @if ($userPackage)
            <div class="alert alert-success d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                    Keanggotaan aktif
                    @if ($userPackage->quota === null)
                        (tanpa batas)
                    @else
                        (sisa {{ $userPackage->quota }} kelas)
                    @endif
                    sampai {{ $userPackage->expired_at?->translatedFormat('d F Y') ?? '-' }}.
                </div>
            </div>
        @endif

            </aside>
            <div class="order-detail-main">

        @if ($order->status === 'pending')
            @php
                $isOwner = $order->user_uuid === auth()->user()->uuid;
                $bankName = config('manual_payment.bank_name');
                $bankAccount = config('manual_payment.bank_account');
                $bankHolder = config('manual_payment.bank_holder');
                $waNumber = preg_replace('/\D/', '', (string) config('manual_payment.admin_whatsapp'));
                $waText = 'Halo Admin Sagita Wedding Organizer, saya telah melakukan transfer Rp ' . number_format($order->amount, 0, ',', '.') . ' untuk pesanan ' . $order->order_number . ' (' . ($order->user?->name ?? '') . '). Terima kasih.';
            @endphp
            <div class="card border-primary mb-4 order-detail-payment">
                <div class="card-header bg-primary bg-opacity-10 fw-bold">
                    <i class="bi bi-bank me-1"></i> Selesaikan Pembayaran — Transfer Manual
                </div>
                <div class="card-body">
                    <ol class="small mb-3 ps-3">
                        <li>Transfer jumlah yang tertera ke rekening bank kami.</li>
                        <li>Unggah bukti transfer (atau konfirmasi melalui WhatsApp).</li>
                        <li>Admin akan memverifikasi — keanggotaan aktif otomatis setelah disetujui.</li>
                    </ol>

                    @php
                        $schemeLabel = $order->promo_snapshot['scheme_label'] ?? 'Bayar Lunas';
                        $fullAmount = $order->promo_snapshot['full_amount'] ?? $order->amount;
                    @endphp
                    <div class="alert alert-light border small mb-3">
                        <div>Skema pembayaran: <strong>{{ $schemeLabel }}</strong></div>
                        @if ((int) $fullAmount !== (int) $order->amount)
                        <div class="text-muted">Total paket Rp {{ number_format($fullAmount, 0, ',', '.') }} — dibayar sekarang Rp {{ number_format($order->amount, 0, ',', '.') }}.</div>
                        @endif
                    </div>

                    @if ($bankAccount)
                        <div class="p-3 rounded-3 bg-light mb-3">
                            <div class="small text-muted">BANK</div>
                            <div class="fw-bold fs-5">{{ $bankName }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="font-monospace fs-5 fw-bold" id="bankAccountNumber">{{ $bankAccount }}</span>
                            </div>
                            @if ($bankHolder)
                                <div class="small text-muted">Atas nama: {{ $bankHolder }}</div>
                            @endif
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                <span>Jumlah: <strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong></span>
                                <span>Pesanan: <strong>{{ $order->order_number }}</strong></span>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if ($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank"
                                rel="noopener" class="btn btn-success">
                                <i class="bi bi-whatsapp me-1"></i> Konfirmasi via WhatsApp
                            </a>
                        @endif
                    </div>

                    @if ($order->proof_image_path)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light mb-3">
                            <button type="button" class="proof-thumb js-proof-lightbox"
                                data-proof-src="{{ $order->proofUrl() }}" aria-label="Perbesar bukti transfer">
                                <img src="{{ $order->proofUrl() }}" alt="Bukti transfer"
                                    class="rounded-3 border" style="height:110px;width:auto;object-fit:cover;">
                            </button>
                            <div class="small">
                                <div class="fw-bold text-success">
                                    <i class="bi bi-check-circle me-1"></i>Bukti diunggah
                                </div>
                                <div class="text-muted">
                                    {{ $order->proof_uploaded_at?->translatedFormat('d F Y H:i') ?? '' }} — menunggu verifikasi admin.
                                </div>
                                @if ($isOwner)
                                    <div class="text-muted">Unggah ulang di bawah untuk menggantinya.</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($isOwner)
                        <form action="{{ route('orders.proof', $order->uuid) }}" method="POST"
                            enctype="multipart/form-data" class="d-flex flex-wrap align-items-end gap-2">
                            @csrf
                            <div class="flex-fill" style="min-width: 220px;">
                                <label class="form-label small fw-semibold" for="proofInput">
                                    Unggah bukti transfer (JPG/PNG/WebP, maks {{ number_format(config('manual_payment.proof_max_kb', 3072) / 1024, 1) }} MB)
                                </label>
                                <input type="file" name="proof" id="proofInput" class="form-control"
                                    accept="image/jpeg,image/png,image/webp" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>
                                {{ $order->proof_image_path ? 'Ganti Bukti' : 'Unggah Bukti' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif


        {{-- Receipt --}}
        <div class="receipt-card">

            {{-- Header --}}
            <div class="receipt-header">

                <div class="receipt-brand">
                    Sagita Wedding Organizer
                </div>

                <div class="receipt-heading">
                    Bukti Pembayaran
                </div>

                <div class="receipt-order-number">
                    {{ $order->order_number }}
                </div>

                <div class="receipt-date">
                    {{ $order->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                </div>

            </div>


            {{-- Status --}}
            <div class="receipt-status-section">

                @if ($order->status === 'paid')

                <div class="status-circle paid">
                    <i class="bi bi-check-lg"></i>
                </div>

                <h3>
                    Pembayaran Berhasil
                </h3>

                <p>
                    Pembayaran Anda telah berhasil diproses.
                </p>

                @elseif ($order->status === 'pending')

                <div class="status-circle pending">
                    <i class="bi bi-clock"></i>
                </div>

                <h3>
                    Pembayaran Menunggu
                </h3>

                <p>
                    Pembayaran ini belum diselesaikan.
                </p>

                @elseif ($order->status === 'failed')

                <div class="status-circle failed">
                    <i class="bi bi-x-lg"></i>
                </div>

                <h3>
                    Pembayaran Gagal
                </h3>

                <p>
                    Pembayaran ini tidak dapat diselesaikan.
                </p>

                @elseif ($order->status === 'expired')

                <div class="status-circle expired">
                    <i class="bi bi-hourglass-bottom"></i>
                </div>

                <h3>
                    Pembayaran Kedaluwarsa
                </h3>

                <p>
                    Sesi pembayaran ini sudah kedaluarsa.
                </p>

                @elseif ($order->status === 'cancelled')

                <div class="status-circle cancelled">
                    <i class="bi bi-x-lg"></i>
                </div>

                <h3>
                    Pesanan Dibatalkan
                </h3>

                <p>
                    Pesanan ini telah dibatalkan.
                </p>

                @endif

            </div>


            {{-- Main Content --}}
            <div class="receipt-content">

                {{-- Membership --}}
                <div class="receipt-block">

                    <div class="receipt-label">
                        KEANGGOTAAN
                    </div>

                    <div class="membership-row">

                        <div class="membership-info">

                            <h4>
                                {{ $order->package?->name ?? '-' }}
                            </h4>

                            @if ($order->packageOption)

                            <div class="membership-meta">

                                <span>
                                    {{ $order->packageOption->name }}
                                </span>

                                <span class="dot"></span>

                                <span>
                                    {{ $order->packageOption->duration }}
                                    {{ ucfirst($order->packageOption->duration_unit) }}
                                </span>

                                <span class="dot"></span>

                                <span>
                                    @if ($order->packageOption->quota === null)
                                    Kelas Tanpa Batas
                                    @else
                                    {{ $order->packageOption->quota }} Kelas
                                    @endif
                                </span>

                            </div>

                            @endif

                        </div>


                        <div class="membership-price">
                            Rp {{ number_format($order->amount, 0, ',', '.') }}
                        </div>

                    </div>

                </div>


                {{-- Divider --}}
                <div class="receipt-divider"></div>


                {{-- Customer --}}
                <div class="receipt-block">

                    <div class="receipt-label">
                        PELANGGAN
                    </div>

                    <div class="customer-name">
                        {{ $order->user?->name ?? '-' }}
                    </div>

                    <div class="customer-email">
                        {{ $order->user?->email ?? '-' }}
                    </div>

                </div>


                {{-- Payment --}}
                <div class="receipt-payment-grid">

                    <div>

                        <div class="receipt-label">
                            STATUS PEMBAYARAN
                        </div>

                        <div class="payment-value">

                            @if ($order->status === 'paid')
                            <span class="mini-status paid">
                                Lunas
                            </span>
                            @elseif ($order->status === 'pending')
                            <span class="mini-status pending">
                                Menunggu
                            </span>
                            @elseif ($order->status === 'failed')
                            <span class="mini-status failed">
                                Gagal
                            </span>
                            @elseif ($order->status === 'expired')
                            <span class="mini-status expired">
                                Kedaluwarsa
                            </span>
                            @else
                            <span class="mini-status cancelled">
                                Dibatalkan
                            </span>
                            @endif

                        </div>

                    </div>


                    <div>

                        <div class="receipt-label">
                            TANGGAL PEMBAYARAN
                        </div>

                        <div class="payment-value">
                            {{ $order->paid_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </div>

                    </div>


                    <div>

                        <div class="receipt-label">
                            METODE PEMBAYARAN
                        </div>

                        <div class="payment-value">
                            {{ $order->payment?->payment_type ?? 'Transfer Manual' }}
                        </div>

                    </div>

                </div>


                {{-- Total --}}
                <div class="receipt-total">

                    <div>
                        <span>
                            Total Dibayar
                        </span>

                        <small>
                            PESANAN PAKET
                        </small>
                    </div>

                    <strong>
                        Rp {{ number_format($order->amount, 0, ',', '.') }}
                    </strong>

                </div>


                {{-- Paid Message --}}
                @if ($order->status === 'paid')

                <div class="receipt-message">

                    <i class="bi bi-heart"></i>

                    <span>
                        Terima kasih telah memilih Sagita Wedding Organizer.
                        Keanggotaan Anda siap dinikmati.
                    </span>

                </div>

                @elseif ($order->status === 'pending')

                <div class="receipt-message pending">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Pembayaran masih menunggu. Selesaikan pembayaran untuk mengaktifkan keanggotaan Anda.
                    </span>

                </div>

                @endif

            </div>


            {{-- Footer --}}
            <div class="receipt-footer">

                <div class="footer-brand">
                    Sagita Wedding Organizer
                </div>

                <div>
                    Wujudkan pernikahan impian Anda bersama kami.
                </div>

            </div>

        </div>
            </div>
        </div>

    </div>

</div>

<style>
    /* =========================================================
       PAGE
    ========================================================= */

    .receipt-page {
        min-height: calc(100vh - 100px);
        padding: 30px 20px 60px;
        background: var(--bs-body-bg);
    }

    .receipt-wrapper {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
    }

    .order-detail-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 22px;
        align-items: start;
    }

    .order-detail-main {
        min-width: 0;
    }

    .order-detail-side {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .order-detail-side .card {
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(16,24,40,.05);
        overflow: hidden;
    }

    .order-detail-side .card-header {
        border-bottom: 1px solid var(--bs-border-color);
        font-size: 13px;
        letter-spacing: .01em;
    }

    .order-detail-eyebrow {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--bs-secondary-color);
    }

    .order-detail-title {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .order-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #eef2ff;
        color: #3538cd;
    }

    .order-status-pill i { font-size: 7px; }
    .order-status-pill.status-paid { background: #ecfdf3; color: #067647; }
    .order-status-pill.status-pending { background: #fffaeb; color: #b54708; }
    .order-status-pill.status-failed,
    .order-status-pill.status-expired,
    .order-status-pill.status-cancelled { background: #fef3f2; color: #b42318; }

    .print-button-primary {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }

    .print-button-primary:hover {
        color: #fff;
        filter: brightness(.95);
    }

    .proof-thumb {
        padding: 0;
        border: 0;
        background: transparent;
        cursor: zoom-in;
        line-height: 0;
    }


    /* =========================================================
       TOPBAR
    ========================================================= */

    .receipt-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--bs-secondary-color);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: color .2s ease;
    }

    .back-link:hover {
        color: var(--bs-primary);
    }

    .print-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border: 1px solid var(--bs-border-color);
        border-radius: 9px;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        font-size: 12px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .print-button:hover {
        border-color: var(--bs-primary);
        color: var(--bs-primary);
    }


    /* =========================================================
       RECEIPT CARD
    ========================================================= */

    .receipt-card {
        overflow: hidden;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 18px;
        box-shadow: 0 15px 45px rgba(49, 57, 47, .07);
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .receipt-header {
        padding: 34px 42px 28px;
        text-align: center;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .receipt-brand {
        color: var(--bs-primary);
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .receipt-heading {
        margin-top: 7px;
        color: var(--bs-body-color);
        font-size: 14px;
        font-weight: 600;
    }

    .receipt-order-number {
        margin-top: 14px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .receipt-date {
        margin-top: 3px;
        color: var(--bs-secondary-color);
        font-size: 11px;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .receipt-status-section {
        padding: 34px 30px 32px;
        text-align: center;
    }

    .status-circle {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 13px;
        border-radius: 50%;
        font-size: 22px;
    }

    .status-circle.paid {
        background: rgba(75, 107, 82, .10);
        color: var(--bs-primary);
    }

    .status-circle.pending {
        background: rgba(234, 179, 8, .10);
        color: #a16207;
    }

    .status-circle.failed,
    .status-circle.cancelled {
        background: rgba(239, 68, 68, .10);
        color: #b91c1c;
    }

    .status-circle.expired {
        background: rgba(107, 114, 128, .10);
        color: #4b5563;
    }

    .receipt-status-section h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 750;
        letter-spacing: -.02em;
    }

    .receipt-status-section p {
        margin: 6px 0 0;
        color: var(--bs-secondary-color);
        font-size: 12px;
    }


    /* =========================================================
       CONTENT
    ========================================================= */

    .receipt-content {
        padding: 0 42px 38px;
    }

    .receipt-block {
        padding: 3px 0;
    }

    .receipt-label {
        margin-bottom: 9px;
        color: var(--bs-secondary-color);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .11em;
    }


    /* =========================================================
       MEMBERSHIP
    ========================================================= */

    .membership-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
    }

    .membership-info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 750;
        letter-spacing: -.02em;
    }

    .membership-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 7px;
        color: var(--bs-secondary-color);
        font-size: 11px;
    }

    .membership-meta .dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: var(--bs-secondary-color);
        opacity: .5;
    }

    .membership-price {
        color: var(--bs-primary);
        font-size: 17px;
        font-weight: 800;
        white-space: nowrap;
    }


    /* =========================================================
       DIVIDER
    ========================================================= */

    .receipt-divider {
        height: 1px;
        margin: 26px 0;
        background: var(--bs-border-color);
    }


    /* =========================================================
       CUSTOMER
    ========================================================= */

    .customer-name {
        font-size: 14px;
        font-weight: 700;
    }

    .customer-email {
        margin-top: 3px;
        color: var(--bs-secondary-color);
        font-size: 12px;
    }


    /* =========================================================
       PAYMENT GRID
    ========================================================= */

    .receipt-payment-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 28px;
        padding-top: 25px;
        border-top: 1px solid var(--bs-border-color);
    }

    .payment-value {
        font-size: 12px;
        font-weight: 650;
    }

    .mini-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 750;
    }

    .mini-status.paid {
        background: rgba(75, 107, 82, .10);
        color: var(--bs-primary);
    }

    .mini-status.pending {
        background: rgba(234, 179, 8, .10);
        color: #a16207;
    }

    .mini-status.failed,
    .mini-status.cancelled {
        background: rgba(239, 68, 68, .10);
        color: #b91c1c;
    }

    .mini-status.expired {
        background: rgba(107, 114, 128, .10);
        color: #4b5563;
    }


    /* =========================================================
       TOTAL
    ========================================================= */

    .receipt-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-top: 30px;
        padding: 20px 0;
        border-top: 2px solid var(--bs-primary);
        border-bottom: 1px solid var(--bs-border-color);
    }

    .receipt-total span {
        display: block;
        font-size: 13px;
        font-weight: 700;
    }

    .receipt-total small {
        display: block;
        margin-top: 3px;
        color: var(--bs-secondary-color);
        font-size: 8px;
        letter-spacing: .1em;
    }

    .receipt-total strong {
        color: var(--bs-primary);
        font-size: 24px;
        font-weight: 800;
        white-space: nowrap;
    }


    /* =========================================================
       MESSAGE
    ========================================================= */

    .receipt-message {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        padding: 12px 15px;
        border-radius: 9px;
        background: rgba(75, 107, 82, .06);
        color: var(--bs-secondary-color);
        font-size: 11px;
        text-align: center;
    }

    .receipt-message i {
        color: var(--bs-primary);
    }

    .receipt-message.pending {
        background: rgba(234, 179, 8, .07);
    }

    .receipt-message.pending i {
        color: #a16207;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .receipt-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 20px 42px;
        background: var(--bs-tertiary-bg);
        color: var(--bs-secondary-color);
        font-size: 10px;
    }

    .footer-brand {
        color: var(--bs-body-color);
        font-size: 12px;
        font-weight: 750;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .receipt-page {
            padding: 20px 12px 40px;
        }

        .receipt-topbar {
            margin-bottom: 14px;
        }

        .print-button span,
        .back-link span {
            display: none;
        }

        .print-button,
        .back-link {
            width: 38px;
            height: 38px;
            justify-content: center;
            padding: 0;
        }

        .receipt-header {
            padding: 28px 20px 24px;
        }

        .receipt-content {
            padding: 0 20px 30px;
        }

        .receipt-status-section {
            padding: 28px 20px;
        }

        .membership-row {
            flex-direction: column;
            gap: 10px;
        }

        .membership-price {
            font-size: 16px;
        }

        .receipt-payment-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .receipt-total strong {
            font-size: 19px;
        }

        .receipt-footer {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px 20px;
        }

    }


    .timeline-admin {
        position: relative;
        padding-left: 18px;
    }

    .timeline-admin::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item-admin {
        position: relative;
        padding-bottom: 14px;
    }

    .timeline-item-admin:last-child {
        padding-bottom: 0;
    }

    .timeline-dot-admin {
        position: absolute;
        left: -17px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #fff;
    }

    @media (max-width: 991.98px) {
        .order-detail-layout {
            grid-template-columns: 1fr;
        }

        .order-detail-side {
            order: 2;
        }
    }

    @media (max-width: 575.98px) {
        .receipt-page {
            padding: 18px 12px 40px;
        }

        .receipt-topbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .order-detail-title {
            font-size: 16px;
        }
    }

    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        @page {
            margin: 15mm;
        }

        body {
            background: #fff !important;
        }

        .receipt-page {
            min-height: auto;
            padding: 0;
        }

        .receipt-topbar {
            display: none !important;
        }

        .receipt-wrapper > .card,
        .order-detail-side,
        .order-detail-payment {
            display: none !important;
        }

        .order-detail-layout,
        .order-detail-main {
            display: block !important;
        }

        .receipt-wrapper {
            max-width: none;
        }

        .receipt-card {
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .receipt-header,
        .receipt-content {
            padding-left: 0;
            padding-right: 0;
        }

        .receipt-footer {
            padding-left: 0;
            padding-right: 0;
        }

    }
</style>

<div class="modal fade" id="proofLightbox" tabindex="-1" aria-labelledby="proofLightboxLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofLightboxLabel">Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="proofLightboxImage" src="" alt="Bukti transfer" class="img-fluid rounded"
                    style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    (function() {
        var bs = window.bootstrap;
        var lightboxEl = document.getElementById('proofLightbox');
        var lightboxImage = document.getElementById('proofLightboxImage');
        var proofButtons = document.querySelectorAll('.js-proof-lightbox');

        if (lightboxEl && lightboxImage && proofButtons.length && bs) {
            var lightbox = new bs.Modal(lightboxEl);

            proofButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    lightboxImage.src = button.getAttribute('data-proof-src') || '';
                    lightbox.show();
                });
            });

            lightboxEl.addEventListener('hidden.bs.modal', function() {
                lightboxImage.src = '';
            });
        }

        document.querySelectorAll('.js-copy-order').forEach(function(button) {
            button.addEventListener('click', async function() {
                var text = button.getAttribute('data-copy') || '';
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        var input = document.createElement('textarea');
                        input.value = text;
                        input.style.position = 'fixed';
                        input.style.opacity = '0';
                        document.body.appendChild(input);
                        input.select();
                        try {
                            document.execCommand('copy');
                        } catch (e) {}
                        document.body.removeChild(input);
                    }
                    var label = button.querySelector('span');

                    if (label) {
                        label.textContent = 'Tersalin';
                        setTimeout(function() {
                            label.textContent = 'Salin';
                        }, 1500);
                    }
                } catch (error) {
                    window.location.href = 'mailto:?subject=' + encodeURIComponent(button.getAttribute('data-copy') || '');
                }
            });
        });
    })();
</script>
@endpush

@endsection