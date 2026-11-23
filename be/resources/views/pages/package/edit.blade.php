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

    @php
        $addonIndexByUuid = [];
        foreach ($package->addons as $addonIndex => $addonItem) {
            $addonIndexByUuid[$addonItem->uuid] = $addonIndex;
        }

        $blackoutValue = old(
            'blackout_dates',
            $package->blackoutDates
                ->map(fn ($item) => $item->date->format('Y-m-d'))
                ->implode(', ')
        );
    @endphp

    <script type="application/json" id="package-reorder-meta">@json(['url' => route('packages.reorder', $package->uuid)])</script>

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

    <form action="{{ route('packages.update', $package->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-information">
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

                <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-event">
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

                <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-options">
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
                            <div class="package-option-item border rounded-3 p-3 mb-3" draggable="true" data-uuid="{{ $option->uuid }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                                        <div>
                                            <span class="fw-semibold package-option-title">
                                                Opsi {{ $index + 1 }}
                                            </span>
                                            <span class="text-muted small ms-1">
                                                Pilihan harga
                                            </span>
                                        </div>
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
                            <div class="package-option-item border rounded-3 p-3 mb-3" draggable="true">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                                        <div>
                                            <span class="fw-semibold package-option-title">
                                                Opsi 1
                                            </span>

                                            <span class="text-muted small ms-1">
                                                Pilihan harga
                                            </span>
                                        </div>
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
                            <div class="package-addon-item border rounded-3 p-3 mb-3" draggable="true" data-uuid="{{ $addon->uuid }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                                        <span class="fw-semibold package-addon-title">
                                            Layanan {{ $index + 1 }}
                                        </span>
                                    </div>

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

                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Min Qty
                                        </label>

                                        <input type="number"
                                            name="addons[{{ $index }}][min_quantity]"
                                            class="form-control"
                                            value="{{ old("addons.$index.min_quantity", $addon->min_quantity) }}"
                                            min="1"
                                            max="99"
                                            placeholder="1">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Max Qty
                                        </label>

                                        <input type="number"
                                            name="addons[{{ $index }}][max_quantity]"
                                            class="form-control"
                                            value="{{ old("addons.$index.max_quantity", $addon->max_quantity) }}"
                                            min="1"
                                            placeholder="Opsional">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Butuh Layanan ke-
                                        </label>

                                        <input type="number"
                                            name="addons[{{ $index }}][requires_addon_index]"
                                            class="form-control"
                                            value="{{ old("addons.$index.requires_addon_index", $addonIndexByUuid[$addon->requires_addon_uuid] ?? '') }}"
                                            min="0"
                                            placeholder="Contoh: 1">
                                    </div>
                                </div>
                            </div>

                            @empty
                            <div class="package-addon-item border rounded-3 p-3 mb-3" draggable="true">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                                        <span class="fw-semibold package-addon-title">
                                            Layanan 1
                                        </span>
                                    </div>

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

                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Min Qty
                                        </label>

                                        <input type="number"
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

                                        <input type="number"
                                            name="addons[0][max_quantity]"
                                            class="form-control"
                                            value="{{ old('addons.0.max_quantity') }}"
                                            min="1"
                                            placeholder="Opsional">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Butuh Layanan ke-
                                        </label>

                                        <input type="number"
                                            name="addons[0][requires_addon_index]"
                                            class="form-control"
                                            value="{{ old('addons.0.requires_addon_index') }}"
                                            min="0"
                                            placeholder="Contoh: 1">
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
                                        Isi Paket
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Kelompokkan layanan yang sudah termasuk, misalnya Dekorasi atau Katering.
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                id="btn-add-package-feature"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Isi Paket
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div id="package-features-container">

                            @forelse ($package->features as $index => $feature)
                            <div class="row g-2 align-items-end mb-3 package-feature-item" draggable="true" data-uuid="{{ $feature->uuid }}">
                                <div class="col-md-1 d-grid">
                                    <span class="btn btn-light border package-sortable-handle" title="Geser untuk mengubah urutan" aria-label="Geser isi paket">
                                        <i class="bi bi-grip-vertical"></i>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold" for="feature-category-{{ $index }}">Kategori</label>
                                    <input type="text"
                                        id="feature-category-{{ $index }}"
                                        name="features[{{ $index }}][category]"
                                        class="form-control package-feature-category"
                                        value="{{ old("features.$index.category", $feature->category) }}"
                                        maxlength="100"
                                        placeholder="Contoh: Dekorasi">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label small fw-semibold" for="feature-text-{{ $index }}">Rincian yang termasuk</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class="bi bi-check2"></i></span>
                                        <input type="text"
                                            id="feature-text-{{ $index }}"
                                            name="features[{{ $index }}][feature]"
                                            class="form-control package-feature-text"
                                            value="{{ old("features.$index.feature", $feature->feature) }}"
                                            maxlength="255"
                                            placeholder="Contoh: Dokumentasi foto dan video">
                                    </div>
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="button"
                                        class="btn btn-outline-danger btn-remove-package-feature"
                                        aria-label="Hapus isi paket">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>

                            @empty
                            <div class="row g-2 align-items-end mb-3 package-feature-item" draggable="true">
                                <div class="col-md-1 d-grid">
                                    <span class="btn btn-light border package-sortable-handle" title="Geser untuk mengubah urutan" aria-label="Geser isi paket">
                                        <i class="bi bi-grip-vertical"></i>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold" for="feature-category-0">Kategori</label>
                                    <input type="text"
                                        id="feature-category-0"
                                        name="features[0][category]"
                                        class="form-control package-feature-category"
                                        value="{{ old('features.0.category') }}"
                                        maxlength="100"
                                        placeholder="Contoh: Dekorasi">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label small fw-semibold" for="feature-text-0">Rincian yang termasuk</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class="bi bi-check2"></i></span>
                                        <input type="text"
                                            id="feature-text-0"
                                            name="features[0][feature]"
                                            class="form-control package-feature-text"
                                            value="{{ old('features.0.feature') }}"
                                            maxlength="255"
                                            placeholder="Contoh: Dokumentasi foto dan video">
                                    </div>
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="button"
                                        class="btn btn-outline-danger btn-remove-package-feature"
                                        aria-label="Hapus isi paket">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @endforelse

                        </div>

                        <small class="text-muted d-block mt-2">
                            Ulangi nama kategori untuk beberapa rincian; layanan dengan kategori sama akan ditampilkan berkelompok. Biarkan kategori kosong untuk rincian tanpa kelompok.
                        </small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 package-step-anchor" id="package-section-availability">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="package-section-icon">
                                    <i class="bi bi-calendar2-week"></i>
                                </div>

                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Promo &amp; Ketersediaan
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Atur waktu pemesanan, tanggal libur, dan promo paket.
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                id="btn-add-package-promo"
                                class="btn btn-sm btn-outline-primary">
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

                                <input type="number"
                                    id="lead_time_days"
                                    name="lead_time_days"
                                    class="form-control"
                                    value="{{ old('lead_time_days', $package->lead_time_days ?? 30) }}"
                                    min="0"
                                    max="365"
                                    placeholder="0">
                            </div>

                            <div class="col-12">
                                <label for="blackout_dates" class="form-label fw-semibold">
                                    Tanggal Blackout
                                </label>

                                <textarea id="blackout_dates"
                                    name="blackout_dates"
                                    rows="2"
                                    class="form-control"
                                    placeholder="2026-12-24, 2026-12-25">{{ $blackoutValue }}</textarea>

                                <small class="text-muted">
                                    Pisahkan tanggal dengan koma. Tanggal yang sama akan dihitung sekali.
                                </small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Promo
                                </label>

                                <div id="package-promos-container">
                                    @forelse ($package->promos as $index => $promo)
                                    <div class="package-promo-item border rounded-3 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fw-semibold package-promo-title">
                                                Promo {{ $index + 1 }}
                                            </span>

                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-remove-package-promo">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Kode Promo <span class="text-danger">*</span></label>

                                                <input type="text"
                                                    name="promos[{{ $index }}][code]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.code", $promo->code) }}"
                                                    placeholder="Contoh: HEMAT2026"
                                                    required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Tipe <span class="text-danger">*</span></label>

                                                <select name="promos[{{ $index }}][type]"
                                                    class="form-select"
                                                    required>
                                                    <option value="percent"
                                                        {{ old("promos.$index.type", $promo->type) === 'percent' ? 'selected' : '' }}>
                                                        Persentase
                                                    </option>

                                                    <option value="fixed"
                                                        {{ old("promos.$index.type", $promo->type) === 'fixed' ? 'selected' : '' }}>
                                                        Nominal
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Nilai <span class="text-danger">*</span></label>

                                                <input type="number"
                                                    name="promos[{{ $index }}][value]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.value", $promo->value) }}"
                                                    min="1"
                                                    required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Minimal Pembelian</label>

                                                <input type="number"
                                                    name="promos[{{ $index }}][min_purchase]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.min_purchase", $promo->min_purchase) }}"
                                                    min="0">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Maksimal Diskon</label>

                                                <input type="number"
                                                    name="promos[{{ $index }}][max_discount]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.max_discount", $promo->max_discount) }}"
                                                    min="1">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Maksimal Pemakaian</label>

                                                <input type="number"
                                                    name="promos[{{ $index }}][max_uses]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.max_uses", $promo->max_uses) }}"
                                                    min="1">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Mulai</label>

                                                <input type="date"
                                                    name="promos[{{ $index }}][starts_at]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.starts_at", $promo->starts_at ? $promo->starts_at->format('Y-m-d') : '') }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Berakhir</label>

                                                <input type="date"
                                                    name="promos[{{ $index }}][ends_at]"
                                                    class="form-control"
                                                    value="{{ old("promos.$index.ends_at", $promo->ends_at ? $promo->ends_at->format('Y-m-d') : '') }}">
                                            </div>

                                            <div class="col-12">
                                                <input type="hidden"
                                                    name="promos[{{ $index }}][is_active]"
                                                    value="0">

                                                <div class="form-check form-switch">
                                                    <input type="checkbox"
                                                        class="form-check-input"
                                                        id="promo-active-{{ $index }}"
                                                        name="promos[{{ $index }}][is_active]"
                                                        value="1"
                                                        {{ filter_var(old("promos.$index.is_active", $promo->is_active), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="promo-active-{{ $index }}">
                                                        Aktif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-muted small package-promo-empty">Belum ada promo.</div>
                                    @endforelse
                                </div>

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

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="is_published">
                                Status Publikasi
                            </label>

                            <input type="hidden" name="is_published" value="0">

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="is_published"
                                    name="is_published"
                                    value="1"
                                    {{ filter_var(old('is_published', $package->is_published), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_published">
                                    Tampilkan paket kepada klien
                                </label>
                            </div>

                            <div class="small text-muted mt-2">
                                Paket berstatus draft tidak muncul pada halaman paket member.
                            </div>
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

        .package-sortable-handle {
            cursor: grab;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #98a2b3;
            user-select: none;
        }

        .package-sortable-handle:active { cursor: grabbing; }
        .package-option-item.is-dragging,
        .package-addon-item.is-dragging,
        .package-feature-item.is-dragging { opacity: .5; }
        .package-option-item.drag-over,
        .package-addon-item.drag-over,
        .package-feature-item.drag-over { outline: 2px dashed #5b7cfa; outline-offset: 2px; }

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

        const promoContainer =
            document.getElementById('package-promos-container');

        const addPromoButton =
            document.getElementById('btn-add-package-promo');

        let promoIndex = promoContainer ?
            promoContainer.querySelectorAll('.package-promo-item').length :
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
            <div class="package-addon-item border rounded-3 p-3 mb-3" draggable="true">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="d-flex align-items-center gap-2">
                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                        <span class="fw-semibold package-addon-title">
                            Layanan ${index + 1}
                        </span>
                    </div>

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

                    <div class="col-md-4">

                        <label class="form-label">
                            Min Qty
                        </label>

                        <input type="number"
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

                        <input type="number"
                               name="addons[${index}][max_quantity]"
                               class="form-control"
                               placeholder="Opsional"
                               min="1">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Butuh Layanan ke-
                        </label>

                        <input type="number"
                               name="addons[${index}][requires_addon_index]"
                               class="form-control"
                               placeholder="Contoh: 1"
                               min="0">

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
            <div class="package-option-item border rounded-3 p-3 mb-3" draggable="true">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="d-flex align-items-center gap-2">
                        <span class="package-sortable-handle" title="Geser untuk mengubah urutan"><i class="bi bi-grip-vertical"></i></span>
                        <div>
                            <span class="fw-semibold package-option-title">
                                Opsi ${index + 1}
                            </span>
                            <span class="text-muted small ms-1">
                                Pilihan harga
                            </span>
                        </div>
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


        function renumberFeatureInputs() {
            if (!featureContainer) return;

            featureContainer.querySelectorAll('.package-feature-item').forEach(function (item, index) {
                const category = item.querySelector('.package-feature-category');
                const feature = item.querySelector('.package-feature-text');
                const categoryLabel = item.querySelector('label[for^="feature-category-"]');
                const featureLabel = item.querySelector('label[for^="feature-text-"]');

                if (category) {
                    category.name = `features[${index}][category]`;
                    category.id = `feature-category-${index}`;
                }
                if (feature) {
                    feature.name = `features[${index}][feature]`;
                    feature.id = `feature-text-${index}`;
                }
                if (categoryLabel) categoryLabel.htmlFor = `feature-category-${index}`;
                if (featureLabel) featureLabel.htmlFor = `feature-text-${index}`;
            });
        }

        function createFeature(index) {

            return `
            <div class="row g-2 align-items-end mb-3 package-feature-item" draggable="true">
                <div class="col-md-1 d-grid">
                    <span class="btn btn-light border package-sortable-handle" title="Geser untuk mengubah urutan" aria-label="Geser isi paket">
                        <i class="bi bi-grip-vertical"></i>
                    </span>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold" for="feature-category-${index}">Kategori</label>
                    <input type="text" id="feature-category-${index}" name="features[${index}][category]"
                        class="form-control package-feature-category" maxlength="100" placeholder="Contoh: Dekorasi">
                </div>
                <div class="col-md-7">
                    <label class="form-label small fw-semibold" for="feature-text-${index}">Rincian yang termasuk</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-check2"></i></span>
                        <input type="text" id="feature-text-${index}" name="features[${index}][feature]"
                            class="form-control package-feature-text" maxlength="255" placeholder="Contoh: MC dan sound system">
                    </div>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-outline-danger btn-remove-package-feature"
                        aria-label="Hapus isi paket">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        `;

        }


        if (addFeatureButton && featureContainer) {

            addFeatureButton.addEventListener('click', function() {

                featureContainer.insertAdjacentHTML(
                    'beforeend',
                    createFeature(featureContainer.querySelectorAll('.package-feature-item').length)
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
                    item.querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                    return;
                }

                item.remove();
                renumberFeatureInputs();

            });

        }


        function createPromo(index) {

            return `
            <div class="package-promo-item border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold package-promo-title">
                        Promo ${index + 1}
                    </span>

                    <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove-package-promo">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
                        <input type="text"
                               name="promos[${index}][code]"
                               class="form-control"
                               placeholder="Contoh: HEMAT2026"
                               required>
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
                        <input type="number"
                               name="promos[${index}][value]"
                               class="form-control"
                               min="1"
                               placeholder="Contoh: 10"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Minimal Pembelian</label>
                        <input type="number"
                               name="promos[${index}][min_purchase]"
                               class="form-control"
                               min="0"
                               placeholder="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Maksimal Diskon</label>
                        <input type="number"
                               name="promos[${index}][max_discount]"
                               class="form-control"
                               min="1"
                               placeholder="Opsional">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Maksimal Pemakaian</label>
                        <input type="number"
                               name="promos[${index}][max_uses]"
                               class="form-control"
                               min="1"
                               placeholder="Opsional">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mulai</label>
                        <input type="date"
                               name="promos[${index}][starts_at]"
                               class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berakhir</label>
                        <input type="date"
                               name="promos[${index}][ends_at]"
                               class="form-control">
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="promos[${index}][is_active]" value="0">
                        <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="promo-active-${index}"
                                   name="promos[${index}][is_active]"
                                   value="1"
                                   checked>
                            <label class="form-check-label" for="promo-active-${index}">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        `;

        }


        function updatePromoTitles() {

            if (!promoContainer) {
                return;
            }

            const items =
                promoContainer.querySelectorAll('.package-promo-item');

            items.forEach(function(item, index) {

                const title =
                    item.querySelector('.package-promo-title');

                if (title) {
                    title.textContent = 'Promo ' + (index + 1);
                }

            });

        }


        if (addPromoButton && promoContainer) {

            addPromoButton.addEventListener('click', function() {

                const empty = promoContainer.querySelector('.package-promo-empty');

                if (empty) {
                    empty.remove();
                }

                promoContainer.insertAdjacentHTML(
                    'beforeend',
                    createPromo(promoIndex)
                );

                promoIndex++;

                updatePromoTitles();

            });

        }


        if (promoContainer) {

            promoContainer.addEventListener('click', function(event) {

                const removeButton =
                    event.target.closest('.btn-remove-package-promo');

                if (!removeButton) {
                    return;
                }

                const item =
                    removeButton.closest('.package-promo-item');

                if (item) {
                    item.remove();
                }

                updatePromoTitles();

            });

        }


        updateOptionTitles();

        updateAddonTitles();

        updatePromoTitles();

        let reorderUrl = '';
        try {
            reorderUrl = JSON.parse(document.getElementById('package-reorder-meta')?.textContent || '{}').url || '';
        } catch (error) {
            reorderUrl = '';
        }

        function renumberIndexedItems(container, selector, prefix) {
            if (!container) return;

            container.querySelectorAll(selector).forEach(function (item, index) {
                item.querySelectorAll('[name^="' + prefix + '["]').forEach(function (field) {
                    field.name = field.name.replace(
                        new RegExp('^' + prefix + '\\[\\d+\\]'),
                        prefix + '[' + index + ']'
                    );
                });
            });
        }

        function persistReorder(type, container, selector) {
            if (!container || !reorderUrl) return;

            const uuids = Array.from(container.querySelectorAll(selector))
                .map(function (item) { return item.dataset.uuid || ''; })
                .filter(Boolean);

            if (!uuids.length) return;

            fetch(reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ type: type, uuids: uuids }),
            }).catch(function () {});
        }

        function initSortable(container, selector, type, afterDrop) {
            if (!container) return;
            let dragged = null;

            container.querySelectorAll(selector).forEach(function (item) {
                item.addEventListener('dragstart', function (event) {
                    dragged = item;
                    item.classList.add('is-dragging');
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', type);
                });

                item.addEventListener('dragend', function () {
                    container.querySelectorAll(selector).forEach(function (node) {
                        node.classList.remove('is-dragging', 'drag-over');
                    });
                    dragged = null;
                });

                item.addEventListener('dragover', function (event) {
                    if (!dragged || dragged === item) return;
                    event.preventDefault();
                    item.classList.add('drag-over');

                    const bounds = item.getBoundingClientRect();
                    const insertAfter = event.clientY > bounds.top + bounds.height / 2;
                    container.insertBefore(dragged, insertAfter ? item.nextSibling : item);
                });

                item.addEventListener('dragleave', function () {
                    item.classList.remove('drag-over');
                });

                item.addEventListener('drop', function (event) {
                    event.preventDefault();
                    item.classList.remove('drag-over');
                    if (!dragged) return;

                    if (afterDrop) afterDrop();
                    persistReorder(type, container, selector);
                });
            });
        }

        initSortable(optionContainer, '.package-option-item', 'options', function () {
            renumberIndexedItems(optionContainer, '.package-option-item', 'options');
            updateOptionTitles();
        });
        initSortable(addonContainer, '.package-addon-item', 'addons', function () {
            renumberIndexedItems(addonContainer, '.package-addon-item', 'addons');
            updateAddonTitles();
        });
        initSortable(featureContainer, '.package-feature-item', 'features', renumberFeatureInputs);

        optionContainer?.addEventListener('click', function (event) {
            if (event.target.closest('.btn-remove-package-option')) {
                setTimeout(function () {
                    renumberIndexedItems(optionContainer, '.package-option-item', 'options');
                }, 0);
            }
        });

        addonContainer?.addEventListener('click', function (event) {
            if (event.target.closest('.btn-remove-package-addon')) {
                setTimeout(function () {
                    renumberIndexedItems(addonContainer, '.package-addon-item', 'addons');
                }, 0);
            }
        });

    });
</script>

@endsection
