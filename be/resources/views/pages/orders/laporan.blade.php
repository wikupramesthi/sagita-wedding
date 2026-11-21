<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan</title>
    <style>
        @page {
            margin: 22px 28px 42px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1f2937;
            line-height: 1.45;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 90px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text .instansi {
            font-size: 14px;
            font-weight: bold;
            color: #1B3A5F;
            letter-spacing: 0.5px;
        }

        .kop-text .sub {
            font-size: 9px;
            color: #4b5563;
        }

        .kop-rule {
            border: none;
            border-top: 3px solid #1B3A5F;
            margin: 6px 0 1px 0;
        }

        .kop-rule2 {
            border: none;
            border-top: 1px solid #C9A227;
            margin: 0 0 12px 0;
        }

        .judul {
            text-align: center;
            margin-bottom: 6px;
        }

        .judul h1 {
            font-size: 15px;
            color: #1B3A5F;
            margin: 0 0 2px 0;
            letter-spacing: 1px;
        }

        .judul .periode {
            font-size: 10px;
            font-weight: bold;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
            font-size: 8px;
            color: #4b5563;
        }

        .meta td {
            padding: 2px 4px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8px;
        }

        .summary td {
            border: 1px solid #e5e7eb;
            padding: 5px 8px;
            text-align: center;
        }

        .summary .val {
            font-size: 12px;
            font-weight: bold;
            color: #1B3A5F;
        }

        .summary .lbl {
            color: #6b7280;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1B3A5F;
            border-left: 4px solid #C9A227;
            padding-left: 8px;
            margin: 12px 0 8px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .data-table th {
            background: #1B3A5F;
            color: #ffffff;
            padding: 5px 4px;
            text-align: left;
        }

        .data-table td {
            padding: 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) td {
            background: #f3f4f6;
        }

        .data-table tfoot td {
            background: #eef2f7;
            font-weight: bold;
            border-top: 2px solid #1B3A5F;
        }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            color: #fff;
        }

        .badge-paid {
            background: #198754;
        }

        .badge-pending {
            background: #b7791f;
        }

        .badge-failed {
            background: #dc3545;
        }

        .badge-expired {
            background: #6c757d;
        }

        .badge-cancelled {
            background: #6c757d;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .ttd-table td {
            width: 50%;
            vertical-align: top;
        }

        .ttd-box {
            text-align: center;
            font-size: 9px;
        }

        .footer {
            position: fixed;
            bottom: -28px;
            left: 0;
            right: 0;
            font-size: 7px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 4px;
        }

        .pagenum:before {
            content: counter(page);
        }

        .filter-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 8px;
        }
    </style>
</head>

