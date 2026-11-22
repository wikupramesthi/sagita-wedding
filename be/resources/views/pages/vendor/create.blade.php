@extends('layouts.app')
@section('title', 'Tambah Vendor')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Vendor" page="Vendor" active="Tambah Vendor" route="{{ route('vendor.index') }}" />
@endsection

@php
    $socials = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'twitter' => 'Twitter',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
    ];
    $selectedVendorsCategories = old('vendors_categories', []);
@endphp

<section class="section">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Mohon periksa kembali:</div>
        <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <form action="{{ route('vendor.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom py-3 px-3 d-flex align-items-center gap-2">
                        <i class="bx bx-image-add text-primary fs-5"></i>
                        <h6 class="mb-0">Foto Vendor</h6>
                    </div>
                    <div class="card-body text-center">
                        <div id="avatar-preview" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle border bg-light overflow-hidden"
                            style="width:150px;height:150px;">
                            <i class="bx bx-user-voice text-muted" style="font-size:3rem;"></i>
                        </div>
                        <label for="avatar" class="form-label small fw-semibold">Pilih foto <span class="text-danger">*</span></label>
                        <input type="file" name="avatar" id="avatar"
                            class="form-control form-control-sm @error('avatar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp" required>
                        <div class="form-text small mt-2">Format JPG, PNG, atau WebP. Maksimal 2 MB.</div>
                        @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-3 px-3 d-flex align-items-center gap-2">
                        <i class="bx bx-user-voice text-primary fs-5"></i>
                        <h6 class="mb-0">Informasi Vendor</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-semibold">Nama Vendor <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="Contoh: Dekorasi Sari"
                                    value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" placeholder="vendor@example.com"
                                    value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="vendors_categories" class="form-label small fw-semibold">Kategori Vendor <span class="text-danger">*</span></label>
                                <select id="vendors_categories" name="vendors_categories[]" class="form-select form-select-sm @error('vendors_categories') is-invalid @enderror" size="5" multiple required>
                                    @foreach ($vendors_categories as $vendors_category)
                                    <option value="{{ $vendors_category->uuid }}" {{ in_array($vendors_category->uuid, $selectedVendorsCategories) ? 'selected' : '' }}>
                                        {{ $vendors_category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="form-text small">Tahan Ctrl/Cmd untuk memilih lebih dari satu kategori.</div>
                                @error('vendors_categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pengalaman" class="form-label small fw-semibold">Pengalaman <span class="text-danger">*</span></label>
                                <input type="text" name="pengalaman" id="pengalaman" placeholder="Contoh: 10 tahun"
                                    value="{{ old('pengalaman') }}" class="form-control form-control-sm @error('pengalaman') is-invalid @enderror" required>
                                @error('pengalaman')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="tempat_lahir" class="form-label small">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                            class="form-control form-control-sm @error('tempat_lahir') is-invalid @enderror">
                                        @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir" class="form-label small">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                            class="form-control form-control-sm @error('tanggal_lahir') is-invalid @enderror">
                                        @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-6">
                                        <label for="jenis_kelamin" class="form-label small">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select form-select-sm @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="agama" class="form-label small">Agama</label>
                                        <select name="agama" id="agama" class="form-select form-select-sm @error('agama') is-invalid @enderror">
                                            <option value="">-- Pilih Agama --</option>
                                            @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agama)
                                            <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                            @endforeach
                                        </select>
                                        @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="no_hp" class="form-label small fw-semibold">Nomor HP</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" inputmode="numeric" name="no_hp" id="no_hp" placeholder="08xxxxxxxxxx"
                                        value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror">
                                </div>
                                @error('no_hp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="is_active" class="form-label small fw-semibold">Bergabung pada <span class="text-danger">*</span></label>
                                <input type="date" name="is_active" id="is_active"
                                    class="form-control form-control-sm @error('is_active') is-invalid @enderror"
                                    value="{{ old('is_active', date('Y-m-d')) }}" required>
                                @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="biografi" class="form-label small fw-semibold">Biografi</label>
                                <textarea name="biografi" id="biografi" rows="4" placeholder="Ceritakan secara singkat tentang vendor ini..."
                                    class="form-control form-control-sm @error('biografi') is-invalid @enderror">{{ old('biografi') }}</textarea>
                                @error('biografi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent border-bottom py-3 px-3 d-flex align-items-center gap-2">
                        <i class="bx bx-share-alt text-primary fs-5"></i>
                        <h6 class="mb-0">Media Sosial</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($socials as $key => $label)
                            <div class="col-md-6">
                                <label for="{{ $key }}" class="form-label small fw-semibold">Link {{ $label }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-{{ $key === 'twitter' ? 'twitter' : ($key === 'tiktok' ? 'music-note-play' : $key) }}"></i></span>
                                    <input type="text" name="{{ $key }}" id="{{ $key }}" placeholder="https://..."
                                        value="{{ old($key) }}" class="form-control @error($key) is-invalid @enderror">
                                </div>
                                @error($key)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body py-3 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <div class="small text-muted"><span class="text-danger">*</span> Wajib diisi</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg me-1"></i> Batal</a>
                    <button type="submit" class="btn btn-sm btn-primary px-4"><i class="bi bi-check2-circle me-1"></i> Simpan Vendor</button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    let avatarObjectUrl = null;
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            if (avatarObjectUrl) URL.revokeObjectURL(avatarObjectUrl);
            avatarObjectUrl = URL.createObjectURL(this.files[0]);
            avatarPreview.innerHTML = '<img src="' + avatarObjectUrl + '" alt="Pratinjau foto vendor" class="w-100 h-100" style="object-fit:cover;">';
        });
    }
</script>
@endsection
