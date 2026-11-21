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
            <div class="order-detail-main">

        {{-- Admin verification (manual transfer) --}}
        @php
            $pendingPayment = $order->payments->firstWhere('verification_status', 'pending') ?? $order->payments->firstWhere('status', 'pending');
            $hasPendingVerification = $pendingPayment || ($order->proof_image_path && in_array($order->status, ['pending','partially_paid'], true));
        @endphp
        @if ($isAdmin && $hasPendingVerification && in_array($order->status, ['pending','partially_paid']))
            <div class="card border-warning mb-4" id="payment-module-focus">
                <div class="card-header bg-warning bg-opacity-10 fw-bold d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <span><i class="bi bi-shield-check me-1"></i> Verifikasi Pembayaran</span>
                    <span class="badge text-bg-warning">Perlu tindakan</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        @if($order->status === 'partially_paid')
                            Verifikasi cicilan/pelunasan untuk <strong>{{ $order->order_number }}</strong>. Sisa saat ini: <strong>Rp {{ number_format((float)$remainingAmount, 0, ',', '.') }}</strong>.
                        @elseif($isDp)
                            Verifikasi pembayaran <strong>DP</strong> untuk <strong>{{ $order->order_number }}</strong>. Pembayaran yang diterima akan masuk ke modul pembayaran dan riwayat. Pesanan akan diproses otomatis setelah lunas.
                        @else
                            Verifikasi transfer untuk <strong>{{ $order->order_number }}</strong>. Pembayaran yang diterima akan masuk ke modul pembayaran dan riwayat. Pesanan akan diproses otomatis setelah lunas.
                        @endif
                    </p>
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <form action="{{ route('orders.approve', $order->uuid) }}" method="POST" data-orders-decision class="h-100">
                                @csrf
                                <input type="hidden" name="idempotency_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                                <label class="form-label small fw-semibold" for="approveNote">Catatan persetujuan (opsional)</label>
                                <textarea name="admin_note" id="approveNote" rows="3" class="form-control mb-2" placeholder="mis. Transfer terverifikasi, BCA ...1234"></textarea>
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i> Terima Pembayaran</button>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            @if($order->status === 'pending')
                            <form action="{{ route('orders.reject', $order->uuid) }}" method="POST" data-orders-decision class="h-100">
                                @csrf
                                <input type="hidden" name="idempotency_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                                <label class="form-label small fw-semibold" for="rejectNote">Alasan penolakan <span class="text-danger">*</span></label>
                                <textarea name="admin_note" id="rejectNote" rows="3" class="form-control mb-2" required placeholder="mis. Nominal/bukti tidak sesuai"></textarea>
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Tolak pesanan ini?')"><i class="bi bi-x-lg me-1"></i> Tolak Pesanan</button>
                            </form>
                            @else
                            <div class="alert alert-info small mb-0 h-100 d-flex align-items-center"><div><i class="bi bi-info-circle me-1"></i>Pesanan sudah memiliki pembayaran terverifikasi. Penolakan otomatis dinonaktifkan; gunakan penyesuaian manual bila diperlukan.</div></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isAdmin && $order->admin_note)
            <div class="alert alert-info mb-4"><strong>Catatan admin:</strong> {{ $order->admin_note }}</div>
        @endif

        @if ($userPackage)
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                <div>Layanan aktif @if ($userPackage->quota === null)(tanpa batas)@else(sisa {{ $userPackage->quota }} sesi)@endif sampai {{ $userPackage->expired_at?->translatedFormat('d F Y') ?? '-' }}.</div>
            </div>
        @endif

        @php
            $isOwner = $order->user_uuid === auth()->user()->uuid;
            $bankName = config('manual_payment.bank_name');
            $bankAccount = config('manual_payment.bank_account');
            $bankHolder = config('manual_payment.bank_holder');
            $waNumber = preg_replace('/\D/', '', (string) config('manual_payment.admin_whatsapp'));
            $waText = 'Halo Admin Sagita Wedding Organizer, saya telah melakukan transfer Rp ' . number_format($order->amount, 0, ',', '.') . ' untuk pesanan ' . $order->order_number . ' (' . ($order->user?->name ?? '') . '). Terima kasih.';
        @endphp
        @if ($order->status === 'pending')
            <div class="card border-primary mb-4 order-detail-payment">
                <div class="card-header bg-primary bg-opacity-10 fw-bold">
                    <i class="bi bi-bank me-1"></i> Instruksi Pembayaran: Transfer Manual
                </div>
                <div class="card-body">
                    <ol class="small mb-3 ps-3">
                        <li>Transfer jumlah yang tertera ke rekening bank kami.</li>
                        <li>Unggah bukti transfer (atau konfirmasi melalui WhatsApp).</li>
                        <li>Admin akan memverifikasi pembayaran. Pesanan diproses setelah pembayaran disetujui.</li>
                    </ol>

                    <div class="alert border small mb-3 bg-white text-dark">
                        <div class="fw-semibold mb-1">Ringkasan Pembayaran</div>
                        <div class="d-flex justify-content-between"><span class="text-muted">Total paket</span><span>Rp {{ number_format($fullAmount, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">Dibayar sekarang</span><span class="fw-semibold text-primary">Rp {{ number_format((float)$order->amount, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">Sisa</span><span class="fw-semibold {{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span></div>
                    </div>

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
                                    {{ $order->proof_uploaded_at?->translatedFormat('d F Y H:i') ?? '' }}. Menunggu verifikasi admin.
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


        {{-- Payment module --}}
        <div class="card border-0 shadow-sm mb-4" id="payment-module">
            <div class="card-header bg-transparent fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-stack me-1"></i> Modul Pembayaran</span>
                <span class="badge bg-primary-subtle text-primary border">{{ $order->scheme_label ?? $schemeLabel }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3 payment-module-summary">
                    <div class="col-md-4"><div class="p-3 rounded-3 bg-light"><div class="small text-muted">Total Paket</div><div class="fw-bold">Rp {{ number_format($fullAmount,0,',','.') }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3 bg-light"><div class="small text-muted">Sudah Dibayar</div><div class="fw-bold text-success">Rp {{ number_format($amountPaid,0,',','.') }}</div></div></div>
                    <div class="col-md-4"><div class="p-3 rounded-3 bg-light"><div class="small text-muted">Sisa</div><div class="fw-bold {{ $remainingAmount>0?'text-danger':'text-success' }}">Rp {{ number_format($remainingAmount,0,',','.') }}</div>@if($order->balance_due_at)<div class="small text-muted">Batas pelunasan: {{ $order->balance_due_at->translatedFormat('d M Y') }}</div>@endif</div></div>
                </div>

                @if(in_array($order->status, ['pending','partially_paid'], true) && ($isOwner || $isAdmin))
                    @php $pendingPayment = $order->payments->firstWhere('verification_status', 'pending') ?? $order->payments->firstWhere('status', 'pending'); $hasPendingPayment = (bool)$pendingPayment; $minInstallment = min($remainingAmount, round($fullAmount * 0.20, 2)); @endphp
                    @if($hasPendingPayment && !$isAdmin)
                        <div class="alert alert-warning small">Bukti pembayaran Rp {{ number_format((float)($pendingPayment->amount ?? $pendingPayment->gross_amount),0,',','.') }} sedang menunggu verifikasi admin.</div>
                    @elseif($isAdmin)
                        @if($hasPendingPayment)
                            <div class="alert alert-warning small">Bukti pembayaran Rp {{ number_format((float)($pendingPayment->amount ?? $pendingPayment->gross_amount),0,',','.') }} masih menunggu verifikasi. Catat pembayaran admin di bawah hanya jika ada pembayaran lain yang sudah diterima.</div>
                        @endif
                        <form action="{{ route('orders.payments.store', $order->uuid) }}" method="POST" enctype="multipart/form-data" class="payment-settlement-form p-3 mb-3">
                            @csrf
                            <input type="hidden" name="record_only" value="1">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div>
                                    <div class="fw-semibold">Catat pembayaran diterima</div>
                                    <div class="small text-muted">Masukkan nominal yang benar-benar sudah diterima. Setiap pencatatan akan masuk ke riwayat pembayaran.</div>
                                </div>
                                <span class="badge text-bg-success">Sisa tagihan Rp {{ number_format($remainingAmount,0,',','.') }}</span>
                            </div>
                            <div class="payment-entry-fields">
                                <div>
                                    <label class="form-label small fw-semibold mb-1" for="adminPaymentAmount">Nominal diterima</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="amount" id="adminPaymentAmount" class="form-control" min="1" max="{{ (int)$remainingAmount }}" step="1" value="{{ (int)$remainingAmount }}" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold mb-1" for="adminPaymentProof">Bukti pembayaran <span class="fw-normal text-muted">(opsional)</span></label>
                                    <input type="file" name="proof" id="adminPaymentProof" class="form-control" accept="image/jpeg,image/png,image/webp">
                                </div>
                            </div>
                            <div class="payment-entry-footer">
                                <div class="payment-entry-note">
                                    <label class="form-label small fw-semibold mb-1" for="adminPaymentNote">Catatan <span class="fw-normal text-muted">(opsional)</span></label>
                                    <input type="text" name="admin_note" id="adminPaymentNote" class="form-control" maxlength="1000" placeholder="Contoh: Transfer bank diterima tanggal ...">
                                </div>
                                <button type="submit" class="btn btn-success text-nowrap" onclick="return confirm('Catat pembayaran sebesar Rp ' + new Intl.NumberFormat('id-ID').format(document.getElementById('adminPaymentAmount').value) + ' sebagai pembayaran yang sudah diterima?')">
                                    <i class="bi bi-check-circle me-1"></i>Catat pembayaran
                                </button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('orders.payments.store', $order->uuid) }}" method="POST" enctype="multipart/form-data" class="border rounded-3 p-3 mb-3">
                            @csrf
                            <div class="fw-semibold mb-2">Tambah Pembayaran</div>
                            <div class="row g-2 mb-2"><div class="col-md-8"><label class="form-label small mb-1">Nominal pembayaran</label><input type="number" name="amount" class="form-control" min="1" max="{{ (int)$remainingAmount }}" value="{{ (int)$remainingAmount }}" required></div><div class="col-md-4 d-flex align-items-end"><div class="small text-muted">Sisa: Rp {{ number_format($remainingAmount,0,",",".") }}</div></div></div>
                            <div class="row g-2 align-items-end">
                                <div class="col-md-8"><input type="file" name="proof" class="form-control" accept="image/jpeg,image/png,image/webp" required></div>
                                <div class="col-md-4"><button class="btn btn-primary w-100"><i class="bi bi-upload me-1"></i>Kirim Bukti</button></div>
                            </div>
                        </form>
                    @endif
                @endif

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Nominal</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($order->payments->sortByDesc('created_at') as $p)
                            @php $pStatus = $p->verification_status ?: $p->status; @endphp
                            <tr>
                                <td>{{ ($p->paid_at ?: $p->created_at)?->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($p->kind ?: 'payment') }}</td>
                                <td>Rp {{ number_format((float)($p->amount ?? $p->gross_amount),0,',','.') }}</td>
                                <td><span class="badge {{ $pStatus==='verified'||$p->status==='paid'?'bg-success':($pStatus==='pending'?'bg-warning text-dark':'bg-danger') }}">{{ $pStatus }}</span></td>
                                <td>@if($p->proof_image_path)<a href="{{ asset('storage/'.$p->proof_image_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>@endif</td>
                                <td>
                                    @if($isAdmin && $pStatus === 'pending')
                                        <form action="{{ route('orders.payments.approve', [$order->uuid, $p->uuid]) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-success">Terima</button></form>
                                        <form action="{{ route('orders.payments.reject', [$order->uuid, $p->uuid]) }}" method="POST" class="d-inline">@csrf<input type="hidden" name="admin_note" value="Bukti tidak valid"><button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak pembayaran ini?')">Tolak</button></form>
                                    @elseif($p->note)
                                        <span class="small text-muted">{{ $p->note }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted small">Belum ada riwayat pembayaran.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


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
                        LAYANAN
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


                @if (!empty($order->addons))
                <div class="receipt-block">
                    <div class="receipt-label">Layanan tambahan</div>
                    @foreach ($order->addons as $addon)
                        <div class="d-flex justify-content-between gap-3 py-1 border-bottom" style="font-size:12px;">
                            <span class="fw-semibold">{{ $addon['name'] ?? '-' }} x{{ (int) ($addon['quantity'] ?? 1) }}</span>
                            <span>Rp {{ number_format((int) ($addon['line_total'] ?? $addon['price'] ?? 0), 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="receipt-divider"></div>
                @endif

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
                        Pesanan Anda siap diproses.
                    </span>

                </div>

                @elseif ($order->status === 'pending')

                <div class="receipt-message pending">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Pembayaran masih menunggu. Selesaikan pembayaran untuk memulai pemrosesan pesanan Anda.
                    </span>

                </div>

                @endif
            </div>

        </div>
    </div>
            <aside class="order-detail-side">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Ringkasan Pembayaran</div>
                    <div class="card-body">
                        @php $paymentPercent = $fullAmount > 0 ? min(100, round(($amountPaid / $fullAmount) * 100)) : 0; @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Progres pembayaran</span>
                            <span class="small fw-bold">{{ $paymentPercent }}%</span>
                        </div>
                        <div class="progress mb-3" role="progressbar" aria-label="Progres pembayaran" style="height:7px">
                            <div class="progress-bar {{ $remainingAmount > 0 ? 'bg-primary' : 'bg-success' }}" style="width:{{ $paymentPercent }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Skema</span><span class="small fw-semibold">{{ $order->scheme_label ?? $schemeLabel }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Total paket</span><span class="small fw-semibold">Rp {{ number_format($fullAmount,0,',','.') }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Sudah dibayar</span><span class="small fw-semibold text-success">Rp {{ number_format($amountPaid,0,',','.') }}</span></div>
                        <div class="d-flex justify-content-between pt-2 border-top"><span class="small fw-semibold">Sisa</span><span class="small fw-bold {{ $remainingAmount>0?'text-danger':'text-success' }}">Rp {{ number_format($remainingAmount,0,',','.') }}</span></div>
                        @if($order->balance_due_at)
                        <div class="small text-muted mt-2">Batas pelunasan: {{ $order->balance_due_at->translatedFormat('d M Y') }}</div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Info Pesanan</div>
                    <div class="card-body small">
                        <div class="d-flex justify-content-between align-items-start mb-3"><span class="text-muted">Pelanggan</span><span class="fw-semibold text-end ms-3">{{ $order->user?->name ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between align-items-start mb-3"><span class="text-muted">Paket</span><span class="fw-semibold text-end ms-3">{{ $order->package?->name ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between align-items-start mb-3"><span class="text-muted">Tanggal acara</span><span class="fw-semibold text-end ms-3">{{ $order->event_date?->translatedFormat('d M Y') ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top"><span class="text-muted">Status</span><span class="badge {{ $order->status==='paid'?'bg-success':($order->status==='partially_paid'?'bg-primary':($order->status==='pending'?'bg-warning text-dark':'bg-secondary')) }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></div>
                    </div>
                </div>
                @if($isAdmin)
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Tindakan Cepat</div>
                    <div class="card-body d-grid gap-2">
                        <a href="#payment-module" class="btn btn-outline-primary btn-sm"><i class="bi bi-cash-stack me-1"></i>Modul Pembayaran</a>
                        <a href="{{ route('orders.whatsapp', $order->uuid) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
                    </div>
                </div>
                @endif
            </aside>
        </div>

    </div>

</div>

<style>
    /* =========================================================
       PAGE
    ========================================================= */

    .receipt-page {
        min-height: calc(100vh - 100px);
        padding: 24px 20px 48px;
        background: var(--bs-body-bg);
    }

    .receipt-wrapper {
        width: 100%;
        max-width: 1380px;
        margin: 0 auto;
    }

    .order-detail-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 350px;
        gap: 24px;
        align-items: start;
    }

    .order-detail-main {
        min-width: 0;
    }

    .order-detail-main > .card,
    .order-detail-main > .alert,
    .order-detail-main > .receipt-card {
        margin-bottom: 16px !important;
    }

    .order-detail-side {
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: sticky;
        top: 24px;
    }

    .order-detail-side .card {
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(16,24,40,.06);
        overflow: hidden;
        margin-bottom: 0 !important;
    }

    .order-detail-side .card-header {
        border-bottom: 1px solid var(--bs-border-color);
        padding: 15px 18px;
        font-size: 13px;
        letter-spacing: .01em;
    }

    .order-detail-side .card-body {
        padding: 16px 18px;
    }

    .order-detail-main > .card {
        border-radius: 16px;
        border: 1px solid var(--bs-border-color);
        box-shadow: 0 10px 28px rgba(16,24,40,.05);
        overflow: hidden;
    }

    .order-detail-main > .card > .card-header {
        min-height: 58px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .order-detail-main > .card > .card-body {
        padding: 20px;
    }

    .payment-settlement-form {
        border: 1px solid rgba(22, 163, 74, .22);
        border-radius: 12px;
        background: #f0fdf4;
    }

    .payment-entry-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 12px;
    }

    .payment-entry-footer {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .payment-entry-note {
        flex: 1;
        min-width: 0;
    }

    .payment-entry-footer .btn {
        min-height: 38px;
    }

    @media (max-width: 575.98px) {
        .payment-entry-fields {
            grid-template-columns: 1fr;
        }

        .payment-entry-footer {
            align-items: stretch;
            flex-direction: column;
        }
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
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #fff !important;
    }

    .print-button-primary:hover {
        color: #fff;
        background: #1d4ed8;
        border-color: #1d4ed8;
        filter: none;
        box-shadow: 0 8px 18px rgba(37, 99, 235, .22);
    }

    .order-detail-main .btn-success,
    .order-detail-side .btn-outline-success,
    .receipt-topbar a[href*="whatsapp"] {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .order-detail-main .btn-success:hover,
    .order-detail-side .btn-outline-success:hover,
    .receipt-topbar a[href*="whatsapp"]:hover {
        background: #15803d;
        border-color: #15803d;
        color: #fff;
        box-shadow: 0 8px 18px rgba(22, 163, 74, .18);
    }

    .order-detail-main .btn-primary,
    .order-detail-main button[type="submit"].btn-primary {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .order-detail-main .btn-primary:hover,
    .order-detail-main button[type="submit"].btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 8px 18px rgba(37, 99, 235, .20);
    }

    .order-detail-main .btn-outline-danger {
        border-width: 1.5px;
        border-color: #dc2626;
        color: #dc2626;
    }

    .order-detail-main .btn-outline-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    .order-detail-side .btn-outline-primary {
        border-width: 1.5px;
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }

    .order-detail-side .btn-outline-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
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
        margin-bottom: 16px;
        padding: 12px 14px;
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        background: var(--bs-body-bg);
        box-shadow: 0 8px 24px rgba(16,24,40,.04);
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

     .order-detail-side .card-body{padding:16px;} .payment-module-summary .p-3{border:1px solid var(--bs-border-color); background:#fff !important;} .receipt-card {
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
            position: static;
        }

        .receipt-topbar {
            align-items: flex-start;
            gap: 12px;
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

         .order-detail-side .card-body{padding:16px;} .payment-module-summary .p-3{border:1px solid var(--bs-border-color); background:#fff !important;} .receipt-card {
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