<body>
    @php
    $statusLabels = [
    'paid' => 'Lunas',
    'pending' => 'Menunggu',
    'failed' => 'Gagal',
    'expired' => 'Kedaluwarsa',
    'cancelled' => 'Dibatalkan',
    ];
    $hasColumn = fn (string $key): bool => in_array($key, $columns, true);
    $visibleColumns = array_values(array_filter(
    ['date', 'order', 'customer', 'package', 'amount', 'status', 'proof'],
    function (string $key) use ($hasColumn, $isAdmin): bool {
    if ($key === 'customer') {
    return $isAdmin && $hasColumn('customer');
    }
    return $hasColumn($key);
    }
    ));
    @endphp
    <div class="footer">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td>Laporan Pesanan — Sagita Wedding Organizer</td>
                <td style="text-align:right;">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </div>

    <table class="kop-table">
        <tr>
            <td class="kop-logo"><img src="{{ public_path('img/logo.webp') }}" style="height:60px;" alt="Logo"></td>
            <td class="kop-text">
                <div class="instansi">SAGITA WEDDING ORGANIZER</div>
                <div class="sub">Wedding Organizer &amp; Event Planner</div>
                <div class="sub">Jakarta, Indonesia</div>
            </td>
            <td class="kop-logo"></td>
        </tr>
    </table>
    <hr class="kop-rule">
    <hr class="kop-rule2">

    <div class="judul">
        <h1>LAPORAN PESANAN</h1>
        <div class="periode">Periode: {{ $periode }}</div>
    </div>

    <table class="meta">
        <tr>
            <td style="width:22%;">Filter Status</td>
            <td style="width:35%;">: {{ $filterStatus }}</td>
            <td style="width:18%;">Dicetak oleh</td>
            <td>: {{ $dicetakOleh }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>: {{ $periode }}</td>
            <td>Waktu Cetak</td>
            <td>: {{ $waktuCetak }} WIB</td>
        </tr>
        <tr>
            <td>Total Data</td>
            <td>: {{ number_format($total) }} pesanan</td>
            <td>Pendapatan (Lunas)</td>
            <td>: Rp {{ number_format($revenue, 0, ',', '.') }}</td>
        </tr>
        @if(!empty($search))<tr>
            <td>Pencarian</td>
            <td colspan="3">: {{ $search }}</td>
        </tr>@endif
    </table>

    <table class="summary">
        <tr>
            <td>
                <div class="val">{{ number_format($total) }}</div>
                <div class="lbl">Total Pesanan</div>
            </td>
            <td>
                <div class="val">{{ number_format($pendingCount) }}</div>
                <div class="lbl">Menunggu</div>
            </td>
            <td>
                <div class="val">{{ number_format($paidCount) }}</div>
                <div class="lbl">Lunas</div>
            </td>
            <td>
                <div class="val">{{ number_format($failedCount) }}</div>
                <div class="lbl">Gagal / Kedaluwarsa / Dibatalkan</div>
            </td>
            <td>
                <div class="val">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                <div class="lbl">Pendapatan (Lunas)</div>
            </td>
        </tr>
    </table>

    <div class="filter-box">
        <strong>Catatan:</strong> Laporan ini dibuat otomatis dari menu <em>Riwayat Pemesanan</em> Sagita Wedding Organizer. Pesanan menunggu perlu verifikasi transfer manual oleh admin sebelum keanggotaan diaktifkan.
    </div>

    <div class="section-title">DAFTAR PESANAN ({{ number_format($total) }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                @if ($hasColumn('date'))
                <th style="width:11%;">Tanggal</th>
                @endif
                @if ($hasColumn('order'))
                <th style="width:12%;">No. Pesanan</th>
                @endif
                @if ($hasColumn('customer') && $isAdmin)
                <th style="width:15%;">Pelanggan</th>
                @endif
                @if ($hasColumn('package'))
                <th style="width:18%;">Paket</th>
                @endif
                @if ($hasColumn('amount'))
                <th style="width:12%; text-align:right;">Jumlah</th>
                @endif
                @if ($hasColumn('status'))
                <th style="width:9%;">Status</th>
                @endif
                @if ($hasColumn('proof'))
                <th style="width:9%;">Bukti</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                @if ($hasColumn('date'))
                <td>{{ $order->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</td>
                @endif
                @if ($hasColumn('order'))
                <td>{{ $order->order_number }}</td>
                @endif
                @if ($hasColumn('customer') && $isAdmin)
                <td>{{ $order->user?->name ?? '-' }}<br><small style="color:#6b7280;">{{ $order->user?->email ?? '-' }}</small></td>
                @endif
                @if ($hasColumn('package'))
                <td>{{ $order->package?->name ?? '-' }}<br><small style="color:#6b7280;">{{ $order->packageOption?->name ?? '' }}</small></td>
                @endif
                @if ($hasColumn('amount'))
                <td style="text-align:right;">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                @endif
                @if ($hasColumn('status'))
                <td><span class="badge badge-{{ $order->status }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span></td>
                @endif
                @if ($hasColumn('proof'))
                <td>{{ $order->proof_image_path ? 'Diunggah' : '-' }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($visibleColumns) + 1 }}" style="text-align:center; color:#6b7280;">Tidak ada data untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
        @if ($orders->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="{{ count($visibleColumns) + 1 }}" style="text-align:right;">Total Pendapatan (Lunas): Rp {{ number_format($revenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <table class="ttd-table">
        <tr>
            <td></td>
            <td>
                <div class="ttd-box">
                    Jakarta, {{ $waktuCetak }}<br>
                    Dicetak oleh,<br><br><br><br><br>
                    <strong><u>{{ $dicetakOleh }}</u></strong>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>