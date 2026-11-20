<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dashboard {{ $periodLabel }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        h2 { font-size: 12px; margin: 18px 0 6px; }
        .meta { color: #6b7280; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 5px 7px; text-align: left; }
        th { background: #f3f4f6; }
        .num { text-align: right; }
    </style>
</head>
<body>
    <h1>{{ e($brandName) }}</h1>
    <div class="meta">Laporan dashboard admin &middot; {{ e($periodLabel) }} &middot; Dicetak {{ now()->translatedFormat('d M Y H:i') }}</div>

    <h2>Ringkasan</h2>
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th class="num">Nilai</th>
                <th class="num">Perubahan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stats as $stat)
                <tr>
                    <td>{{ e($stat['label']) }}</td>
                    <td class="num">{{ number_format($stat['value']) }}</td>
                    <td class="num">{{ $stat['change'] === null ? '-' : $stat['change'].'%' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (! empty($consultationPipeline))
        <h2>Pipeline Konsultasi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tahap</th>
                    <th class="num">Jumlah</th>
                    <th class="num">WIP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultationPipeline as $stage)
                    <tr>
                        <td>{{ e($stage['label']) }}</td>
                        <td class="num">{{ number_format($stage['count']) }}</td>
                        <td class="num">{{ number_format($stage['limit']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Funnel</h2>
    <table>
        <thead>
            <tr>
                <th>Tahap</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($funnel as $step)
                <tr>
                    <td>{{ e($step['label']) }}</td>
                    <td class="num">{{ number_format($step['value']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (! empty($consultationHighlights))
        <h2>Konsultasi</h2>
        <table>
            <thead>
                <tr>
                    <th>Label</th>
                    <th class="num">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultationHighlights as $label => $value)
                    <tr>
                        <td>{{ e(['conversion_rate' => 'Tingkat konversi', 'sla_breach' => 'SLA terlewat', 'sla_warning' => 'SLA mendekat', 'converted' => 'Terkonversi'][$label] ?? $label) }}</td>
                        <td class="num">{{ e($value) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Metrik cepat</h2>
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th class="num">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pesanan baru</td>
                <td class="num">{{ number_format($newOrdersCount) }}</td>
            </tr>
            <tr>
                <td>Keanggotaan baru</td>
                <td class="num">{{ number_format($newMembershipCount) }}</td>
            </tr>
            <tr>
                <td>Pendapatan periode</td>
                <td class="num">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pendapatan total</td>
                <td class="num">Rp {{ number_format($revenueTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Keamanan (24 jam terakhir)</h2>
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Login gagal</td>
                <td class="num">{{ number_format($security['failedLogins']) }}</td>
            </tr>
            <tr>
                <td>Akun terkunci</td>
                <td class="num">{{ number_format($security['lockouts']) }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Perlu tindakan</h2>
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pembayaran perlu verifikasi</td>
                <td class="num">{{ number_format($actionQueue['orderCount']) }}</td>
            </tr>
            <tr>
                <td>Follow-up terlewat</td>
                <td class="num">{{ number_format($actionQueue['messageCount']) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
