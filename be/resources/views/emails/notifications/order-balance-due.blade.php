@extends('emails.layouts.wedding')

@section('title', 'Pengingat pembayaran pesanan')
@section('preheader', 'Informasi sisa pembayaran dan jadwal pesanan Anda.')

@section('content')
    <div style="margin-bottom:10px;color:#8a7651;font-size:11px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">Pengingat pembayaran</div>
    <h1 style="margin:0 0 18px;color:#263a32;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:normal;line-height:1.25;">Mari lanjutkan persiapan hari istimewa Anda</h1>
    <p style="margin:0 0 18px;color:#45524a;font-size:15px;line-height:1.75;">Halo {{ $customerName }},</p>
    <p style="margin:0 0 20px;color:#45524a;font-size:15px;line-height:1.75;">
        Kami ingin mengingatkan bahwa pesanan <strong>{{ $orderNumber }}</strong> masih memiliki sisa pembayaran.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 22px;background:#f7f7f2;border:1px solid #e9eadf;border-radius:12px;">
        <tr>
            <td style="padding:14px 16px;color:#788078;font-size:13px;">Sisa pembayaran</td>
            <td align="right" style="padding:14px 16px;color:#405b4a;font-size:17px;font-weight:bold;">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</td>
        </tr>
        @if ($installmentAmount !== null)
            <tr>
                <td style="padding:0 16px 14px;color:#788078;font-size:13px;">{{ $installmentLabel ?: 'Termin pembayaran' }}</td>
                <td align="right" style="padding:0 16px 14px;color:#263a32;font-size:14px;font-weight:bold;">Rp {{ number_format($installmentAmount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td style="padding:0 16px 14px;color:#788078;font-size:13px;">{{ $installmentAmount !== null ? 'Jadwal pembayaran' : 'Batas pelunasan' }}</td>
            <td align="right" style="padding:0 16px 14px;color:#263a32;font-size:13px;font-weight:bold;">{{ $dueLabel }}</td>
        </tr>
    </table>
    <p style="margin:0 0 22px;color:#45524a;font-size:14px;line-height:1.7;">
        Silakan buka detail pesanan untuk melihat informasi pembayaran dan melanjutkan prosesnya.
    </p>
    @if ($installmentAmount !== null && $reminderDays !== null)
        <p style="margin:0 0 22px;color:#788078;font-size:13px;line-height:1.6;">
            Pengingat ini dikirim H-{{ $reminderDays }} sebelum jadwal pembayaran.
        </p>
    @endif
    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td style="border-radius:8px;background:#405b4a;">
                <a href="{{ $orderUrl }}" style="display:inline-block;padding:13px 20px;color:#ffffff;font-size:14px;font-weight:bold;text-decoration:none;">Lihat detail pesanan</a>
            </td>
        </tr>
    </table>
    <p style="margin:24px 0 0;color:#45524a;font-size:14px;line-height:1.7;">
        Jika Anda sudah menyelesaikan pembayaran, abaikan pengingat ini.<br>
        Salam hangat,<br><strong style="color:#405b4a;">Tim Sagita Wedding Planner</strong>
    </p>
@endsection
