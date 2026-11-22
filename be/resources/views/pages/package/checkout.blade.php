@extends('layouts.app')
@section('title', 'Pembayaran Paket')
@section('content')

@php
$leadTimeDays = (int) ($package->lead_time_days ?? 0);
$minEventDate = now()->addDays($leadTimeDays)->toDateString();
$blackoutDates = $package->blackoutDates ?? collect();
$initialOption = $package->options->first();
$firstFinalPrice = $initialOption
    ? (int) ($initialOption->discount_price !== null && $initialOption->discount_price < $initialOption->price
        ? $initialOption->discount_price
        : $initialOption->price)
    : 0;
$initialBookingFee = min((int) $bookingFeeAmount, $firstFinalPrice);
$initialNetAmount = max(0, $firstFinalPrice - $initialBookingFee);
$initialDueNow = $initialBookingFee;
$initialRemainingAmount = max(0, $firstFinalPrice - $initialDueNow);
$promos = ($package->promos ?? collect())->map(function ($promo) {
    return [
        'code' => strtoupper($promo->code),
        'type' => $promo->type,
        'value' => (int) $promo->value,
        'min_purchase' => (int) $promo->min_purchase,
        'max_discount' => $promo->max_discount,
    ];
})->values()->all();
@endphp

<div class="container-fluid package-checkout">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                Lengkapi Pemesanan Paket
            </h4>
            <p class="text-muted mb-0">
                Pilih opsi paket yang paling sesuai dengan kebutuhan pernikahan Anda.
            </p>
        </div>

        <a href="{{ route('packages.member') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar Paket
        </a>
    </div>


    <div class="row g-4">

        {{-- =====================================================
             LEFT
        ====================================================== --}}
        <div class="col-lg-8">

            {{-- Package Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex justify-content-between align-items-start gap-3">

                        <div class="d-flex align-items-center gap-3">

                            <div class="package-section-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    {{ $package->name }}
                                </h5>

                                <p class="text-muted small mb-0">
                                    Paket Wedding Organizer
                                </p>
                            </div>

                        </div>

                        @if ($package->is_popular)
                        <span class="popular-label">
                            <i class="bi bi-star-fill me-1"></i>
                            Paling Dipilih
                        </span>
                        @endif

                    </div>

                </div>


                <div class="card-body px-4 pb-4">

                    @if ($package->description)
                    <p class="text-muted mb-3 package-description">
                        {{ $package->description }}
                    </p>
                    @endif

                    @if ($package->guest_capacity || $package->event_duration_hours || $package->venue_note)
                    <div class="checkout-event-meta">
                        @if ($package->guest_capacity)
                        <span><i class="bi bi-people me-1"></i>Kapasitas Tamu: <strong>{{ number_format($package->guest_capacity, 0, ',', '.') }} tamu</strong></span>
                        @endif

                        @if ($package->event_duration_hours)
                        <span><i class="bi bi-clock me-1"></i>Durasi Acara: <strong>{{ $package->event_duration_hours }} jam</strong></span>
                        @endif

                        @if ($package->venue_note)
                        <span><i class="bi bi-geo-alt me-1"></i>Lokasi Acara: <strong>{{ $package->venue_note }}</strong></span>
                        @endif
                    </div>
                    @endif


                </div>

            </div>


            {{-- Pilih Opsi --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="package-section-icon">
                            <i class="bi bi-tags"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Pilih Opsi Paket
                            </h5>

                            <p class="text-muted small mb-0">
                                Pilih durasi dan kuota yang diinginkan.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <div class="package-options">

                        @foreach ($package->options as $index => $option)

                        @php
                        $hasDiscount =
                        $option->discount_price !== null &&
                        $option->discount_price < $option->price;

                            $finalPrice = $hasDiscount
                            ? $option->discount_price
                            : $option->price;
                            @endphp

                            <button
                                type="button"
                                class="package-option-choice {{ $index === 0 ? 'selected' : '' }}"
                                data-option-uuid="{{ $option->uuid }}"
                                data-option-name="{{ $option->name }}"
                                data-option-price="{{ $finalPrice }}"
                                data-option-regular-price="{{ $option->price }}"
                                data-option-quota="{{ $option->quota ?? '' }}"
                                data-option-duration="{{ $option->duration }}"
                                data-option-duration-unit="{{ $option->duration_unit }}">

                                <div class="option-radio">
                                    <span></span>
                                </div>


                                <div class="option-main">

                                    <div class="d-flex justify-content-between align-items-start gap-3">

                                        <div>

                                            <h6 class="fw-bold mb-1">
                                                {{ $option->name }}
                                            </h6>

                                            <div class="option-meta">

                                                <span>
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ $option->duration }}
                                                    {{ ucfirst($option->duration_unit) }}
                                                </span>

                                                <span>
                                                    <i class="bi bi-person-check"></i>

                                                    @if ($option->quota === null)
                                                    Kuota tanpa batas
                                                    @else
                                                    Kuota {{ $option->quota }}
                                                    @endif
                                                </span>

                                            </div>

                                        </div>


                                        <div class="option-price-wrapper text-end">

                                            @if ($hasDiscount)
                                            <div class="option-old-price">
                                                Rp {{ number_format($option->price, 0, ',', '.') }}
                                            </div>
                                            @endif

                                            <div class="option-price">
                                                Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="option-check">
                                    <i class="bi bi-check-lg"></i>
                                </div>

                            </button>

                            @endforeach

                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="package-section-icon">
                            <i class="bi bi-plus-square"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Layanan Tambahan (Opsional)
                            </h5>

                            <p class="text-muted small mb-0">
                                Pilih layanan tambahan yang ingin disertakan dalam pesanan.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    @forelse ($package->addons ?? [] as $addon)
                    <label class="addon-option-item">
                        <input
                            type="checkbox"
                            class="addon-option form-check-input"
                            data-addon-uuid="{{ $addon->uuid }}"
                            data-addon-name="{{ $addon->name }}"
                            data-addon-price="{{ (int) $addon->final_price }}">

                        <span class="addon-option-body">
                            <span class="fw-semibold d-block">
                                {{ $addon->name }}
                            </span>

                            @if ($addon->description)
                            <span class="text-muted small d-block">
                                {{ $addon->description }}
                            </span>
                            @endif
                        </span>

                        <span class="addon-qty-field">
                            <span class="text-muted small d-block">Jumlah</span>
                            <input
                                type="number"
                                class="form-control form-control-sm addon-quantity"
                                form="checkoutForm"
                                name="addon_quantities[{{ $addon->uuid }}]"
                                data-addon-uuid="{{ $addon->uuid }}"
                                data-addon-name="{{ $addon->name }}"
                                data-addon-price="{{ (int) $addon->final_price }}"
                                min="{{ max(1, (int) $addon->min_quantity) }}"
                                max="{{ $addon->max_quantity ?? 99 }}"
                                value="{{ max(1, (int) $addon->min_quantity) }}">
                        </span>

                        <span class="addon-option-price">
                            Rp {{ number_format($addon->final_price, 0, ',', '.') }}
                        </span>
                    </label>
                    @empty
                    <p class="text-muted small mb-0">
                        Belum ada layanan tambahan untuk paket ini.
                    </p>
                    @endforelse

                </div>

            </div>


            {{-- Features --}}
            @if ($package->features->count())

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="package-section-icon">
                            <i class="bi bi-list-check"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Yang Termasuk
                            </h5>

                            <p class="text-muted small mb-0">
                                Semua layanan yang termasuk dalam paket ini.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        @foreach ($package->features as $feature)

                        <div class="col-md-6">

                            <div class="feature-item">

                                <div class="feature-icon">
                                    <i class="bi bi-check2"></i>
                                </div>

                                <span>
                                    {{ $feature->feature }}
                                </span>

                            </div>

                        </div>

                        @endforeach

                    </div>

                </div>

            </div>

            @endif

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="package-section-icon">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Kebijakan Pengembalian Dana
                            </h5>

                            <p class="text-muted small mb-0">
                                Harap baca sebelum melakukan pembayaran
                            </p>
                        </div>

                    </div>

                </div>

                <div class="card-body px-4 pb-4">

                    <p class="text-muted mb-3">
                        Semua pembayaran bersifat
                        <strong class="text-dark">final dan tidak dapat dikembalikan</strong>
                        setelah pemesanan selesai.
                    </p>

                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>

                        <p class="text-muted mb-0">
                            Paket yang sudah dipesan
                            <strong class="text-dark">
                                tidak dapat dialihkan, diperpanjang, atau ditukar.
                            </strong>
                        </p>
                    </div>

                </div>

                </div>

                <div class="mt-4 checkout-scheme-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-cash-coin fs-5"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Skema Pembayaran</h6>
                            <div class="text-muted small mb-0">Pilih jumlah yang dibayar sekarang.</div>
                        </div>
                    </div>

                    <div class="checkout-scheme-options">
                        <label class="checkout-scheme-option">
                            <input type="radio" name="payment_scheme" value="no_dp" form="checkoutForm" class="payment-scheme-input" checked>
                            <span class="checkout-scheme-body">
                                <strong>Tanpa DP</strong>
                                <small>Bayar booking fee sekarang, sisa setelah booking fee</small>
                            </span>
                        </label>
                        <label class="checkout-scheme-option">
                            <input type="radio" name="payment_scheme" value="dp30" form="checkoutForm" class="payment-scheme-input">
                            <span class="checkout-scheme-body">
                                <strong>DP 30%</strong>
                                <small>30% dari sisa setelah booking fee</small>
                            </span>
                        </label>
                        <label class="checkout-scheme-option">
                            <input type="radio" name="payment_scheme" value="dp50" form="checkoutForm" class="payment-scheme-input">
                            <span class="checkout-scheme-body">
                                <strong>DP 50%</strong>
                                <small>50% dari sisa setelah booking fee</small>
                            </span>
                        </label>
                        <label class="checkout-scheme-option">
                            <input type="radio" name="payment_scheme" value="full" form="checkoutForm" class="payment-scheme-input">
                            <span class="checkout-scheme-body">
                                <strong>Bayar Lunas</strong>
                                <small>Lunasi seluruh sisa</small>
                            </span>
                        </label>
                    </div>
                </div>

            </div>


        {{-- =====================================================
             RIGHT
        ====================================================== --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm checkout-summary">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="summary-icon">
                            <i class="bi bi-receipt"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Ringkasan Pesanan
                            </h5>

                            <p class="text-muted small mb-0">
                                Periksa Pesanan Paket Anda
                            </p>
                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    {{-- Package --}}
                    <div class="summary-package">

                        <div class="summary-label">
                            PAKET
                        </div>

                        <div class="summary-package-name">
                            {{ $package->name }}
                        </div>

                    </div>


                    {{-- Option --}}
                    <div class="summary-selected">

                        <div class="summary-label mb-1">
                            OPSI TERPILIH
                        </div>

                        <div
                            id="summaryOptionName"
                            class="fw-bold">
                            {{ $package->options->first()?->name ?? '-' }}
                        </div>


                        <div class="summary-details">

                            <div class="summary-detail">

                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    Durasi
                                </span>

                                <strong id="summaryDuration">
                                    @if ($package->options->first())
                                    {{ $package->options->first()->duration }}
                                    {{ ucfirst($package->options->first()->duration_unit) }}
                                    @else
                                    -
                                    @endif
                                </strong>

                            </div>


                            <div class="summary-detail">

                                <span>
                                    <i class="bi bi-person-check"></i>
                                    Kuota
                                </span>

                                <strong id="summaryQuota">

                                    @if ($package->options->first())

                                    @if ($package->options->first()->quota === null)
                                    Tanpa batas
                                    @else
                                    Kuota {{ $package->options->first()->quota }}
                                    @endif

                                    @else
                                    -
                                    @endif

                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="summary-addons d-none" id="summaryAddonsBlock">

                        <div class="summary-label mb-1">
                            LAYANAN TAMBAHAN
                        </div>

                        <div id="summaryAddonsList" class="small text-muted"></div>

                    </div>


                    @if ($package->addons->isNotEmpty() || count($promos))
                    <div class="summary-pricing">
                        <div class="d-flex justify-content-between align-items-end summary-addon-row d-none" id="summaryAddonRow">

                            <span class="text-muted small">
                                Layanan tambahan
                            </span>

                            <div class="text-end">
                                <div id="summaryAddonTotal" class="summary-price">
                                    Rp 0
                                </div>
                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-end summary-discount-row d-none" id="summaryDiscountRow">

                            <span class="text-muted small">
                                Diskon promo
                            </span>

                            <div class="text-end">
                                <div id="summaryDiscount" class="summary-price">
                                    Rp 0
                                </div>
                            </div>

                        </div>
                        @endif


                        <div class="summary-divider"></div>

                        <div class="checkout-price-breakdown">
                            <div class="checkout-summary-row">
                                <span class="text-muted small">Total pesanan</span>
                                <strong id="summaryTotal" class="summary-total">Rp {{ number_format($firstFinalPrice, 0, ',', '.') }}</strong>
                            </div>

                            <div class="checkout-summary-row">
                                <span class="text-muted small">Booking Fee (wajib)</span>
                                <strong id="summaryBookingFee" class="summary-price">Rp {{ number_format($initialBookingFee, 0, ',', '.') }}</strong>
                            </div>

                            <div class="checkout-summary-row">
                                <span class="text-muted small">Sisa setelah booking fee</span>
                                <strong id="summaryNetAmount" class="summary-price">Rp {{ number_format($initialNetAmount, 0, ',', '.') }}</strong>
                            </div>

                            <div id="summarySchemeRow" class="checkout-summary-row d-none">
                                <span id="summarySchemeLabel" class="text-muted small">DP dari sisa</span>
                                <strong id="summarySchemeDue" class="summary-price">Rp 0</strong>
                            </div>

                            <div class="checkout-due-row">
                                <span class="fw-semibold">Dibayar sekarang</span>
                                <strong id="summaryDue" class="summary-total">Rp {{ number_format($initialDueNow, 0, ',', '.') }}</strong>
                            </div>

                            <div class="checkout-remaining-row">
                                <span class="text-muted small">Sisa pembayaran</span>
                                <strong id="summaryRemainingAmount" class="summary-price">Rp {{ number_format($initialRemainingAmount, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                    </div>


                    {{-- CTA --}}

                    <form
                        action="{{ route('orders.store') }}"
                        method="POST"
                        id="checkoutForm">
                        @csrf

                        <div class="mb-3">
                            <label for="event_date" class="form-label small fw-semibold">
                                Tanggal Acara
                            </label>

                            <input
                                type="date"
                                name="event_date"
                                id="event_date"
                                class="form-control"
                                min="{{ $minEventDate }}"
                                value="{{ old('event_date', $minEventDate) }}"
                                required>

                            <div class="form-text">
                                Minimal {{ $leadTimeDays }} hari sebelum acara.

                                @if ($blackoutDates->count())
                                Tanggal tidak tersedia:
                                {{ $blackoutDates->pluck('date')->map(fn ($date) => $date?->format('d M Y'))->implode(', ') }}
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="promo_code" class="form-label small fw-semibold">
                                Kode Promo
                            </label>

                            <input
                                type="text"
                                name="promo_code"
                                id="promo_code"
                                class="form-control"
                                maxlength="50"
                                placeholder="Masukkan kode promo"
                                value="{{ old('promo_code') }}">

                            @if ($promos)
                            <div class="form-text">
                                Promo tersedia: {{ collect($promos)->pluck('code')->implode(', ') }}
                            </div>
                            @endif
                        </div>

                        <input
                            type="hidden"
                            name="type"
                            value="package">

                        <input
                            type="hidden"
                            name="package_option_uuid"
                            id="packageOptionUuid">

                        <div id="addonUuidInputs"></div>

                        <button
                            type="button"
                            id="continuePayment"
                            class="btn btn-primary w-100 continue-payment">
                            Lanjutkan ke Pembayaran
                            <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>

                    <p class="text-muted text-center small mb-0 mt-3">
                        Anda akan meninjau pesanan sebelum melakukan pembayaran.
                    </p>

                </div>

            </div>


            {{-- Pemesanan Aman --}}
            <div class="secure-checkout">

                <div class="secure-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <div class="fw-semibold small">
                        Pemesanan Aman
                    </div>

                    <div class="text-muted">
                        Informasi pembayaran Anda ditangani dengan aman.
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="checkoutConfirmModal" tabindex="-1" aria-labelledby="checkoutConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkout-confirm-modal">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="checkoutConfirmLabel">Konfirmasi Pesanan</h5>
                    <div class="text-muted small">Periksa detail sebelum melanjutkan pembayaran.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="checkout-confirm-row">
                    <span>Paket</span>
                    <strong id="confirmOption">-</strong>
                </div>
                <div class="checkout-confirm-row">
                    <span>Tanggal acara</span>
                    <strong id="confirmDate">-</strong>
                </div>
                <div class="checkout-confirm-row">
                    <span>Skema pembayaran</span>
                    <strong id="confirmScheme">Bayar Lunas</strong>
                </div>
                <div class="checkout-confirm-row">
                    <span>Total paket</span>
                    <strong id="confirmTotal">Rp 0</strong>
                </div>
                <div class="checkout-confirm-row checkout-confirm-due">
                    <span>Dibayar sekarang</span>
                    <strong id="confirmDue">Rp 0</strong>
                </div>
                <div class="checkout-confirm-row">
                    <span>Sisa pembayaran</span>
                    <strong id="confirmRemaining">Rp 0</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Ubah</button>
                <button type="submit" form="checkoutForm" id="confirmCheckout" class="btn btn-primary">
                    Konfirmasi & Bayar
                    <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    /* =========================================================
   PEMBAYARAN
========================================================= */

    .package-checkout {
        padding-bottom: 40px;
        max-width: 1520px;
        margin: 0 auto;
    }


    /* =========================================================
   SECTION ICON
========================================================= */

    .package-section-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(75, 107, 82, .08);
        color: var(--bs-primary);

        font-size: 17px;
        flex-shrink: 0;
    }


    /* =========================================================
   PACKAGE
========================================================= */

    .package-description {
        font-size: 14px;
        line-height: 1.7;
        max-width: 850px;
    }


    .checkout-event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 20px;

        color: var(--bs-secondary-color);
        font-size: 12px;
    }


    .checkout-event-meta span {
        display: inline-flex;
        align-items: center;
    }


    .popular-label {
        display: inline-flex;
        align-items: center;

        padding: 7px 11px;

        border-radius: 999px;

        background: rgba(196, 112, 60, .10);
        color: #c4703c;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;
    }


    /* =========================================================
   OPTIONS
========================================================= */

    .package-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }


    .package-option-choice {
        width: 100%;

        position: relative;

        display: flex;
        align-items: center;

        gap: 15px;

        padding: 18px;

        border: 1px solid var(--bs-border-color);
        border-radius: 12px;

        background: var(--bs-body-bg);
        color: inherit;

        text-align: left;

        cursor: pointer;

        transition:
            border-color .2s ease,
            background .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }


    .package-option-choice:hover {
        border-color: rgba(75, 107, 82, .45);
        transform: translateY(-1px);
    }


    .package-option-choice.selected {
        border-color: var(--bs-primary);
        background: rgba(75, 107, 82, .035);
        box-shadow: 0 0 0 3px rgba(75, 107, 82, .07);
    }


    .option-radio {
        width: 20px;
        height: 20px;

        border: 2px solid var(--bs-border-color);
        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;
    }


    .package-option-choice.selected .option-radio {
        border-color: var(--bs-primary);
    }

    .bi-check-lg {
        width: auto;
        height: auto;
    }


    .package-option-choice.selected .option-radio span {
        width: 10px;
        height: 10px;

        border-radius: 50%;

        background: var(--bs-primary);
    }


    .option-main {
        flex: 1;
        min-width: 0;
    }


    .option-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;

        margin-top: 7px;

        color: var(--bs-secondary-color);
        font-size: 12px;
    }


    .option-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }


    .option-meta i {
        font-size: 13px;
    }


    .option-price-wrapper {
        padding-right: 22px;
    }


    .option-price {
        color: #c4703c;
        font-size: 19px;
        font-weight: 800;
        white-space: nowrap;
    }


    .option-old-price {
        color: var(--bs-secondary-color);
        font-size: 11px;
        text-decoration: line-through;
    }


    .option-check {
        position: absolute;

        top: 12px;
        right: 12px;

        width: 21px;
        height: 21px;

        border-radius: 50%;

        display: none;
        align-items: center;
        justify-content: center;

        background: var(--bs-primary);
        color: #fff;

        font-size: 11px;
    }


    .package-option-choice.selected .option-check {
        display: flex;
    }


    .addon-option-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px;

        border: 1px solid var(--bs-border-color);
        border-radius: 12px;

        cursor: pointer;

        transition:
            border-color .2s ease,
            background .2s ease;
    }


    .addon-option-item + .addon-option-item {
        margin-top: 10px;
    }


    .addon-option-item:hover {
        border-color: rgba(75, 107, 82, .45);
    }


    .addon-option-item:has(.addon-option:checked) {
        border-color: var(--bs-primary);
        background: rgba(75, 107, 82, .035);
    }


    .addon-option {
        margin-top: 2px;
        flex-shrink: 0;
    }


    .addon-option-body {
        flex: 1;
        min-width: 0;
    }


    .addon-option-price {
        color: #c4703c;
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
    }


    .addon-qty-field {
        width: 78px;
        flex-shrink: 0;
    }


    .addon-qty-field input {
        text-align: center;
    }


    .summary-addons {
        margin-top: 18px;
        padding: 15px;

        border-radius: 11px;

        background: var(--bs-tertiary-bg);
    }


    .summary-addon-row {
        margin-top: 12px;
    }


    .summary-discount-row {
        margin-top: 12px;
    }


    /* =========================================================
   FEATURES
========================================================= */

    .feature-item {
        min-height: 42px;

        display: flex;
        align-items: center;

        gap: 10px;

        padding: 9px 12px;

        border-radius: 10px;

        background: var(--bs-tertiary-bg);

        font-size: 13px;
    }


    .feature-icon {
        width: 24px;
        height: 24px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 50%;

        background: rgba(75, 107, 82, .10);
        color: var(--bs-primary);

        font-size: 13px;
    }


    /* =========================================================
   SUMMARY
========================================================= */

    .checkout-summary {
        position: sticky;
        top: calc(var(--package-sticky-top, 76px) + 16px);
        align-self: start;
        z-index: 2;
    }


    .summary-icon {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: rgba(196, 112, 60, .10);
        color: #c4703c;

        font-size: 17px;
    }


    .summary-label {
        color: var(--bs-secondary-color);

        font-size: 10px;
        font-weight: 800;

        letter-spacing: .08em;
    }


    .summary-package {
        padding-bottom: 18px;

        border-bottom: 1px solid var(--bs-border-color);
    }


    .summary-package-name {
        margin-top: 4px;

        font-size: 18px;
        font-weight: 800;
    }


    .summary-selected {
        margin-top: 18px;

        padding: 15px;

        border-radius: 11px;

        background: var(--bs-tertiary-bg);
    }


    .summary-details {
        margin-top: 13px;

        padding-top: 12px;

        border-top: 1px solid var(--bs-border-color);
    }


    .summary-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 12px;

        margin-bottom: 9px;

        font-size: 12px;
    }


    .summary-detail:last-child {
        margin-bottom: 0;
    }


    .summary-detail span {
        display: flex;
        align-items: center;
        gap: 6px;

        color: var(--bs-secondary-color);
    }


    .summary-pricing {
        padding: 8px 0 14px;
    }


    .summary-price {
        color: #c4703c;

        font-size: 18px;
        font-weight: 800;
    }


    .summary-divider {
        height: 1px;

        margin: 17px 0;

        background: var(--bs-border-color);
    }


    .summary-total {
        color: #4b6b52;

        font-size: 24px;
        font-weight: 800;
    }

    .checkout-price-breakdown {
        display: grid;
        gap: 10px;
        padding: 16px;
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        background: var(--bs-tertiary-bg);
        container-type: inline-size;
    }

    .checkout-summary-row,
    .checkout-due-row,
    .checkout-remaining-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) max-content;
        align-items: center;
        column-gap: 8px;
    }

    .checkout-summary-row > span,
    .checkout-due-row > span,
    .checkout-remaining-row > span {
        min-width: 0;
        white-space: normal;
    }

    .checkout-summary-row > :last-child,
    .checkout-remaining-row > :last-child {
        text-align: right;
        white-space: nowrap;
    }

    .checkout-summary-row .summary-price,
    .checkout-remaining-row .summary-price {
        font-size: 16px;
        font-variant-numeric: tabular-nums;
    }

    .checkout-summary-row .summary-total {
        font-size: 22px;
        font-variant-numeric: tabular-nums;
    }


    .continue-payment {
        min-height: 48px;

        border-radius: 10px;

        font-weight: 700;
    }

    #checkoutForm {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--bs-border-color);
    }

    #checkoutForm .mb-3 {
        margin-bottom: 20px !important;
    }

    #checkoutForm .form-label {
        margin-bottom: 7px;
    }

    #checkoutForm .form-control {
        min-height: 44px;
        padding: 9px 12px;
        border-radius: 10px;
    }

    #checkoutForm .form-text {
        margin-top: 7px;
        line-height: 1.5;
    }


    .secure-checkout {
        display: flex;
        align-items: flex-start;

        gap: 11px;

        margin-top: 14px;

        padding: 13px;

        border-radius: 11px;

        background: var(--bs-tertiary-bg);
    }


    .secure-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 9px;

        background: rgba(75, 107, 82, .08);
        color: var(--bs-primary);
    }


    .secure-checkout .text-muted {
        font-size: 11px;
        line-height: 1.5;
    }


    .checkout-scheme-card {
        padding: 18px;
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        background: var(--bs-tertiary-bg);
        box-shadow: 0 6px 20px rgba(34, 45, 34, .04);
    }

    .checkout-scheme-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .checkout-scheme-option {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 0;
        padding: 12px 10px;
        min-height: 76px;
        border: 1px solid var(--bs-border-color);
        border-radius: 11px;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease;
    }

    .checkout-scheme-option:has(input:checked) {
        border-color: #4b6b52;
        background: rgba(75, 107, 82, .07);
        box-shadow: 0 0 0 2px rgba(75, 107, 82, .06);
    }

    .checkout-scheme-option input {
        margin-top: 3px;
        width: 17px;
        height: 17px;
        flex: 0 0 auto;
        accent-color: #4b6b52;
    }

    .checkout-scheme-body {
        display: grid;
        gap: 3px;
        line-height: 1.3;
    }

    .checkout-scheme-body strong {
        font-size: 14px;
    }

    .checkout-scheme-body small {
        color: var(--bs-secondary-color);
        font-size: 12px;
        line-height: 1.35;
    }

    .checkout-due-row {
        margin-top: 2px;
        padding-top: 12px;
        border-top: 1px dashed var(--bs-border-color);
    }

    .checkout-due-row .summary-total {
        font-size: clamp(18px, 2vw, 22px);
        line-height: 1.2;
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .checkout-remaining-row {
        margin-top: 0;
        padding-top: 10px;
        border-top: 1px solid var(--bs-border-color);
    }

    @container (max-width: 320px) {
        .checkout-summary-row,
        .checkout-due-row,
        .checkout-remaining-row {
            grid-template-columns: minmax(0, 1fr);
            row-gap: 2px;
        }

        .checkout-summary-row > span,
        .checkout-due-row > span,
        .checkout-remaining-row > span {
            white-space: normal;
        }

        .checkout-summary-row > :last-child,
        .checkout-remaining-row > :last-child,
        .checkout-due-row .summary-total {
            text-align: left;
        }
    }

    @media (max-width: 575.98px) {
        .checkout-summary .card-body {
            padding: 1.5rem !important;
        }

        .checkout-price-breakdown {
            gap: 10px;
            padding: 16px;
        }

        #checkoutForm {
            margin-top: 24px;
            padding-top: 24px;
        }

        #checkoutForm .mb-3 {
            margin-bottom: 24px !important;
        }

        #checkoutForm .form-label {
            margin-bottom: 9px;
        }

        #checkoutForm .form-control {
            min-height: 48px;
        }

        #checkoutForm .form-text {
            margin-top: 8px;
        }

        .checkout-summary .continue-payment {
            min-height: 50px;
        }

        .checkout-summary .card-body > p.text-center {
            margin-top: 16px !important;
            line-height: 1.5;
        }

        .checkout-summary-row > span,
        .checkout-remaining-row > span {
            font-size: 13px !important;
        }

        .checkout-summary-row .summary-price,
        .checkout-remaining-row .summary-price {
            font-size: 16px;
        }

        .checkout-summary-row .summary-total {
            font-size: 19px;
        }

        .checkout-due-row .summary-total {
            font-size: 18px;
        }
    }

    @media (max-width: 359.98px) {
        .checkout-scheme-options {
            grid-template-columns: 1fr;
        }
    }

    .checkout-confirm-modal .modal-body {
        display: grid;
        gap: 10px;
    }

    .checkout-confirm-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-size: 13px;
    }

    .checkout-confirm-row > span {
        color: var(--bs-secondary-color);
    }

    .checkout-confirm-due {
        margin-top: 4px;
        padding-top: 12px;
        border-top: 1px solid var(--bs-border-color);
        font-size: 15px;
    }

    @media (max-width: 767.98px) {
        .checkout-scheme-options { grid-template-columns: 1fr; }
    }


    /* =========================================================
   RESPONSIVE
========================================================= */

    @media (max-width: 991.98px) {

        .checkout-summary {
            position: static;
        }

    }


    @media (max-width: 767.98px) {

        .package-option-choice {
            align-items: flex-start;
        }

        .option-price-wrapper {
            padding-right: 18px;
        }

        .option-price {
            font-size: 17px;
        }

        .option-meta {
            gap: 8px;
            flex-direction: column;
        }

    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const options =
            document.querySelectorAll('.package-option-choice');

        const packageOptionUuid =
            document.getElementById('packageOptionUuid');

        const summaryOptionName =
            document.getElementById('summaryOptionName');

        const summaryDuration =
            document.getElementById('summaryDuration');

        const summaryQuota =
            document.getElementById('summaryQuota');

        const summaryTotal =
            document.getElementById('summaryTotal');

        const checkoutForm =
            document.getElementById('checkoutForm');

        const addonOptions =
            document.querySelectorAll('.addon-option');

        const summaryAddonsBlock =
            document.getElementById('summaryAddonsBlock');

        const summaryAddonsList =
            document.getElementById('summaryAddonsList');

        const summaryAddonRow =
            document.getElementById('summaryAddonRow');

        const summaryAddonTotal =
            document.getElementById('summaryAddonTotal');

        const addonUuidInputs =
            document.getElementById('addonUuidInputs');

        const quantityInputs =
            document.querySelectorAll('.addon-quantity');

        const promoInput =
            document.getElementById('promo_code');

        const summaryDiscountRow =
            document.getElementById('summaryDiscountRow');

        const summaryDiscount =
            document.getElementById('summaryDiscount');

        const summaryDue =
            document.getElementById('summaryDue');

        const summarySchemeRow =
            document.getElementById('summarySchemeRow');

        const summarySchemeLabel =
            document.getElementById('summarySchemeLabel');

        const summarySchemeDue =
            document.getElementById('summarySchemeDue');

        const summaryRemainingAmount =
            document.getElementById('summaryRemainingAmount');

        const paymentSchemeInputs =
            document.querySelectorAll('.payment-scheme-input');

        const continuePayment =
            document.getElementById('continuePayment');

        const confirmModalElement =
            document.getElementById('checkoutConfirmModal');

        const promos = @json($promos);
        const BOOKING_FEE = @json((int) $bookingFeeAmount);


        /*
        |--------------------------------------------------------------------------
        | Format Rupiah
        |--------------------------------------------------------------------------
        */

        function formatRupiah(value) {
            return 'Rp ' + Number(value).toLocaleString('id-ID');
        }


        function addonQuantity(item) {
            const input = document.querySelector(
                '[name="addon_quantities[' + item.dataset.addonUuid + ']"]'
            );
            const min = Number(input?.min || 1);
            const max = Number(input?.max || 99);
            const value = Number(input?.value || min);
            return Math.min(max, Math.max(min, value));
        }


        function promoDiscount(subtotal) {
            const code = (promoInput?.value || '').trim().toUpperCase();

            if (!code) {
                return 0;
            }

            const promo = promos.find(item => item.code === code);

            if (!promo || subtotal < promo.min_purchase) {
                return 0;
            }

            let discount = promo.type === 'percent'
                ? Math.round(subtotal * promo.value / 100)
                : promo.value;

            if (promo.max_discount !== null) {
                discount = Math.min(discount, promo.max_discount);
            }

            return Math.max(0, Math.min(discount, subtotal));
        }


        function selectedPaymentScheme() {
            const selected = Array.from(paymentSchemeInputs).find(input => input.checked);
            const value = selected?.value || 'full';

            return {
                value,
                label: value === 'no_dp' ? 'Tanpa DP' : value === 'dp30' ? 'DP 30%' : value === 'dp50' ? 'DP 50%' : 'Bayar Lunas',
                factor: value === 'dp30' ? 0.30 : value === 'dp50' ? 0.50 : value === 'full' ? 1 : 0,
            };
        }

        function calculatePaymentBreakdown(total, scheme) {
            const safeTotal = Math.max(0, Math.round(Number(total) || 0));
            const bookingFee = Math.min(BOOKING_FEE, safeTotal);
            const netAmount = Math.max(0, safeTotal - bookingFee);
            const schemeDue = Math.min(
                netAmount,
                Math.max(0, Math.round(netAmount * scheme.factor))
            );
            const dueNow = Math.min(safeTotal, bookingFee + schemeDue);

            return {
                total: safeTotal,
                bookingFee,
                netAmount,
                schemeDue,
                dueNow,
                remainingAmount: Math.max(0, safeTotal - dueNow),
            };
        }


        /*
        |--------------------------------------------------------------------------
        | Perbarui Ringkasan
        |--------------------------------------------------------------------------
        */

        function updateSummary(option) {

            if (!option) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Selected State
            |--------------------------------------------------------------------------
            */

            options.forEach(item => {
                item.classList.remove('selected');
            });


            /*
            |--------------------------------------------------------------------------
            | Add Selected State
            |--------------------------------------------------------------------------
            */

            option.classList.add('selected');


            /*
            |--------------------------------------------------------------------------
            | Set Package Option UUID
            |--------------------------------------------------------------------------
            */

            packageOptionUuid.value =
                option.dataset.optionUuid;


            /*
            |--------------------------------------------------------------------------
            | Get Option Data
            |--------------------------------------------------------------------------
            */

            const name =
                option.dataset.optionName;

            const price =
                Number(option.dataset.optionPrice);

            const quota =
                option.dataset.optionQuota;

            const duration =
                option.dataset.optionDuration;

            const durationUnit =
                option.dataset.optionDurationUnit;


            /*
            |--------------------------------------------------------------------------
            | Update Option Name
            |--------------------------------------------------------------------------
            */

            summaryOptionName.textContent =
                name;


            /*
            |--------------------------------------------------------------------------
            | Update Duration
            |--------------------------------------------------------------------------
            */

            summaryDuration.textContent =
                `${duration} ${durationUnit}`;


            /*
            |--------------------------------------------------------------------------
            | Perbarui Kuota
            |--------------------------------------------------------------------------
            */

            summaryQuota.textContent =
                quota === '' ?
                'Tanpa batas' :
                `Kuota ${quota}`;


            const selectedAddons =
                Array.from(addonOptions).filter(
                    item => item.checked
                );


            const addonTotal = selectedAddons.reduce(
                (total, item) =>
                total + Number(item.dataset.addonPrice || 0) * addonQuantity(item),
                0
            );

            const discount = promoDiscount(price + addonTotal);

            if (summaryDiscountRow) {
                summaryDiscountRow.classList.toggle(
                    'd-none',
                    discount === 0
                );
            }

            if (summaryDiscount) {
                summaryDiscount.textContent =
                    formatRupiah(discount);
            }


            if (summaryAddonsList) {

                summaryAddonsList.textContent = '';

                selectedAddons.forEach(item => {

                    const line =
                        document.createElement('div');

                    line.className =
                        'd-flex justify-content-between gap-3 mb-1';

                    const label =
                        document.createElement('span');

                    label.textContent =
                        item.dataset.addonName + ' x' + addonQuantity(item);

                    const value =
                        document.createElement('strong');

                    value.textContent =
                        formatRupiah(
                            Number(item.dataset.addonPrice) * addonQuantity(item)
                        );

                    line.appendChild(label);
                    line.appendChild(value);

                    summaryAddonsList.appendChild(line);

                });

            }


            if (summaryAddonsBlock) {

                summaryAddonsBlock.classList.toggle(
                    'd-none',
                    selectedAddons.length === 0
                );

            }


            if (summaryAddonRow) {

                summaryAddonRow.classList.toggle(
                    'd-none',
                    selectedAddons.length === 0
                );

            }


            if (summaryAddonTotal) {

                summaryAddonTotal.textContent =
                    formatRupiah(addonTotal);

            }


            const scheme = selectedPaymentScheme();
            const breakdown = calculatePaymentBreakdown(
                price + addonTotal - discount,
                scheme
            );

            summaryTotal.textContent = formatRupiah(breakdown.total);

            const summaryBookingFee =
                document.getElementById('summaryBookingFee');

            const summaryNetAmount =
                document.getElementById('summaryNetAmount');

            if (summaryBookingFee) {
                summaryBookingFee.textContent =
                    formatRupiah(breakdown.bookingFee);
            }

            if (summaryNetAmount) {
                summaryNetAmount.textContent =
                    formatRupiah(breakdown.netAmount);
            }

            if (summarySchemeRow) {
                summarySchemeRow.classList.toggle(
                    'd-none',
                    breakdown.schemeDue === 0
                );
            }

            if (summarySchemeLabel) {
                summarySchemeLabel.textContent =
                    scheme.value === 'full' ? 'Pelunasan sisa' : 'DP dari sisa';
            }

            if (summarySchemeDue) {
                summarySchemeDue.textContent =
                    formatRupiah(breakdown.schemeDue);
            }

            if (summaryDue) {
                summaryDue.textContent =
                    formatRupiah(breakdown.dueNow);
            }

            if (summaryRemainingAmount) {
                summaryRemainingAmount.textContent =
                    formatRupiah(breakdown.remainingAmount);
            }


            if (addonUuidInputs) {

                addonUuidInputs.textContent = '';

                selectedAddons.forEach(item => {

                    const input =
                        document.createElement('input');

                    input.type = 'hidden';
                    input.name = 'addon_uuids[]';
                    input.value = item.dataset.addonUuid;

                    addonUuidInputs.appendChild(input);

                });

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Click Package Option
        |--------------------------------------------------------------------------
        */

        options.forEach(option => {

            option.addEventListener('click', function() {

                updateSummary(this);

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Select First Option
        |--------------------------------------------------------------------------
        */

        if (options.length) {

            const defaultOption =
                document.querySelector(
                    '.package-option-choice.selected'
                ) || options[0];

            updateSummary(defaultOption);
        }


        addonOptions.forEach(addon => {

            addon.addEventListener('change', function() {

                const selected =
                    document.querySelector(
                        '.package-option-choice.selected'
                    ) || options[0];

                updateSummary(selected);

            });

        });


        quantityInputs.forEach(input => {

            input.addEventListener('input', function() {

                const selected =
                    document.querySelector(
                        '.package-option-choice.selected'
                    ) || options[0];

                updateSummary(selected);

            });

        });


        if (promoInput) {

            promoInput.addEventListener('input', function() {

                const selected =
                    document.querySelector(
                        '.package-option-choice.selected'
                    ) || options[0];

                updateSummary(selected);

            });

        }

        paymentSchemeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const selected =
                    document.querySelector(
                        '.package-option-choice.selected'
                    ) || options[0];

                updateSummary(selected);
            });
        });


        /*
        |--------------------------------------------------------------------------
        | Submit Checkout
        |--------------------------------------------------------------------------
        */

        if (checkoutForm && continuePayment && confirmModalElement) {

            continuePayment.addEventListener('click', function() {

                const selected =
                    document.querySelector(
                        '.package-option-choice.selected'
                    );

                if (!selected) {
                    alert('Silakan pilih opsi paket.');
                    return;
                }

                if (!checkoutForm.reportValidity()) {
                    return;
                }

                packageOptionUuid.value = selected.dataset.optionUuid;

                const total = Number(
                    (summaryTotal.textContent || '').replace(/\D/g, '')
                ) || 0;
                const scheme = selectedPaymentScheme();
                const breakdown = calculatePaymentBreakdown(total, scheme);

                document.getElementById('confirmOption').textContent =
                    selected.dataset.optionName || '-';
                document.getElementById('confirmDate').textContent =
                    document.getElementById('event_date')?.value || '-';
                document.getElementById('confirmScheme').textContent =
                    scheme.label;
                document.getElementById('confirmTotal').textContent =
                    formatRupiah(total);
                document.getElementById('confirmDue').textContent =
                    formatRupiah(breakdown.dueNow);
                document.getElementById('confirmRemaining').textContent =
                    formatRupiah(breakdown.remainingAmount);

                bootstrap.Modal.getOrCreateInstance(confirmModalElement).show();
            });

            confirmModalElement.addEventListener('shown.bs.modal', function() {
                document.getElementById('confirmCheckout')?.focus();
            });
        }

    });
</script>

@endsection