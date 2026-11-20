<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h3 {
            margin-bottom: 4px;
        }

        p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px;
        }

        th {
            background: #f3f4f6;
            text-align: center;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Data Calon Pelanggan</h3>
    <p style="text-align: center;">Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}</p>

    @if (! empty($filters['q']) || ! empty($filters['status']) || ! empty($filters['sumber']) || ! empty($filters['tahun']) || ! empty($filters['start_date']) || ! empty($filters['end_date']))
        <p style="margin-top: 12px;">
            <strong>Filter:</strong>
            @if (! empty($filters['q']))
                Kata kunci <u>{{ $filters['q'] }}</u>
            @endif
            @if (! empty($filters['status']))
                | Status <u>{{ App\Models\User::PROSPECT_STATUS_LABELS[$filters['status']] ?? '-' }}</u>
            @endif
            @if (! empty($filters['sumber']))
                | Sumber <u>{{ $sourceOptions[$filters['sumber']] ?? '-' }}</u>
            @endif
            @if (! empty($filters['tahun']))
                | Tahun <u>{{ $filters['tahun'] }}</u>
            @endif
            @if (! empty($filters['start_date']))
                | Dari <u>{{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}</u>
            @endif
            @if (! empty($filters['end_date']))
                | Sampai <u>{{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</u>
            @endif
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Sumber Informasi</th>
                <th>Jumlah Calon Pelanggan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sourceOptions as $key => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td style="text-align: right;">{{ number_format($sourceStats[$key]) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td style="text-align: right;"><strong>{{ number_format(array_sum($sourceStats)) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Calon Pelanggan</th>
                <th>Email</th>
                <th>No. WhatsApp</th>
                <th>Tanggal Daftar</th>
                <th>Status Calon Pelanggan</th>
                <th>Lead dari Mana</th>
                <th>Paket Pemesanan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengguna as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_hp ?? '-' }}</td>
                    <td>{{ $user->created_at?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $user->prospect_status_label }}</td>
                    <td>{{ $user->sumber_informasi_label }}</td>
                    <td>{{ $user->latestOrder?->package?->name ?? 'Belum ada' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data yang cocok dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
