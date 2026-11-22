@extends('emails.layouts.wedding')

@section('title', 'Permintaan konsultasi diterima')
@section('preheader', 'Formulir konsultasi pernikahan Anda sudah kami terima.')

@section('content')
    <div style="margin-bottom:10px;color:#8a7651;font-size:11px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">Konfirmasi konsultasi</div>
    <h1 style="margin:0 0 18px;color:#263a32;font-family:Georgia,'Times New Roman',serif;font-size:28px;font-weight:normal;line-height:1.25;">Permintaan Anda sudah kami terima</h1>
    <p style="margin:0 0 14px;color:#45524a;font-size:15px;line-height:1.75;">Halo {{ $customerName }},</p>
    <p style="margin:0 0 14px;color:#45524a;font-size:15px;line-height:1.75;">
        Terima kasih telah menghubungi Sagita Wedding Planner. Formulir konsultasi pernikahan Anda sudah kami terima.
    </p>
    <p style="margin:0;color:#45524a;font-size:15px;line-height:1.75;">
        Tim kami akan meninjau kebutuhan Anda dan menghubungi melalui kontak yang telah dicantumkan. Semoga persiapan hari istimewa Anda terasa lebih tenang bersama kami.
    </p>
    <p style="margin:24px 0 0;color:#45524a;font-size:15px;line-height:1.75;">
        Salam hangat,<br><strong style="color:#405b4a;">Tim Sagita Wedding Planner</strong>
    </p>
@endsection
