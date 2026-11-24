@extends('layouts.app')
@section('title', 'Profil')
@section('breadcrumb')
<x-breadcrumb
    title="Profil"
    page="Pengaturan Akun"
    active="Profil"
    route="{{ route('profile.edit') }}" />
@endsection

@section('content')
@php
    $profileAvatar = auth()->user()->avatar;
    $profileAvatarIsRemote = $profileAvatar && \Illuminate\Support\Str::startsWith($profileAvatar, ['http://', 'https://']);
    $profileAvatarUrl = $profileAvatarIsRemote
        ? $profileAvatar
        : ($profileAvatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($profileAvatar)
            ? asset('storage/' . $profileAvatar)
            : asset('dist/assets/images/avatar.jpg'));
@endphp

{{-- Success Alert --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>

    <span class="text-white">
        {{ session('success') }}
    </span>

    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>
</div>
@endif


{{-- Password Error --}}
@if ($errors->updatePassword->any())

@foreach ($errors->updatePassword->all() as $error)

<div class="alert alert-danger alert-dismissible fade show mb-4"
    role="alert">

    <i class="bi bi-exclamation-triangle-fill me-2"></i>

    <span class="text-white">
        {{ $error }}
    </span>

    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>

</div>

@endforeach

@endif


{{-- Profile Header --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

    <div class="card-body p-4">

        <div class="row align-items-center g-4">

            {{-- Avatar --}}
            <div class="col-auto">

                <div class="position-relative">

                    <img src="{{ $profileAvatarUrl }}"
                        alt="{{ auth()->user()->name }}"
                        class="rounded-circle border border-4 border-white shadow"
                        style="width: 100px; height: 100px; object-fit: cover;">

                </div>

            </div>


            {{-- User Information --}}
            <div class="col">

                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                    <h3 class="fw-semibold mb-0">
                        {{ auth()->user()->name }}
                    </h3>

                    @if (auth()->user()->getRoleNames()->isNotEmpty())

                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                        {{ ucfirst(auth()->user()->getRoleNames()[0]) }}
                    </span>

                    @endif

                </div>

                <div class="text-muted mb-3">
                    <i class="bi bi-envelope me-1"></i>
                    {{ auth()->user()->email }}
                </div>

                <div class="d-flex flex-wrap gap-2">

                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Akun Aktif
                    </span>

                    <span class="badge rounded-pill bg-light text-muted border px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        Bergabung {{ auth()->user()->created_at->format('d M Y') }}
                    </span>

                </div>

            </div>


            {{-- Actions --}}
            <div class="col-auto">

                <div class="d-flex flex-wrap gap-2">

                    <button type="button"
                        class="btn btn-light border px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-form-edit-password-{{ auth()->user()->id }}">

                        <i class="bi bi-lock me-1"></i>
                        Ganti Password

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Main Form --}}
<form action="{{ route('account.update', $user->uuid) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')


    <div class="row g-4">


        {{-- Profile Photo --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h5 class="fw-semibold mb-1">
                                Foto Profil
                            </h5>

                            <small class="text-muted">
                                Foto profil akun Anda
                            </small>

                        </div>

                        <div class="bg-primary-subtle rounded-3 p-2">
                            <i class="bi bi-camera text-primary fs-5"></i>
                        </div>

                    </div>


                    {{-- Current Avatar --}}
                    <div class="text-center mb-4">

                        <img src="{{ $profileAvatarUrl }}"
                            alt="{{ auth()->user()->name }}"
                            class="rounded-circle border border-4 border-light shadow-sm"
                            style="width: 170px; height: 170px; object-fit: cover;">

                    </div>


                    <div class="bg-primary-subtle rounded-3 p-3 mb-3">

                        <div class="d-flex align-items-center gap-3">

                            <i class="bi bi-image text-primary fs-4"></i>

                            <div>

                                <div class="fw-semibold">
                                    Unggah Foto Baru
                                </div>

                                <small class="text-muted">
                                    JPG atau PNG, maksimal 1 MB
                                </small>

                            </div>

                        </div>

                    </div>


                    <input
                        class="form-control @error('avatar') is-invalid @enderror"
                        id="avatar"
                        type="file"
                        name="avatar"
                        accept="image/png,image/jpeg">

                    @error('avatar')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Personal Information --}}
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h5 class="fw-semibold mb-1">
                                Informasi Pribadi
                            </h5>

                            <small class="text-muted">
                                Pastikan data pribadi Anda akurat dan selalu terbaru.
                            </small>

                        </div>

                        <div class="bg-success-subtle rounded-3 p-2">

                            <i class="bi bi-person-vcard text-success fs-5"></i>

                        </div>

                    </div>


                    {{-- Full Name --}}
                    <div class="mb-4">

                        <label for="name" class="form-label fw-medium">
                            Nama Lengkap
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap Anda"
                            required>

                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Email & Phone --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <label for="email" class="form-label fw-medium">
                                Alamat Email
                            </label>

                            <input
                                class="form-control bg-light"
                                id="email"
                                type="email"
                                value="{{ $user->email }}"
                                disabled>

                            <small class="text-muted">
                                Alamat email Anda tidak dapat diubah.
                            </small>

                        </div>


                        <div class="col-md-6">

                            <label for="no_hp" class="form-label fw-medium">
                                Nomor WhatsApp
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control @error('no_hp') is-invalid @enderror"
                                id="no_hp"
                                type="text"
                                name="no_hp"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="Contoh: 08123456789"
                                required>

                            @error('no_hp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Birth Information --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <label for="tempat_lahir" class="form-label fw-medium">
                                Tempat Lahir
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                id="tempat_lahir"
                                type="text"
                                name="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                placeholder="Masukkan tempat lahir Anda"
                                required>

                            @error('tempat_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label for="tanggal_lahir" class="form-label fw-medium">
                                Tanggal Lahir
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir"
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                required>

                            @error('tanggal_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Gender & Religion --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <label for="jenis_kelamin" class="form-label fw-medium">
                                Jenis Kelamin
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                required>

                                <option value="">
                                    Pilih jenis kelamin
                                </option>

                                <option value="L"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>

                            </select>

                            @error('jenis_kelamin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label for="agama" class="form-label fw-medium">
                                Agama
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="agama"
                                id="agama"
                                class="form-select @error('agama') is-invalid @enderror"
                                required>

                                <option value="">
                                    Pilih agama
                                </option>

                                @foreach ([
                                'Islam',
                                'Kristen',
                                'Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu',
                                'Lainnya'
                                ] as $agama)

                                <option value="{{ $agama }}"
                                    {{ old('agama', $user->agama) == $agama ? 'selected' : '' }}>
                                    {{ $agama }}
                                </option>

                                @endforeach

                            </select>

                            @error('agama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Address --}}
                    <div class="mb-4">

                        <label for="alamat" class="form-label fw-medium">
                            Alamat
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            class="form-control @error('alamat') is-invalid @enderror"
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat Anda saat ini"
                            required>{{ old('alamat', $user->alamat) }}</textarea>

                        @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Professional Information --}}
                    @role('user')

                    {{-- Hidden for regular users --}}

                    @else

                    <div class="border-top pt-4 mt-4">

                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div>

                                <h5 class="fw-semibold mb-1">
                                    Informasi Profesional
                                </h5>

                                <small class="text-muted">
                                    Pengalaman dan keahlian profesional Anda.
                                </small>

                            </div>

                            <div class="bg-warning-subtle rounded-3 p-2">

                                <i class="bi bi-award text-warning fs-5"></i>

                            </div>

                        </div>


                        {{-- Vendor Categories --}}
                        <div class="mb-4">

                            <label for="vendors_categories"
                                class="form-label fw-medium">

                                Kategori Vendor
                                <span class="text-danger">*</span>

                            </label>

                            @php
                            $selectedVendorsCategories = old(
                            'vendors_categories',
                            $user->vendors_categories->pluck('uuid')->toArray()
                            );
                            @endphp

                            <select
                                class="form-control"
                                id="vendors_categories"
                                name="vendors_categories[]"
                                multiple
                                required>

                                @foreach ($vendors_categories as $vendors_category)

                                <option
                                    value="{{ $vendors_category->uuid }}"
                                    {{ in_array($vendors_category->uuid, $selectedVendorsCategories) ? 'selected' : '' }}>

                                    {{ $vendors_category->name }}

                                </option>

                                @endforeach

                            </select>

                            @error('vendors_categories')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Experience --}}
                        <div class="mb-4">

                            <label for="pengalaman"
                                class="form-label fw-medium">

                                Pengalaman
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                class="form-control @error('pengalaman') is-invalid @enderror"
                                id="pengalaman"
                                type="text"
                                name="pengalaman"
                                value="{{ old('pengalaman', $user->pengalaman) }}"
                                placeholder="Contoh: 10 tahun"
                                required>

                            @error('pengalaman')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Biography --}}
                        <div class="mb-3">

                            <label for="biografi"
                                class="form-label fw-medium">

                                Biografi

                            </label>

                            <textarea
                                class="form-control @error('biografi') is-invalid @enderror"
                                id="biografi"
                                name="biografi"
                                rows="4"
                                placeholder="Ceritakan tentang diri Anda...">{{ old('biografi', $user->biografi) }}</textarea>

                            @error('biografi')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>

                    </div>

                    @endrole


                    {{-- Social Media --}}
                    <div class="border-top pt-4 mt-4">

                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div>

                                <h5 class="fw-semibold mb-1">
                                    Media Sosial
                                </h5>

                                <small class="text-muted">
                                    Hubungkan akun media sosial Anda.
                                </small>

                            </div>

                            <div class="bg-info-subtle rounded-3 p-2">

                                <i class="bi bi-share text-info fs-5"></i>

                            </div>

                        </div>


                        <div class="row g-3">

                            {{-- Facebook --}}
                            <div class="col-md-6">

                                <label for="facebook"
                                    class="form-label fw-medium">

                                    Facebook

                                </label>

                                <input
                                    class="form-control"
                                    id="facebook"
                                    type="text"
                                    name="facebook"
                                    value="{{ old('facebook', $user->facebook) }}"
                                    placeholder="Tautan profil Facebook">

                            </div>


                            {{-- Instagram --}}
                            <div class="col-md-6">

                                <label for="instagram"
                                    class="form-label fw-medium">

                                    Instagram

                                </label>

                                <input
                                    class="form-control"
                                    id="instagram"
                                    type="text"
                                    name="instagram"
                                    value="{{ old('instagram', $user->instagram) }}"
                                    placeholder="Tautan profil Instagram">

                            </div>


                            {{-- TikTok --}}
                            <div class="col-md-6">

                                <label for="tiktok"
                                    class="form-label fw-medium">

                                    TikTok

                                </label>

                                <input
                                    class="form-control"
                                    id="tiktok"
                                    type="text"
                                    name="tiktok"
                                    value="{{ old('tiktok', $user->tiktok) }}"
                                    placeholder="Tautan profil TikTok">

                            </div>


                            {{-- YouTube --}}
                            <div class="col-md-6">

                                <label for="youtube"
                                    class="form-label fw-medium">

                                    YouTube

                                </label>

                                <input
                                    class="form-control"
                                    id="youtube"
                                    type="text"
                                    name="youtube"
                                    value="{{ old('youtube', $user->youtube) }}"
                                    placeholder="Tautan kanal YouTube">

                            </div>

                        </div>

                    </div>


                    {{-- Save Button --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">

                        <button
                            type="button"
                            class="btn btn-light border px-4"
                            onclick="window.history.back()">

                            Batal

                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary px-4">

                            <i class="bi bi-check2 me-1"></i>
                            Simpan Perubahan

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>


@include('profile.partials.change-password')

@endsection