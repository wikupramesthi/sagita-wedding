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

  <form action="{{ route('packages.store') }}" method="POST">
    @csrf

    <div class="row g-4">

      <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">
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

        <div class="card border-0 shadow-sm mb-4">
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

        <div class="card border-0 shadow-sm mb-4">
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
                    Atur harga, masa berlaku, dan kuota untuk setiap pilihan.
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
                      Kuota
                      <span class="text-danger">*</span>
                    </label>

                    <input
                      type="number"
                      name="options[0][quota]"
                      class="form-control"
                      value="{{ old('options.0.quota') }}"
                      min="1"
                      placeholder="Contoh: 1"
                      required>

                    <small class="text-muted">
                      Jumlah yang disertakan pada opsi ini.
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
                      Durasi
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
                      Satuan Durasi
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
              Setiap opsi dapat memiliki harga, kuota, dan masa berlaku tersendiri.
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
                    Manfaat Paket
                  </h5>
                  <p class="text-muted small mb-0">
                    Tampilkan layanan yang termasuk dalam paket ini.
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addPackageFeature()">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Manfaat
              </button>
            </div>
          </div>

          <div class="card-body p-4 pt-2">
            <div id="package-features-container">
              <div class="input-group mb-2 package-feature-item">
                <span class="input-group-text bg-transparent">
                  <i class="bi bi-check2"></i>
                </span>

                <input
                  type="text"
                  name="features[]"
                  class="form-control"
                  placeholder="Contoh: Dekorasi pelaminan">

                <button
                  type="button"
                  class="btn btn-outline-danger"
                  onclick="removePackageFeature(this)">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            <small class="text-muted d-block mt-2">
              Contoh: Dokumentasi foto dan video
            </small>

            @error('features.*')
            <div class="text-danger small mt-2">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

      </div>

      <div class="col-lg-4 package-sidebar">
        <div class="card border-0 shadow-sm mb-4">
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

  @media (min-width: 992px) {

    .package-sidebar {
      position: sticky;
      top: 16px;
      align-self: start;
    }

  }

  @media (max-width: 767.98px) {

    .package-option-item {
      padding: 14px !important;
    }

    .package-option-item .row>div,
    .package-addon-item .row>div {
      margin-bottom: 12px;
    }

    .package-addon-item {
      padding: 14px !important;
    }

  }
</style>


<script>
  let packageOptionIndex = 1;

  let packageAddonIndex =
    document.querySelectorAll('#package-addons-container .package-addon-item')
      .length || 1;


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
                            Kuota
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="options[${index}][quota]"
                            class="form-control"
                            placeholder="Contoh: 1"
                            min="1"
                            required>

                        <small class="text-muted">
                            Jumlah yang disertakan pada opsi ini.
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
                            Durasi
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
                            Satuan Durasi
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

                </div>

            </div>
        `;

    container.insertAdjacentHTML(
      'beforeend',
      html
    );

    updatePackageAddonTitles();
  }


  function removePackageAddon(button) {

    button
      .closest('.package-addon-item')
      .remove();

    updatePackageAddonTitles();
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


  function addPackageFeature() {

    const container = document.getElementById(
      'package-features-container'
    );

    const html = `
            <div class="package-feature-item mb-2">

                <div class="input-group">

                    <span class="input-group-text bg-transparent">
                        <i class="bi bi-check2"></i>
                    </span>

                    <input
                        type="text"
                        name="features[]"
                        class="form-control"
                        placeholder="Contoh: Rias pengantin">

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        onclick="removePackageFeature(this)">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>

                <small class="text-muted d-block mt-1">
                    Contoh: Dekorasi pelaminan
                </small>

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

    if (items.length <= 1) {

      button
        .closest('.package-feature-item')
        .querySelector('input')
        .value = '';

      return;
    }

    button
      .closest('.package-feature-item')
      .remove();
  }
</script>

@endsection
