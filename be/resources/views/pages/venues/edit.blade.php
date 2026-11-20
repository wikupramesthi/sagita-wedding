@extends('layouts.app')

@section('title', 'Ubah Venue')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="Ubah Venue" page="Venue" active="Ubah Venue" route="{{ route('venues.index') }}" />
@endsection

<section class="section">
    <form action="{{ route('venues.update', $venue->uuid) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Informasi Venue
                        </h5>

                        <p class="text-muted small mb-4">
                            Perbarui informasi utama agar data venue tetap akurat dan mudah dikenali.
                        </p>


                        {{-- Name --}}
                        <div class="mb-4">

                            <label for="name" class="form-label fw-semibold">
                                Nama Venue <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $venue->name) }}"
                                placeholder="Contoh: Gedung Serbaguna Melati"
                                required>

                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="mb-4">

                            <label for="slug" class="form-label fw-semibold">
                                Slug
                            </label>

                            <input type="text"
                                id="slug"
                                name="slug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $venue->slug) }}"
                                placeholder="gedung-serbaguna-melati">

                            <div class="form-text">
                                Digunakan sebagai pengenal URL venue.
                            </div>

                            @error('slug')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Excerpt --}}
                        <div class="mb-4">

                            <label for="excerpt" class="form-label fw-semibold">
                                Ringkasan
                            </label>

                            <textarea id="excerpt"
                                name="excerpt"
                                rows="3"
                                class="form-control @error('excerpt') is-invalid @enderror"
                                placeholder="Contoh: Tempat pertemuan dengan kapasitas 100 orang.">{{ old('excerpt', $venue->excerpt) }}</textarea>

                            @error('excerpt')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div>

                            <label for="description" class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea id="description"
                                name="description"
                                rows="7"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Ceritakan fasilitas, kapasitas, suasana, dan hal menarik lain di venue ini...">{{ old('description', $venue->description) }}</textarea>

                            @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Location & Contact --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Lokasi & Kontak
                        </h5>

                        <p class="text-muted small mb-4">
                            Pastikan alamat dan kontak masih valid agar pengunjung mudah menemukan venue.
                        </p>


                        {{-- Address --}}
                        <div class="mb-4">

                            <label for="address" class="form-label fw-semibold">
                                Alamat <span class="text-danger">*</span>
                            </label>

                            <textarea id="address"
                                name="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Contoh: Jl. Melati No. 12, Jakarta Selatan"
                                required>{{ old('address', $venue->address) }}</textarea>

                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        <div class="row g-3">

                            {{-- Phone --}}
                            <div class="col-md-6">

                                <label for="phone" class="form-label fw-semibold">
                                    Telepon
                                </label>

                                <input type="text"
                                    id="phone"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $venue->phone) }}"
                                    placeholder="+62 812 3456 7890">

                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div class="col-md-6">

                                <label for="email" class="form-label fw-semibold">
                                    Email
                                </label>

                                <input type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $venue->email) }}"
                                    placeholder="kontak@namavenue.com">

                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Google Maps --}}
                        <div class="mt-4">

                            <label for="google_maps_url" class="form-label fw-semibold">
                                URL Google Maps
                            </label>

                            <input type="url"
                                id="google_maps_url"
                                name="google_maps_url"
                                class="form-control @error('google_maps_url') is-invalid @enderror"
                                value="{{ old('google_maps_url', $venue->google_maps_url) }}"
                                placeholder="https://maps.google.com/...">

                            <div class="form-text">
                                Tempel tautan Google Maps agar lokasi venue dapat dibuka langsung.
                            </div>

                            @error('google_maps_url')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror


                            {{-- View Map --}}
                            @if ($venue->google_maps_url)

                            <div class="mt-2">

                                <a href="{{ $venue->google_maps_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-geo-alt me-1"></i>
                                    Lihat Lokasi Saat Ini

                                </a>

                            </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">

                {{-- Venue Image --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Gambar Venue
                        </h5>

                        <p class="text-muted small mb-4">
                            Ganti gambar jika ingin memperbarui tampilan venue.
                        </p>


                        <div class="mb-3">

                            <div id="imagePreview"
                                class="rounded overflow-hidden bg-light d-flex align-items-center justify-content-center mb-3"
                                style="height: 220px;">

                                @if ($venue->image)

                                <img src="{{ asset('storage/' . $venue->image) }}"
                                    alt="{{ $venue->name }}"
                                    class="w-100 h-100"
                                    style="object-fit: cover;">

                                @else

                                <div class="text-center text-muted">

                                    <i class="bi bi-image fs-1 d-block mb-2"></i>

                                    <small>
                                        Tidak ada gambar tersedia
                                    </small>

                                </div>

                                @endif

                            </div>


                            <input type="file"
                                id="image"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp">

                            <div class="form-text">
                                Biarkan kosong untuk mempertahankan gambar saat ini.
                                Maksimal 2 MB.
                            </div>

                            @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Status
                        </h5>

                        <p class="text-muted small mb-4">
                            Tentukan apakah venue ini tampil di halaman publik.
                        </p>


                        <div>

                            <label for="status" class="form-label fw-semibold">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                                <option value="active"
                                    {{ old('status', $venue->status) === 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="inactive"
                                    {{ old('status', $venue->status) === 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>

                            </select>

                            @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <button type="submit"
                            class="btn btn-primary w-100 mb-2">

                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Perubahan

                        </button>

                        <a href="{{ route('venues.index') }}"
                            class="btn btn-light w-100">

                            Batal

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</section>


{{-- Image Preview --}}
<script>
    document.getElementById('image').addEventListener('change', function(event) {

        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}"
                    alt="Pratinjau Venue"
                    class="w-100 h-100"
                    style="object-fit: cover;">
            `;
        };

        reader.readAsDataURL(file);
    });
</script>

@endsection