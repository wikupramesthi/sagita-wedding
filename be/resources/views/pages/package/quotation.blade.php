<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Penawaran {{ $package->name }}</title>
    <style>
        @page { margin: 13mm 15mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #343a35; line-height: 1.5; }
        table { border-collapse: collapse; }
        .header { width: 100%; margin-bottom: 18px; border-bottom: 2px solid #587b62; }
        .header td { padding: 0 0 10px; vertical-align: middle; }
        .brand-mark { width: 38px; height: 38px; border-radius: 10px; background: #587b62; color: #fff; text-align: center; font-size: 12px; font-weight: bold; }
        .brand { padding-left: 10px !important; color: #42634c; font-size: 15px; font-weight: bold; }
        .brand-sub { color: #7d877f; font-size: 8px; font-weight: normal; }
        .header-meta { text-align: right; color: #778078; font-size: 8px; }
        .header-meta strong { color: #42634c; font-size: 10px; }
        .hero { width: 100%; margin-bottom: 14px; background: #edf4ee; border: 1px solid #dfe9e0; }
        .hero td { padding: 13px 15px; vertical-align: middle; }
        .hero-kicker { margin-bottom: 4px; color: #587b62; font-size: 7px; font-weight: bold; letter-spacing: 1px; }
        .hero-title { color: #2f4936; font-size: 17px; font-weight: bold; line-height: 1.3; }
        .hero-copy { margin-top: 4px; color: #67736a; font-size: 8px; }
        .hero-date { width: 115px; border-left: 1px solid #d7e3d8; color: #647068; text-align: center; font-size: 8px; }
        .hero-date strong { display: block; margin-top: 3px; color: #42634c; font-size: 9px; }
        .package-card { width: 100%; margin-bottom: 12px; border: 1px solid #e5e9e4; }
        .package-card td { padding: 9px 11px; }
        .package-title { color: #2f4936; font-size: 13px; font-weight: bold; }
        .package-label { padding: 4px 7px !important; border-radius: 12px; background: #fff1f2; color: #b94a5b; text-align: center; font-size: 7px; font-weight: bold; }
        .package-description { padding-top: 0 !important; color: #737c75; font-size: 8px; }
        .section-title { margin: 14px 0 6px; color: #34493a; font-size: 10px; font-weight: bold; }
        .section-subtitle { margin: -4px 0 7px; color: #818a83; font-size: 8px; }
        .details { width: 100%; margin-bottom: 10px; }
        .details td { width: 50%; padding: 7px 9px; border: 1px solid #e7ebe6; vertical-align: top; }
        .label { display: block; margin-bottom: 2px; color: #858d86; font-size: 7px; }
        .value { color: #3c453e; font-weight: bold; }
        .data { width: 100%; margin-top: 4px; }
        .data th { padding: 7px 8px; background: #edf3ee; color: #526457; text-align: left; font-size: 7px; }
        .data td { padding: 7px 8px; border-bottom: 1px solid #e8ece8; vertical-align: top; }
        .data tr:last-child td { border-bottom: 0; }
        .num { text-align: right !important; white-space: nowrap; }
        .muted { color: #818a83; font-size: 8px; }
        .old-price { display: block; color: #969d97; font-size: 7px; text-decoration: line-through; }
        .empty-price { padding: 12px !important; background: #fbfcfb; color: #778078; text-align: center; }
        .totals-wrap { width: 100%; margin-top: 10px; }
        .totals { width: 58%; margin-left: 42%; }
        .totals td { padding: 4px 7px; color: #687169; }
        .totals .grand td { padding-top: 8px; border-top: 1px solid #dce5dd; color: #34523b; font-size: 11px; font-weight: bold; }
        .features { width: 100%; margin: 3px 0 12px; }
        .features td { width: 50%; padding: 4px 7px 4px 0; color: #59645b; vertical-align: top; }
        .check { width: 14px !important; color: #587b62; font-weight: bold; }
        .note { margin-top: 12px; padding: 9px 11px; border-left: 3px solid #d4a36a; background: #fbf7f0; color: #716758; font-size: 8px; }
        .cta { width: 100%; margin-top: 13px; background: #587b62; }
        .cta td { padding: 10px 12px; color: #fff; vertical-align: middle; }
        .cta-title { font-size: 10px; font-weight: bold; }
        .cta-copy { margin-top: 2px; color: #e7efe8; font-size: 8px; }
        .cta-link { padding: 7px 9px !important; border-radius: 4px; background: #fff; color: #42634c !important; text-align: center; font-size: 8px; font-weight: bold; text-decoration: none; }
        .cta-fallback { color: #e7efe8; font-size: 8px; text-align: right; }
        .footer { margin-top: 14px; padding-top: 6px; border-top: 1px solid #e7ebe6; color: #929a93; text-align: center; font-size: 7px; }
    </style>
</head>
<body>
    @php
    $durationUnitLabels = [
        'day' => 'hari',
        'week' => 'minggu',
        'month' => 'bulan',
        'year' => 'tahun',
    ];
    $durationLabel = $option
        ? $option->duration.' '.($durationUnitLabels[$option->duration_unit] ?? $option->duration_unit)
        : '-';
    $optionHasDiscount = $option && $option->discount_price !== null && $option->discount_price < $option->price;
    $optionPrice = $option
        ? (int) ($optionHasDiscount ? $option->discount_price : $option->price)
        : 0;
    @endphp

    <table class="header">
        <tr>
            <td class="brand-mark">SW</td>
            <td class="brand">{{ $siteName }}<span class="brand-sub"><br>Wedding organizer untuk hari istimewa Anda</span></td>
            <td class="header-meta"><strong>PENAWARAN PAKET</strong><br>Dibuat {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="hero">
        <tr>
            <td>
                <div class="hero-kicker">LANGKAH PERTAMA MENUJU HARI BAHAGIA</div>
                <div class="hero-title">Rencana istimewa, untuk hari istimewa.</div>
                <div class="hero-copy">Berikut gambaran paket dan biaya yang dapat disesuaikan dengan kebutuhan acara Anda.</div>
            </td>
            <td class="hero-date">TANGGAL ACARA<strong>{{ $eventDate?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</strong></td>
        </tr>
    </table>

    <table class="package-card">
        <tr>
            <td class="package-title">{{ $package->name }}</td>
            <td class="package-label">PILIHAN PAKET</td>
        </tr>
        @if ($package->description)
        <tr>
            <td colspan="2" class="package-description">{{ $package->description }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">Ringkasan pilihan Anda</div>
    <table class="details">
        <tr>
            <td><span class="label">Opsi paket</span><span class="value">{{ $option?->name ?? 'Belum dipilih' }}</span></td>
            <td><span class="label">Durasi paket</span><span class="value">{{ $durationLabel }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Kuota layanan</span><span class="value">{{ $option ? ($option->quota === null ? 'Tanpa batas' : number_format($option->quota, 0, ',', '.').' layanan') : '-' }}</span></td>
            <td><span class="label">Kapasitas tamu</span><span class="value">{{ $package->guest_capacity ? number_format($package->guest_capacity, 0, ',', '.').' orang' : 'Dikonfirmasi bersama tim' }}</span></td>
        </tr>
        @if ($package->dp_percentage !== null || $package->payment_terms_note)
        <tr>
            <td><span class="label">Uang muka (DP)</span><span class="value">{{ $package->dp_percentage !== null ? (int) $package->dp_percentage.'%' : 'Dikonfirmasi bersama tim' }}</span></td>
            <td><span class="label">Ketentuan pembayaran</span><span class="value">{{ $package->payment_terms_note ?: 'Akan dikonfirmasi bersama tim' }}</span></td>
        </tr>
        @endif
    </table>

    <div class="section-title">Rincian biaya</div>
    <div class="section-subtitle">Harga ditampilkan secara transparan agar mudah dibandingkan.</div>
    <table class="data">
        <thead>
            <tr>
                <th width="43%">RINCIAN</th>
                <th class="num" width="12%">JUMLAH</th>
                <th class="num" width="22%">HARGA SATUAN</th>
                <th class="num" width="23%">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @if ($option)
            <tr>
                <td><strong>{{ $package->name }}</strong><br><span class="muted">Opsi: {{ $option->name }}</span></td>
                <td class="num">1</td>
                <td class="num">
                    @if ($optionHasDiscount)
                    <span class="old-price">Rp {{ number_format((int) $option->price, 0, ',', '.') }}</span>
                    @endif
                    Rp {{ number_format($optionPrice, 0, ',', '.') }}
                </td>
                <td class="num">Rp {{ number_format($optionPrice, 0, ',', '.') }}</td>
            </tr>
            @else
            <tr>
                <td colspan="4" class="empty-price">Harga paket belum tersedia. Tim kami akan membantu menyusun penawaran yang sesuai.</td>
            </tr>
            @endif

            @forelse ($addons as $addon)
            <tr>
                <td>{{ $addon->name }}</td>
                <td class="num">{{ $addon->quantity }}</td>
                <td class="num">Rp {{ number_format((int) $addon->final_price, 0, ',', '.') }}</td>
                <td class="num">Rp {{ number_format((int) $addon->line_total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="muted">Belum ada layanan tambahan yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($option)
    <table class="totals-wrap">
        <tr>
            <td>
                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td class="num">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if ($discount > 0)
                    <tr>
                        <td>Diskon promo</td>
                        <td class="num">− Rp {{ number_format($discount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="grand">
                        <td>Total penawaran</td>
                        <td class="num">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @endif

    @if ($package->features->isNotEmpty())
    <div class="section-title">Yang sudah termasuk</div>
    <table class="features">
        @foreach ($package->features->take(8)->chunk(2) as $featureRow)
        <tr>
            @foreach ($featureRow as $feature)
            <td><span class="check">✓</span> {{ $feature->feature }}</td>
            @endforeach
            @if ($featureRow->count() === 1)
            <td></td>
            @endif
        </tr>
        @endforeach
    </table>
    @if ($package->features->count() > 8)
    <div class="muted">Dan {{ $package->features->count() - 8 }} fitur lainnya tersedia dalam paket ini.</div>
    @endif
    @endif

    <div class="note">
        <strong>Catatan penawaran</strong><br>
        Rincian ini merupakan gambaran awal dan dapat disesuaikan setelah kebutuhan acara serta ketersediaan tanggal dikonfirmasi bersama tim {{ $siteName }}.
    </div>

    <table class="cta">
        <tr>
            <td>
                <div class="cta-title">Mari wujudkan pernikahan impian Anda.</div>
                <div class="cta-copy">Tim kami siap membantu menjawab pertanyaan dan menyesuaikan kebutuhan acara.</div>
            </td>
            @if ($waUrl)
            <td class="cta-link"><a href="{{ $waUrl }}" style="color: #42634c; text-decoration: none;">KONFIRMASI VIA WHATSAPP</a></td>
            @else
            <td class="cta-fallback">Hubungi tim {{ $siteName }} untuk melanjutkan.</td>
            @endif
        </tr>
    </table>

    <div class="footer">{{ $siteName }} · Penawaran Paket {{ $package->name }} · {{ now()->translatedFormat('d F Y') }}</div>
</body>
</html>
