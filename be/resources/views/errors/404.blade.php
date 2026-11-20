@extends('layouts.auth')
@section('title', 'Halaman Tidak Ditemukan')
@section('content')

<main class="error-page">

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

    <p class="eyebrow"><span class="dash"></span>Halaman Tidak Ditemukan</p>
    <h1>4<em>0</em>4</h1>
    <h2>Sepertinya halaman ini tidak tersedia.</h2>
    <p class="lead">Alamat yang Anda tuju mungkin salah atau sudah berubah. Silakan kembali ke beranda Sagita Wedding atau masuk ke akun Anda.</p>

    <div class="action-row">
      <a href="{{ route('home') }}" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 11l9-8 9 8" />
          <path d="M5 10v10h14V10" />
        </svg>
        Ke Beranda
      </a>
      <a href="{{ route('login') }}" class="btn-secondary">
        Masuk
      </a>
    </div>

  </div>

</main>

@endsection