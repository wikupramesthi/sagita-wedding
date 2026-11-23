@extends('layouts.app')

@section('title', 'Tambah Paket')

@section('content')

<div class="container-fluid">

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h4 class="mb-1 fw-bold">
        Tambah Paket
      </h4>
      <p class="text-muted mb-0">
        Buat paket layanan wedding organizer baru.
      </p>
    </div>

    <a href="{{ route('packages.index') }}" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-1"></i>
      Kembali
    </a>
  </div>

  @if ($errors->any())
  <div class="alert alert-danger border-0 mb-4">
    <div class="d-flex align-items-start gap-2">
      <i class="bi bi-exclamation-circle-fill mt-1"></i>

      <div>
        <strong>Silakan periksa kesalahan berikut:</strong>

        <ul class="mb-0 mt-2 ps-3">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  @endif

  <div class="package-stepper card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-3">
      <div class="row g-2">
        <div class="col-6 col-md-4 col-xl">
          <a class="package-step" href="#package-section-information">
            <span class="package-step-number">1</span>
            <span>Informasi</span>
          </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
          <a class="package-step" href="#package-section-event">
            <span class="package-step-number">2</span>
            <span>Detail Acara</span>
          </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
          <a class="package-step" href="#package-section-options">
            <span class="package-step-number">3</span>
            <span>Opsi &amp; Add-on</span>
          </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
          <a class="package-step" href="#package-section-availability">
            <span class="package-step-number">4</span>
            <span>Promo &amp; Ketersediaan</span>
          </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
          <a class="package-step" href="#package-section-publication">
            <span class="package-step-number">5</span>
            <span>Publikasi</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <form action="{{ route('packages.store') }}" method="POST">
    @csrf

    <div class="row g-4">

      <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-information">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
              <div class="package-section-icon">
                <i class="bi bi-box-seam"></i>
              </div>

              <div>
                <h5 class="mb-1 fw-semibold">
                  Informasi Paket
                </h5>
                <p class="text-muted small mb-0">
                  Nama dan deskripsi yang tampil kepada klien.
                </p>
              </div>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div class="mb-4">
              <label for="name" class="form-label fw-semibold">
                Nama Paket
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                placeholder="Contoh: Paket Silver"
                required>

              @error('name')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div>
              <label for="description" class="form-label fw-semibold">
                Deskripsi
              </label>

              <textarea
                id="description"
                name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror"
                placeholder="Jelaskan layanan dalam paket ini, misalnya dekorasi, dokumentasi, dan rias pengantin...">{{ old('description') }}</textarea>

              @error('description')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-event">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
              <div class="package-section-icon">
                <i class="bi bi-calendar2-event"></i>
              </div>

              <div>
                <h5 class="mb-1 fw-semibold">
                  Detail Acara
                </h5>
                <p class="text-muted small mb-0">
                  Kapasitas, durasi, lokasi, dan foto untuk kebutuhan acara.
                </p>
              </div>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="guest_capacity" class="form-label fw-semibold">
                  Kapasitas Tamu
                </label>

                <input
                  type="number"
                  id="guest_capacity"
                  name="guest_capacity"
                  class="form-control @error('guest_capacity') is-invalid @enderror"
                  value="{{ old('guest_capacity') }}"
                  min="1"
                  placeholder="Contoh: 100 orang">

                @error('guest_capacity')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="event_duration_hours" class="form-label fw-semibold">
                  Durasi Acara (jam)
                </label>

                <input
                  type="number"
                  id="event_duration_hours"
                  name="event_duration_hours"
                  class="form-control @error('event_duration_hours') is-invalid @enderror"
                  value="{{ old('event_duration_hours') }}"
                  min="1"
                  placeholder="Contoh: 6">

                @error('event_duration_hours')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-12">
                <label for="venue_note" class="form-label fw-semibold">
                  Lokasi Acara
                </label>

                <textarea
                  id="venue_note"
                  name="venue_note"
                  rows="3"
                  class="form-control @error('venue_note') is-invalid @enderror"
                  placeholder="Contoh: Jakarta Selatan dan sekitarnya">{{ old('venue_note') }}</textarea>

                @error('venue_note')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-md-4">
                <label for="dp_percentage" class="form-label fw-semibold">
                  DP awal (%)
                </label>

                <input
                  type="number"
                  id="dp_percentage"
                  name="dp_percentage"
                  class="form-control @error('dp_percentage') is-invalid @enderror"
                  value="{{ old('dp_percentage', 30) }}"
                  min="0"
                  max="100"
                  placeholder="30">

                @error('dp_percentage')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-md-8">
                <label for="payment_terms_note" class="form-label fw-semibold">
                  Catatan termin pembayaran
                </label>

                <textarea
                  id="payment_terms_note"
                  name="payment_terms_note"
                  rows="3"
                  class="form-control @error('payment_terms_note') is-invalid @enderror"
                  placeholder="Contoh: Pelunasan maksimal H-7 acara">{{ old('payment_terms_note') }}</textarea>

                @error('payment_terms_note')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-12">
                <label for="cover_url" class="form-label fw-semibold">
                  Foto Paket (URL)
                </label>

                <input
                  type="url"
                  id="cover_url"
                  name="cover_url"
                  class="form-control @error('cover_url') is-invalid @enderror"
                  value="{{ old('cover_url') }}"
                  placeholder="https://...">

                @error('cover_url')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-options">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
              <div class="d-flex align-items-center gap-2">
                <div class="package-section-icon">
                  <i class="bi bi-tags"></i>
                </div>

                <div>
                  <h5 class="mb-1 fw-semibold">
                    Pilihan Paket
                  </h5>
                  <p class="text-muted small mb-0">
                    Atur harga, masa berlaku, dan jumlah layanan untuk setiap pilihan.
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addPackageOption()">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Opsi
              </button>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div id="package-options-container">
              <div class="package-option-item border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <span class="fw-semibold package-option-title">
                      Opsi 1
                    </span>
                    <span class="text-muted small ms-1">
                      Pilihan harga
                    </span>
                  </div>

                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger package-remove-option"
                    onclick="removePackageOption(this)">
                    <i class="bi bi-trash3"></i>
                  </button>
                </div>

                <div class="mb-3">
                  <label class="form-label">
                    Nama Opsi
                    <span class="text-danger">*</span>
                  </label>

                  <input
                    type="text"
                    name="options[0][name]"
                    class="form-control @error('options.0.name') is-invalid @enderror"
                    value="{{ old('options.0.name') }}"
                    placeholder="Contoh: Silver"
                    required>

                  @error('options.0.name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>

                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">
                      Jumlah layanan termasuk
                    </label>

                    <input
                      type="number"
                      name="options[0][quota]"
                      class="form-control"
                      value="{{ old('options.0.quota') }}"
                       min="1"
                      placeholder="Kosongkan = tanpa batas">


                    <small class="text-muted">
                      Jumlah layanan yang disertakan pada opsi ini. Kosongkan jika tidak ada batasan jumlah layanan.
                    </small>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">
                      Harga Reguler
                      <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                      <span class="input-group-text">
                        Rp
                      </span>

                      <input
                        type="number"
                        name="options[0][price]"
                        class="form-control"
                        value="{{ old('options.0.price') }}"
                        min="0"
                        placeholder="0"
                        required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">
                      Harga Diskon
                    </label>

                    <div class="input-group">
                      <span class="input-group-text">
                        Rp
                      </span>

                      <input
                        type="number"
                        name="options[0][discount_price]"
                        class="form-control"
                        value="{{ old('options.0.discount_price') }}"
                        min="0"
                        placeholder="Opsional">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">
                      Masa berlaku penawaran
                      <span class="text-danger">*</span>
                    </label>

                    <input
                      type="number"
                      name="options[0][duration]"
                      class="form-control"
                      value="{{ old('options.0.duration', 1) }}"
                       min="1"
                       placeholder="Contoh: 1"
                       required>

                  </div>

                  <div class="col-md-6">
                    <label class="form-label">
                      Satuan masa berlaku
                      <span class="text-danger">*</span>
                    </label>

                    <select
                      name="options[0][duration_unit]"
                      class="form-select"
                      required>

                      <option value="">
                        -- Pilih --
                      </option>

                      <option
                        value="day"
                        {{ old('options.0.duration_unit', 'month') === 'day' ? 'selected' : '' }}>
                        Hari
                      </option>

                      <option
                        value="week"
                        {{ old('options.0.duration_unit', 'month') === 'week' ? 'selected' : '' }}>
                        Minggu
                      </option>

                      <option
                        value="month"
                        {{ old('options.0.duration_unit', 'month') === 'month' ? 'selected' : '' }}>
                        Bulan
                      </option>

                      <option
                        value="year"
                        {{ old('options.0.duration_unit', 'month') === 'year' ? 'selected' : '' }}>
                        Tahun
                      </option>

                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="package-option-info mt-3">
              <i class="bi bi-info-circle me-1"></i>
              Setiap opsi dapat memiliki harga, jumlah layanan, dan masa berlaku tersendiri.
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
              <div class="d-flex align-items-center gap-2">
                <div class="package-section-icon">
                  <i class="bi bi-plus-square"></i>
                </div>

                <div>
                  <h5 class="mb-1 fw-semibold">
                    Layanan Tambahan (Opsional)
                  </h5>
                  <p class="text-muted small mb-0">
                    Tawarkan layanan tambahan di luar paket utama.
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addPackageAddon()">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Layanan
              </button>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div id="package-addons-container">
              <div class="package-addon-item border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="fw-semibold package-addon-title">
                    Layanan 1
                  </span>

                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger package-remove-addon"
                    onclick="removePackageAddon(this)">
                    <i class="bi bi-trash3"></i>
                  </button>
                </div>

                <div class="mb-3">
                  <label class="form-label">
                    Nama Layanan
                    <span class="text-danger">*</span>
                  </label>

                  <input
                    type="text"
                    name="addons[0][name]"
                    class="form-control @error('addons.0.name') is-invalid @enderror"
                    value="{{ old('addons.0.name') }}"
                    placeholder="Contoh: Dekorasi tambahan">

                  @error('addons.0.name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">
                    Deskripsi
                  </label>

                  <textarea
                    name="addons[0][description]"
                    rows="2"
                    class="form-control"
                    placeholder="Contoh: Pelaminan bunga segar untuk area pagar">{{ old('addons.0.description') }}</textarea>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">
                      Harga Reguler
                    </label>

                    <div class="input-group">
                      <span class="input-group-text">
                        Rp
                      </span>

                      <input
                        type="number"
                        name="addons[0][price]"
                        class="form-control"
                        value="{{ old('addons.0.price') }}"
                        min="0"
                        placeholder="0">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">
                      Harga Diskon
                    </label>

                    <div class="input-group">
                      <span class="input-group-text">
                        Rp
                      </span>

                      <input
                        type="number"
                        name="addons[0][discount_price]"
                        class="form-control"
                        value="{{ old('addons.0.discount_price') }}"
                        min="0"
                        placeholder="Opsional">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">
                      Min Qty
                    </label>

                    <input
                      type="number"
                      name="addons[0][min_quantity]"
                      class="form-control"
                      value="{{ old('addons.0.min_quantity', 1) }}"
                      min="1"
                      max="99"
                      placeholder="1">
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">
                      Max Qty
                    </label>

                    <input
                      type="number"
                      name="addons[0][max_quantity]"
                      class="form-control"
                      value="{{ old('addons.0.max_quantity') }}"
                      min="1"
                      placeholder="Opsional">
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">
                      Butuh Layanan Lainnya
                    </label>

                    <select name="addons[0][requires_addon_index]" class="form-select">
                      <option value="">Tidak ada</option>
                    </select>

                  </div>
                </div>
              </div>
            </div>

            @error('addons.*')
            <div class="text-danger small mt-2">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
              <div class="d-flex align-items-center gap-2">
                <div class="package-section-icon">
                  <i class="bi bi-list-check"></i>
                </div>

                <div>
                  <h5 class="mb-1 fw-semibold">
                    Isi Paket
                  </h5>
                  <p class="text-muted small mb-0">
                    Kelompokkan layanan yang sudah termasuk, misalnya Dekorasi atau Katering.
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addPackageFeature()">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Isi Paket
              </button>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div id="package-features-container">
              <div class="row g-2 align-items-end mb-3 package-feature-item">
                <div class="col-md-4">
                  <label class="form-label small fw-semibold" for="feature-category-0">Kategori</label>
                  <input
                    type="text"
                    id="feature-category-0"
                    name="features[0][category]"
                    class="form-control package-feature-category"
                    maxlength="100"
                    placeholder="Contoh: Dekorasi">
                </div>

                <div class="col-md-7">
                  <label class="form-label small fw-semibold" for="feature-text-0">Rincian yang termasuk</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent">
                      <i class="bi bi-check2"></i>
                    </span>
                    <input
                      type="text"
                      id="feature-text-0"
                      name="features[0][feature]"
                      class="form-control package-feature-text"
                      maxlength="255"
                      placeholder="Contoh: Dekorasi pelaminan">
                  </div>
                </div>

                <div class="col-md-1 d-grid">
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    onclick="removePackageFeature(this)"
                    aria-label="Hapus isi paket">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              </div>
            </div>

            <small class="text-muted d-block mt-2">
              Ulangi nama kategori untuk beberapa rincian; layanan dengan kategori sama akan ditampilkan berkelompok. Biarkan kategori kosong untuk rincian tanpa kelompok.
            </small>

            @error('features.*')
            <div class="text-danger small mt-2">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-availability">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
              <div class="d-flex align-items-center gap-2">
                <div class="package-section-icon">
                  <i class="bi bi-calendar2-week"></i>
                </div>

                <div>
                  <h5 class="mb-1 fw-semibold">
                    Promo &amp; Ketersediaan
                  </h5>
                  <p class="text-muted small mb-0">
                    Atur waktu pemesanan, tanggal libur, dan promo paket.
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addPackagePromo()">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Promo
              </button>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="lead_time_days" class="form-label fw-semibold">
                  Waktu Minimal Pemesanan (hari)
                </label>

                <input
                  type="number"
                  id="lead_time_days"
                  name="lead_time_days"
                  class="form-control @error('lead_time_days') is-invalid @enderror"
                  value="{{ old('lead_time_days', 30) }}"
                  min="0"
                  max="365"
                  placeholder="0">

                @error('lead_time_days')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-12">
                <label for="blackout_dates" class="form-label fw-semibold">
                  Tanggal Blackout
                </label>

                <textarea
                  id="blackout_dates"
                  name="blackout_dates"
                  rows="2"
                  class="form-control @error('blackout_dates') is-invalid @enderror"
                  placeholder="2026-12-24, 2026-12-25">{{ old('blackout_dates') }}</textarea>

                <small class="text-muted">
                  Pisahkan tanggal dengan koma. Tanggal yang sama akan dihitung sekali.
                </small>

                @error('blackout_dates')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">
                  Promo
                </label>

                <div id="package-promos-container"></div>

                <small class="text-muted">
                  Kode promo harus unik. Promo persentase bernilai 1–100, promo tetap minimal Rp 1.000.
                </small>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-4 package-sidebar">
        <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-publication">
          <div class="card-header bg-transparent border-0 p-4 pb-2">
            <h5 class="mb-1 fw-semibold">
              Pengaturan Paket
            </h5>
            <p class="text-muted small mb-0">
              Atur status tampil dan penanda paket.
            </p>
          </div>

          <div class="card-body p-4 pt-2">
            <div class="mb-4">
              <label for="is_active" class="form-label fw-semibold">
                Status
                <span class="text-danger">*</span>
              </label>

              <select
                name="is_active"
                id="is_active"
                class="form-select @error('is_active') is-invalid @enderror"
                required>

                <option value="">
                  -- Pilih Status --
                </option>

                <option
                  value="active"
                  {{ old('is_active', 'active') === 'active' ? 'selected' : '' }}>
                  Aktif
                </option>

                <option
                  value="inactive"
                  {{ old('is_active') === 'inactive' ? 'selected' : '' }}>
                  Nonaktif
                </option>

              </select>

              @error('is_active')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold" for="is_published">
                Status Publikasi
              </label>

              <input type="hidden" name="is_published" value="0">

              <div class="form-check form-switch">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="is_published"
                  name="is_published"
                  value="1"
                  {{ filter_var(old('is_published', true), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>

                <label class="form-check-label" for="is_published">
                  Tampilkan paket kepada klien
                </label>
              </div>

              <small class="text-muted">
                Paket berstatus draft tidak muncul pada halaman paket member.
              </small>
            </div>

            <div class="package-popular-box">
              <div class="form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="is_popular"
                  name="is_popular"
                  value="1"
                  {{ old('is_popular') ? 'checked' : '' }}>

                <label
                  class="form-check-label fw-semibold"
                  for="is_popular">
                  Tandai sebagai Paling Dipilih
                </label>
              </div>

              <small class="text-muted d-block mt-1">
                Paket paling dipilih akan ditonjolkan pada halaman paket.
              </small>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
              <i class="bi bi-lightbulb text-warning"></i>
              <h6 class="fw-semibold mb-0">
                Tips Paket
              </h6>
            </div>

            <ul class="text-muted small mb-0 ps-3">
              <li class="mb-2">
                Gunakan nama paket yang mudah dikenali, misalnya Silver, Gold, atau Platinum.
              </li>

              <li class="mb-2">
                Rincikan manfaat utama seperti dekorasi, dokumentasi, rias pengantin, dan MC.
              </li>

              <li>
                Pastikan harga diskon lebih rendah daripada harga reguler.
              </li>
            </ul>
          </div>
        </div>

        <div class="d-flex gap-2 w-100">
          <a
            href="{{ route('packages.index') }}"
            class="btn btn-light flex-fill">
            Batal
          </a>

          <button
            type="submit"
            class="btn btn-primary flex-fill">
            <i class="bi bi-check-lg me-1"></i>
            Simpan Paket
          </button>
        </div>
      </div>

    </div>
  </form>

</div>


<style>
  .package-section-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(75, 107, 82, 0.08);
    color: var(--bs-primary);
    flex-shrink: 0;
  }

  .package-option-item,
  .package-addon-item {
    background: var(--bs-body-bg);
  }

  .package-addon-item .form-control {
    min-height: 42px;
  }

  .package-option-info {
    font-size: 13px;
    color: var(--bs-secondary-color);
    background: var(--bs-tertiary-bg);
    border-radius: 8px;
    padding: 10px 12px;
  }

  .package-popular-box {
    padding: 14px;
    border: 1px solid var(--bs-border-color);
    border-radius: 10px;
    background: var(--bs-tertiary-bg);
  }

  .package-feature-item .form-control {
    min-height: 42px;
  }

  .package-option-item .form-control,
  .package-option-item .form-select {
    min-height: 42px;
  }

  .package-stepper {
    background: var(--bs-tertiary-bg);
  }

  .package-stepper .package-step {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 8px;
    color: var(--bs-body-color);
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
  }

  .package-stepper .package-step:hover {
    background: var(--bs-border-color-translucent);
  }

  .package-stepper .package-step-number {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--bs-primary);
    color: #fff;
    font-size: .75rem;
    flex-shrink: 0;
  }

  .package-step-anchor {
    scroll-margin-top: calc(var(--package-sticky-top, 76px) + 16px);
  }

  .package-promo-item {
    background: var(--bs-body-bg);
  }

  .package-promo-item .form-control,
  .package-promo-item .form-select {
    min-height: 42px;
  }

  @media (min-width: 992px) {

    .package-sidebar {
      position: sticky;
      top: calc(var(--package-sticky-top, 76px) + 12px);
      align-self: start;
    }

  }

  @media (max-width: 767.98px) {

    .package-option-item {
      padding: 14px !important;
    }

    .package-option-item .row>div,
    .package-addon-item .row>div,
    .package-promo-item .row>div {
      margin-bottom: 4px;
    }

    .package-addon-item,
    .package-promo-item {
      padding: 14px !important;
    }

  }
</style>


@php
  $oldOptionRows = collect(old('options'))->values()->map(fn ($row) => (object) $row);
  $oldAddonRows = collect(old('addons'))->values()->map(fn ($row) => (object) $row);
  $oldPromoRows = collect(old('promos'))->values()->map(fn ($row) => (object) $row);
  $oldFeatureRows = collect(old('features'))
    ->values()
    ->map(fn ($row) => is_array($row) ? (object) $row : (object) ['feature' => $row]);

  $oldRowsMeta = [
    'options' => $oldOptionRows,
    'addons' => $oldAddonRows,
    'promos' => $oldPromoRows,
    'features' => $oldFeatureRows,
  ];
@endphp

<script type="application/json" id="package-old-rows">@json($oldRowsMeta)</script>


<script>
  let packageOptionIndex = 1;

  let packageAddonIndex =
    document.querySelectorAll('#package-addons-container .package-addon-item')
      .length || 1;

  let packagePromoIndex =
    document.querySelectorAll('#package-promos-container .package-promo-item')
      .length || 0;


  function addPackageOption() {

    const container = document.getElementById(
      'package-options-container'
    );

    const index = packageOptionIndex++;

    const html = `
            <div class="package-option-item border rounded-3 p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <span class="fw-semibold package-option-title">
                            Opsi ${index + 1}
                        </span>

                        <span class="text-muted small ms-1">
                            Pilihan harga
                        </span>
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        onclick="removePackageOption(this)">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama Opsi
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="options[${index}][name]"
                        class="form-control"
                        placeholder="Contoh: Platinum"
                        required>

                </div>


                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Jumlah layanan termasuk
                        </label>

                        <input
                            type="number"
                            name="options[${index}][quota]"
                            class="form-control"
                            placeholder="Kosongkan = tanpa batas"
                            min="1">

                        <small class="text-muted">
                            Jumlah layanan yang disertakan pada opsi ini. Kosongkan jika tidak ada batasan jumlah layanan.
                        </small>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Harga Reguler
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="options[${index}][price]"
                                class="form-control"
                                placeholder="0"
                                min="0"
                                required>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Harga Diskon
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="options[${index}][discount_price]"
                                class="form-control"
                                placeholder="Opsional"
                                min="0">

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Masa berlaku penawaran
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="options[${index}][duration]"
                            class="form-control"
                            value="1"
                            min="1"
                            placeholder="Contoh: 1"
                            required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Satuan masa berlaku
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="options[${index}][duration_unit]"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih --
                            </option>

                            <option value="day">
                                Hari
                            </option>

                            <option value="week">
                                Minggu
                            </option>

                            <option value="month" selected>
                                Bulan
                            </option>

                            <option value="year">
                                Tahun
                            </option>

                        </select>

                    </div>

                </div>

            </div>
        `;

    container.insertAdjacentHTML(
      'beforeend',
      html
    );

    updatePackageOptionTitles();
  }


  function removePackageOption(button) {

    const items = document.querySelectorAll(
      '#package-options-container .package-option-item'
    );

    if (items.length <= 1) {
      return;
    }

    button
      .closest('.package-option-item')
      .remove();

    updatePackageOptionTitles();
  }


  function addPackageAddon() {

    const container = document.getElementById(
      'package-addons-container'
    );

    const index = packageAddonIndex++;

    const html = `
            <div class="package-addon-item border rounded-3 p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <span class="fw-semibold package-addon-title">
                        Layanan ${index + 1}
                    </span>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger package-remove-addon"
                        onclick="removePackageAddon(this)">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama Layanan
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="addons[${index}][name]"
                        class="form-control"
                        placeholder="Contoh: Dokumentasi tambahan">

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        name="addons[${index}][description]"
                        rows="2"
                        class="form-control"
                        placeholder="Contoh: Foto close-up momen akad"></textarea>

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Harga Reguler
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="addons[${index}][price]"
                                class="form-control"
                                placeholder="0"
                                min="0">

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Harga Diskon
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="addons[${index}][discount_price]"
                                class="form-control"
                                placeholder="Opsional"
                                min="0">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Min Qty
                        </label>

                        <input
                            type="number"
                            name="addons[${index}][min_quantity]"
                            class="form-control"
                            value="1"
                            min="1"
                            max="99"
                            placeholder="1">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Max Qty
                        </label>

                        <input
                            type="number"
                            name="addons[${index}][max_quantity]"
                            class="form-control"
                            placeholder="Opsional"
                            min="1">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Butuh Layanan Lainnya
                        </label>

                        <select name="addons[${index}][requires_addon_index]"
                            class="form-select"></select>

                    </div>

                </div>

            </div>
        `;

    container.insertAdjacentHTML(
      'beforeend',
      html
    );

    updatePackageAddonTitles();
    syncPackageAddonDependencyOptions();
  }


  function removePackageAddon(button) {

    button
      .closest('.package-addon-item')
      .remove();

    updatePackageAddonTitles();
    syncPackageAddonDependencyOptions();
  }


  function syncPackageAddonDependencyOptions() {

    const container = document.getElementById(
      'package-addons-container'
    );

    if (!container) {
      return;
    }

    const items = Array.from(
      container.querySelectorAll('.package-addon-item')
    );

    const names = items.map(function (item) {
      const nameField = item.querySelector('[name$="[name]"]');

      return nameField ? String(nameField.value || '').trim() : '';
    });

    items.forEach(function (item, index) {
      const field = item.querySelector('[name$="[requires_addon_index]"]');

      if (!field) {
        return;
      }

      const selected = String(field.value || '');
      field.replaceChildren(new Option('Tidak ada', ''));

      names.forEach(function (name, addonIndex) {
        if (addonIndex === index || name === '') {
          return;
        }

        field.add(new Option(
          name,
          String(addonIndex),
          false,
          selected === String(addonIndex)
        ));
      });
    });
  }


  function updatePackageAddonTitles() {

    const items = document.querySelectorAll(
      '#package-addons-container .package-addon-item'
    );

    items.forEach((item, index) => {

      const title = item.querySelector(
        '.package-addon-title'
      );

      if (title) {
        title.textContent = `Layanan ${index + 1}`;
      }

    });
  }


  function updatePackageOptionTitles() {

    const items = document.querySelectorAll(
      '#package-options-container .package-option-item'
    );

    items.forEach((item, index) => {

      const title = item.querySelector(
        '.package-option-title'
      );

      if (title) {
        title.textContent = `Opsi ${index + 1}`;
      }

    });
  }


  function addPackagePromo() {

    const container = document.getElementById('package-promos-container');

    if (!container) {
      return;
    }

    const index = packagePromoIndex++;

    const html = `
            <div class="package-promo-item border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold package-promo-title">
                        Promo ${index + 1}
                    </span>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger package-remove-promo"
                        onclick="removePackagePromo(this)">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="hidden" name="promos[${index}][uuid]" value="">
                        <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
                        <input type="text" name="promos[${index}][code]" class="form-control" placeholder="Contoh: HEMAT2026" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="promos[${index}][type]" class="form-select" required>
                            <option value="percent">Persentase</option>
                            <option value="fixed">Nominal</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nilai <span class="text-danger">*</span></label>
                        <input type="number" name="promos[${index}][value]" class="form-control" min="1" placeholder="Contoh: 10" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Minimal Pembelian</label>
                        <input type="number" name="promos[${index}][min_purchase]" class="form-control" min="0" placeholder="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Maksimal Diskon</label>
                        <input type="number" name="promos[${index}][max_discount]" class="form-control" min="1" placeholder="Opsional">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Maksimal Pemakaian</label>
                        <input type="number" name="promos[${index}][max_uses]" class="form-control" min="1" placeholder="Opsional">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mulai</label>
                        <input type="date" name="promos[${index}][starts_at]" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berakhir</label>
                        <input type="date" name="promos[${index}][ends_at]" class="form-control">
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="promos[${index}][is_active]" value="0">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="promo-active-${index}" name="promos[${index}][is_active]" value="1" checked>
                            <label class="form-check-label" for="promo-active-${index}">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        `;

    container.insertAdjacentHTML('beforeend', html);
    updatePackagePromoTitles();
  }


  function removePackagePromo(button) {

    button.closest('.package-promo-item').remove();
    updatePackagePromoTitles();
  }


  function updatePackagePromoTitles() {

    const items = document.querySelectorAll(
      '#package-promos-container .package-promo-item'
    );

    items.forEach((item, index) => {

      const title = item.querySelector('.package-promo-title');

      if (title) {
        title.textContent = `Promo ${index + 1}`;
      }

    });
  }


  function addPackageFeature() {

    const container = document.getElementById(
      'package-features-container'
    );
    const index = container.querySelectorAll('.package-feature-item').length;

    const html = `
            <div class="row g-2 align-items-end mb-3 package-feature-item">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold" for="feature-category-${index}">Kategori</label>
                    <input type="text" id="feature-category-${index}" name="features[${index}][category]"
                        class="form-control package-feature-category" maxlength="100" placeholder="Contoh: Dekorasi">
                </div>
                <div class="col-md-7">
                    <label class="form-label small fw-semibold" for="feature-text-${index}">Rincian yang termasuk</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-check2"></i></span>
                        <input type="text" id="feature-text-${index}" name="features[${index}][feature]"
                            class="form-control package-feature-text" maxlength="255" placeholder="Contoh: Rias pengantin">
                    </div>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-outline-danger" onclick="removePackageFeature(this)"
                        aria-label="Hapus isi paket">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        `;

    container.insertAdjacentHTML(
      'beforeend',
      html
    );
  }


  function removePackageFeature(button) {

    const items = document.querySelectorAll(
      '#package-features-container .package-feature-item'
    );
    const item = button.closest('.package-feature-item');

    if (items.length <= 1) {
      item.querySelectorAll('input').forEach(function(input) {
        input.value = '';
      });

      return;
    }

    item.remove();
    document.querySelectorAll('#package-features-container .package-feature-item').forEach(function(row, index) {
      row.querySelector('.package-feature-category').name = `features[${index}][category]`;
      row.querySelector('.package-feature-category').id = `feature-category-${index}`;
      row.querySelector('.package-feature-text').name = `features[${index}][feature]`;
      row.querySelector('.package-feature-text').id = `feature-text-${index}`;
      row.querySelector('label[for^="feature-category-"]').htmlFor = `feature-category-${index}`;
      row.querySelector('label[for^="feature-text-"]').htmlFor = `feature-text-${index}`;
    });
  }


  function setPackageFieldValue(row, name, value) {
    if (value === undefined || value === null) {
      return;
    }

    const field = row.querySelector(`[name="${name}"]`);

    if (!field) {
      return;
    }

    if (field.type === 'checkbox') {
      field.checked = ['1', 1, true, 'true', 'on'].includes(value);

      return;
    }

    if (
      field.tagName === 'SELECT' &&
      value !== undefined &&
      value !== null &&
      value !== '' &&
      !Array.from(field.options).some(option => option.value === String(value))
    ) {
      field.add(new Option(`Layanan ${Number(value) + 1}`, String(value), false, true));
    }

    field.value = value;
  }


  function restorePackageRows(selector, itemSelector, rows, addRow, fields) {
    if (!Array.isArray(rows) || rows.length === 0) {
      return;
    }

    const container = document.querySelector(selector);

    if (!container) {
      return;
    }

    while (container.querySelectorAll(itemSelector).length < rows.length) {
      addRow();
    }

    container.querySelectorAll(itemSelector).forEach(function(row, index) {
      const values = rows[index] ?? {};

      fields.forEach(function([key, name]) {
        setPackageFieldValue(row, name.replace('{index}', index), values[key]);
      });
    });
  }


  function restorePackageFormFromOldInput() {
    const meta = document.getElementById('package-old-rows');

    if (!meta) {
      return;
    }

    const oldRows = JSON.parse(meta.textContent || '{}');

    restorePackageRows(
      '#package-options-container',
      '.package-option-item',
      oldRows.options,
      addPackageOption,
      [
        ['name', 'options[{index}][name]'],
        ['quota', 'options[{index}][quota]'],
        ['price', 'options[{index}][price]'],
        ['discount_price', 'options[{index}][discount_price]'],
        ['duration', 'options[{index}][duration]'],
        ['duration_unit', 'options[{index}][duration_unit]'],
      ]
    );

    restorePackageRows(
      '#package-addons-container',
      '.package-addon-item',
      oldRows.addons,
      addPackageAddon,
      [
        ['name', 'addons[{index}][name]'],
        ['description', 'addons[{index}][description]'],
        ['price', 'addons[{index}][price]'],
        ['discount_price', 'addons[{index}][discount_price]'],
        ['min_quantity', 'addons[{index}][min_quantity]'],
        ['max_quantity', 'addons[{index}][max_quantity]'],
        ['requires_addon_index', 'addons[{index}][requires_addon_index]'],
      ]
    );

    syncPackageAddonDependencyOptions();

    restorePackageRows(
      '#package-promos-container',
      '.package-promo-item',
      oldRows.promos,
      addPackagePromo,
      [
        ['uuid', 'promos[{index}][uuid]'],
        ['code', 'promos[{index}][code]'],
        ['type', 'promos[{index}][type]'],
        ['value', 'promos[{index}][value]'],
        ['min_purchase', 'promos[{index}][min_purchase]'],
        ['max_discount', 'promos[{index}][max_discount]'],
        ['max_uses', 'promos[{index}][max_uses]'],
        ['starts_at', 'promos[{index}][starts_at]'],
        ['ends_at', 'promos[{index}][ends_at]'],
      ]
    );

    document.querySelectorAll('#package-promos-container .package-promo-item').forEach(function(row, index) {
      const values = (oldRows.promos || [])[index] || {};
      const active = row.querySelector(`input[type="checkbox"][name="promos[${index}][is_active]"]`);

      if (active) {
        active.checked = values.is_active === undefined
          ? true
          : ['1', 1, true, 'true', 'on'].includes(values.is_active);
      }
    });

    restorePackageRows(
      '#package-features-container',
      '.package-feature-item',
      oldRows.features,
      addPackageFeature,
      [
        ['category', 'features[{index}][category]'],
        ['feature', 'features[{index}][feature]'],
      ]
    );
  }


  restorePackageFormFromOldInput();
</script>

@endsection
