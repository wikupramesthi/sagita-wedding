<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pratinjau Penawaran {{ $package->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f1f4ef; color: #31392f; font-family: 'DejaVu Sans', sans-serif; }
        .toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 20px; background: #fff; border-bottom: 2px solid #4b6b52; }
        .brand { font-size: 16px; font-weight: bold; color: #4b6b52; }
        .brand-sub { font-size: 10px; color: #858d83; font-weight: normal; }
        .download { display: inline-block; padding: 8px 16px; border-radius: 6px; background: #4b6b52; color: #fff; font-size: 12px; font-weight: bold; text-decoration: none; }
        .download:hover { background: #3b5541; }
        .terms-note { font-size: 11px; color: #5b6559; max-width: 460px; }
        .frame-wrap { padding: 20px; }
        iframe { display: block; width: 100%; height: calc(100vh - 100px); min-height: 520px; border: 1px solid #e3e7e1; border-radius: 8px; background: #fff; }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="brand">{{ $siteName }}<span class="brand-sub"><br>Pratinjau penawaran paket {{ $package->name }}</span></div>

        @if ($package->dp_percentage !== null || $package->payment_terms_note)
        <div class="terms-note">
            @if ($package->dp_percentage !== null)
            <div>DP awal: <strong>{{ (int) $package->dp_percentage }}%</strong></div>
            @endif

            @if ($package->payment_terms_note)
            <div>{{ $package->payment_terms_note }}</div>
            @endif
        </div>
        @endif

        <a class="download" href="{{ route('packages.quotation.download', request()->query() + ['package' => $package->uuid]) }}">Unduh PDF</a>
    </div>
    <div class="frame-wrap">
        <iframe src="{{ route('packages.quotation', request()->query() + ['package' => $package->uuid]) }}" title="Pratinjau penawaran {{ $package->name }}"></iframe>
    </div>
</body>
</html>
