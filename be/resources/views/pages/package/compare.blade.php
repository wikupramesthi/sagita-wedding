@extends('layouts.app')
@section('title', 'Bandingkan Paket')
@section('content')

<style>
    .package-list-page { min-height: 100vh; padding: 40px 0 70px; }
    .package-list-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
    .package-list-head h1 { margin: 0 0 8px; font-size: 28px; font-weight: 800; color: #202020; }
    .package-list-head p { margin: 0; color: #888; font-size: 14px; }
    .package-list-actions { display: flex; flex-wrap: wrap; gap: 10px; }
    .package-list-link { display: inline-flex; align-items: center; gap: 7px; padding: 10px 16px; border: 1px solid #6d4aff; border-radius: 10px; color: #6d4aff; background: #fff; font-size: 13px; font-weight: 700; text-decoration: none; }
    .package-list-link:hover { background: #f7f5ff; }
    .package-list-link.danger { border-color: #dc9a8f; color: #b8503f; }
    .package-list-link.danger:hover { background: #fff5f3; }
    .package-list-card { display: grid; grid-template-columns: 190px 1fr; gap: 24px; margin-bottom: 18px; padding: 20px; background: #fff; border: 1px solid #ece9e4; border-radius: 20px; box-shadow: 0 8px 30px rgba(0, 0, 0, .045); }
    .package-list-card.no-cover { grid-template-columns: 1fr; }
    .package-list-cover { width: 100%; height: 155px; border-radius: 14px; object-fit: cover; background: #f4f1eb; }
    .package-list-title { margin: 0 0 6px; font-size: 21px; font-weight: 700; color: #202020; }
    .package-list-meta { display: flex; flex-wrap: wrap; gap: 8px 16px; margin-bottom: 12px; color: #6b7280; font-size: 12px; }
    .package-list-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .package-list-price { margin-bottom: 8px; color: #202020; font-size: 19px; font-weight: 800; }
    .package-list-price small { color: #8b8f87; font-size: 11px; font-weight: 600; }
    .package-list-addons { margin-bottom: 16px; color: #6b7280; font-size: 12px; line-height: 1.6; }
    .package-list-actions-row { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin: 16px 0 14px; }
    .package-list-actions-row form { margin: 0; }
    .package-list-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; min-height: 42px; padding: 8px 6px; border: 1px solid #e7e4df; border-radius: 9px; background: #fff; color: #555; font-size: 11px; font-weight: 700; line-height: 1.2; text-decoration: none; cursor: pointer; }
    .package-list-btn i { font-size: 14px; }
    .package-list-btn:hover { border-color: #6d4aff; color: #6d4aff; }
    .package-list-btn.primary { display: flex; border-color: #6d4aff; background: #6d4aff; color: #fff; font-size: 13px; }
    .package-list-btn.primary:hover { background: #5b38f0; color: #fff; }
    .package-list-table { margin-top: 26px; }
    .package-list-table table { background: #fff; border-color: #ece9e4; font-size: 13px; }
    .package-list-table th, .package-list-table td { min-width: 170px; border-color: #ece9e4; vertical-align: middle; }
    .package-list-table th:first-child, .package-list-table td:first-child { position: sticky; left: 0; z-index: 1; min-width: 170px; background: #fff; color: #444; }
    .package-list-table thead th:first-child { z-index: 2; }
    .package-list-table thead th { background: #faf8ff; color: #444; font-weight: 700; }
    .package-list-table th:first-child, .package-list-table td:first-child { font-weight: 700; color: #444; }
    .package-empty { background: #fff; border: 1px solid #ece9e4; border-radius: 20px; padding: 60px 30px; box-shadow: 0 8px 30px rgba(0, 0, 0, .035); }
    .package-empty-icon { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; background: #f5f2ff; border-radius: 50%; color: #6d4aff; font-size: 28px; }
    .package-empty h5 { color: #333; margin-bottom: 8px; }
    .package-empty p { color: #888; margin-bottom: 0; font-size: 14px; }

    @media (max-width: 991.98px) {
        .package-list-actions-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 767px) {
        .package-list-head { flex-direction: column; align-items: flex-start; }
        .package-list-card { grid-template-columns: 1fr; }
        .package-list-actions-row { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .package-list-cover { height: 175px; }
    }
</style>

<div class="package-list-page">
    <div class="container">

        <div class="package-list-head">
            <div>
                <p class="text-uppercase fw-bold mb-1" style="color: #6d4aff; font-size: 11px; letter-spacing: .08em;">Bandingkan Paket</p>
                <h1>Pilih Paket Terbaikmu</h1>
                <p>Lihat harga mulai, kapasitas tamu, dan layanan tambahan secara berdampingan.</p>
            </div>
            <div class="package-list-actions">
                @if (count($packages))
                <form method="POST" action="{{ route('packages.compare.clear') }}">
                    @csrf
                    <button type="submit" class="package-list-link danger">
                        <i class="bi bi-trash3"></i>Kosongkan Perbandingan
                    </button>
                </form>
                @endif
                <a href="{{ route('packages.member') }}" class="package-list-link">
                    <i class="bi bi-tags"></i>Jelajahi Paket
                </a>
            </div>
        </div>

        @forelse ($packages as $package)
        @php
            $finalPrices = $package->options->map(fn ($option) => $option->discount_price !== null && $option->discount_price < $option->price ? $option->discount_price : $option->price);
            $priceFrom = $finalPrices->count() ? $finalPrices->min() : 0;
            $addonNames = $package->addons->take(3)->pluck('name')->join(', ');

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
        <div class="package-list-card {{ $package->cover_url ? '' : 'no-cover' }}">
            @if ($package->cover_url)
            <img class="package-list-cover" src="{{ $package->cover_url }}" alt="Foto {{ $package->name }}" loading="lazy">
            @endif
            <div>
                <h2 class="package-list-title">{{ $package->name }}</h2>
                <div class="package-list-meta">
                    <span><i class="bi bi-people"></i>{{ $package->guest_capacity ? number_format($package->guest_capacity, 0, ',', '.') . ' tamu' : 'Kapasitas belum ditentukan' }}</span>
                    <span><i class="bi bi-check-circle"></i>Tersedia</span>
                </div>
                <div class="package-list-price">Mulai dari Rp {{ number_format($priceFrom, 0, ',', '.') }}</div>
                <div class="package-list-addons">
                    <strong>Layanan tambahan:</strong> {{ $addonNames !== '' ? $addonNames : 'Belum ada layanan tambahan' }}
                </div>
                <div class="package-list-actions-row">
                    <form method="POST" action="{{ route('packages.compare.remove', $package->uuid) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="package-list-btn">
                            <i class="bi bi-x-circle"></i>Hapus Bandingan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('packages.wishlist.toggle', $package->uuid) }}">
                        @csrf
                        <button type="submit" class="package-list-btn">
                            <i class="bi bi-heart"></i>Simpan Wishlist
                        </button>
                    </form>
                    <a href="{{ route('packages.quotation', $quotationParams) }}" class="package-list-btn">
                        <i class="bi bi-file-earmark-pdf"></i>Penawaran
                    </a>
                </div>
                <a href="{{ route('checkout.package', $package->uuid) }}" class="package-list-btn primary">
                    <i class="bi bi-arrow-right"></i>Pilih Paket
                </a>
            </div>
        </div>
        @empty
        <div class="package-empty text-center">
            <div class="package-empty-icon"><i class="bi bi-list-check"></i></div>
            <h5 class="fw-bold">Belum Ada Paket untuk Dibandingkan</h5>
            <p>Tambahkan maksimal empat paket dari katalog untuk melihat perbandingannya.</p>
            <div class="mt-4">
                <a href="{{ route('packages.member') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-tags me-1"></i>Lihat Paket
                </a>
            </div>
        </div>
        @endforelse

        @if (count($packages))
        <div class="package-list-table table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Informasi</th>
                        @foreach ($packages as $package)
                        <th scope="col">{{ $package->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Harga mulai</th>
                        @foreach ($packages as $package)
                        <td>
                            @php
                                $finals = $package->options->map(fn ($option) => $option->discount_price !== null && $option->discount_price < $option->price ? $option->discount_price : $option->price);
                            @endphp
                            Rp {{ number_format($finals->count() ? $finals->min() : 0, 0, ',', '.') }}
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Kapasitas Tamu</th>
                        @foreach ($packages as $package)
                        <td>{{ $package->guest_capacity ? number_format($package->guest_capacity, 0, ',', '.') . ' tamu' : '-' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Layanan Tambahan</th>
                        @foreach ($packages as $package)
                        <td>{{ $package->addons->take(3)->pluck('name')->join(', ') ?: '-' }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

@endsection
