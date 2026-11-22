@extends('layouts.auth')
@section('title', 'Keluar')
@section('content')

<main class="error-page logout-page">
    <svg class="bg-leaf one" viewBox="0 0 120 200" fill="none">
        <path d="M60 5C30 40 15 90 25 140c8 38 35 55 35 55s27-17 35-55c10-50-5-100-35-135z" stroke="#8a9a7e" stroke-width="1.4" fill="none" />
        <path d="M60 10v185" stroke="#8a9a7e" stroke-width="1.2" />
    </svg>
    <svg class="bg-leaf two" viewBox="0 0 120 200" fill="none">
        <path d="M60 5C30 40 15 90 25 140c8 38 35 55 35 55s27-17 35-55c10-50-5-100-35-135z" stroke="#b96a4b" stroke-width="1.4" fill="none" />
        <path d="M60 10v185" stroke="#b96a4b" stroke-width="1.2" />
    </svg>

    <div class="logo-row">
        <img class="error-brand-logo" src="{{ asset('img/logo.WebP') }}" alt="Sagita Wedding Planner">
    </div>

    <div class="stage">
        <p class="eyebrow"><span class="dash"></span>Keluar dari Akun</p>
        <h1>Sampai <em>jumpa</em></h1>
        <h2>Anda yakin ingin keluar?</h2>
        <p class="lead">Sesi Anda akan diakhiri. Anda selalu bisa masuk kembali kapan saja untuk melanjutkan persiapan pernikahan.</p>

        <div class="action-row">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary">Ya, Keluar</button>
            </form>
            <a href="{{ route('dashboard.index') }}" class="btn-secondary">Batal</a>
        </div>
    </div>
</main>

<style>
    .logout-page .stage h1 { font-size: clamp(52px, 8vw, 76px); }
    .logout-page .stage h2 { margin-top: 4px; }
    .logout-page .action-row { align-items: center; }
    .logout-page .action-row form { margin: 0; }
</style>

@endsection
