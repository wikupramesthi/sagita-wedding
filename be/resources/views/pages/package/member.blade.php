@extends('layouts.app')
@section('title', 'Jelajahi Paket')
@section('content')

@php
    $compareUuids = array_values(array_filter(
        (array) session('package_compare', []),
        'is_string'
    ));
@endphp

<style>
.package-catalog-page { min-height: 100vh; padding: 40px 0 70px; }

/* Filter */
.package-catalog-hero { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 26px; }
.package-catalog-eyebrow { margin: 0 0 8px; color: #4b6b52; font-size: 11px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
.package-catalog-hero h1 { margin: 0 0 8px; color: #202020; font-size: 30px; font-weight: 800; letter-spacing: -.5px; }
.package-catalog-hero p { margin: 0; color: #7b7f78; font-size: 14px; }
.package-catalog-count { display: inline-flex; align-items: center; gap: 8px; padding: 10px 15px; border: 1px solid #ece9e4; border-radius: 12px; background: #fff; color: #4b6b52; font-size: 12px; font-weight: 700; }
.package-preview-notice { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 22px; padding: 12px 16px; border: 1px solid #d8e6da; border-radius: 14px; background: #eef3ee; color: #4b6b52; font-size: 13px; }
.package-filter { position: sticky; top: 76px; z-index: 20; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 40px; padding: 6px; background: #fff; border: 1px solid #ece9e4; border-radius: 14px; box-shadow: 0 8px 24px rgba(0, 0, 0, .06); }
.package-filter-left { display: flex; align-items: center; gap: 4px; }
.package-filter-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 15px; border-radius: 9px; color: #777; background: transparent; text-decoration: none; font-size: 13px; font-weight: 600; transition: all .2s ease; }
.package-filter-btn:hover { color: #4b6b52; background: #eef3ee; }
.package-filter-btn.active { color: #4b6b52; background: #e3ece4; }
.package-filter-btn[data-filter="popular"]:not(.active) { background: #fff4df; color: #a2602c; }
.package-filter-btn[data-filter="popular"].active { background: #f3c886; color: #5d3818; }
.package-filter-btn i { font-size: 16px; margin-top: 1px; }

/* Sort */
.package-sort { display: flex; align-items: center; gap: 9px; padding-right: 4px; }
.sort-label { color: #888; font-size: 13px; white-space: nowrap; }
.package-sort .form-select { width: 175px; border-color: #e7e4df; border-radius: 9px; color: #444; font-size: 13px; cursor: pointer; }
.package-sort .form-select:focus { border-color: #4b6b52; box-shadow: 0 0 0 .15rem rgba(75, 107, 82, .10); }

/* Card */
.package-catalog-card { position: relative; height: 100%; background: #fff; border: 1px solid #ece9e4; border-radius: 24px; padding: 30px; display: flex; flex-direction: column; box-shadow: 0 8px 30px rgba(0, 0, 0, .045); transition: transform .3s ease, box-shadow .3s ease; }
.package-catalog-card:hover { transform: translateY(-7px); box-shadow: 0 18px 45px rgba(0, 0, 0, .10); }
.package-catalog-card.popular { background: linear-gradient(145deg, #365140 0%, #4b6b52 50%, #2f4436 100%); border: 2px solid #4b6b52; color: #fff; box-shadow: 0 20px 45px rgba(75, 107, 82, .25); transform: translateY(-10px); }
.package-catalog-card.popular:hover { transform: translateY(-12px); }
.popular-badge { position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: #4b6b52; color: #fff; padding: 7px 18px; border-radius: 50px; font-size: 12px; font-weight: 700; white-space: nowrap; box-shadow: 0 6px 18px rgba(75, 107, 82, .35); }

.package-name { font-size: 25px; font-weight: 700; color: #202020; margin-bottom: 7px; }
.popular .package-name { color: #fff; }
.package-description { color: #6b7280; font-size: 14px; line-height: 1.6; min-height: 45px; margin-bottom: 22px; }
.popular .package-description { color: rgba(255, 255, 255, .78); }
.package-divider { border-top: 1px solid #eee; margin-bottom: 22px; }
.popular .package-divider { border-color: rgba(255, 255, 255, .16); }

/* Price */
.package-price-box { margin-bottom: 22px; padding-bottom: 20px; border-bottom: 1px solid #f0ede8; }
.popular .package-price-box { border-color: rgba(255, 255, 255, .12); }
.package-price-label { font-size: 11px; font-weight: 600; color: #8b8f87; margin-bottom: 5px; }
.package-price-range { font-size: 24px; line-height: 1.2; font-weight: 800; letter-spacing: -0.5px; color: #202020; }
.package-price-range.discounted { color: #c4703c; }
.popular .package-price-range { color: #fff; }
.popular .package-price-range span { color: rgba(255, 255, 255, .6); }
.popular .package-price-original { color: rgba(255, 255, 255, .55); }
.popular .package-price-label { color: rgba(255, 255, 255, .7); }
.package-price-range span { font-size: 16px; font-weight: 500; color: #9b9d97; margin: 0 3px; }
.package-price-original { margin-top: 4px; font-size: 11px; color: #9b9d97; }
.package-price-original span { text-decoration: line-through; }

/* Features */
.features-title { font-size: 13px; font-weight: 700; color: #444; margin-bottom: 14px; }
.popular .features-title { color: #fff; }
.package-features { list-style: none; padding: 0; margin: 0 0 28px; flex-grow: 1; }
.package-features li { display: flex; align-items: flex-start; gap: 10px; color: #555; font-size: 14px; line-height: 1.5; margin-bottom: 11px; }
.package-features li:last-child { margin-bottom: 0; }
.package-features li i { color: #39a66b; font-size: 18px; flex-shrink: 0; }
.popular .package-features li { color: rgba(255, 255, 255, .86); }
.popular .package-features li i { color: #cfe3d2; }

/* Button */
.choose-package { display: inline-flex; align-items: center; justify-content: center; width: 100%; border-radius: 12px; padding: 12px 20px; font-size: 14px; font-weight: 700; border: 1.5px solid #4b6b52; background: transparent; color: #4b6b52; text-decoration: none; transition: all .25s ease; }
.choose-package:hover { background: #4b6b52; color: #fff; }
.choose-package:disabled { opacity: .62; cursor: not-allowed; }
.popular .choose-package { background: #4b6b52; border-color: #4b6b52; color: #fff; }
.popular .choose-package:hover { background: #6b8f6f; border-color: #6b8f6f; }
.saving-text { text-align: center; margin-top: 17px; color: #4b6b52; font-size: 13px; font-weight: 700; }
.popular .saving-text { color: rgba(255, 255, 255, .75); }

.package-cover { margin: -10px 0 18px; border-radius: 16px; overflow: hidden; }
.package-cover img { display: block; width: 100%; height: 175px; object-fit: cover; }
.package-event-meta { display: flex; flex-wrap: wrap; gap: 6px 14px; margin: -8px 0 20px; color: #6b7280; font-size: 12px; }
.package-event-meta span { display: inline-flex; align-items: center; }
.package-catalog-card.popular .package-event-meta { color: rgba(255, 255, 255, .78); }
.package-card-actions { display: grid; gap: 10px; }

.package-quick-links { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
.package-quick-link { display: inline-flex; align-items: center; gap: 6px; padding: 8px 13px; border: 1px solid #ece9e4; border-radius: 9px; background: #fff; color: #4b6b52; font-size: 12px; font-weight: 700; text-decoration: none; transition: all .2s ease; }
.package-quick-link:hover { background: #eef3ee; border-color: #d8e6da; }
.package-quick-link i { font-size: 15px; }

.package-card-tools { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin: 14px 0 18px; }
.package-tool-form { margin: 0; }
.package-tool-btn, .package-tool-link { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; min-height: 58px; padding: 7px 4px; border: 1px solid #e7e4df; border-radius: 12px; background: #fff; color: #666; font-size: 12px; font-weight: 700; line-height: 1.15; text-align: center; text-decoration: none; transition: all .2s ease; }
.package-tool-btn i, .package-tool-link i { flex-shrink: 0; font-size: 16px; line-height: 1; }
.package-tool-btn:hover, .package-tool-link:hover { border-color: #4b6b52; color: #4b6b52; }
.package-tool-btn.active { border-color: #4b6b52; background: #e3ece4; color: #4b6b52; }
.package-catalog-card.popular .package-tool-btn, .package-catalog-card.popular .package-tool-link { border-color: rgba(255, 255, 255, .28); background: rgba(255, 255, 255, .08); color: #fff; }
.package-catalog-card.popular .package-tool-btn.active { border-color: #cfe3d2; background: rgba(207, 227, 210, .22); color: #fff; }

.package-filter-form { margin-bottom: 28px; padding: 14px 16px; background: #fff; border: 1px solid #ece9e4; border-radius: 14px; box-shadow: 0 4px 18px rgba(0, 0, 0, .035); }
.package-filter-form .form-label { color: #777; font-weight: 600; }
.package-filter-form .form-control { border-color: #e7e4df; border-radius: 9px; font-size: 13px; }

.package-compare { background: #fff; border: 1px solid #ece9e4; border-radius: 18px; padding: 20px 22px; box-shadow: 0 8px 30px rgba(0, 0, 0, .035); }
.package-compare summary { display: flex; align-items: center; justify-content: space-between; cursor: pointer; list-style: none; color: #202020; font-size: 17px; font-weight: 800; }
.package-compare summary::-webkit-details-marker { display: none; }
.package-compare summary::after { content: '\f282'; font-family: 'bootstrap-icons'; font-size: 14px; color: #4b6b52; transition: transform .2s ease; }
.package-compare[open] summary::after { transform: rotate(180deg); }
.package-compare-summary { margin: 6px 0 0; color: #888; font-size: 13px; }
.package-compare-details { margin-top: 18px; }
.compare-table { background: #fff; border-color: #ece9e4; font-size: 13px; }
.compare-table th, .compare-table td { min-width: 180px; border-color: #ece9e4; vertical-align: middle; }
.compare-table thead th { background: #faf8ff; color: #444; font-weight: 700; }
.compare-table th:first-child, .compare-table td:first-child { position: sticky; left: 0; z-index: 1; min-width: 170px; background: #fff; font-weight: 700; color: #444; }
.compare-table thead th:first-child { z-index: 2; background: #faf8ff; }
.compare-table tbody tr:hover td { background: #faf8ff; }

/* Empty state */
.package-empty { background: #fff; border: 1px solid #ece9e4; border-radius: 20px; padding: 60px 30px; box-shadow: 0 8px 30px rgba(0, 0, 0, .035); }
.package-empty-icon { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; background: #eef3ee; border-radius: 50%; color: #4b6b52; font-size: 28px; }
.package-empty h5 { color: #333; margin-bottom: 8px; }
.package-empty p { color: #888; margin-bottom: 0; font-size: 14px; }

@media (max-width: 1199px) {
    .package-catalog-card.popular { transform: none; }
    .package-catalog-card.popular:hover { transform: translateY(-7px); }
}

@media (max-width: 767px) {
    .package-catalog-page { padding: 30px 0 50px; }
    .package-filter { flex-direction: column; align-items: stretch; padding: 8px; gap: 8px; }
    .package-filter-left { width: 100%; overflow-x: auto; scrollbar-width: none; }
    .package-filter-left::-webkit-scrollbar { display: none; }
    .package-filter-btn { white-space: nowrap; padding: 8px 12px; }
    .package-sort { width: 100%; padding: 4px 2px 0; }
    .package-sort .form-select { flex: 1; width: auto; }
    .package-catalog-card { padding: 25px; }
    .package-catalog-card.popular { transform: none; }
    .package-catalog-card.popular:hover { transform: translateY(-5px); }
    .package-cover img { height: 150px; }
    .compare-table th, .compare-table td { min-width: 150px; }
}
</style>

<div class="package-catalog-page">
    <div class="container">

        <div class="package-catalog-hero">
            <div>
                <p class="package-catalog-eyebrow">Katalog Paket Wedding</p>
                <h1>Temukan Paket yang Pas untuk Harimu</h1>
                <p>Bandingkan pilihan paket, simpan favorit, lalu lanjut checkout dengan mudah.</p>
            </div>
            <div class="package-catalog-count">
                <i class="bi bi-box-seam"></i>{{ $packages->count() }} paket tersedia
            </div>
        </div>

        @if (!empty($previewMode))
        <div class="package-preview-notice">
            <span><i class="bi bi-eye me-1"></i>Pratinjau paket dari admin. Tombol checkout dinonaktifkan dalam mode ini.</span>
            <a href="{{ route('packages.edit', $previewPackage->uuid) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil me-1"></i>Kembali Edit
            </a>
        </div>
        @endif

        @if (\Illuminate\Support\Facades\Route::has('packages.wishlist.index') && \Illuminate\Support\Facades\Route::has('packages.compare.index'))
        <div class="package-quick-links">
            <a href="{{ route('packages.wishlist.index') }}" class="package-quick-link">
                <i class="bi bi-heart"></i>Wishlist Saya
            </a>
            <a href="{{ route('packages.compare.index') }}" class="package-quick-link">
                <i class="bi bi-list-check"></i>Bandingkan ({{ count($compareUuids) }})
            </a>
        </div>
        @endif

        <div class="package-filter">
            <div class="package-filter-left">
                <a href="{{ route('packages.member', array_filter(['sort' => request('sort'), 'guests' => request('guests'), 'budget_max' => request('budget_max')])) }}" class="package-filter-btn {{ !request('filter') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i> Semua Paket
                </a>
                <a href="{{ route('packages.member', array_filter(['filter' => 'popular', 'sort' => request('sort'), 'guests' => request('guests'), 'budget_max' => request('budget_max')])) }}" data-filter="popular" class="package-filter-btn {{ request('filter') === 'popular' ? 'active' : '' }}">
                    <i class="bi bi-star-fill"></i> Paling Dipilih
                </a>
                <a href="{{ route('packages.member', array_filter(['filter' => 'unlimited', 'sort' => request('sort'), 'guests' => request('guests'), 'budget_max' => request('budget_max')])) }}" class="package-filter-btn {{ request('filter') === 'unlimited' ? 'active' : '' }}">
                    <i class="bi bi-infinity"></i> Tanpa batas
                </a>
            </div>

            <div class="package-sort">
                <span class="sort-label">Urutkan</span>
                <select id="packageSort" class="form-select form-select-sm" onchange="sortPackages(this.value)">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Rekomendasi</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="duration_short" {{ request('sort') === 'duration_short' ? 'selected' : '' }}>Durasi Terpendek</option>
                    <option value="duration_long" {{ request('sort') === 'duration_long' ? 'selected' : '' }}>Durasi Terpanjang</option>
                </select>
            </div>
        </div>

        <form action="{{ route('packages.member') }}" method="GET" class="package-filter-form row g-2 align-items-end">
            @if (request('filter'))
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            @if (request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="col-6 col-md-3">
                <label for="guests" class="form-label small mb-1">Kapasitas tamu</label>
                <input type="number" id="guests" name="guests" min="1" class="form-control form-control-sm" value="{{ request('guests') }}" placeholder="Contoh: 100">
            </div>

            <div class="col-6 col-md-3">
                <label for="budget_max" class="form-label small mb-1">Budget maksimal</label>
                <input type="number" id="budget_max" name="budget_max" min="0" class="form-control form-control-sm" value="{{ request('budget_max') }}" placeholder="Contoh: 50000000">
            </div>

            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Terapkan
                </button>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('packages.member') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            </div>
        </form>

        <div class="row g-4 align-items-stretch">
            @forelse ($packages as $package)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="package-catalog-card {{ $package->is_popular ? 'popular' : '' }}">

                    @php
                        $isWishlisted = in_array($package->uuid, $wishlistedUuids, true);
                        $isCompared = in_array($package->uuid, $compareUuids, true);

                        $quotationParams = ['package' => $package->uuid];

                        $firstOption = $package->options->first();

                        if ($firstOption) {
                            $quotationParams['option_uuid'] = $firstOption->uuid;
                        }

                        $quotationAddons = $package->addons->take(2)->pluck('uuid')->filter()->all();

                        if ($quotationAddons) {
                            $quotationParams['addon_uuids'] = $quotationAddons;
                        }

                        if (request('event_date')) {
                            $quotationParams['event_date'] = request('event_date');
                        }
                    @endphp

                    @if ($package->is_popular)
                    <div class="popular-badge"><i class="bi bi-star-fill me-1"></i>Paling Dipilih</div>
                    @endif

                    @if ($package->cover_url)
                    <div class="package-cover">
                        <img src="{{ $package->cover_url }}" alt="Foto {{ $package->name }}" loading="lazy">
                    </div>
                    @endif

                    <div class="package-name">{{ $package->name }}</div>
                    <div class="package-description">{{ $package->description ?: 'Temukan paket wedding organizer yang sesuai untuk hari istimewa Anda.' }}</div>

                    @if ($package->guest_capacity || $package->event_duration_hours || $package->venue_note)
                    <div class="package-event-meta">
                        @if ($package->guest_capacity)
                        <span><i class="bi bi-people me-1"></i>{{ number_format($package->guest_capacity, 0, ',', '.') }} tamu</span>
                        @endif

                        @if ($package->event_duration_hours)
                        <span><i class="bi bi-clock me-1"></i>{{ $package->event_duration_hours }} jam</span>
                        @endif

                        @if ($package->venue_note)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $package->venue_note }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="package-divider"></div>

                    @php
                        $regularPrices = $package->options->pluck('price');
                        $finalPrices = $package->options->map(fn ($o) => $o->discount_price !== null && $o->discount_price < $o->price ? $o->discount_price : $o->price);
                        $minRegular = $regularPrices->min();
                        $maxRegular = $regularPrices->max();
                        $minFinal = $finalPrices->min();
                        $maxFinal = $finalPrices->max();
                        $hasDiscount = $package->options->contains(fn ($o) => $o->discount_price !== null && $o->discount_price < $o->price);
                    @endphp

                    <div class="package-price-box">
                        @if ($finalPrices->isNotEmpty())
                            <div class="package-price-label">Mulai dari</div>
                            <div class="package-price-range {{ $hasDiscount ? 'discounted' : '' }}">
                                Rp {{ number_format($minFinal, 0, ',', '.') }}
                                @if ($minFinal != $maxFinal)
                                <span>-</span> Rp {{ number_format($maxFinal, 0, ',', '.') }}
                                @endif
                            </div>
                            @if ($hasDiscount)
                            <div class="package-price-original">
                                dari <span>Rp {{ number_format($minRegular, 0, ',', '.') }}@if ($minRegular != $maxRegular) - Rp {{ number_format($maxRegular, 0, ',', '.') }}@endif</span>
                            </div>
                            @endif
                        @else
                            <div class="package-price-label">Informasi harga</div>
                            <div class="package-price-range">Harga belum tersedia</div>
                        @endif
                    </div>

                    <div class="features-title">Yang Termasuk</div>
                    @forelse ($package->features->groupBy(fn ($feature) => trim((string) $feature->category)) as $category => $features)
                    @if ($category !== '')
                    <h6 class="fw-semibold mt-3 mb-2">{{ $category }}</h6>
                    @endif
                    <ul class="package-features">
                        @foreach ($features as $feature)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature->feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @empty
                    <ul class="package-features">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Akses ke layanan Sagita Wedding Organizer</span>
                        </li>
                    </ul>
                    @endforelse

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
                            <i class="bi bi-file-earmark-pdf"></i>Penawaran
                        </a>
                    </div>
                    @endif

                    <div class="mt-auto package-card-actions">
                        @if (!empty($previewMode))
                        <button type="button" class="choose-package w-100" disabled>
                            Pilih Paket <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        @elseif ($package->options->isNotEmpty())
                        <a href="{{ route('checkout.package', $package->uuid) }}" class="choose-package w-100">
                            Pilih Paket <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        @else
                        <button type="button" class="choose-package w-100" disabled>
                            Paket belum tersedia
                        </button>
                        @endif

                        <div class="saving-text"><i class="bi bi-heart-fill me-1"></i>Wujudkan pernikahan impian Anda</div>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="package-empty text-center">
                    <div class="package-empty-icon"><i class="bi bi-box-seam"></i></div>
                    <h5 class="fw-bold">Belum Ada Paket Tersedia</h5>
                    <p>Paket wedding organizer saat ini belum tersedia.</p>
                    @if (request('filter') || request('sort') || request('guests') || request('budget_max'))
                    <div class="mt-4">
                        <a href="{{ route('packages.member') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Lihat Semua Paket
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        @if (count($packages))
        <details class="package-compare mt-5">
            <summary>Bandingkan Paket</summary>
            <p class="package-compare-summary">Lihat perbedaan tiap paket untuk memilih yang paling sesuai.</p>

            <div class="package-compare-details table-responsive">
                <table class="table table-bordered table-hover compare-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Paket</th>
                            @foreach ($packages as $package)
                            <th scope="col" class="text-center">{{ $package->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $comparePrices = $packages->values()->map(function ($item) {
                                $finals = $item->options->map(fn ($option) => $option->discount_price !== null && $option->discount_price < $option->price ? $option->discount_price : $option->price);

                                return $finals->count() ? $finals->min() : null;
                            });
                        @endphp
                        <tr>
                            <th scope="row">Harga mulai</th>
                            @foreach ($packages as $package)
                            @php($comparePrice = $comparePrices[$loop->index] ?? null)
                            <td class="text-center">
                                @if ($comparePrice !== null)
                                Rp {{ number_format($comparePrice, 0, ',', '.') }}
                                @else
                                Harga belum tersedia
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="row">Kapasitas Tamu</th>
                            @foreach ($packages as $package)
                            <td class="text-center">{{ $package->guest_capacity ? number_format($package->guest_capacity, 0, ',', '.') . ' tamu' : '-' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="row">Durasi Acara</th>
                            @foreach ($packages as $package)
                            <td class="text-center">{{ $package->event_duration_hours ? $package->event_duration_hours . ' jam' : '-' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="row">Lokasi Acara</th>
                            @foreach ($packages as $package)
                            <td class="text-center">{{ $package->venue_note ?: '-' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="row">Yang Termasuk</th>
                            @foreach ($packages as $package)
                            <td class="text-center">
                                @if ($package->features->count())
                                {{ \Illuminate\Support\Str::limit($package->features->pluck('feature')->join(', '), 120) }}
                                @else
                                -
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th scope="row">Pilihan</th>
                            @foreach ($packages as $package)
                            <td class="text-center">
                                @if (!empty($previewMode))
                                <button type="button" class="btn btn-sm btn-outline-primary" disabled>Pilih</button>
                                @elseif ($package->options->isNotEmpty())
                                <a href="{{ route('checkout.package', $package->uuid) }}" class="btn btn-sm btn-outline-primary">
                                    Pilih
                                </a>
                                @else
                                <span class="text-muted">Paket belum tersedia</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </details>
        @endif

    </div>
</div>

<script>
    function sortPackages(sort) {
        const url = new URL(window.location.href);
        const currentFilter = url.searchParams.get('filter');

        if (sort) {
            url.searchParams.set('sort', sort);
        } else {
            url.searchParams.delete('sort');
        }

        if (currentFilter) {
            url.searchParams.set('filter', currentFilter);
        }

        window.location.href = url.toString();
    }
</script>

@endsection
