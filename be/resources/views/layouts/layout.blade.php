<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>

    <meta name="title" content="Sagita Wedding Organizer | Wedding Planner & Organizer">
    <meta name="description"
        content="Sagita Wedding Organizer menyelenggarakan pernikahan Anda dengan layanan perencanaan, dekorasi, dan koordinasi acara.">
    <meta name="keywords"
        content="Sagita Wedding Organizer, wedding organizer, wedding planner, dekorasi pernikahan, organizer pernikahan">
    <meta name="author" content="Sagita Wedding Organizer">
    <meta name="robots" content="index, follow">

    <meta property="og:title"
        content="Sagita Wedding Organizer | Wedding Planner & Organizer">
    <meta property="og:description"
        content="Ruang perencanaan pernikahan untuk mewujudkan hari istimewa Anda bersama Sagita Wedding Organizer.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Sagita Wedding Organizer">
    <meta property="og:image" content="{{ asset('img/seamless-pattern3.png') }}">

    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist&display=swap" rel="stylesheet">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WTJ09KDXS5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-WTJ09KDXS5');
    </script>


    @vite(['resources/css/app.css'])

</head>

<body class="min-h-screen bg-white selection:bg-primary/10 selection:text-primary dark:bg-gray-900">
    <x-web.header />
    @yield('content')
    <x-footer />

    @stack('before-script')
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://website-widgets.pages.dev/dist/sienna.min.js" defer></script>
    <script src="{{ asset('/frontend/js/particles.js') }}"></script>
    <script src="{{ asset('/frontend/js/app.js') }}"></script>
    @stack('after-script')

</body>

</html>