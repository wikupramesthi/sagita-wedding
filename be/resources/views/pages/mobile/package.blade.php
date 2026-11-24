@extends('layouts.mobile')

@section('title', __('mobile.packages'))

@section('content')

<style>
    .mobile-packages {
        padding: 18px 16px 0;
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

    .packages-quick-links {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .packages-quick-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: var(--card, #fffdf8);
        color: var(--sage, #6b8f6f);
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
    }

    .packages-quick-link i {
        font-size: 12px;
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

    .mobile-filter-btn[data-filter="popular"]:not(.active) {
        background: #fff4df;
        border-color: #efd5a6;
        color: #a2602c;
    }

    .mobile-filter-btn[data-filter="popular"].active {
        background: #f3c886;
        border-color: #e2a94f;
        color: #5d3818;
    }

    .mobile-package-filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: end;
        gap: 8px;
        margin: -6px 0 18px;
        padding: 12px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--card, #fffdf8);
    }

    .mobile-package-filter-form label {
        display: block;
        margin-bottom: 4px;
        color: var(--muted);
        font-size: 9px;
        font-weight: 700;
    }

    .mobile-package-filter-form input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--bg, #faf8f2);
        color: var(--fg, #31392f);
        font-size: 11px;
    }

    .mobile-package-filter-form input:focus {
        border-color: var(--sage, #6b8f6f);
        outline: none;
    }

    .mobile-filter-layer {
        position: fixed;
        inset: 0;
        z-index: 1045;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        visibility: hidden;
        pointer-events: none;
    }

    .mobile-filter-layer.is-open {
        visibility: visible;
    }

    .mobile-filter-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(28, 36, 28, .46);
        opacity: 0;
        transition: opacity .2s ease;
    }

    .mobile-filter-layer.is-open .mobile-filter-backdrop {
        opacity: 1;
        pointer-events: auto;
    }

    .mobile-filter-sheet {
        position: relative;
        width: 100%;
        max-width: 430px;
        padding: 10px 16px calc(22px + env(safe-area-inset-bottom, 0px));
        border-radius: 22px 22px 0 0;
        background: var(--card, #fffdf8);
        box-shadow: 0 -12px 40px rgba(28, 36, 28, .18);
        transform: translateY(105%);
        transition: transform .25s ease;
        pointer-events: auto;
    }

    .mobile-filter-layer.is-open .mobile-filter-sheet {
        transform: translateY(0);
    }

    .mobile-filter-handle {
        width: 38px;
        height: 4px;
        margin: 0 auto 12px;
        border-radius: 8px;
        background: var(--line);
    }

    .mobile-filter-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
    }

    .mobile-filter-heading strong {
        color: var(--fg, #31392f);
        font-size: 13px;
        font-weight: 800;
    }

    .mobile-filter-heading button {
        border: 0;
        background: transparent;
        color: var(--muted);
        font-size: 18px;
        line-height: 1;
    }

    .mobile-filter-sheet .mobile-package-filter-form {
        margin: 0;
        border: 0;
        padding: 0;
        box-shadow: none;
    }

    .mobile-filter-sheet .mobile-package-filter-form label {
        font-size: 10px;
    }

    .mobile-filter-sheet .mobile-package-filter-form input {
        padding: 10px;
        font-size: 12px;
    }

    .mobile-filter-sheet .mobile-package-filter-form button {
        grid-column: 1 / -1;
        margin-top: 2px;
    }

    .mobile-active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: -6px 0 18px;
    }

    .mobile-active-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid rgba(107, 143, 111, .35);
        border-radius: 30px;
        background: rgba(107, 143, 111, .10);
        color: var(--sage, #6b8f6f);
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
    }

    .availability-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 10px;
        color: var(--sage, #6b8f6f);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .package-card-tools {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px;
        margin: 14px 0 16px;
    }

    .package-tool-form {
        margin: 0;
    }

    .package-tool-btn,
    .package-tool-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        width: 100%;
        min-height: 42px;
        padding: 8px 4px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: var(--card, #fffdf8);
        color: var(--muted);
        font-size: 9px;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        cursor: pointer;
    }

    .package-tool-btn.active {
        border-color: var(--sage, #6b8f6f);
        background: rgba(107, 143, 111, .10);
        color: var(--sage, #6b8f6f);
    }

    @media (max-width: 360px) {
        .mobile-package-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .mobile-package-filter-form button {
            grid-column: 1 / -1;
        }
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
        border: 0;
        background: #2f5f5a;
        color: #fff;
        box-shadow: 0 14px 32px rgba(36, 78, 73, .24);
        padding-top: 56px;
    }

    .featured-label {
        position: absolute;
        top: -1px;
        left: 50%;
        z-index: 1;
        transform: translateX(-50%);
        padding: 7px 13px;
        border-radius: 0 0 10px 10px;
        background: #3d756f;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .04em;
        white-space: nowrap;
        box-shadow: 0 5px 12px rgba(25, 69, 64, .12);
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
        padding-top: 0;
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

    .mobile-package-card.featured .package-name,
    .mobile-package-card.featured .package-description,
    .mobile-package-card.featured .options-heading span,
    .mobile-package-card.featured .features-heading,
    .mobile-package-card.featured .package-features li,
    .mobile-package-card.featured .option-name,
    .mobile-package-card.featured .option-info span,
    .mobile-package-card.featured .package-price-label,
    .mobile-package-card.featured .package-price,
    .mobile-package-card.featured .package-price span,
    .mobile-package-card.featured .package-old-price {
        color: #fff;
    }

    .mobile-package-card.featured .availability-badge {
        background: rgba(255, 255, 255, .10);
        color: #dcead6;
    }

    .mobile-package-card.featured .package-meta span {
        background: rgba(255, 255, 255, .10);
        color: #fff;
    }

    .mobile-package-card.featured .package-price-box {
        background: rgba(255, 255, 255, .07);
        border: 0;
        border-bottom: 1px solid rgba(255, 255, 255, .12);
        border-radius: 0;
        margin-left: -20px;
        margin-right: -20px;
        padding: 16px 20px;
    }

    .mobile-package-card.featured .options-heading span:last-child {
        color: rgba(255, 255, 255, .72);
    }

    .mobile-package-card.featured .package-option {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
    }

    .mobile-package-card.featured .option-info span {
        background: rgba(255, 255, 255, .10);
    }

    .mobile-package-card.featured .package-features i {
        color: #dcead6;
    }

    .mobile-package-card.featured .package-card-tools {
        border-top: 1px solid rgba(255, 255, 255, .12);
        padding-top: 12px;
    }

    .mobile-package-card.featured .package-tool-btn,
    .mobile-package-card.featured .package-tool-link {
        background: rgba(255, 255, 255, .10);
        border-color: rgba(255, 255, 255, .12);
        color: #fff;
    }

    .mobile-package-card.featured .choose-package {
        background: #fff;
        color: #2f5f5a;
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
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border: 0;
        border-radius: 13px;
        padding: 12px 15px;
        background: var(--fg, #31392f);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .choose-package:active {
        transform: scale(.98);
    }

    .choose-package i {
        margin-left: 5px;
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

    @php
    $filterQuery = array_filter([
        'filter' => request('filter'),
        'sort' => request('sort'),
        'guests' => request('guests'),
        'budget_max' => request('budget_max'),
    ], fn ($value) => $value !== null && $value !== '');

    $filterWithoutGuests = $filterQuery;
    unset($filterWithoutGuests['guests']);

    $filterWithoutBudget = $filterQuery;
    unset($filterWithoutBudget['budget_max']);

    $compareUuids = array_values(array_filter(
        (array) session('package_compare', []),
        'is_string'
    ));
    @endphp

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

        @if (\Illuminate\Support\Facades\Route::has('packages.wishlist.index') && \Illuminate\Support\Facades\Route::has('packages.compare.index'))
        <div class="packages-quick-links">
            <a href="{{ route('packages.wishlist.index') }}" class="packages-quick-link">
                <i class="bi bi-heart"></i>Wishlist Saya
            </a>
            <a href="{{ route('packages.compare.index') }}" class="packages-quick-link">
                <i class="bi bi-list-check"></i>Bandingkan ({{ count($compareUuids) }})
            </a>
            @if (auth()->user()?->hasRole('user') && \Illuminate\Support\Facades\Route::has('package-offers.mine'))
            <a href="{{ route('package-offers.mine') }}" class="packages-quick-link">
                <i class="bi bi-file-earmark-check"></i>Penawaran Saya
            </a>
            @endif
        </div>
        @endif

    </div>


    {{-- FILTER --}}
    <div class="mobile-package-filter">

        <a href="{{ route('packages.member', array_diff_key($filterQuery, ['filter' => true])) }}"
            class="mobile-filter-btn {{ !request('filter') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>{{ __('mobile.all') }} Paket</a>

        <a href="{{ route('packages.member', array_merge($filterQuery, ['filter' => 'popular'])) }}"
            data-filter="popular"
            class="mobile-filter-btn {{ request('filter') === 'popular' ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i>{{ __('mobile.paling_dipilih') }}</a>

        <a href="{{ route('packages.member', array_merge($filterQuery, ['filter' => 'unlimited'])) }}"
            class="mobile-filter-btn {{ request('filter') === 'unlimited' ? 'active' : '' }}">
            <i class="bi bi-infinity"></i>{{ __('mobile.unlimited') }}</a>

        <button type="button" id="mobile-filter-trigger" class="mobile-filter-btn" aria-expanded="false" aria-controls="mobile-filter-layer" onclick="openMobileFilter()">
            <i class="bi bi-funnel"></i>Filter
        </button>

    </div>

    @if (request('guests') || request('budget_max'))
    <div class="mobile-active-filters">
        @if (request('guests'))
        <a href="{{ route('packages.member', $filterWithoutGuests) }}" class="mobile-active-chip">
            Jumlah Tamu: {{ request('guests') }}<i class="bi bi-x"></i>
        </a>
        @endif
        @if (request('budget_max'))
        <a href="{{ route('packages.member', $filterWithoutBudget) }}" class="mobile-active-chip">
            Budget Maksimum: {{ request('budget_max') }}<i class="bi bi-x"></i>
        </a>
        @endif
    </div>
    @endif

    <div id="mobile-filter-layer" class="mobile-filter-layer" aria-hidden="true">
        <div class="mobile-filter-backdrop" onclick="closeMobileFilter()"></div>
        <div class="mobile-filter-sheet" role="dialog" aria-modal="true" aria-labelledby="mobile-filter-title">
            <div class="mobile-filter-handle"></div>
            <div class="mobile-filter-heading">
                <strong id="mobile-filter-title">Kapasitas & Budget</strong>
                <button type="button" aria-label="Tutup filter" onclick="closeMobileFilter()">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <form action="{{ route('packages.member') }}" method="GET" class="mobile-package-filter-form">
                @if (request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                @if (request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div>
                    <label for="mobile-guests">{{ __('mobile.filter_guests') }}</label>
                    <input id="mobile-guests" type="number" name="guests" min="1" value="{{ request('guests') }}" placeholder="100">
                </div>
                <div>
                    <label for="mobile-budget">{{ __('mobile.filter_budget') }}</label>
                    <input id="mobile-budget" type="number" name="budget_max" min="0" value="{{ request('budget_max') }}" placeholder="50000000">
                </div>
                <button type="submit" class="btn btn-sage btn-sm">{{ __('mobile.terapkan') }} Filter</button>
            </form>
        </div>
    </div>


    {{-- PACKAGES --}}
    @forelse ($packages as $package)

    @php

    $isPopular = $package->is_popular;

    $isWishlisted = in_array($package->uuid, $wishlistedUuids, true);

    $isCompared = in_array($package->uuid, $compareUuids, true);

    $quotationParams = ['package' => $package->uuid];

    $firstOption = $package->options->first();

    if ($firstOption) {
        $quotationParams['option_uuid'] = $firstOption->uuid;
    }

    if (request('event_date')) {
        $quotationParams['event_date'] = request('event_date');
    }

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

            <div class="availability-badge">
                <i class="bi bi-check-circle-fill"></i>Tersedia
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


        @if (\Illuminate\Support\Facades\Route::has('packages.wishlist.toggle') && \Illuminate\Support\Facades\Route::has('packages.compare.add') && \Illuminate\Support\Facades\Route::has('packages.quotation'))
        <div class="package-card-tools">
            <form method="POST" action="{{ route('packages.wishlist.toggle', $package->uuid) }}" class="package-tool-form">
                @csrf
                <button type="submit" class="package-tool-btn {{ $isWishlisted ? 'active' : '' }}">
                    <i class="bi bi-heart{{ $isWishlisted ? '-fill' : '' }}"></i>{{ $isWishlisted ? 'Simpan' : 'Wishlist' }}
                </button>
            </form>
            <form method="POST" action="{{ route('packages.compare.add', $package->uuid) }}" class="package-tool-form">
                @csrf
                <button type="submit" class="package-tool-btn {{ $isCompared ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i>Bandingkan
                </button>
            </form>
            <a href="{{ route('packages.quotation', $quotationParams) }}" class="package-tool-link">
                <i class="bi bi-file-earmark-text"></i>Penawaran
            </a>
        </div>
        @endif

        {{-- CTA --}}
        <a href="{{ route('checkout.package', $package->uuid) }}" class="choose-package">
            {{ __('mobile.choose_package') }}
            <i class="bi bi-arrow-right"></i>
        </a>

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

        @if ($filterQuery)
        <a href="{{ route('packages.member') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset filter
        </a>
        @endif

    </div>

    @endforelse

</div>

<script>
    function openMobileFilter() {
        var layer = document.getElementById('mobile-filter-layer');
        var trigger = document.getElementById('mobile-filter-trigger');

        if (!layer) {
            return;
        }

        layer.classList.add('is-open');
        layer.setAttribute('aria-hidden', 'false');

        if (trigger) {
            trigger.setAttribute('aria-expanded', 'true');
        }
    }

    function closeMobileFilter() {
        var layer = document.getElementById('mobile-filter-layer');
        var trigger = document.getElementById('mobile-filter-trigger');

        if (!layer) {
            return;
        }

        layer.classList.remove('is-open');
        layer.setAttribute('aria-hidden', 'true');

        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileFilter();
        }
    });
</script>

@endsection
