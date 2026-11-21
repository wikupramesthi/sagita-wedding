@extends('layouts.mobile')

@section('title', __('mobile.checkout'))

@section('content')

@php
$leadTimeDays = (int) ($package->lead_time_days ?? 0);
$minEventDate = now()->addDays($leadTimeDays)->toDateString();
$blackoutDates = $package->blackoutDates ?? collect();
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

<section class="screen active" id="checkout">

    <div class="px-4 pt-4">

        {{-- HEADER --}}
        <div class="mb-4">

            <a
                href="{{ route('packages.member') }}"
                class="text-dark text-decoration-none d-inline-flex align-items-center mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <i class="bi bi-arrow-left fs-5"></i>
                <span class="ms-2 small fw-semibold">{{ __('mobile.back') }}</span>
            </a>

            <p class="eyebrow mb-1">{{ __('mobile.packages') }}</p>

            <h1
                class="fw-semibold"
                style="font-size:28px">{{ __('mobile.checkout_package') }}</h1>

        </div>


        {{-- =====================================================
             PACKAGE
        ====================================================== --}}

        <div class="app-card p-4 mb-4">

            <div class="d-flex align-items-start gap-3">

                <div
                    class="rounded-3 bg-sage-soft text-sage d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:46px;height:46px">

                    <i class="bi bi-box-seam"></i>

                </div>


                <div class="flex-fill">

                    <h2 class="h5 fw-semibold mb-1">
                        {{ $package->name }}
                    </h2>

                    @if($package->description)
                    <p class="small text-muted2 mb-0"
                        style="line-height:1.6">
                        {{ $package->description }}
                    </p>
                    @endif

                </div>

            </div>

            @if (!empty($package->guest_capacity) || !empty($package->event_duration_hours) || !empty($package->venue_note))
            <div class="package-wo-meta">

                @if (!empty($package->guest_capacity))
                <span>
                    <i class="bi bi-people"></i>
                    {{ __('mobile.kapasitas_tamu', ['count' => $package->guest_capacity]) }}
                </span>
                @endif

                @if (!empty($package->event_duration_hours))
                <span>
                    <i class="bi bi-clock-history"></i>
                    {{ __('mobile.durasi_acara', ['count' => $package->event_duration_hours]) }}
                </span>
                @endif

                @if (!empty($package->venue_note))
                <span>
                    <i class="bi bi-geo-alt"></i>
                    {{ __('mobile.lokasi_acara', ['location' => $package->venue_note]) }}
                </span>
                @endif

            </div>
            @endif

        </div>


        {{-- =====================================================
             CHOOSE OPTION
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-end mb-2">

            <div>
                <h3 class="h5 fw-semibold mb-1">{{ __('mobile.choose_option') }}</h3>

                <p class="small text-muted2 mb-0">
                    {{ __('mobile.choose_option_desc') }}
                </p>
            </div>

        </div>


        <div class="d-flex flex-column gap-2 mt-3">

            @foreach($package->options as $index => $option)

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
                    class="package-option {{ $index === 0 ? 'selected' : '' }}"
                    data-option-uuid="{{ $option->uuid }}"
                    data-option-name="{{ $option->name }}"
                    data-option-price="{{ $finalPrice }}"
                    data-option-regular-price="{{ $option->price }}"
                    data-option-quota="{{ $option->quota ?? '' }}"
                    data-option-duration="{{ $option->duration }}"
                    data-option-duration-unit="{{ $option->duration_unit }}">

                    <div class="d-flex align-items-start gap-3">

                        {{-- RADIO --}}
                        <div class="option-radio flex-shrink-0">
                            <span></span>
                        </div>


                        <div class="flex-fill">

                            <div class="d-flex justify-content-between align-items-start gap-2">

                                <div>

                                    <p class="small fw-semibold mb-1">
                                        {{ $option->name }}
                                    </p>

                                    <div class="option-meta">

                                        <span>
                                            <i class="bi bi-calendar3"></i>

                                            {{ $option->duration }} {{ __('mobile.duration_unit.' . $option->duration_unit) }}
                                        </span>

                                        <span>
                                            <i class="bi bi-person-check"></i>

                                            @if($option->quota === null)
                                            {{ __('mobile.unlimited') }}
                                            @else
                                            {{ __('mobile.quota') }} {{ $option->quota }}
                                            @endif
                                        </span>

                                    </div>

                                </div>


                                <div class="text-end">

                                    @if($hasDiscount)

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

                    </div>

                </button>

                @endforeach

        </div>


        @if($package->addons->count())

        <div class="mt-4 mb-2">

            <h3 class="h5 fw-semibold mb-1">{{ __('mobile.layanan_tambahan') }}</h3>

            <p class="small text-muted2 mb-0">
                {{ __('mobile.layanan_tambahan_desc') }}
            </p>

        </div>


        <div class="app-card overflow-hidden mt-3 mb-4">

            @foreach($package->addons as $addon)

            @php
            $addonHasDiscount =
            $addon->discount_price !== null &&
            $addon->discount_price < $addon->price;

            $addonFinalPrice = $addon->final_price;
            @endphp

            <label class="addon-item d-flex align-items-start gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                <input
                    type="checkbox"
                    class="addon-checkbox mt-1 flex-shrink-0"
                    name="addon_uuids[]"
                    form="checkoutForm"
                    value="{{ $addon->uuid }}"
                    data-addon-price="{{ $addonFinalPrice }}">

                <span class="flex-fill">

                    <span class="d-flex justify-content-between align-items-start gap-2">

                        <span class="small fw-semibold">
                            {{ $addon->name }}
                        </span>

                        <span class="text-end flex-shrink-0">

                            @if($addonHasDiscount)
                            <span class="option-old-price d-block">
                                Rp {{ number_format($addon->price, 0, ',', '.') }}
                            </span>
                            @endif

                            <span class="option-price">
                                Rp {{ number_format($addonFinalPrice, 0, ',', '.') }}
                            </span>

                        </span>

                    </span>

                    @if($addon->description)
                    <span class="small text-muted2 d-block mt-1" style="line-height:1.5">
                        {{ $addon->description }}
                    </span>
                    @endif

                </span>

                <span class="addon-qty-field flex-shrink-0">
                    <span class="m-micro text-muted2 d-block">Jumlah</span>
                    <input
                        type="number"
                        class="form-control form-control-sm addon-quantity"
                        form="checkoutForm"
                        name="addon_quantities[{{ $addon->uuid }}]"
                        data-addon-uuid="{{ $addon->uuid }}"
                        data-addon-name="{{ $addon->name }}"
                        data-addon-price="{{ $addonFinalPrice }}"
                        min="{{ max(1, (int) $addon->min_quantity) }}"
                        max="{{ $addon->max_quantity ?? 99 }}"
                        value="{{ max(1, (int) $addon->min_quantity) }}">
                </span>

            </label>

            @endforeach

        </div>

        @endif


        <div class="app-card p-4 mt-4 mb-4">

            <h3 class="h5 fw-semibold mb-1">Tanggal Acara</h3>

            <p class="small text-muted2 mb-3">
                Pemesanan minimal {{ $leadTimeDays }} hari sebelum acara.

                @if ($blackoutDates->count())
                Tanggal tidak tersedia:
                {{ $blackoutDates->pluck('date')->map(fn ($date) => $date?->format('d M Y'))->implode(', ') }}
                @endif
            </p>

            <input
                type="date"
                name="event_date"
                id="event_date"
                form="checkoutForm"
                class="form-control"
                min="{{ $minEventDate }}"
                value="{{ old('event_date', $minEventDate) }}"
                required>

            <h3 class="h6 fw-semibold mt-4 mb-1">Kode Promo</h3>

            <input
                type="text"
                name="promo_code"
                id="promo_code"
                form="checkoutForm"
                class="form-control"
                maxlength="50"
                placeholder="Masukkan kode promo"
                value="{{ old('promo_code') }}">

            @if ($promos)
            <p class="small text-muted2 mt-2 mb-0">
                Promo tersedia: {{ collect($promos)->pluck('code')->implode(', ') }}
            </p>
            @endif

        </div>


        <div class="app-card p-4 mt-4 mb-4 checkout-scheme-card">
            <h3 class="h6 fw-semibold mb-1">Skema Pembayaran</h3>
            <p class="small text-muted2 mb-3">Pilih jumlah yang dibayar sekarang.</p>

            <div class="checkout-scheme-options">
                <label class="checkout-scheme-option">
                    <input type="radio" name="payment_scheme" value="dp30" form="checkoutForm" class="payment-scheme-input">
                    <span><strong>DP 30%</strong><small>Bayar sebagian di awal</small></span>
                </label>
                <label class="checkout-scheme-option">
                    <input type="radio" name="payment_scheme" value="dp50" form="checkoutForm" class="payment-scheme-input">
                    <span><strong>DP 50%</strong><small>Setengah dari total paket</small></span>
                </label>
                <label class="checkout-scheme-option">
                    <input type="radio" name="payment_scheme" value="full" form="checkoutForm" class="payment-scheme-input" checked>
                    <span><strong>Bayar Lunas</strong><small>Lunasi seluruh paket</small></span>
                </label>
            </div>
        </div>


        {{-- =====================================================
             INCLUDED
        ====================================================== --}}

        @if($package->features->count())

        <div class="d-flex justify-content-between align-items-end mt-4 mb-2">

            <div>
                <h3 class="h5 fw-semibold mb-1">{{ __('mobile.whats_included') }}</h3>

                <p class="small text-muted2 mb-0">
                    {{ __('mobile.package_benefits') }}
                </p>
            </div>

        </div>


        <div class="app-card overflow-hidden mt-3 mb-4">

            @foreach($package->features as $index => $feature)

            <div
                class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                <div class="feature-check">
                    <i class="bi bi-check2"></i>
                </div>

                <span class="small">
                    {{ $feature->feature }}
                </span>

            </div>

            @endforeach

        </div>

        @endif

        <div class="mt-4 mb-2">

            <h3 class="h5 fw-semibold mb-1">{{ __('mobile.refund_policy') }}</h3>

            <p class="small text-muted2 mb-0">
                {{ __('mobile.refund_review') }}
            </p>

        </div>

        <div class="app-card p-4 mb-4">

            <p class="small text-muted2 mb-3">
                {{ __('mobile.refund_final') }}
            </p>

            <p class="small text-muted2 mb-0">
                {{ __('mobile.refund_credits') }}
            </p>

        </div>


        {{-- =====================================================
             BOTTOM SPACE
        ====================================================== --}}

        <div style="height:100px"></div>

        <p class="small text-muted2 text-center mb-2" style="font-size:11px;">
            <i class="bi bi-shield-check me-1"></i>{{ __('mobile.bank_transfer_hint') }}
        </p>

    </div>


    {{-- =========================================================
         BOTTOM CHECKOUT BAR
    ========================================================== --}}

    <div class="checkout-bottom-bar">

        <div class="checkout-bottom-details mb-2">

            <div class="small fw-semibold">{{ $package->name }}</div>

            <div id="summaryAddonsList"></div>

            <div class="d-flex justify-content-between d-none" id="summaryDiscountRow">
                <span>Diskon promo</span>
                <span id="summaryDiscount">Rp 0</span>
            </div>

            <div class="d-flex justify-content-between">
                <span>Total</span>
                <span id="summaryTotal">Rp 0</span>
            </div>

            <div class="d-flex justify-content-between checkout-due-row">
                <span>Dibayar sekarang</span>
                <span id="summaryDue">Rp 0</span>
            </div>

        </div>

        <div class="d-flex align-items-center gap-3">

            <div class="flex-fill">

                <p class="mb-1 text-muted2 m-tiny" style="letter-spacing:.08em;">{{ __('mobile.total') }}</p>

                <div
                    id="summaryPrice"
                    class="summary-price">

                    @if($package->options->first())

                    @php
                    $firstOption = $package->options->first();

                    $firstHasDiscount =
                    $firstOption->discount_price !== null &&
                    $firstOption->discount_price < $firstOption->price;

                        $firstFinalPrice = $firstHasDiscount
                        ? $firstOption->discount_price
                        : $firstOption->price;
                        @endphp

                        Rp {{ number_format($firstFinalPrice, 0, ',', '.') }}

                        @else
                        Rp 0
                        @endif

                </div>

                <div
                    id="summaryOption"
                    class="summary-option">

                    {{ $package->options->first()?->name ?? '-' }}

                </div>

            </div>


            <form
                action="{{ route('orders.store') }}"
                method="POST"
                id="checkoutForm"
                class="m-0">
                @csrf

                <input
                    type="hidden"
                    name="type"
                    value="package">

                <input
                    type="hidden"
                    name="package_option_uuid"
                    id="packageOptionUuid">

                <button
                    type="button"
                    id="continuePayment"
                    class="btn btn-warm checkout-button">

                    {{ __('mobile.continue') }}

                    <i class="bi bi-arrow-right ms-1"></i>

                </button>

            </form>

        </div>

    </div>


    {{-- =========================================================
         CONFIRMATION SHEET
    ========================================================== --}}

    <div class="checkout-confirm-overlay" id="checkoutConfirmOverlay" aria-hidden="true">
        <div class="checkout-confirm-sheet">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="h6 fw-semibold mb-1">Konfirmasi Pesanan</h3>
                    <div class="small text-muted2">Periksa detail sebelum melanjutkan.</div>
                </div>
                <button type="button" class="btn-close" id="closeCheckoutConfirm" aria-label="Tutup"></button>
            </div>

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

            <button type="submit" form="checkoutForm" id="confirmCheckout" class="btn btn-warm w-100 mt-4">
                Konfirmasi & Bayar
                <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

</section>


<style>
    /* =========================================================
   CHECKOUT MOBILE
========================================================= */

    #checkout {
        background: var(--bg, #faf8f2);
    }


    /* =========================================================
   PACKAGE OPTION
========================================================= */

    .package-option {

        position: relative;

        width: 100%;

        border: 1px solid rgba(75, 107, 82, .10);

        border-radius: 14px;

        padding: 15px;

        background: var(--card, #fffdf8);

        color: var(--fg, #31392f);

        text-align: left;

        cursor: pointer;

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;

    }


    .package-option.selected {

        border-color: #4b6b52;

        background: rgba(75, 107, 82, .035);

        box-shadow:
            0 0 0 2px rgba(75, 107, 82, .07);

    }


    /* =========================================================
   RADIO
========================================================= */

    .option-radio {

        width: 19px;
        height: 19px;

        margin-top: 1px;

        border: 2px solid #cfd5cd;

        border-radius: 50%;

        display: flex;

        align-items: center;
        justify-content: center;

    }


    .package-option.selected .option-radio {

        border-color: #4b6b52;

    }


    .package-option.selected .option-radio span {

        width: 9px;
        height: 9px;

        border-radius: 50%;

        background: #4b6b52;

    }


    /* =========================================================
   OPTION PRICE
========================================================= */

    .option-price {

        color: #c4703c;

        font-size: 15px;

        font-weight: 800;

        white-space: nowrap;

    }


    .option-old-price {

        color: #999;

        font-size: 9px;

        text-decoration: line-through;

    }


    .option-meta {

        display: flex;

        flex-wrap: wrap;

        gap: 9px;

        color: #858d83;

        font-size: 9px;

    }


    .option-meta span {

        display: inline-flex;

        align-items: center;

        gap: 4px;

    }


    .option-meta i {

        font-size: 10px;

    }


    .package-wo-meta {

        display: flex;

        flex-wrap: wrap;

        gap: 7px;

        margin-top: 14px;

    }


    .package-wo-meta span {

        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 5px 9px;

        border-radius: 8px;

        background: rgba(75, 107, 82, .07);

        color: #4b6b52;

        font-size: 9px;

        font-weight: 700;

        line-height: 1.4;

    }


    .package-wo-meta i {

        font-size: 11px;

    }


    .addon-item {

        cursor: pointer;

    }


    .addon-checkbox {

        width: 17px;
        height: 17px;

        accent-color: #4b6b52;

        cursor: pointer;

    }


    .addon-qty-field {

        width: 72px;

    }


    .addon-qty-field input {

        text-align: center;

    }


    /* =========================================================
   CHECK ICON
========================================================= */

    .option-check {

        position: absolute;

        top: 10px;
        right: 10px;

        width: 20px;
        height: 20px;

        display: none;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #4b6b52;

        color: #fff;

        font-size: 10px;

    }


    .package-option.selected .option-check {

        display: flex;

    }


    /* =========================================================
   FEATURES
========================================================= */

    .feature-check {

        width: 23px;
        height: 23px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(75, 107, 82, .09);

        color: #4b6b52;

        font-size: 12px;

    }


    /* =========================================================
   BOTTOM BAR
========================================================= */

    .checkout-bottom-bar {

        position: fixed;

        left: 50%;

        bottom: 0;

        width: 100%;

        max-width: 430px;

        transform: translateX(-50%);

        padding: 12px 16px calc(12px + env(safe-area-inset-bottom, 0px));

        background: rgba(255, 253, 248, .96);

        border-top: 1px solid rgba(75, 107, 82, .10);

        box-shadow:
            0 -6px 25px rgba(0, 0, 0, .06);

        backdrop-filter: blur(12px);

        z-index: 1000;

    }


    .summary-price {

        color: #c4703c;

        font-size: 18px;

        font-weight: 800;

        line-height: 1.2;

    }


    .summary-option {

        overflow: hidden;

        margin-top: 2px;

        color: #858d83;

        font-size: 9px;

        text-overflow: ellipsis;

        white-space: nowrap;

    }


    .checkout-bottom-details {

        color: #858d83;

        font-size: 9px;

        line-height: 1.5;

    }


    .checkout-bottom-details .small {

        color: #31392f;

    }


    .checkout-button {

        min-width: 118px;

        min-height: 44px;

        border-radius: 11px;

        font-size: 12px;

        font-weight: 700;

    }


    .checkout-bottom-details #summaryAddonsList {

        max-height: 58px;

        overflow-y: auto;

    }


    .checkout-due-row {

        margin-top: 4px;

        padding-top: 4px;

        border-top: 1px dashed rgba(75, 107, 82, .18);

        color: #4b6b52;

        font-weight: 700;

    }


    .checkout-scheme-options {

        display: grid;

        gap: 8px;

    }


    .checkout-scheme-option {

        display: flex;

        align-items: flex-start;

        gap: 9px;

        padding: 11px 12px;

        border: 1px solid var(--line);

        border-radius: 11px;

        background: var(--card, #fffdf8);

        cursor: pointer;

    }


    .checkout-scheme-option input {

        margin-top: 3px;

        accent-color: #4b6b52;

    }


    .checkout-scheme-option span {

        display: grid;

        gap: 2px;

        line-height: 1.3;

    }


    .checkout-scheme-option strong {

        font-size: 12px;

    }


    .checkout-scheme-option small {

        color: #858d83;

        font-size: 10px;

    }


    .checkout-confirm-overlay {

        position: fixed;

        inset: 0;

        z-index: 1200;

        display: none;

        align-items: flex-end;

        justify-content: center;

        background: rgba(20, 26, 20, .45);

    }


    .checkout-confirm-overlay.is-open {

        display: flex;

    }


    .checkout-confirm-sheet {

        width: 100%;

        max-width: 430px;

        max-height: 88vh;

        overflow-y: auto;

        padding: 20px 18px calc(20px + env(safe-area-inset-bottom, 0px));

        border-radius: 20px 20px 0 0;

        background: #fffdf8;

    }


    .checkout-confirm-row {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 14px;

        padding: 9px 0;

        border-bottom: 1px solid rgba(75, 107, 82, .08);

        font-size: 12px;

    }


    .checkout-confirm-row > span {

        color: #858d83;

    }


    .checkout-confirm-due {

        border-bottom: 0;

        color: #4b6b52;

        font-size: 14px;

    }


    /* =========================================================
   SMALL SCREEN
========================================================= */

    @media (max-width: 360px) {

        .package-option {

            padding: 13px;

        }

        .option-price {

            font-size: 14px;

        }

        .checkout-button {

            min-width: 105px;

        }

    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const options =
            document.querySelectorAll('.package-option');

        const addons =
            document.querySelectorAll('.addon-checkbox');

        const summaryPrice =
            document.getElementById('summaryPrice');

        const summaryOption =
            document.getElementById('summaryOption');

        const continuePayment =
            document.getElementById('continuePayment');

        const checkoutForm =
            document.getElementById('checkoutForm');

        const packageOptionUuid =
            document.getElementById('packageOptionUuid');

        const paymentSchemeInputs =
            document.querySelectorAll('.payment-scheme-input');

        const summaryDue =
            document.getElementById('summaryDue');

        const confirmOverlay =
            document.getElementById('checkoutConfirmOverlay');

        const quantityInputs =
            document.querySelectorAll('.addon-quantity');

        const promoInput =
            document.getElementById('promo_code');

        const summaryAddonsList =
            document.getElementById('summaryAddonsList');

        const summaryDiscountRow =
            document.getElementById('summaryDiscountRow');

        const summaryDiscount =
            document.getElementById('summaryDiscount');

        const summaryTotal =
            document.getElementById('summaryTotal');

        const promos = @json($promos);


        function formatRupiah(value) {

            return 'Rp ' +
                Number(value).toLocaleString('id-ID');

        }


        function selectedPaymentScheme() {

            const selected =
                Array.from(paymentSchemeInputs)
                    .find(input => input.checked);

            const value =
                selected?.value || 'full';

            return {
                value: value,
                label: value === 'dp30'
                    ? 'DP 30%'
                    : value === 'dp50'
                        ? 'DP 50%'
                        : 'Bayar Lunas',
                factor: value === 'dp30'
                    ? 0.30
                    : value === 'dp50'
                        ? 0.50
                        : 1,
            };

        }


        function selectedOption() {

            return document.querySelector('.package-option.selected');

        }


        function addonQuantity(addon) {

            const input = document.querySelector(
                '[name="addon_quantities[' + addon.value + ']"]'
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


        function addonTotal() {

            let total = 0;

            addons.forEach(addon => {

                if (addon.checked) {
                    total += Number(addon.dataset.addonPrice || 0) * addonQuantity(addon);
                }

            });

            return total;

        }


        function updateSummary(option) {

            const selected = option || selectedOption();

            if (!selected) {
                return;
            }

            const subtotal =
                Number(selected.dataset.optionPrice) + addonTotal();

            const discount =
                promoDiscount(subtotal);

            const total =
                subtotal - discount;

            summaryPrice.textContent =
                formatRupiah(total);

            summaryTotal.textContent =
                formatRupiah(total);

            if (summaryDue) {
                summaryDue.textContent =
                    formatRupiah(Math.round(total * selectedPaymentScheme().factor));
            }

            summaryOption.textContent =
                selected.dataset.optionName;

            packageOptionUuid.value =
                selected.dataset.optionUuid;

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

                addons.forEach(addon => {

                    if (!addon.checked) {
                        return;
                    }

                    const input = document.querySelector(
                        '[name="addon_quantities[' + addon.value + ']"]'
                    );

                    const line =
                        document.createElement('div');

                    line.className =
                        'd-flex justify-content-between gap-2';

                    const label =
                        document.createElement('span');

                    label.textContent =
                        (input?.dataset.addonName || '') +
                        ' x' + addonQuantity(addon);

                    const value =
                        document.createElement('span');

                    value.textContent =
                        formatRupiah(
                            Number(input?.dataset.addonPrice || 0) *
                            addonQuantity(addon)
                        );

                    line.appendChild(label);
                    line.appendChild(value);

                    summaryAddonsList.appendChild(line);

                });

            }

        }


        function selectOption(option) {

            options.forEach(item => {

                item.classList.remove('selected');

            });


            option.classList.add('selected');

            updateSummary(option);

        }


        options.forEach(option => {

            option.addEventListener('click', function() {

                selectOption(this);

            });

        });


        addons.forEach(addon => {

            addon.addEventListener('change', function() {

                updateSummary();

            });

        });


        quantityInputs.forEach(input => {

            input.addEventListener('input', function() {

                updateSummary();

            });

        });


        if (promoInput) {

            promoInput.addEventListener('input', function() {

                updateSummary();

            });

        }


        if (options.length) {

            selectOption(
                document.querySelector('.package-option.selected') ||
                options[0]
            );

        }


        paymentSchemeInputs.forEach(input => {

            input.addEventListener('change', function() {

                updateSummary();

            });

        });


        if (confirmOverlay && continuePayment && checkoutForm) {

            function closeCheckoutConfirm() {

                confirmOverlay.classList.remove('is-open');
                confirmOverlay.setAttribute('aria-hidden', 'true');

            }

            document.getElementById('closeCheckoutConfirm')
                ?.addEventListener('click', closeCheckoutConfirm);

            confirmOverlay.addEventListener('click', function(event) {

                if (event.target === confirmOverlay) {
                    closeCheckoutConfirm();
                }

            });

            continuePayment.addEventListener('click', function() {

                const selected =
                    selectedOption();

                if (!selected) {
                    alert('Silakan pilih opsi paket.');
                    return;
                }

                if (!checkoutForm.reportValidity()) {
                    return;
                }

                packageOptionUuid.value =
                    selected.dataset.optionUuid;

                const total =
                    Number(
                        (summaryTotal.textContent || '')
                            .replace(/\D/g, '')
                    ) || 0;

                const scheme =
                    selectedPaymentScheme();

                const due =
                    Math.round(total * scheme.factor);

                document.getElementById('confirmOption').textContent =
                    selected.dataset.optionName || '-';
                document.getElementById('confirmDate').textContent =
                    document.getElementById('event_date')?.value || '-';
                document.getElementById('confirmScheme').textContent =
                    scheme.label;
                document.getElementById('confirmTotal').textContent =
                    formatRupiah(total);
                document.getElementById('confirmDue').textContent =
                    formatRupiah(due);

                confirmOverlay.classList.add('is-open');
                confirmOverlay.setAttribute('aria-hidden', 'false');

            });

        }


        checkoutForm.addEventListener('submit', function(event) {

            const selected = selectedOption();

            if (!selected) {
                event.preventDefault();
                return;
            }

            /*
             * Manual transfer: create Order, then upload
             * proof on the order detail page.
             */
            packageOptionUuid.value =
                selected.dataset.optionUuid;

        });

    });
</script>

@endsection
