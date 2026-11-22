<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        if (window.matchMedia('(max-width: 768px)').matches && new URLSearchParams(window.location.search).get('view') !== 'mobile') {
            const url = new URL(window.location.href);
            url.searchParams.set('view', 'mobile');
            window.location.replace(url.toString());
        }
    </script>
    <title>@yield('title')</title>

    <meta name="title" content="Masuk | Sagita Wedding Planner">
    <meta name="description"
        content="Masuk ke akun Sagita Wedding Planner untuk melanjutkan persiapan pernikahan dan berkoordinasi dengan tim WO.">
    <meta name="keywords"
        content="Sagita Wedding Planner, wedding organizer, persiapan pernikahan, vendor pernikahan">
    <meta name="author" content="Sagita Wedding Planner">
    <meta name="robots" content="index, follow">

    <meta property="og:title"
        content="Masuk | Sagita Wedding Planner">
    <meta property="og:description"
        content="Masuk ke akun Sagita Wedding Planner untuk melanjutkan persiapan hari istimewa Anda.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Sagita Wedding Planner">
    <meta property="og:image" content="{{ asset('img/logo.WebP') }}">

    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/auth.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

</head>

<body>

    <div id="leaf">
        <svg class="bg-leaf one" viewBox="0 0 120 200" fill="none">
            <path d="M60 5C30 40 15 90 25 140c8 38 35 55 35 55s27-17 35-55c10-50-5-100-35-135z" stroke="#8a9a7e" stroke-width="1.4" fill="none" />
            <path d="M60 10v185" stroke="#8a9a7e" stroke-width="1.2" />
        </svg>
        <svg class="bg-leaf two" viewBox="0 0 120 200" fill="none">
            <path d="M60 5C30 40 15 90 25 140c8 38 35 55 35 55s27-17 35-55c10-50-5-100-35-135z" stroke="#b96a4b" stroke-width="1.4" fill="none" />
            <path d="M60 10v185" stroke="#b96a4b" stroke-width="1.2" />
        </svg>
    </div>

    <div id="auth">

        @yield('content')

    </div>

    <script>
        function toggleBtn() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const btn = document.getElementById('lanjutBtn');
            if (!emailInput || !passwordInput || !btn) return;
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            const ok = email.length > 3 && email.includes('@') && email.includes('.') && password.length >= 6;
            btn.classList.toggle('active', ok);
        }

        const eyeBtn = document.getElementById('eyeBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        const passwordInput = document.getElementById('password');
        if (eyeBtn && eyeIcon && passwordInput) eyeBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            eyeIcon.innerHTML = isHidden ?
                '<path d="M3 3l18 18" /><path d="M10.6 10.6a3 3 0 0 0 4.24 4.24"/><path d="M6.5 6.6C3.9 8.3 2 12 2 12s3.5 7 10 7c1.9 0 3.5-.5 4.9-1.3"/><path d="M17.9 17.9C20.4 16.1 22 12 22 12s-3.5-7-10-7c-.6 0-1.2.05-1.8.14"/>' :
                '<path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/>';
        });

        const modal = document.getElementById('helpModal');
        const helpBtn = document.getElementById('helpBtn');
        const closeModal = document.getElementById('closeModal');
        if (modal) {
            helpBtn?.addEventListener('click', () => modal.classList.add('open'));
            closeModal?.addEventListener('click', () => modal.classList.remove('open'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.remove('open');
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') modal.classList.remove('open');
            });
        }

        document.querySelectorAll('.faq-q').forEach(q => {
            q.addEventListener('click', () => {
                q.parentElement.classList.toggle('open');
            });
        });
    </script>

</body>

</html>