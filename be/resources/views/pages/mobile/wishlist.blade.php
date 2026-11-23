@extends('layouts.mobile')

@section('title', 'Wishlist Paket')

@section('content')

<style>
    .mobile-list-page {
        padding: 18px 16px 100px;
        background: var(--bg, #faf8f2);
        min-height: 100vh;
    }

    .mobile-list-head {
        margin-bottom: 18px;
    }

    .mobile-list-head h1 {
        color: var(--fg, #31392f);
        font-size: 22px;
        font-weight: 700;
        margin: 4px 0 6px;
    }

    .mobile-list-head p {
        color: var(--muted);
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    .mobile-list-links {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .mobile-list-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--card, #fffdf8);
        color: var(--sage, #6b8f6f);
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
    }

    .mobile-list-card {
        background: var(--card, #fffdf8);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 14px;
        box-shadow: 0 8px 25px rgba(49, 57, 47, .05);
    }

    .mobile-list-cover {
        display: block;
        width: 100%;
        height: 135px;
        border-radius: 14px;
        object-fit: cover;
        background: #f1ede4;
        margin-bottom: 13px;
    }

    .mobile-list-title {
        color: var(--fg, #31392f);
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .mobile-list-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }

    .mobile-list-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 8px;
        background: rgba(107, 143, 111, .09);
        color: var(--sage, #6b8f6f);
        font-size: 9px;
        font-weight: 700;
    }

    .mobile-list-price {
        color: var(--fg, #31392f);
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .mobile-list-price small {
        color: var(--muted);
        font-size: 9px;
        font-weight: 700;
    }

    .mobile-list-addons {
        color: var(--muted);
        font-size: 10px;
        line-height: 1.6;
        margin-bottom: 14px;
    }

    .mobile-list-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px;
        margin-bottom: 12px;
    }

    .mobile-list-actions form {
        margin: 0;
    }

    .mobile-list-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: 100%;
        padding: 10px 8px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: var(--card, #fffdf8);
        color: var(--muted);
        min-height: 42px;
        font-size: 9px;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
    }

    .mobile-list-btn.primary {
        grid-column: 1 / -1;
        border-color: var(--fg, #31392f);
        background: var(--fg, #31392f);
        color: #fff;
    }

    .mobile-list-btn.active {
        border-color: var(--sage, #6b8f6f);
        background: rgba(107, 143, 111, .10);
        color: var(--sage, #6b8f6f);
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
        margin: 0 0 14px;
    }

    @media (min-width: 500px) {
        .mobile-list-page {
            max-width: 430px;
            margin: 0 auto;
        }
    }
</style>

<div class="mobile-list-page">

    <div class="mobile-list-head">
        <p class="eyebrow mb-1">Wishlist Paket</p>
        <h1>Paket yang Kamu Simpan</h1>
        <p>Kumpulkan paket wedding organizer favoritmu untuk membantu memilih.</p>
        <div class="mobile-list-links">
            <a href="{{ route('packages.compare.index') }}" class="mobile-list-link">
                <i class="bi bi-list-check"></i>Bandingkan Paket
            </a>
            <a href="{{ route('packages.member') }}" class="mobile-list-link">
                <i class="bi bi-tags"></i>Jelajahi Paket
            </a>
        </div>
    </div>

    @php
        $compareUuids = array_values(array_filter(
            (array) session('package_compare', []),
            'is_string'
        ));
    @endphp

    @forelse ($packages as $package)
    @php
        $finalPrices = $package->options->map(fn ($option) => $option->discount_price !== null && $option->discount_price < $option->price ? $option->discount_price : $option->price);
        $priceFrom = $finalPrices->count() ? $finalPrices->min() : 0;
        $addonNames = $package->addons->take(3)->pluck('name')->join(', ');
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
    <div class="mobile-list-card">
        @if ($package->cover_url)
        <img class="mobile-list-cover" src="{{ $package->cover_url }}" alt="{{ $package->name }}" loading="lazy">
        @endif

        <div class="mobile-list-title">{{ $package->name }}</div>
        <div class="mobile-list-meta">
            <span><i class="bi bi-people"></i>{{ $package->guest_capacity ? number_format($package->guest_capacity, 0, ',', '.') . ' tamu' : 'Kapasitas belum ditentukan' }}</span>
            <span><i class="bi bi-check-circle"></i>Tersedia</span>
        </div>
        <div class="mobile-list-price"><small>Mulai dari </small>Rp {{ number_format($priceFrom, 0, ',', '.') }}</div>
        <div class="mobile-list-addons">
            <strong>Layanan tambahan:</strong> {{ $addonNames !== '' ? $addonNames : 'Belum ada layanan tambahan' }}
        </div>
        <div class="mobile-list-actions">
            <form method="POST" action="{{ route('packages.wishlist.toggle', $package->uuid) }}">
                @csrf
                <button type="submit" class="mobile-list-btn">
                    <i class="bi bi-heart-break"></i>Hapus
                </button>
            </form>
            <form method="POST" action="{{ route('packages.compare.add', $package->uuid) }}">
                @csrf
                <button type="submit" class="mobile-list-btn {{ $isCompared ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i>Bandingkan
                </button>
            </form>
            <a href="{{ route('packages.quotation', $quotationParams) }}" class="mobile-list-btn">
                <i class="bi bi-file-earmark-text"></i>Penawaran
            </a>
            <a href="{{ route('checkout.package', $package->uuid) }}" class="mobile-list-btn primary">
                <i class="bi bi-arrow-right"></i>Pilih Paket
            </a>
        </div>
    </div>
    @empty
    <div class="package-empty">
        <i class="bi bi-heart"></i>
        <h6>Belum Ada Paket di Wishlist</h6>
        <p>Simpan paket favoritmu dari katalog agar mudah ditemukan lagi.</p>
        <a href="{{ route('packages.member') }}" class="btn btn-sage btn-sm">Lihat Paket</a>
    </div>
    @endforelse

</div>

@endsection
