@extends('emails.layouts.wedding')

@section('title', 'Bukti pembayaran diterima')
@section('preheader', 'Bukti pembayaran Anda sudah kami terima dan menunggu pemeriksaan.')

@section('content')
    <div style="margin-bottom:10px;color:#8a7651;font-size:11px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">Pembaruan pembayaran</div>
    <h1 style="margin:0 0 18px;color:#263a32;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:normal;line-height:1.25;">Bukti pembayaran diterima</h1>
    <p style="margin:0 0 14px;color:#45524a;font-size:15px;line-height:1.75;">Halo {{ $customerName }},</p>
    <p style="margin:0 0 20px;color:#45524a;font-size:15px;line-height:1.75;">
        Terima kasih. Bukti pembayaran untuk pesanan Anda sudah kami terima dan sedang menunggu pemeriksaan tim kami.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 22px;background:#f7f7f2;border:1px solid #e9eadf;border-radius:12px;">
        <tr>
            <td style="padding:13px 16px;color:#788078;font-size:13px;">Nomor pesanan</td>
            <td align="right" style="padding:13px 16px;color:#263a32;font-size:13px;font-weight:bold;">{{ $orderNumber }}</td>
        </tr>
        <tr>
            <td style="padding:0 16px 14px;color:#788078;font-size:13px;">Nominal yang dilaporkan</td>
            <td align="right" style="padding:0 16px 14px;color:#405b4a;font-size:15px;font-weight:bold;">Rp {{ number_format($amount, 0, ',', '.') }}</td>
        </tr>
    </table>
    <p style="margin:0 0 22px;color:#45524a;font-size:14px;line-height:1.7;">
        Status pembayaran akan diperbarui setelah proses pemeriksaan selesai.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td style="border-radius:8px;background:#405b4a;">
                <a href="{{ $orderUrl }}" style="display:inline-block;padding:13px 20px;color:#ffffff;font-size:14px;font-weight:bold;text-decoration:none;">Lihat detail pesanan</a>
            </td>
        </tr>
    </table>
    <p style="margin:24px 0 0;color:#45524a;font-size:14px;line-height:1.7;">
        Salam hangat,<br><strong style="color:#405b4a;">Tim Sagita Wedding Planner</strong>
    </p>
@endsection
