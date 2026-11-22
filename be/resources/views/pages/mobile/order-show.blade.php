@extends('layouts.mobile')
@section('title', __('mobile.order_detail'))
@section('content')

<section class="screen active" id="order-detail">
    <div class="px-4 pt-4">

        {{-- BACK --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('orders.index') }}"
                class="text-dark text-decoration-none d-inline-flex align-items-center" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <i class="bi bi-arrow-left fs-5"></i>
                <span class="ms-2 small fw-semibold">{{ __('mobile.back') }}</span>
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i>{{ __('mobile.refresh') }}
            </button>
        </div>

        @php
            $isOwner = $order->user_uuid === auth()->user()->uuid;
            $bankName = config('manual_payment.bank_name');
            $bankAccount = config('manual_payment.bank_account');
            $bankHolder = config('manual_payment.bank_holder');
            $waNumber = preg_replace('/\D/', '', (string) config('manual_payment.admin_whatsapp'));
            $waText = 'Halo Admin Sagita Wedding Organizer, saya telah melakukan transfer Rp ' . number_format($order->amount, 0, ',', '.') . ' untuk pesanan ' . $order->order_number . ' (' . ($order->user?->name ?? '') . '). Terima kasih.';
        @endphp

        {{-- STATUS --}}
        <div class="app-card p-4 text-center mt-2">
            @if ($order->status === 'paid')
                <div class="status-circle paid d-inline-flex align-items-center justify-content-center mx-auto"
                    style="width:56px;height:56px;border-radius:50%;background:#e8f3ea;">
                    <i class="bi bi-check-lg text-success fs-4"></i>
                </div>
                <h2 class="h5 fw-semibold mt-3 mb-1">{{ __('mobile.payment_successful') }}</h2>
                <p class="mb-0 text-muted2 text-small">{{ __('mobile.payment_successful_desc') }}</p>
            @elseif ($order->status === 'pending')
                <div class="d-inline-flex align-items-center justify-content-center mx-auto"
                    style="width:56px;height:56px;border-radius:50%;background:#fff4e5;">
                    <i class="bi bi-clock text-warning fs-4"></i>
                </div>
                <h2 class="h5 fw-semibold mt-3 mb-1">{{ __('mobile.payment_pending') }}</h2>
                <p class="mb-0 text-muted2 text-small">{{ __('mobile.payment_pending_desc') }}</p>
            @elseif ($order->status === 'failed')
                <div class="d-inline-flex align-items-center justify-content-center mx-auto"
                    style="width:56px;height:56px;border-radius:50%;background:#fdecea;">
                    <i class="bi bi-x-lg text-danger fs-4"></i>
                </div>
                <h2 class="h5 fw-semibold mt-3 mb-1">{{ __('mobile.payment_failed') }}</h2>
                <p class="mb-0 text-muted2 text-small">{{ __('mobile.payment_failed_desc') }}</p>
            @else
                <div class="d-inline-flex align-items-center justify-content-center mx-auto"
                    style="width:56px;height:56px;border-radius:50%;background:#ededed;">
                    <i class="bi bi-hourglass-bottom text-secondary fs-4"></i>
                </div>
                <h2 class="h5 fw-semibold mt-3 mb-1">{{ __('mobile.order_status.' . $order->status) }}</h2>
                <p class="mb-0 text-muted2 text-small">{{ __('mobile.order') }} #{{ $order->order_number }}</p>
            @endif
        </div>

        {{-- MEMBERSHIP ACTIVE --}}
        @if ($userPackage)
            <div class="alert alert-success d-flex align-items-center gap-2 mt-3">
                <i class="bi bi-check-circle-fill"></i>
                <div style="font-size:13px;">
                    @if ($userPackage->quota === null)
                        {{ __('mobile.membership_active_unlimited', ['date' => $userPackage->expired_at?->format('d M Y') ?? '-']) }}
                    @else
                        {{ __('mobile.membership_active_until', ['quota' => $userPackage->quota == 1 ? __('mobile.class_left', ['count' => $userPackage->quota]) : __('mobile.classes_left', ['count' => $userPackage->quota]), 'date' => $userPackage->expired_at?->format('d M Y') ?? '-']) }}
                    @endif
                </div>
            </div>
        @endif

        {{-- SUMMARY --}}
        @php
            $fullAmount = (float) ($order->total_amount ?: $order->amount);
            $amountPaid = (float) ($order->amount_paid_value ?? ($order->status === 'paid' ? $fullAmount : 0));
            $remainingAmount = (float) ($order->remaining_amount_value ?? max(0, $fullAmount - $amountPaid));
            $schemeLabel = $order->scheme_label ?? ($order->promo_snapshot['scheme_label'] ?? 'Bayar Lunas');
        @endphp
        <div class="app-card p-3 mt-3">
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.order') }}</span>
                <span class="small fw-semibold">#{{ $order->order_number }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.package') }}</span>
                <span class="small fw-semibold text-end" style="max-width:60%;">
                    {{ $order->package?->name ?? '-' }}
                    @if ($order->packageOption?->name)
                        <small class="d-block text-muted2 fw-normal">{{ $order->packageOption->name }}</small>
                    @endif
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">Skema</span>
                <span class="small fw-semibold">{{ $schemeLabel }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.date') }}</span>
                <span class="small fw-semibold">{{ $order->created_at?->format('d M Y') ?? '-' }}</span>
            </div>
            @if ($order->event_date)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted2 text-small">Tanggal Acara</span>
                    <span class="small fw-semibold">{{ $order->event_date->translatedFormat('d F Y') }}</span>
                </div>
            @endif
            @if ($order->promo_code)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted2 text-small">Promo</span>
                    <span class="small fw-semibold text-end">{{ $order->promo_code }} (Hemat Rp {{ number_format((int) $order->promo_discount, 0, ',', '.') }})</span>
                </div>
            @endif
            @if (!empty($order->addons))
                <div class="py-2 border-bottom">
                    <div class="text-muted2 text-small mb-1">{{ __('mobile.layanan_tambahan') }}</div>
                    @foreach ($order->addons as $addon)
                        <div class="d-flex justify-content-between gap-3 py-1">
                            <span class="small">{{ $addon['name'] ?? '-' }} x{{ (int) ($addon['quantity'] ?? 1) }}</span>
                            <span class="small fw-semibold">Rp {{ number_format((int) ($addon['line_total'] ?? $addon['price'] ?? 0), 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">Total Paket</span>
                <span class="small fw-semibold">Rp {{ number_format($fullAmount, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted2 text-small">Sudah Dibayar</span>
                <span class="small fw-semibold text-success">Rp {{ number_format($amountPaid, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
                <span class="text-muted2 text-small">Sisa</span>
                <span class="small fw-semibold {{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
            </div>
            @if($order->balance_due_at)
            <div class="pt-1 m-micro text-muted2">Batas pelunasan: {{ $order->balance_due_at->translatedFormat('d M Y') }}</div>
            @endif
        </div>

        {{-- ADD PAYMENT --}}
        @if ($isOwner && in_array($order->status, ['pending','partially_paid'], true))
            @php
                $pendingPayment = $order->payments->firstWhere('verification_status', 'pending') ?? $order->payments->firstWhere('status', 'pending');
                $hasPendingPayment = (bool) $pendingPayment;
            @endphp
            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-2">Tambah Pembayaran</h3>
                <div class="m-meta text-muted2 mb-2">Sisa pembayaran: <strong class="text-danger">Rp {{ number_format($remainingAmount,0,',','.') }}</strong></div>
                @if($hasPendingPayment)
                    <div class="alert alert-warning small mb-0">Bukti pembayaran Rp {{ number_format((float)($pendingPayment->amount ?? $pendingPayment->gross_amount),0,',','.') }} sedang menunggu verifikasi admin.</div>
                @else
                    <form action="{{ route('orders.payments.store', $order->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="form-label small mb-1">Nominal pembayaran</label>
                        <input type="number" name="amount" class="form-control mb-2" min="1" max="{{ (int)$remainingAmount }}" value="{{ (int)$remainingAmount }}" required>
                        <label class="form-label small mb-1">Bukti transfer</label>
                        <input type="file" name="proof" class="form-control mb-2" accept="image/jpeg,image/png,image/webp" required>
                        <button class="btn btn-sage w-100"><i class="bi bi-upload me-1"></i>Kirim Bukti Pembayaran</button>
                    </form>
                @endif
            </div>
        @endif

        {{-- PAYMENT HISTORY --}}
        @if($order->payments->isNotEmpty())
            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-2">Riwayat Pembayaran</h3>
                @foreach($order->payments->sortByDesc('created_at') as $p)
                    @php $pStatus = $p->verification_status ?: $p->status; @endphp
                    <div class="py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                <div class="small fw-semibold">{{ ucfirst($p->kind ?: 'payment') }} - Rp {{ number_format((float)($p->amount ?? $p->gross_amount),0,',','.') }}</div>
                                <div class="m-micro text-muted2">{{ ($p->paid_at ?: $p->created_at)?->format('d M Y H:i') }}</div>
                            </div>
                            <span class="badge {{ $pStatus==='verified'||$p->status==='paid'?'bg-success-subtle text-success':($pStatus==='pending'?'bg-warning-subtle text-warning-emphasis':'bg-danger-subtle text-danger') }} align-self-start">{{ $pStatus }}</span>
                        </div>
                        @if($p->proof_image_path)
                            <a href="{{ route('orders.payments.proof', [$order->uuid, $p->uuid]) }}" target="_blank" class="small">Lihat bukti</a>
                        @endif
                        @if($p->note)<div class="m-micro text-muted2">{{ $p->note }}</div>@endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- PAYMENT INSTRUCTIONS --}}
        @if ($order->status === 'pending')
            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-2">{{ __('mobile.complete_payment') }}</h3>
                <ol class="small text-muted2 mb-3 ps-3 text-small" style="line-height:1.6;">
                    @foreach (explode('|', __('mobile.payment_steps')) as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>

                @php
                    $schemeLabel = $order->promo_snapshot['scheme_label'] ?? 'Bayar Lunas';
                    $fullAmount = $order->promo_snapshot['full_amount'] ?? $order->amount;
                @endphp
                <div class="app-card p-2 mb-3 text-small">
                    <div>Skema pembayaran: <strong>{{ $schemeLabel }}</strong></div>
                    @if ((int) $fullAmount !== (int) $order->amount)
                    <div class="text-muted2">Total paket Rp {{ number_format($fullAmount, 0, ',', '.') }} — dibayar sekarang Rp {{ number_format($order->amount, 0, ',', '.') }}.</div>
                    @endif
                </div>

                @if ($bankAccount)
                    <div class="p-3 rounded-3 bg-sage-soft mb-3">
                        <div class="m-micro text-uppercase text-muted2">{{ __('mobile.bank') }}</div>
                        <div class="fw-bold">{{ $bankName }}</div>
                        <div class="m-micro fw-semibold">{{ $bankAccount }}</div>
                        @if ($bankHolder)
                            <div class="m-micro text-muted2">{{ __('mobile.account_holder', ['name' => $bankHolder]) }}</div>
                        @endif
                        <div class="mt-2 m-meta">
                            {{ __('mobile.amount') }}: <strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                @endif

                @if ($waNumber)
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank"
                        rel="noopener" class="btn btn-success w-100 mb-3">
                        <i class="bi bi-whatsapp me-1"></i> {{ __('mobile.confirm_whatsapp') }}
                    </a>
                @endif

                @if ($order->proof_image_path)
                    <div class="p-3 rounded-3 bg-sage-soft mb-3">
                        <a href="{{ $order->proofUrl() }}" target="_blank" rel="noopener" class="text-decoration-none d-block mb-2">
                            <img src="{{ $order->proofUrl() }}" alt="Bukti transfer"
                                class="rounded-3 border w-100" style="max-height:200px;object-fit:cover;" loading="lazy" decoding="async">
                        </a>
                        <div class="m-meta text-success fw-semibold">
                            <i class="bi bi-check-circle me-1"></i>{{ __('mobile.proof_uploaded') }}
                        </div>
                        <div class="m-micro text-muted2">
                            {{ $order->proof_uploaded_at?->translatedFormat('d F Y H:i') ?? '' }} — menunggu verifikasi admin.
                        </div>
                    </div>
                @endif

                @if ($isOwner)
                    <form action="{{ route('orders.proof', $order->uuid) }}" method="POST"
                        enctype="multipart/form-data" id="proofForm">
                        @csrf
                        <label class="m-meta fw-semibold" for="proofInput">
                            {{ __('mobile.proof_hint', ['size' => number_format(config('manual_payment.proof_max_kb', 3072) / 1024, 1)]) }}
                        </label>
                        <input type="file" name="proof" id="proofInput" class="form-control mt-1"
                            accept="image/jpeg,image/png,image/webp" required>
                        <img id="proofPreview" alt="" class="rounded-3 border w-100 mt-2 d-none" style="max-height:200px;object-fit:cover;">
                        <p id="proofError" class="text-danger m-meta mt-1 mb-0 d-none"></p>
                        <button type="submit" class="btn btn-sage w-100 mt-2" id="proofSubmit">
                            <i class="bi bi-upload me-1"></i>
                            {{ $order->proof_image_path ? __('mobile.replace_proof') : __('mobile.upload_proof') }}
                        </button>
                    </form>
                    <script>
                        (function () {
                            var input = document.getElementById('proofInput');
                            var preview = document.getElementById('proofPreview');
                            var err = document.getElementById('proofError');
                            var maxKb = {{ (int) config('manual_payment.proof_max_kb', 3072) }};
                            var tooLargeTpl = @json(__('mobile.file_too_large', ['size' => '__SIZE__']));
                            if (!input) return;
                            input.addEventListener('change', function () {
                                err.classList.add('d-none');
                                var f = input.files && input.files[0];
                                if (!f) { preview.classList.add('d-none'); return; }
                                if (f.size > maxKb * 1024) {
                                    err.textContent = tooLargeTpl.replace('__SIZE__', (maxKb / 1024).toFixed(1));
                                    err.classList.remove('d-none');
                                    input.value = '';
                                    preview.classList.add('d-none');
                                    return;
                                }
                                var r = new FileReader();
                                r.onload = function (e) { preview.src = e.target.result; preview.classList.remove('d-none'); };
                                r.readAsDataURL(f);
                            });
                        })();
                    </script>
                @endif
            </div>
        @endif

        {{-- REORDER --}}
        @if (in_array($order->status, ['failed', 'expired', 'cancelled'], true))
            <form action="{{ route('orders.reorder', $order->uuid) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-warm w-100">
                    <i class="bi bi-arrow-repeat me-1"></i> Pesan Lagi
                </button>
            </form>
        @endif

        <div style="height: 24px"></div>
    </div>
</section>

@if ($order->status === 'pending')
    <script>
        // Auto-refresh ringan: cek status tiap 45 detik, hanya saat tab terlihat
        // dan user tidak sedang memilih file bukti transfer.
        (function () {
            setInterval(function () {
                if (document.visibilityState !== 'visible') return;
                var input = document.getElementById('proofInput');
                if (input && input.files && input.files.length) return;
                window.location.reload();
            }, 45000);
        })();
    </script>
@endif

@endsection
