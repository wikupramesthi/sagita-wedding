<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 16mm 14mm 18mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #233028; font-size: 10.5px; line-height: 1.6; }
        .brand-row { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .brand-row td { vertical-align: top; }
        .brand-logo { width: 56px; height: auto; max-height: 56px; object-fit: contain; }
        .brand-name { font-size: 17px; font-weight: bold; color: #254734; letter-spacing: 1px; }
        .brand-sub { font-size: 8.5px; color: #7a887e; letter-spacing: 1.6px; text-transform: uppercase; }
        .doc-badge { text-align: right; }
        .doc-pill { display: inline-block; padding: 6px 12px; border: 1px solid #cfdfd2; border-radius: 20px; color: #315d3d; background: #f1f7f2; font-size: 9px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .doc-number { margin-top: 6px; color: #6f7c73; font-size: 9px; }
        .title { font-size: 23px; font-weight: bold; color: #254734; margin: 0 0 2px; }
        .subtitle { color: #7a887e; font-size: 10px; margin-bottom: 18px; }
        .accent { height: 4px; background: #c9a961; margin-bottom: 18px; }
        .two-col { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .two-col td { width: 50%; vertical-align: top; padding-right: 10px; }
        .two-col td:last-child { padding-left: 10px; padding-right: 0; }
        .box { border: 1px solid #dfe8df; border-radius: 8px; padding: 12px 14px; background: #fbfdfb; }
        .box-label { color: #8a958c; font-size: 8px; font-weight: bold; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 7px; }
        .box-name { font-size: 12px; font-weight: bold; color: #233028; }
        .box-line { color: #66766a; margin-top: 3px; }
        .section-title { font-size: 11px; font-weight: bold; color: #315d3d; margin: 20px 0 8px; text-transform: uppercase; letter-spacing: 1px; }
        table.detail { width: 100%; border-collapse: collapse; }
        table.detail th { background: #f1f7f2; color: #315d3d; font-size: 9px; text-align: left; text-transform: uppercase; letter-spacing: .8px; padding: 9px 10px; border-bottom: 1px solid #dfe8df; }
        table.detail td { padding: 9px 10px; border-bottom: 1px solid #edf1ed; vertical-align: top; }
        table.detail tr:last-child td { border-bottom: 0; }
        .label-cell { color: #6f7c73; width: 45%; }
        .value-cell { font-weight: bold; text-align: right; }
        .total-row td { background: #f8faf8; border-top: 2px solid #315d3d; font-size: 12px; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 12px; background: #ecf7ef; color: #24653a; font-size: 9px; font-weight: bold; letter-spacing: .8px; }
        .contract-copy { padding: 14px 16px; border: 1px solid #dfe8df; border-radius: 8px; background: #fff; text-align: justify; line-height: 1.85; }
        .note { margin-top: 16px; color: #7a887e; font-size: 9px; }
        .signature { width: 100%; border-collapse: collapse; margin-top: 34px; }
        .signature td { width: 50%; text-align: center; vertical-align: bottom; }
        .signature .space { height: 62px; }
        .signature .line { border-top: 1px solid #9aa99e; padding-top: 6px; font-size: 9.5px; color: #546258; }
        .footer { position: fixed; bottom: -12mm; left: 0; right: 0; border-top: 1px solid #e3e9e3; padding-top: 7px; color: #98a49a; font-size: 8px; }
        .footer td { width: 50%; }
        .footer .right { text-align: right; }
    </style>
</head>
<body>
    @php
        $snapshot = $document->snapshot ?? [];
        $rupiah = static fn (mixed $value): string => 'Rp '.number_format((float) $value, 0, ',', '.');
        $isContract = $document->type === 'contract';
        $isInvoice = $document->type === 'invoice';
        $title = $isContract ? 'Kontrak Layanan' : ($isInvoice ? 'Invoice Pembayaran' : 'Tanda Terima Pembayaran');
        $badge = $isContract ? 'Kontrak' : ($isInvoice ? 'Invoice' : 'Tanda Terima');
        $remaining = (float) ($snapshot['remaining_amount'] ?? 0);
        $paymentStatus = $remaining <= 0.01 ? 'LUNAS' : 'DP TERVERIFIKASI';
        $customer = (string) ($snapshot['customer_name'] ?? '-');
        $orderNumber = (string) ($snapshot['order_number'] ?? '-');
        $packageName = (string) ($snapshot['package_name'] ?? '-');
        $optionName = (string) ($snapshot['option_name'] ?? '-');
        $eventDate = (string) ($snapshot['event_date'] ?? '-');
        $totalAmount = (float) ($snapshot['total_amount'] ?? 0);
        $amountPaid = (float) ($snapshot['amount_paid'] ?? 0);
        $paymentAmount = (float) ($snapshot['payment_amount'] ?? 0);
        $paymentDate = (string) ($snapshot['payment_date'] ?? '-');
    @endphp

    <table class="brand-row">
        <tr>
            <td style="width: 56px;">
                <img class="brand-logo" src="{{ public_path('img/logo-swp.WebP') }}" alt="Sagita Wedding">
            </td>
            <td>
                <div class="brand-name">SAGITA WEDDING</div>
                <div class="brand-sub">Wedding Organizer</div>
            </td>
            <td class="doc-badge">
                <div class="doc-pill">{{ $badge }}</div>
                <div class="doc-number">{{ $document->document_number }}</div>
            </td>
        </tr>
    </table>

    <div class="title">{{ $title }}</div>
    <div class="subtitle">Diterbitkan pada {{ $document->issued_at?->translatedFormat('d F Y') ?? '-' }} · Nomor pesanan {{ $orderNumber }}</div>
    <div class="accent"></div>

    <table class="two-col">
        <tr>
            <td>
                <div class="box">
                    <div class="box-label">{{ $isContract ? 'Pihak Klien' : 'Ditagihkan Kepada' }}</div>
                    <div class="box-name">{{ $customer }}</div>
                    <div class="box-line">Pesanan {{ $orderNumber }}</div>
                    <div class="box-line">Tanggal acara {{ $eventDate }}</div>
                </div>
            </td>
            <td>
                <div class="box">
                    <div class="box-label">Detail Layanan</div>
                    <div class="box-name">{{ $packageName }}</div>
                    <div class="box-line">{{ $optionName }}</div>
                    <div class="box-line"><span class="status">{{ $paymentStatus }}</span></div>
                </div>
            </td>
        </tr>
    </table>

    @if ($isContract)
        @php
            $values = [
                '{{document_number}}' => (string) $document->document_number,
                '{{issued_at}}' => (string) ($snapshot['issued_at'] ?? ''),
                '{{customer_name}}' => $customer,
                '{{order_number}}' => $orderNumber,
                '{{package_name}}' => $packageName,
                '{{option_name}}' => $optionName,
                '{{event_date}}' => $eventDate,
                '{{total_amount}}' => $rupiah($totalAmount),
                '{{amount_paid}}' => $rupiah($amountPaid),
                '{{remaining_amount}}' => $rupiah($remaining),
            ];
            $contractText = trim((string) strtr((string) ($snapshot['contract_template'] ?? ''), $values));
            if ($contractText === '') {
                $contractText = 'Kontrak layanan ini mengatur hak dan kewajiban kedua pihak sehubungan dengan paket '.$packageName.' ('.$optionName.') untuk acara pada '.$eventDate.'.';
            }
        @endphp

        <div class="section-title">Ringkasan Finansial</div>
        <table class="detail">
            <tr>
                <td class="label-cell">Total nilai layanan</td>
                <td class="value-cell">{{ $rupiah($totalAmount) }}</td>
            </tr>
            <tr>
                <td class="label-cell">Pembayaran terverifikasi</td>
                <td class="value-cell">{{ $rupiah($amountPaid) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label-cell">Sisa pembayaran</td>
                <td class="value-cell">{{ $rupiah($remaining) }}</td>
            </tr>
        </table>

        <div class="section-title">Syarat & Ketentuan</div>
        <div class="contract-copy">{!! nl2br(e($contractText)) !!}</div>
    @else
        <div class="section-title">Rincian Pembayaran</div>
        <table class="detail">
            <tr>
                <th colspan="2">Keterangan</th>
                <th style="text-align: right; width: 30%;">Jumlah</th>
            </tr>
            <tr>
                <td class="label-cell">Total nilai pesanan</td>
                <td>{{ $packageName }} · {{ $optionName }}</td>
                <td class="value-cell">{{ $rupiah($totalAmount) }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ $isInvoice ? 'Pembayaran pertama' : 'Pembayaran diterima' }}</td>
                <td>{{ $paymentDate }}</td>
                <td class="value-cell">{{ $rupiah($paymentAmount) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label-cell">Total terverifikasi</td>
                <td>{{ $paymentStatus }}</td>
                <td class="value-cell">{{ $rupiah($amountPaid) }}</td>
            </tr>
        </table>

        <div class="section-title">Sisa Tagihan</div>
        <table class="detail">
            <tr>
                <td class="label-cell">Sisa yang harus dibayar</td>
                <td class="value-cell">{{ $rupiah($remaining) }}</td>
            </tr>
        </table>

        <div class="note">Dokumen ini dibuat dari pembayaran yang telah diverifikasi oleh admin Sagita Wedding Organizer.</div>
    @endif

    <table class="signature">
        <tr>
            <td>
                <div class="space"></div>
                <div class="line">Sagita Wedding Organizer</div>
            </td>
            <td>
                <div class="space"></div>
                <div class="line">{{ $customer }}</div>
            </td>
        </tr>
    </table>

    <table class="footer">
        <tr>
            <td>Sagita Wedding Organizer · Dokumen digital</td>
            <td class="right">{{ $document->document_number }}</td>
        </tr>
    </table>
</body>
</html>
