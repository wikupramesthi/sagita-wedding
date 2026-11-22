<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#405847">
    <title>Formulir Konsultasi | Sagita Wedding Planner</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
            color: #29372d;
            background: #f5f4ef;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-width: 320px;
            background: radial-gradient(circle at 8% 10%, #e8eee7 0, transparent 32rem), #f5f4ef;
        }

        a { color: inherit; }

        .shell {
            width: min(100% - 32px, 1000px);
            margin: 0 auto;
            padding: 32px 0 48px;
        }

        .back-link {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 20px;
            color: #526b57;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .card {
            overflow: hidden;
            border: 1px solid #e0e5dc;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 18px 48px rgba(39, 57, 43, .09);
        }

        .intro {
            padding: 32px 36px;
            color: #fff;
            background: #405847;
        }

        .brand {
            display: block;
            margin-bottom: 28px;
            color: #e8d8ba;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
        }

        .eyebrow {
            color: #e4d7bd;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        h1 {
            margin: 9px 0 10px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(30px, 5vw, 46px);
            font-weight: 400;
            line-height: 1.15;
        }

        .intro p {
            max-width: 660px;
            margin: 0;
            color: #edf1ec;
            font-size: 15px;
            line-height: 1.65;
        }

        .form-body { padding: 30px 36px 36px; }

        .section-title {
            margin: 0 0 18px;
            color: #405847;
            font-size: 16px;
            font-weight: 700;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px 20px;
        }

        .field { min-width: 0; }
        .wide { grid-column: 1 / -1; }

        label, legend {
            display: block;
            margin-bottom: 7px;
            color: #38443b;
            font-size: 13px;
            font-weight: 700;
        }

        .required { color: #a64e43; }

        input, select, textarea {
            width: 100%;
            min-height: 46px;
            padding: 11px 12px;
            border: 1px solid #d5ddd3;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: #29372d;
            font: inherit;
            font-size: 14px;
        }

        textarea { min-height: 118px; resize: vertical; }
        input:focus, select:focus, textarea:focus { border-color: #66836a; box-shadow: 0 0 0 3px #66836a20; }
        input::placeholder, textarea::placeholder { color: #929b93; }

        .hint {
            display: block;
            margin-top: 6px;
            color: #737e75;
            font-size: 12px;
        }

        .divider { height: 1px; margin: 28px 0; background: #e8ece6; }

        fieldset {
            min-width: 0;
            margin: 24px 0;
            padding: 0;
            border: 0;
        }

        .service-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .service-option {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 0;
            padding: 12px;
            border: 1px solid #e0e5dc;
            border-radius: 9px;
            font-weight: 400;
            line-height: 1.4;
        }

        .service-option input { width: 16px; min-height: 16px; margin: 1px 0 0; accent-color: #405847; }

        .captcha {
            display: grid;
            grid-template-columns: auto 160px minmax(110px, 1fr) 44px;
            gap: 12px;
            align-items: center;
            margin-top: 24px;
            padding: 14px;
            border: 1px solid #e0e5dc;
            border-radius: 12px;
            background: #fafbf9;
        }

        .captcha-title { margin: 0 0 4px; font-size: 13px; font-weight: 700; }
        .captcha img { display: block; width: 160px; height: 48px; border: 1px solid #e0e5dc; border-radius: 8px; }
        .captcha input { min-width: 0; text-align: center; letter-spacing: .15em; }
        .reload {
            width: 44px;
            height: 44px;
            border: 1px solid #d5ddd3;
            border-radius: 9px;
            background: #fff;
            color: #405847;
            cursor: pointer;
            font-size: 20px;
        }

        .error-summary, .success {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 9px;
            font-size: 14px;
            line-height: 1.5;
        }

        .error-summary { border: 1px solid #edc7c2; background: #fff5f3; color: #8d372c; }
        .success { border: 1px solid #c9ddca; background: #f1f8f1; color: #35593c; }
        .error-text { margin-top: 5px; color: #a13e32; font-size: 12px; }

        .submit {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 22px;
            padding: 0 22px;
            border: 0;
            border-radius: 10px;
            background: #405847;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
        }

        .submit:hover { background: #33493a; }
        .privacy { margin: 14px 0 0; color: #68746b; font-size: 12px; line-height: 1.5; }
        .bot-field { position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden; }

        @media (max-width: 640px) {
            .shell { width: min(100% - 20px, 1000px); padding: 16px 0 28px; }
            .back-link { margin: 0 0 14px 4px; }
            .card { border-radius: 16px; }
            .intro { padding: 25px 22px; }
            .brand { margin-bottom: 22px; }
            .intro p { font-size: 14px; }
            .form-body { padding: 24px 20px 26px; }
            .fields, .service-list { grid-template-columns: 1fr; gap: 14px; }
            .wide { grid-column: auto; }
            .captcha { grid-template-columns: 1fr 44px; gap: 10px; }
            .captcha img { grid-column: 1; grid-row: 2; }
            .captcha input { grid-column: 1; grid-row: 3; }
            .reload { grid-column: 2; grid-row: 2; }
            .submit { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; }
        }
    </style>
</head>

<body>
    <main class="shell">
        <a class="back-link" href="{{ route('dashboard.index') }}">← Kembali</a>

        <article class="card">
            <header class="intro">
                <span class="brand">SAGITA WEDDING PLANNER</span>
                <span class="eyebrow">Awal yang penuh makna</span>
                <h1>Mari rencanakan hari yang terasa seperti Anda.</h1>
                <p>Ceritakan sedikit tentang pernikahan yang Anda impikan. Tim kami akan membantu menjajaki layanan, paket, dan langkah berikutnya yang paling sesuai dengan rencana Anda.</p>
            </header>

            <div class="form-body">
                @if (session('success'))
                    <div class="success" role="status">{{ session('success') }}</div>
                    <a class="back-link" href="{{ route('consultation.form') }}">Kirim permintaan konsultasi lain</a>
                @else
                    @if ($errors->any())
                        <div class="error-summary" role="alert">
                            Periksa kembali formulir Anda:
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('consultation.form.submit') }}">
                        @csrf
                        <div class="section-title">Tentang Anda</div>
                        <div class="fields">
                            <div class="field">
                                <label for="full_name">Nama lengkap <span class="required">*</span></label>
                                <input id="full_name" name="full_name" type="text" autocomplete="name" maxlength="150" value="{{ old('full_name') }}" placeholder="Nama Anda" required>
                                @error('full_name')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="whatsapp">Nomor WhatsApp <span class="required">*</span></label>
                                <input id="whatsapp" name="whatsapp" type="tel" autocomplete="tel" inputmode="tel" maxlength="20" value="{{ old('whatsapp') }}" placeholder="Contoh: 0812 3456 7890" required>
                                @error('whatsapp')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field wide">
                                <label for="email">Alamat email <span class="required">*</span></label>
                                <input id="email" name="email" type="email" autocomplete="email" maxlength="255" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                @error('email')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="divider"></div>
                        <div class="section-title">Rencana pernikahan</div>
                        <div class="fields">
                            <div class="field">
                                <label for="wedding_date">Perkiraan tanggal pernikahan</label>
                                <input id="wedding_date" name="wedding_date" type="date" value="{{ old('wedding_date') }}">
                                <span class="hint">Tidak masalah jika tanggalnya belum ditentukan.</span>
                                @error('wedding_date')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="wedding_city">Kota pernikahan</label>
                                <input id="wedding_city" name="wedding_city" type="text" maxlength="100" value="{{ old('wedding_city') }}" placeholder="Contoh: Jakarta">
                                @error('wedding_city')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="venue">Lokasi atau venue (jika sudah ada)</label>
                                <input id="venue" name="venue" type="text" maxlength="255" value="{{ old('venue') }}" placeholder="Nama venue atau area">
                                @error('venue')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="guest_estimate">Perkiraan jumlah tamu</label>
                                <select id="guest_estimate" name="guest_estimate">
                                    <option value="">Pilih perkiraan</option>
                                    @foreach ($guestEstimates as $option)
                                        <option value="{{ $option }}" @selected(old('guest_estimate') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('guest_estimate')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="field wide">
                                <label for="budget">Kisaran anggaran</label>
                                <select id="budget" name="budget">
                                    <option value="">Pilih perkiraan</option>
                                    @foreach ($budgetOptions as $option)
                                        <option value="{{ $option }}" @selected(old('budget') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('budget')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <fieldset>
                            <legend>Layanan apa yang ingin Anda diskusikan?</legend>
                            <div class="service-list">
                                @php($selectedServices = old('services', []))
                                @foreach ($serviceOptions as $option)
                                    <label class="service-option">
                                        <input type="checkbox" name="services[]" value="{{ $option }}" @checked(is_array($selectedServices) && in_array($option, $selectedServices, true))>
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('services')<div class="error-text">{{ $message }}</div>@enderror
                        </fieldset>

                        <div class="field">
                            <label for="message">Hal lain yang ingin kami ketahui? <span class="required">*</span></label>
                            <textarea id="message" name="message" rows="4" maxlength="2000" placeholder="Ceritakan ide, prioritas, atau pertanyaan Anda..." required>{{ old('message') }}</textarea>
                            @error('message')<div class="error-text">{{ $message }}</div>@enderror
                        </div>

                        <div class="captcha">
                            <div>
                                <p class="captcha-title">Verifikasi singkat</p>
                                <span class="hint">Masukkan 4 angka pada gambar.</span>
                            </div>
                            <img src="{{ $captchaImage }}" alt="Kode verifikasi empat angka" width="160" height="48">
                            <input name="captcha_answer" type="text" inputmode="numeric" pattern="[0-9]{4}" maxlength="4" autocomplete="off" aria-label="Kode verifikasi" placeholder="4 angka" required>
                            <input name="captcha_id" type="hidden" value="{{ $captchaId }}">
                            <button class="reload" type="button" aria-label="Muat ulang kode verifikasi" title="Muat ulang verifikasi" onclick="window.location.reload()">↻</button>
                        </div>
                        @error('captcha_answer')<div class="error-text">{{ $message }}</div>@enderror

                        <div class="bot-field" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>

                        <button class="submit" type="submit">Kirim permintaan konsultasi <span aria-hidden="true">→</span></button>
                        <p class="privacy">Informasi Anda hanya digunakan untuk menindaklanjuti konsultasi ini.</p>
                    </form>
                @endif
            </div>
        </article>
    </main>
</body>

</html>
