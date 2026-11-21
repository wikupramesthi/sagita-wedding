@extends('layouts.app')

@section('title', 'Ubah Paket')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Ubah Paket
            </h4>
            <p class="text-muted mb-0">
                Perbarui paket layanan wedding organizer.
            </p>
        </div>

        <a href="{{ route('packages.index') }}" class="btn btn-secondary border">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="fw-semibold mb-2">
            Silakan periksa kesalahan berikut:
        </div>

        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('packages.update', $package->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="package-section-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <div>
                                <h5 class="fw-semibold mb-1">
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

                            <input type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $package->name) }}"
                                placeholder="Contoh: Paket Gold"
                                required>
                        </div>

                        <div>
                            <label for="description" class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea id="description"
                                name="description"
                                class="form-control"
                                rows="4"
                                placeholder="Perbarui deskripsi layanan paket ini...">{{ old('description', $package->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="package-section-icon">
                                <i class="bi bi-calendar2-event"></i>
                            </div>

                            <div>
                                <h5 class="fw-semibold mb-1">
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

                                <input type="number"
                                    id="guest_capacity"
                                    name="guest_capacity"
                                    class="form-control"
                                    value="{{ old('guest_capacity', $package->guest_capacity) }}"
                                    min="1"
                                    placeholder="Contoh: 100 orang">
                            </div>

                            <div class="col-md-6">
                                <label for="event_duration_hours" class="form-label fw-semibold">
                                    Durasi Acara (jam)
                                </label>

                                <input type="number"
                                    id="event_duration_hours"
                                    name="event_duration_hours"
                                    class="form-control"
                                    value="{{ old('event_duration_hours', $package->event_duration_hours) }}"
                                    min="1"
                                    placeholder="Contoh: 6">
                            </div>

                            <div class="col-12">
                                <label for="venue_note" class="form-label fw-semibold">
                                    Lokasi Acara
                                </label>

                                <textarea id="venue_note"
                                    name="venue_note"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Contoh: Jakarta Selatan dan sekitarnya">{{ old('venue_note', $package->venue_note) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="cover_url" class="form-label fw-semibold">
                                    Foto Paket (URL)
                                </label>

                                <input type="url"
                                    id="cover_url"
                                    name="cover_url"
                                    class="form-control"
                                    value="{{ old('cover_url', $package->cover_url) }}"
                                    placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="package-section-icon">
                                    <i class="bi bi-tags"></i>
                                </div>

                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Pilihan Paket
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Atur harga, masa berlaku, dan kuota untuk setiap pilihan.
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                id="btn-add-package-option"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Opsi
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div id="package-options-container">

                            @forelse ($package->options as $index => $option)
                            <div class="package-option-item border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="fw-semibold package-option-title">
                                            Opsi {{ $index + 1 }}
                                        </span>

                                        <span class="text-muted small ms-1">
                                            Pilihan harga
                                        </span>
                                    </div>

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-remove-package-option">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Opsi
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="options[{{ $index }}][name]"
                                        class="form-control"
                                        value="{{ old("options.$index.name", $option->name) }}"
                                        placeholder="Contoh: Gold"
                                        required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Kuota
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                            name="options[{{ $index }}][quota]"
                                            class="form-control"
                                            value="{{ old("options.$index.quota", $option->quota) }}"
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

                                            <input type="number"
                                                name="options[{{ $index }}][price]"
                                                class="form-control"
                                                value="{{ old("options.$index.price", $option->price) }}"
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

                                            <input type="number"
                                                name="options[{{ $index }}][discount_price]"
                                                class="form-control"
                                                value="{{ old("options.$index.discount_price", $option->discount_price) }}"
                                                placeholder="Opsional"
                                                min="0">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Durasi
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                            name="options[{{ $index }}][duration]"
                                            class="form-control"
                                            value="{{ old("options.$index.duration", $option->duration) }}"
                                            placeholder="Contoh: 1"
                                            min="1"
                                            required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Satuan Durasi
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="options[{{ $index }}][duration_unit]"
                                            class="form-select"
                                            required>

                                            <option value="">
                                                -- Pilih --
                                            </option>

                                            <option value="day"
                                                {{ old("options.$index.duration_unit", $option->duration_unit) == 'day' ? 'selected' : '' }}>
                                                Hari
                                            </option>

                                            <option value="week"
                                                {{ old("options.$index.duration_unit", $option->duration_unit) == 'week' ? 'selected' : '' }}>
                                                Minggu
                                            </option>

                                            <option value="month"
                                                {{ old("options.$index.duration_unit", $option->duration_unit) == 'month' ? 'selected' : '' }}>
                                                Bulan
                                            </option>

                                            <option value="year"
                                                {{ old("options.$index.duration_unit", $option->duration_unit) == 'year' ? 'selected' : '' }}>
                                                Tahun
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            @empty
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

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-remove-package-option">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Opsi
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="options[0][name]"
                                        class="form-control"
                                        placeholder="Contoh: Gold"
                                        required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Kuota
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                            name="options[0][quota]"
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

                                            <input type="number"
                                                name="options[0][price]"
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

                                            <input type="number"
                                                name="options[0][discount_price]"
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

                                        <input type="number"
                                            name="options[0][duration]"
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

                                        <select name="options[0][duration_unit]"
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
                            @endforelse

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
                            <div class="d-flex align-items-center gap-3">
                                <div class="package-section-icon">
                                    <i class="bi bi-plus-square"></i>
                                </div>

                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Layanan Tambahan (Opsional)
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Tawarkan layanan tambahan di luar paket utama.
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                id="btn-add-package-addon"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Layanan
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div id="package-addons-container">

                            @forelse ($package->addons ?? [] as $index => $addon)
                            <div class="package-addon-item border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold package-addon-title">
                                        Layanan {{ $index + 1 }}
                                    </span>

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-remove-package-addon">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Layanan
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="addons[{{ $index }}][name]"
                                        class="form-control"
                                        value="{{ old("addons.$index.name", $addon->name) }}"
                                        placeholder="Contoh: Dekorasi tambahan">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Deskripsi
                                    </label>

                                    <textarea name="addons[{{ $index }}][description]"
                                        rows="2"
                                        class="form-control"
                                        placeholder="Contoh: Pelaminan bunga segar untuk area pagar">{{ old("addons.$index.description", $addon->description) }}</textarea>
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

                                            <input type="number"
                                                name="addons[{{ $index }}][price]"
                                                class="form-control"
                                                value="{{ old("addons.$index.price", $addon->price) }}"
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

                                            <input type="number"
                                                name="addons[{{ $index }}][discount_price]"
                                                class="form-control"
                                                value="{{ old("addons.$index.discount_price", $addon->discount_price) }}"
                                                min="0"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <div class="package-addon-item border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold package-addon-title">
                                        Layanan 1
                                    </span>

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-remove-package-addon">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Layanan
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="addons[0][name]"
                                        class="form-control"
                                        value="{{ old('addons.0.name') }}"
                                        placeholder="Contoh: Dekorasi tambahan">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Deskripsi
                                    </label>

                                    <textarea name="addons[0][description]"
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

                                            <input type="number"
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

                                            <input type="number"
                                                name="addons[0][discount_price]"
                                                class="form-control"
                                                value="{{ old('addons.0.discount_price') }}"
                                                min="0"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforelse

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
                            <div class="d-flex align-items-center gap-3">
                                <div class="package-section-icon">
                                    <i class="bi bi-list-check"></i>
                                </div>

                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Manfaat Paket
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Tampilkan layanan yang termasuk dalam paket ini.
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                id="btn-add-package-feature"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Manfaat
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div id="package-features-container">

                            @forelse ($package->features as $index => $feature)
                            <div class="input-group mb-2 package-feature-item">
                                <span class="input-group-text bg-transparent">
                                    <i class="bi bi-check2"></i>
                                </span>

                                <input type="text"
                                    name="features[]"
                                    class="form-control"
                                    value="{{ old("features.$index", $feature->feature) }}"
                                    placeholder="Contoh: Dokumentasi foto dan video">

                                <button type="button"
                                    class="btn btn-outline-danger btn-remove-package-feature">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            @empty
                            <div class="input-group mb-2 package-feature-item">
                                <span class="input-group-text bg-transparent">
                                    <i class="bi bi-check2"></i>
                                </span>

                                <input type="text"
                                    name="features[]"
                                    class="form-control"
                                    placeholder="Contoh: Dokumentasi foto dan video">

                                <button type="button"
                                    class="btn btn-outline-danger btn-remove-package-feature">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @endforelse

                        </div>

                        <small class="text-muted d-block mt-2">
                            Contoh: Rias pengantin
                        </small>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 package-sidebar">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <h5 class="fw-semibold mb-1">
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
                            </label>

                            <select name="is_active"
                                id="is_active"
                                class="form-select"
                                required>

                                <option value="active"
                                    {{ old('is_active', $package->is_active) == 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="inactive"
                                    {{ old('is_active', $package->is_active) == 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>

                            </select>
                        </div>

                        <div class="package-popular-box">
                            <div class="form-check">
                                <input type="checkbox"
                                    name="is_popular"
                                    value="1"
                                    class="form-check-input"
                                    id="is_popular"
                                    {{ old('is_popular', $package->is_popular) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                    for="is_popular">
                                    Tandai sebagai Paling Dipilih
                                </label>
                            </div>

                            <div class="small text-muted mt-2">
                                Paket paling dipilih akan ditonjolkan pada halaman paket.
                            </div>
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

                        <ul class="small text-muted mb-0 ps-3">
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
                        Perbarui Paket
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
    document.addEventListener('DOMContentLoaded', function() {

        const optionContainer =
            document.getElementById('package-options-container');

        const addOptionButton =
            document.getElementById('btn-add-package-option');

        let optionIndex = optionContainer ?
            optionContainer.querySelectorAll('.package-option-item').length :
            0;

        const addonContainer =
            document.getElementById('package-addons-container');

        const addAddonButton =
            document.getElementById('btn-add-package-addon');

        let addonIndex = addonContainer ?
            addonContainer.querySelectorAll('.package-addon-item').length :
            0;


        function updateOptionTitles() {

            if (!optionContainer) {
                return;
            }

            const items =
                optionContainer.querySelectorAll('.package-option-item');

            items.forEach(function(item, index) {

                const title =
                    item.querySelector('.package-option-title');

                if (title) {
                    title.textContent = 'Opsi ' + (index + 1);
                }

            });

        }


        function updateAddonTitles() {

            if (!addonContainer) {
                return;
            }

            const items =
                addonContainer.querySelectorAll('.package-addon-item');

            items.forEach(function(item, index) {

                const title =
                    item.querySelector('.package-addon-title');

                if (title) {
                    title.textContent = 'Layanan ' + (index + 1);
                }

            });

        }


        function createAddon(index) {

            return `
            <div class="package-addon-item border rounded-3 p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <span class="fw-semibold package-addon-title">
                        Layanan ${index + 1}
                    </span>

                    <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove-package-addon">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama Layanan
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="addons[${index}][name]"
                           class="form-control"
                           placeholder="Contoh: Dokumentasi tambahan">

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea name="addons[${index}][description]"
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

                            <input type="number"
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

                            <input type="number"
                                   name="addons[${index}][discount_price]"
                                   class="form-control"
                                   placeholder="Opsional"
                                   min="0">

                        </div>

                    </div>

                </div>

            </div>
        `;

        }


        function addPackageAddon() {

            if (!addonContainer) {
                return;
            }

            addonContainer.insertAdjacentHTML(
                'beforeend',
                createAddon(addonIndex)
            );

            addonIndex++;

            updateAddonTitles();

        }


        function removePackageAddon(button) {

            const item =
                button.closest('.package-addon-item');

            if (item) {
                item.remove();
            }

            updateAddonTitles();

        }


        function createOption(index) {

            return `
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

                    <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove-package-option">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama Opsi
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
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

                        <input type="number"
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

                            <input type="number"
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

                            <input type="number"
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

                        <input type="number"
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

                        <select name="options[${index}][duration_unit]"
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
        }


        if (addOptionButton && optionContainer) {

            addOptionButton.addEventListener('click', function() {

                optionContainer.insertAdjacentHTML(
                    'beforeend',
                    createOption(optionIndex)
                );

                optionIndex++;

                updateOptionTitles();

            });

        }


        if (optionContainer) {

            optionContainer.addEventListener('click', function(event) {

                const removeButton =
                    event.target.closest('.btn-remove-package-option');

                if (!removeButton) {
                    return;
                }

                const items =
                    optionContainer.querySelectorAll('.package-option-item');

                if (items.length <= 1) {
                    return;
                }

                const item =
                    removeButton.closest('.package-option-item');

                if (item) {
                    item.remove();
                }

                updateOptionTitles();

            });

        }


        if (addAddonButton && addonContainer) {

            addAddonButton.addEventListener('click', addPackageAddon);

        }


        if (addonContainer) {

            addonContainer.addEventListener('click', function(event) {

                const removeButton =
                    event.target.closest('.btn-remove-package-addon');

                if (!removeButton) {
                    return;
                }

                removePackageAddon(removeButton);

            });

        }


        const featureContainer =
            document.getElementById('package-features-container');

        const addFeatureButton =
            document.getElementById('btn-add-package-feature');


        function createFeature() {

            return `
            <div class="package-feature-item mb-2">

                <div class="input-group">

                    <span class="input-group-text bg-transparent">
                        <i class="bi bi-check2"></i>
                    </span>

                    <input type="text"
                           name="features[]"
                           class="form-control"
                           placeholder="Contoh: MC dan sound system">

                    <button type="button"
                            class="btn btn-outline-danger btn-remove-package-feature">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>

                <small class="text-muted d-block mt-1">
                    Contoh: Dekorasi pelaminan
                </small>

            </div>
        `;

        }


        if (addFeatureButton && featureContainer) {

            addFeatureButton.addEventListener('click', function() {

                featureContainer.insertAdjacentHTML(
                    'beforeend',
                    createFeature()
                );

            });

        }


        if (featureContainer) {

            featureContainer.addEventListener('click', function(event) {

                const removeButton =
                    event.target.closest('.btn-remove-package-feature');

                if (!removeButton) {
                    return;
                }

                const items =
                    featureContainer.querySelectorAll('.package-feature-item');

                const item =
                    removeButton.closest('.package-feature-item');

                if (!item) {
                    return;
                }


                if (items.length <= 1) {

                    const input =
                        item.querySelector('input');

                    if (input) {
                        input.value = '';
                    }

                    return;
                }


                item.remove();

            });

        }


        updateOptionTitles();

        updateAddonTitles();

    });
</script>

@endsection
