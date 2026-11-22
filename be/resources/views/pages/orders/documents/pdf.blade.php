<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #263228; font-size: 12px; line-height: 1.65; }
        h1 { font-size: 20px; margin: 0 0 6px; color: #36523c; }
        .meta { color: #647064; margin-bottom: 24px; }
        .card { border: 1px solid #dfe6df; border-radius: 8px; padding: 14px; margin: 16px 0; }
        .row { display: flex; justify-content: space-between; gap: 16px; padding: 5px 0; border-bottom: 1px solid #edf0ed; }
        .row:last-child { border-bottom: 0; }
        .label { color: #687368; }
        .value { font-weight: bold; text-align: right; }
        .contract { white-space: normal; }
        .muted { color: #6b756b; font-size: 10px; }
    </style>
</head>
<body>
    @php
        $snapshot = $document->snapshot ?? [];
    @endphp
    <h1>{{ $document->type === 'contract' ? 'Kontrak Layanan' : ($document->type === 'invoice' ? 'Invoice Pembayaran' : 'Tanda Terima Pembayaran') }}</h1>
    <div class="meta">Nomor dokumen: {{ $document->document_number }} · Diterbitkan {{ $document->issued_at?->translatedFormat('d F Y') }}</div>

    @if ($document->type === 'contract')
        @php
            $values = [
                '{{document_number}}' => $document->document_number,
                '{{issued_at}}' => $snapshot['issued_at'] ?? '',
                '{{customer_name}}' => $snapshot['customer_name'] ?? '',
                '{{order_number}}' => $snapshot['order_number'] ?? '',
                '{{package_name}}' => $snapshot['package_name'] ?? '',
                '{{option_name}}' => $snapshot['option_name'] ?? '',
                '{{event_date}}' => $snapshot['event_date'] ?? '',
                '{{total_amount}}' => 'Rp '.number_format((float) ($snapshot['total_amount'] ?? 0), 0, ',', '.'),
                '{{amount_paid}}' => 'Rp '.number_format((float) ($snapshot['amount_paid'] ?? 0), 0, ',', '.'),
                '{{remaining_amount}}' => 'Rp '.number_format((float) ($snapshot['remaining_amount'] ?? 0), 0, ',', '.'),
            ];
            $contractText = strtr((string) ($snapshot['contract_template'] ?? ''), $values);
        @endphp
        <div class="contract">{!! nl2br(e($contractText)) !!}</div>
    @else
        <div class="card">
            <div class="row"><span class="label">Pelanggan</span><span class="value">{{ $snapshot['customer_name'] ?? '-' }}</span></div>
            <div class="row"><span class="label">Nomor pesanan</span><span class="value">{{ $snapshot['order_number'] ?? '-' }}</span></div>
            <div class="row"><span class="label">Paket</span><span class="value">{{ $snapshot['package_name'] ?? '-' }} · {{ $snapshot['option_name'] ?? '-' }}</span></div>
            <div class="row"><span class="label">Tanggal acara</span><span class="value">{{ $snapshot['event_date'] ?? '-' }}</span></div>
            <div class="row"><span class="label">Total pesanan</span><span class="value">Rp {{ number_format((float) ($snapshot['total_amount'] ?? 0), 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">{{ $document->type === 'invoice' ? 'Pembayaran pertama' : 'Pembayaran diterima' }}</span><span class="value">Rp {{ number_format((float) ($snapshot['payment_amount'] ?? 0), 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Total terverifikasi</span><span class="value">Rp {{ number_format((float) ($snapshot['amount_paid'] ?? 0), 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Sisa pembayaran</span><span class="value">Rp {{ number_format((float) ($snapshot['remaining_amount'] ?? 0), 0, ',', '.') }}</span></div>
        </div>
        <p class="muted">Pembayaran tercatat pada {{ $snapshot['payment_date'] ?? '-' }}. Dokumen ini dibuat dari pembayaran yang telah diverifikasi.</p>
    @endif
</body>
</html>
