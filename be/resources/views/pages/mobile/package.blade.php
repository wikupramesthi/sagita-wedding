@extends('layouts.mobile')

@section('title', __('mobile.packages'))

@section('content')

<style>
    .mobile-packages {
        padding: 18px 16px 100px;
        background: var(--bg, #faf8f2);
        min-height: 100vh;
    }

    .packages-top {
        margin-bottom: 20px;
    }

    .packages-note {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(107, 143, 111, .08);
        color: var(--sage);
        font-size: 11px;
        font-weight: 600;
    }

    .packages-note i {
        font-size: 14px;
    }

    /* FILTER */

    .compare-hint {
        margin: 0 0 7px;
        color: var(--fg, #31392f);
        font-size: 11px;
        font-weight: 800;
    }

    .mobile-package-filter {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 18px;
        scrollbar-width: none;
    }

    .mobile-package-filter::-webkit-scrollbar {
        display: none;
    }

    .mobile-filter-btn {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 13px;
        border-radius: 30px;
        border: 1px solid var(--line);
        background: var(--card);
        color: var(--muted);
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: .2s ease;
    }

    .mobile-filter-btn.active {
        background: var(--fg, #31392f);
        border-color: var(--fg, #31392f);
        color: #fff;
    }

    /* CARD */

    .mobile-package-card {
        position: relative;
        background: var(--card, #fffdf8);
        border: 1px solid var(--line);
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 8px 25px rgba(49, 57, 47, .05);
        overflow: hidden;
    }

    .mobile-package-card.featured {
        border-color: #8b6fa8;
        box-shadow: 0 10px 28px rgba(91, 67, 112, .12);
    }

    .featured-label {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
        padding: 7px 13px;
        border-radius: 0 0 0 13px;
        background: #74558e;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .04em;
    }

    .package-cover {
        margin: -20px -20px 16px;
        overflow: hidden;
        background: #f1ede4;
    }

    .package-cover img {
        display: block;
        width: 100%;
        height: 155px;
        object-fit: cover;
    }

    .package-card-header {
        padding-right: 52px;
    }

    .mobile-package-card.featured .package-card-header {
        padding-right: 0;
        padding-top: 14px;
    }

    .package-name {
        font-family: "Cormorant Garamond", serif;
        font-size: 27px;
        line-height: 1.05;
        font-weight: 600;
        color: var(--fg, #31392f);
        margin-bottom: 7px;
    }

    .package-description {
        font-size: 12px;
        line-height: 1.6;
        color: var(--muted);
    }

    .package-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 12px;
    }

    .package-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 8px;
        background: rgba(107, 143, 111, .09);
        color: var(--sage, #6b8f6f);
        font-size: 9px;
        font-weight: 700;
        line-height: 1.4;
    }

    .package-meta i {
        font-size: 11px;
    }

    /* PRICE */

    .package-price-box {
        margin-top: 17px;
        padding: 14px 15px;
        border-radius: 15px;
        background: rgba(107, 143, 111, .07);
        border: 1px solid rgba(107, 143, 111, .10);
    }

    .package-price-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .1em;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .package-price-badge {
        display: inline-block;
        margin-bottom: 5px;
        padding: 3px 7px;
        border-radius: 6px;
        background: #c4703c;
        color: #fff;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .05em;
    }

    .package-old-price {
        color: var(--muted);
        font-size: 10px;
        text-decoration: line-through;
    }

    .package-price {
        color: var(--fg, #31392f);
        font-size: 19px;
        font-weight: 800;
    }

    .package-price span {
        color: var(--muted);
        font-size: 16px;
        margin: 0 2px;
    }

    /* OPTIONS */

    .options-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 19px 0 10px;
    }

    .options-heading span:first-child {
        font-size: 11px;
        font-weight: 800;
        color: var(--fg, #31392f);
    }

    .options-heading span:last-child {
        font-size: 10px;
        color: var(--muted);
    }

    .package-option {
        padding: 13px;
        border: 1px solid #e9e5db;
        border-radius: 14px;
        background: #fff;
        margin-bottom: 8px;
    }

    .option-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--fg, #31392f);
        margin-bottom: 8px;
    }

    .option-info {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .option-info span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 8px;
        border-radius: 8px;
        background: #f7f5ef;
        color: var(--muted);
        font-size: 9px;
        font-weight: 600;
    }

    .option-info i {
        font-size: 10px;
    }

    /* FEATURES */

    .features-heading {
        font-size: 11px;
        font-weight: 800;
        color: var(--fg, #31392f);
        margin: 19px 0 9px;
    }

    .package-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .package-features li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 7px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.4;
    }

    .package-features i {
        color: var(--sage, #6b8f6f);
        font-size: 12px;
        margin-top: 1px;
    }

    /* BUTTON */

    .choose-package {
        width: 100%;
        border: 0;
        border-radius: 13px;
        padding: 12px 15px;
        margin-top: 17px;
        background: var(--fg, #31392f);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .choose-package:active {
        transform: scale(.98);
    }

    .choose-package i {
        margin-left: 5px;
    }

    .ask-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        margin-top: 9px;
        padding: 11px 15px;
        border: 1px solid #25d366;
        border-radius: 13px;
        background: #fff;
        color: #128c4a;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .ask-wa i {
        font-size: 15px;
    }

    .ask-wa:active {
        transform: scale(.98);
    }

    .package-empty {
        text-align: center;
        padding: 45px 20px;
        background: var(--card, #fffdf8);
        border: 1px solid var(--line);
        border-radius: 20px;
    }

    .package-empty i {
        font-size: 30px;
        color: var(--muted);
        margin-bottom: 10px;
    }

    .package-empty h6 {
        font-weight: 700;
        color: var(--fg, #31392f);
        margin-bottom: 5px;
    }

    .package-empty p {
        font-size: 11px;
        color: var(--muted);
        margin: 0;
    }

    @media (min-width: 500px) {
        .mobile-packages {
            max-width: 430px;
            margin: 0 auto;
        }
    }
</style>


<div class="mobile-packages">

    {{-- HEADER --}}
    <div class="packages-top">

        {{-- Header --}}
        <p class="eyebrow mb-1">{{ __('mobile.packages') }}</p>

        <h1 class="fw-semibold m-h1">
            {{ __('mobile.journey_title') }}
        </h1>

        <div class="packages-note">
            <i class="bi bi-stars"></i>
            <span>{{ __('mobile.packages_note') }}</span>
        </div>

    </div>


    {{-- FILTER --}}
    <p class="compare-hint">{{ __('mobile.bandingkan_paket') }}</p>

    <div class="mobile-package-filter">

        <a href="{{ route('packages.member') }}"
            class="mobile-filter-btn {{ !request('filter') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>{{ __('mobile.all') }}</a>

        <a href="{{ route('packages.member', [
                'filter' => 'popular',
                'sort' => request('sort')
            ]) }}"
            class="mobile-filter-btn {{ request('filter') === 'popular' ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i>{{ __('mobile.paling_dipilih') }}</a>

        @if (request('filter') === 'unlimited')
        <a href="{{ route('packages.member', [
                    'filter' => 'unlimited',
                    'sort' => request('sort')
                ]) }}"
            class="mobile-filter-btn active">
            <i class="bi bi-infinity"></i>{{ __('mobile.unlimited') }}</a>
        @endif

    </div>


    {{-- PACKAGES --}}
    @forelse ($packages as $package)

    @php

    $isPopular = $package->is_popular;

    $waNumber = \App\Models\Consultation::normalizeWhatsapp(
        (string) config('manual_payment.admin_whatsapp')
    );

    $waText = __('mobile.tanya_paket_message', [
        'package' => $package->name,
    ]);

    @endphp


    <div class="mobile-package-card {{ $isPopular ? 'featured' : '' }}">

        {{-- POPULAR --}}
        @if ($isPopular)

        <div class="featured-label">
            <i class="bi bi-star-fill me-1"></i>{{ __('mobile.paling_dipilih') }}
        </div>

        @endif

        @if (!empty($package->cover_url))
        <div class="package-cover">
            <img src="{{ $package->cover_url }}" alt="{{ $package->name }}" loading="lazy">
        </div>
        @endif


        {{-- HEADER --}}
        <div class="package-card-header">

            <div class="package-name">
                {{ $package->name }}
            </div>

            <div class="package-description">
                {{ $package->description ?: __('mobile.package_fallback_desc') }}
            </div>

            @if (!empty($package->guest_capacity) || !empty($package->event_duration_hours) || !empty($package->venue_note))
            <div class="package-meta">

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


        {{-- PRICE RANGE --}}
        @php
        $regularPrices = $package->options->pluck('price');

        $finalPrices = $package->options->map(function ($option) {
            return $option->discount_price !== null
            && $option->discount_price < $option->price
                ? $option->discount_price
                : $option->price;
        });

        $minRegular = $regularPrices->min();
        $maxRegular = $regularPrices->max();

        $minFinal = $finalPrices->min();
        $maxFinal = $finalPrices->max();

        $hasDiscount = $package->options->contains(function ($option) {
            return $option->discount_price !== null
            && $option->discount_price < $option->price;
        });
        @endphp

        <div class="package-price-box">

            <div class="package-price-label">
                {{ __('mobile.membership_from') }}
            </div>

            @if ($hasDiscount)
            <div class="package-price-badge">
                {{ __('mobile.special_price') }}
            </div>

            <div class="package-old-price">
                Rp {{ number_format($minRegular, 0, ',', '.') }}

                @if ($minRegular !== $maxRegular)
                - Rp {{ number_format($maxRegular, 0, ',', '.') }}
                @endif
            </div>
            @endif

            <div class="package-price">
                Rp {{ number_format($minFinal, 0, ',', '.') }}

                @if ($minFinal !== $maxFinal)
                <span>-</span>
                Rp {{ number_format($maxFinal, 0, ',', '.') }}
                @endif
            </div>

        </div>


        {{-- OPTIONS --}}
        @if ($package->options->count())

        <div class="options-heading">

            <span>
                {{ __('mobile.membership_options') }}
            </span>

            <span>
                {{ $package->options->count() }}
                {{ trans_choice('mobile.options_count', $package->options->count(), ['count' => $package->options->count()]) }}
            </span>

        </div>


        @foreach ($package->options as $option)

        @php
        $finalPrice = $option->discount_price ?? $option->price;
        @endphp

        <div class="package-option">

            <div class="option-name">
                {{ $option->name }}
            </div>

            <div class="option-info">

                <span>
                    <i class="bi bi-wallet2"></i>

                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </span>

                @if ($option->quota === null)

                <span>
                    <i class="bi bi-infinity"></i>{{ __('mobile.unlimited') }}</span>

                @else

                <span>
                    <i class="bi bi-calendar-check"></i>
                    {{ __('mobile.quota') }} {{ $option->quota }}
                </span>

                @endif

                <span>
                    <i class="bi bi-clock"></i>

                    {{ $option->duration }} {{ __('mobile.duration_unit.' . $option->duration_unit) }}
                </span>

            </div>

        </div>

        @endforeach

        @endif


        {{-- FEATURES --}}
        <div class="features-heading">{{ __('mobile.whats_included') }}</div>

        <ul class="package-features">

            @forelse ($package->features as $feature)

            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>
                    {{ $feature->feature }}
                </span>
            </li>

            @empty

            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>
                    {{ __('mobile.default_feature') }}
                </span>
            </li>

            @endforelse

        </ul>


        {{-- CTA --}}
        <button
            type="button"
            class="choose-package"
            onclick="window.location.href='{{ route('checkout.package', $package->uuid) }}'">

            {{ __('mobile.choose_package') }}
            <i class="bi bi-arrow-right"></i>

        </button>

        @if ($waNumber)
        <a
            class="ask-wa"
            href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}"
            target="_blank"
            rel="noopener">

            <i class="bi bi-whatsapp"></i>{{ __('mobile.tanya_paket') }}

        </a>
        @endif

    </div>

    @empty

    <div class="package-empty">

        <i class="bi bi-box-seam"></i>

        <h6>
            {{ __('mobile.no_packages') }}
        </h6>

        <p>
            {{ __('mobile.no_packages_desc') }}
        </p>

    </div>

    @endforelse

</div>

@endsection
