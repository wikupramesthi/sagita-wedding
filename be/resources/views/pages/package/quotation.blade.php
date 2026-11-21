<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penawaran {{ $package->name }}</title>
    <style>
        @page { margin: 14mm 16mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #31392f; line-height: 1.5; }
        .header { width: 100%; border-bottom: 2px solid #4b6b52; padding-bottom: 10px; margin-bottom: 16px; }
        .brand { font-size: 17px; font-weight: bold; color: #4b6b52; }
        .brand-sub { font-size: 8px; color: #858d83; }
        .doc-title { font-size: 14px; font-weight: bold; text-align: right; }
        .doc-meta { font-size: 8px; color: #858d83; text-align: right; }
        .section-title { font-size: 11px; font-weight: bold; color: #31392f; margin: 14px 0 6px 0; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { border: 1px solid #e3e7e1; padding: 7px 9px; font-size: 9px; vertical-align: top; }
        .label { color: #858d83; font-size: 8px; display: block; margin-bottom: 2px; }
        .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 4px; }
        table.data th { background: #f1f4ef; text-align: left; border: 1px solid #e3e7e1; padding: 6px 8px; font-size: 8px; }
        table.data td { border: 1px solid #e3e7e1; padding: 6px 8px; font-size: 9px; }
        .num { text-align: right; white-space: nowrap; }
        .totals { width: 100%; margin-top: 12px; }
        .totals td { padding: 4px 8px; font-size: 9px; }
        .totals .grand { font-size: 12px; font-weight: bold; color: #4b6b52; }
        .notes { margin-top: 16px; padding: 9px 11px; background: #f7f8f5; border: 1px solid #e3e7e1; font-size: 8px; }
        .wa { margin-top: 14px; font-size: 9px; }
        .wa a { color: #4b6b52; text-decoration: none; word-break: break-all; }
        .footer { margin-top: 18px; border-top: 1px solid #e3e7e1; padding-top: 6px; font-size: 7px; color: #9aa093; text-align: center; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="brand">Sagita Wedding Organizer<span class="brand-sub"><br>Penyelenggara pernikahan Anda</span></td>
            <td class="doc-title">Penawaran Paket<br><span class="doc-meta">Dicetak {{ now()->translatedFormat('d F Y') }}</span></td>
        </tr>
    </table>

    <div class="section-title">Detail Paket</div>
    <table class="info">
        <tr>
            <td width="60%"><span class="label">Nama paket</span><span class="value">{{ $package->name }}</span></td>
            <td width="40%"><span class="label">Opsi paket</span><span class="value">{{ $option?->name ?? '-' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Deskripsi</span><span class="value">{{ $package->description ?: '-' }}</span></td>
            <td><span class="label">Durasi layanan</span><span class="value">
                @if ($option)
                {{ $option->duration }} {{ ucfirst($option->duration_unit) }}
                @else
                -
                @endif
            </span></td>
        </tr>
        <tr>
            <td><span class="label">Tanggal acara</span><span class="value">{{ $eventDate?->translatedFormat('d F Y') ?? '-' }}</span></td>
            <td><span class="label">Kuota</span><span class="value">
                @if ($option)
                {{ $option->quota === null ? 'Tanpa batas' : $option->quota . ' kelas' }}
                @else
                -
                @endif
            </span></td>
        </tr>
    </table>

    <div class="section-title">Layanan Tambahan</div>
    <table class="data">
        <thead>
            <tr>
                <th width="45%">Layanan</th>
                <th class="num" width="15%">Jumlah</th>
                <th class="num" width="20%">Harga Satuan</th>
                <th class="num" width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($addons as $addon)
            <tr>
                <td>{{ $addon->name }}</td>
                <td class="num">x{{ $addon->quantity }}</td>
                <td class="num">Rp {{ number_format((int) $addon->final_price, 0, ',', '.') }}</td>
                <td class="num">Rp {{ number_format((int) $addon->line_total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Tidak ada layanan tambahan yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td width="70%">Subtotal paket dan layanan tambahan</td>
            <td class="num">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon promo</td>
            <td class="num">- Rp {{ number_format($discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="grand">Total penawaran</td>
            <td class="num grand">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="notes">
        Penawaran ini berlaku sebagai gambaran biaya layanan wedding organizer untuk tanggal acara yang telah disebutkan.
        Harga dapat disesuaikan kembali setelah detail teknis acara dikonfirmasi oleh tim Sagita Wedding Organizer.
        Mohon konfirmasi Ketersediaan tanggal dan pemesanan melalui tautan WhatsApp di bawah ini.
    </div>

    @if ($waUrl)
    <div class="wa">
        Konfirmasi dan lanjutkan melalui WhatsApp:
        <a href="{{ $waUrl }}">{{ $waUrl }}</a>
    </div>
    @endif

    <div class="footer">Sagita Wedding Organizer — Penawaran Paket {{ $package->name }}</div>
</body>
</html>
