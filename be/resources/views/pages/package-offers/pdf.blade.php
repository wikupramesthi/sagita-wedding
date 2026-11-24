<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $offer->title }}</title>
    <style>
        @page { margin: 34px 38px; }
        body { color: #29323b; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.55; }
        .header { border-bottom: 2px solid #a87b52; margin-bottom: 22px; padding-bottom: 14px; }
        .brand { color: #795b3f; font-size: 21px; font-weight: bold; }
        .muted { color: #75808a; }
        .eyebrow { color: #a87b52; font-size: 9px; font-weight: bold; letter-spacing: 1.3px; text-transform: uppercase; }
        h1 { color: #29323b; font-size: 23px; margin: 5px 0 3px; }
        h2 { color: #795b3f; font-size: 13px; margin: 0 0 10px; }
        .meta { margin-top: 13px; }
        .meta td { padding: 3px 14px 3px 0; }
        .panel { background: #f7f4f0; border-radius: 5px; margin: 14px 0 19px; padding: 12px 14px; }
        .details { width: 100%; }
        .details td { padding: 4px 8px 4px 0; vertical-align: top; width: 50%; }
        table.items { border-collapse: collapse; margin-top: 8px; width: 100%; }
        .items th { background: #f2ede7; color: #554332; font-size: 9px; text-align: left; }
        .items th, .items td { border-bottom: 1px solid #e4e0dc; padding: 8px 7px; }
        .right { text-align: right !important; }
        .totals { margin: 12px 0 0 auto; width: 48%; }
        .totals td { padding: 4px 0; }
        .grand-total td { border-top: 1px solid #a87b52; color: #795b3f; font-size: 13px; font-weight: bold; padding-top: 8px; }
        .note { border-left: 3px solid #a87b52; margin-top: 16px; padding: 5px 0 5px 10px; }
        .footer { border-top: 1px solid #e4e0dc; color: #75808a; font-size: 9px; margin-top: 26px; padding-top: 9px; }
        .keep { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">{{ $identity->site_name ?: 'Sagita Wedding' }}</div>
        @if ($identity->tagline)<div class="muted">{{ $identity->tagline }}</div>@endif
        <table class="meta">
            <tr>
                <td><span class="eyebrow">Penawaran privat</span></td>
                <td class="right muted">No. {{ strtoupper(substr(str_replace('-', '', $offer->uuid), 0, 12)) }} · Versi {{ $offer->version }}</td>
            </tr>
        </table>
    </div>

    <div class="eyebrow">Disiapkan khusus untuk</div>
    <h1>{{ $offer->title }}</h1>
    <div class="muted">{{ $offer->user?->name }} · {{ $offer->user?->email }}</div>

    <div class="panel keep">
        <h2>Rencana acara</h2>
        <table class="details">
            <tr>
                <td><span class="muted">Tanggal</span><br><strong>{{ $offer->event_date?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</strong></td>
                <td><span class="muted">Jumlah tamu</span><br><strong>{{ $offer->guest_count ? number_format($offer->guest_count, 0, ',', '.').' orang' : 'Belum ditentukan' }}</strong></td>
            </tr>
            <tr>
                <td colspan="2"><span class="muted">Lokasi</span><br><strong>{{ $offer->venue_note ?: 'Akan dikonfirmasi' }}</strong></td>
            </tr>
        </table>
        @if ($offer->description)
        <div class="note"><strong>Rincian kebutuhan</strong><br>{{ $offer->description }}</div>
        @endif
        @if ($offer->customer_note)
        <div class="note"><strong>Catatan untuk pelanggan</strong><br>{{ $offer->customer_note }}</div>
        @endif
    </div>

    @foreach ($offer->options as $option)
    <div class="keep">
        <h2>{{ $option->label }}</h2>
        @if ($option->description)<div class="muted">{{ $option->description }}</div>@endif
        <table class="items">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="right">Jumlah</th>
                    <th class="right">Harga satuan</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($option->items as $item)
                <tr>
                    <td><strong>{{ $item->name }}</strong>@if ($item->description)<br><span class="muted">{{ $item->description }}</span>@endif</td>
                    <td class="right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="totals keep">
            <tr><td>Subtotal</td><td class="right">Rp {{ number_format($option->subtotal_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Diskon</td><td class="right">− Rp {{ number_format($option->discount_amount, 0, ',', '.') }}</td></tr>
            <tr class="grand-total"><td>Total</td><td class="right">Rp {{ number_format($option->total_amount, 0, ',', '.') }}</td></tr>
        </table>
        <div class="panel keep">
            <h2>Ketentuan pembayaran</h2>
            <div>DP awal: <strong>{{ $option->deposit_percentage }}%</strong> (Rp {{ number_format((int) round($option->total_amount * $option->deposit_percentage / 100), 0, ',', '.') }})</div>
            @if ($option->payment_terms_note)<div style="margin-top:5px">{{ $option->payment_terms_note }}</div>@endif
        </div>
    </div>
    @endforeach

    <div class="muted" style="margin-top:10px">
        @if ($offer->status === 'sent' && $offer->expires_at)
            Berlaku sampai {{ $offer->expires_at->translatedFormat('d F Y, H:i') }}.
        @elseif ($offer->status === 'draft')
            Pratinjau draf — belum dikirim dan belum berlaku.
        @else
            Status: {{ match ($offer->display_status) {
                'accepted' => 'Disetujui',
                'rejected' => 'Ditolak',
                'expired' => 'Kedaluwarsa',
                'superseded' => 'Digantikan versi baru',
                'revision_requested' => 'Menunggu revisi',
                default => 'Dalam proses',
            } }}.
        @endif
    </div>

    <div class="footer">
        {{ $identity->site_name ?: 'Sagita Wedding' }}
        @if ($identity->phone) · {{ $identity->phone }}@endif
        @if ($identity->email) · {{ $identity->email }}@endif
        <br>Dokumen ini dibuat khusus untuk penerima yang tercantum di atas.
    </div>
</body>
</html>
