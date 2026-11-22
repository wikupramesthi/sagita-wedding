<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>@yield('title', 'Sagita Wedding Planner')</title>
</head>
<body style="margin:0;padding:0;background:#f4f3ee;color:#263a32;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">@yield('preheader')</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f3ee;">
        <tr>
            <td align="center" style="padding:32px 14px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border:1px solid #e8e7df;border-radius:18px;overflow:hidden;">
                    <tr>
                        <td style="padding:26px 30px;background:#405b4a;color:#ffffff;">
                            <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#e8ddc8;">Wedding Planner</div>
                            <div style="margin-top:5px;font-family:Georgia,'Times New Roman',serif;font-size:25px;line-height:1.25;">Sagita Wedding</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#faf9f6;border-top:1px solid #eeede7;color:#788078;font-size:12px;line-height:1.6;">
                            Email ini dikirim otomatis oleh Sagita Wedding Planner. Mohon tidak membalas email ini.
                        </td>
                    </tr>
                </table>
                <div style="padding:16px 8px 0;color:#8a9089;font-size:11px;line-height:1.5;">
                    Direncanakan dengan hati untuk hari istimewa Anda.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
