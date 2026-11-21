@extends('layouts.app')
@section('title', 'Jelajahi Paket')
@section('content')

@php
    $waNumber = \App\Models\Consultation::normalizeWhatsapp((string) config('manual_payment.admin_whatsapp'));
@endphp

<style>
.package-catalog-page { min-height: 100vh; padding: 40px 0 70px; }

/* Filter */
.package-filter { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 30px; padding: 6px; background: #fff; border: 1px solid #ece9e4; border-radius: 14px; box-shadow: 0 4px 18px rgba(0, 0, 0, .035); }
.package-filter-left { display: flex; align-items: center; gap: 4px; }
.package-filter-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 15px; border-radius: 9px; color: #777; background: transparent; text-decoration: none; font-size: 13px; font-weight: 600; transition: all .2s ease; }
.package-filter-btn:hover { color: #6d4aff; background: #f7f5ff; }
.package-filter-btn.active { color: #6d4aff; background: #f1edff; }
.package-filter-btn i { font-size: 16px; margin-top: 1px; }

/* Sort */
.package-sort { display: flex; align-items: center; gap: 9px; padding-right: 4px; }
.sort-label { color: #888; font-size: 13px; white-space: nowrap; }
.package-sort .form-select { width: 175px; border-color: #e7e4df; border-radius: 9px; color: #444; font-size: 13px; cursor: pointer; }
.package-sort .form-select:focus { border-color: #6d4aff; box-shadow: 0 0 0 .15rem rgba(109, 74, 255, .10); }

/* Card */
.package-catalog-card { position: relative; height: 100%; background: #fff; border: 1px solid #ece9e4; border-radius: 24px; padding: 30px; display: flex; flex-direction: column; box-shadow: 0 8px 30px rgba(0, 0, 0, .045); transition: transform .3s ease, box-shadow .3s ease; }
.package-catalog-card:hover { transform: translateY(-7px); box-shadow: 0 18px 45px rgba(0, 0, 0, .10); }
.package-catalog-card.popular { background: linear-gradient(145deg, #32165f 0%, #47217d 50%, #29205d 100%); border: 2px solid #7047e8; color: #fff; box-shadow: 0 20px 45px rgba(73, 37, 126, .25); transform: translateY(-10px); }
.package-catalog-card.popular:hover { transform: translateY(-16px); }
.popular-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: #7047e8; color: #fff; padding: 7px 18px; border-radius: 50px; font-size: 12px; font-weight: 700; white-space: nowrap; box-shadow: 0 6px 18px rgba(112, 71, 232, .35); }

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
.popular .package-features li i { color: #b49aff; }

/* Button */
.choose-package { width: 100%; border-radius: 12px; padding: 12px 20px; font-size: 14px; font-weight: 700; border: 1.5px solid #6d4aff; background: transparent; color: #6d4aff; transition: all .25s ease; }
.choose-package:hover { background: #6d4aff; color: #fff; }
.popular .choose-package { background: #7047e8; border-color: #7047e8; color: #fff; }
.popular .choose-package:hover { background: #805cf0; border-color: #805cf0; }
.saving-text { text-align: center; margin-top: 17px; color: #6d4aff; font-size: 13px; font-weight: 700; }
.popular .saving-text { color: rgba(255, 255, 255, .75); }

.package-cover { margin: -10px 0 18px; border-radius: 16px; overflow: hidden; }
.package-cover img { display: block; width: 100%; height: 175px; object-fit: cover; }
.package-event-meta { display: flex; flex-wrap: wrap; gap: 6px 14px; margin: -8px 0 20px; color: #6b7280; font-size: 12px; }
.package-event-meta span { display: inline-flex; align-items: center; }
.package-catalog-card.popular .package-event-meta { color: rgba(255, 255, 255, .78); }
.package-card-actions { display: grid; gap: 10px; }
.choose-package-wa { border-color: #25d366; color: #128c4b; text-decoration: none; }
.choose-package-wa:hover { background: #25d366; color: #fff; }
.package-catalog-card.popular .choose-package-wa { border-color: #25d366; color: #fff; }

.package-filter-form { margin-bottom: 28px; padding: 14px 16px; background: #fff; border: 1px solid #ece9e4; border-radius: 14px; box-shadow: 0 4px 18px rgba(0, 0, 0, .035); }
.package-filter-form .form-label { color: #777; font-weight: 600; }
.package-filter-form .form-control { border-color: #e7e4df; border-radius: 9px; font-size: 13px; }

.package-compare h4 { color: #202020; }
.compare-table { background: #fff; border-color: #ece9e4; font-size: 13px; }
.compare-table th, .compare-table td { min-width: 180px; border-color: #ece9e4; }
.compare-table thead th { background: #faf8ff; color: #444; font-weight: 700; }
.compare-table th:first-child, .compare-table td:first-child { position: sticky; left: 0; z-index: 1; min-width: 170px; background: #fff; font-weight: 700; color: #444; }
.compare-table thead th:first-child { z-index: 2; background: #faf8ff; }
.compare-table tbody tr:hover td { background: #faf8ff; }

/* Empty state */
.package-empty { background: #fff; border: 1px solid #ece9e4; border-radius: 20px; padding: 60px 30px; box-shadow: 0 8px 30px rgba(0, 0, 0, .035); }
.package-empty-icon { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; background: #f5f2ff; border-radius: 50%; color: #6d4aff; font-size: 28px; }
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

        <div class="package-filter">
            <div class="package-filter-left">
                <a href="{{ route('packages.member') }}" class="package-filter-btn {{ !request('filter') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i> Semua Paket
                </a>
                <a href="{{ route('packages.member', ['filter' => 'popular', 'sort' => request('sort')]) }}" class="package-filter-btn {{ request('filter') === 'popular' ? 'active' : '' }}">
                    <i class="bi bi-star-fill"></i> Paling Dipilih
                </a>
                <a href="{{ route('packages.member', ['filter' => 'unlimited', 'sort' => request('sort')]) }}" class="package-filter-btn {{ request('filter') === 'unlimited' ? 'active' : '' }}">
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
        </form>

        <div class="row g-4 align-items-stretch">
            @forelse ($packages as $package)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="package-catalog-card {{ $package->is_popular ? 'popular' : '' }}">

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
                    </div>

                    <div class="features-title">Yang Termasuk</div>
                    <ul class="package-features">
                        @forelse ($package->features as $feature)
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature->feature }}</span>
                        </li>
                        @empty
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Akses ke layanan Sagita Wedding Organizer</span>
                        </li>
                        @endforelse
                    </ul>

                    <div class="mt-auto package-card-actions">
                        <button type="button" class="choose-package w-100" onclick="window.location.href='{{ route('checkout.package', $package->uuid) }}'">
                            Pilih Paket <i class="bi bi-arrow-right ms-1"></i>
                        </button>

                        @if ($waNumber)
                        @php
                            $waText = 'Halo Admin Sagita Wedding, saya tertarik dengan paket "' . $package->name . '". Boleh saya tahu detail paket, ketersediaan tanggal, dan cara pemesanannya?';
                        @endphp
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank" rel="noopener" class="choose-package choose-package-wa w-100">
                            <i class="bi bi-whatsapp me-1"></i>Tanya Paket Ini
                        </a>
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
                    @if (request('filter') || request('sort'))
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
        <div class="package-compare mt-5">
            <h4 class="fw-bold mb-1">Bandingkan Paket</h4>
            <p class="text-muted small mb-4">Lihat perbedaan tiap paket untuk memilih yang paling sesuai.</p>

            <div class="table-responsive">
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

                                return $finals->count() ? $finals->min() : 0;
                            });
                        @endphp
                        <tr>
                            <th scope="row">Harga mulai</th>
                            @foreach ($packages as $package)
                            <td class="text-center">Rp {{ number_format($comparePrices[$loop->index] ?? 0, 0, ',', '.') }}</td>
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
                                <a href="{{ route('checkout.package', $package->uuid) }}" class="btn btn-sm btn-outline-primary">
                                    Pilih
                                </a>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
