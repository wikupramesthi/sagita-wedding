@extends('layouts.app')
@section('title', 'Ubah Artikel')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Ubah Artikel" page="Artikel" active="Ubah Artikel" route="{{ route('articles.index') }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-body">

            <form action="{{ route('articles.update', $article->uuid) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="title" class="mb-2">Judul  <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="excerpt" class="mb-2">Ringkasan</label>
                    <input type="text" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
                        value="{{ old('excerpt', $article->excerpt) }}">
                    @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="category_id" class="mb-2">Kategori <span class='text-danger'>*</span></label>
                    <select name="category_uuid" id="category_id"
                        class="form-control @error('category_uuid') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->uuid }}"
                                data-name="{{ strtolower(str_replace(' ', '-', $category->name)) }}"
                                {{ old('category_uuid', $article->category_uuid ?? '') == $category->uuid ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_uuid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3" id="videoForm" style="display: none;">
                    <label for="video" class="mb-2">Tautan YouTube <span class="text-danger">*Contoh: PlNOD--gPQU</span></label>
                    <input type="text" name="video" id="video" class="form-control"
                        value="{{ old('video', $article->video ?? '') }}" placeholder="Masukkan tautan YouTube">
                </div>

                <div class="form-group mb-3">
                    <label for="content" class="mb-2">Konten  <span class='text-danger'>*</span></label>
                    <textarea name="content" id="deskripsi" cols="30" rows="5"
                        class="form-control @error('content') is-invalid @enderror">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="featured_image" class="mb-2">Gambar Utama <span class="text-danger">*Ukuran file maksimal: 2 MB</span></label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                        class="form-control @error('featured_image') is-invalid @enderror">
                    @if ($article->featured_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="Gambar Utama"
                                width="150">
                        </div>
                    @endif
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="tagging" class="mb-2">Tag <span class="text-danger"> *Pisahkan tag dengan koma, mis. pernikahan, dekorasi, catering</span></label>
                    <input type="text" name="tagging" class="form-control @error('tagging') is-invalid @enderror"
                        value="{{ old('tagging', $article->tagging) }}"
                        placeholder="Pisahkan dengan koma, mis.: slb patriot, berita, kota bekasi">
                    @error('tagging')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="status" class="mb-2">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="published"
                            {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Diterbitkan</option>
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draf
                        </option>
                        <option value="scheduled"
                            {{ old('status', $article->status) == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="search_engine" class="mb-2">Mesin Pencari <span class="text-danger">*</span></label>
                    <select name="search_engine" class="form-control @error('search_engine') is-invalid @enderror">
                        <option value="index"
                            {{ old('search_engine', $article->search_engine) == 'index' ? 'selected' : '' }}>Indeks
                        </option>
                        <option value="noindex"
                            {{ old('search_engine', $article->search_engine) == 'noindex' ? 'selected' : '' }}>Tanpa Indeks
                        </option>
                    </select>
                    @error('search_engine')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="scheduled_at" class="mb-2">Tanggal Terbit <span
                            class="text-danger">*</span></label>
                    <input type="date" name="scheduled_at"
                        class="form-control @error('scheduled_at') is-invalid @enderror"
                        value="{{ old('scheduled_at', optional($article->scheduled_at)->format('Y-m-d')) }}" required>
                    @error('scheduled_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-right mt-4">
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button class="btn btn-danger">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const videoForm = document.getElementById('videoForm');
        const videoInput = document.getElementById('video');

        function toggleVideoForm() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const categoryName = selectedOption?.getAttribute('data-name')?.toLowerCase() || '';

            if (categoryName.includes('video-galeri')) {
                videoForm.style.display = 'block';
                videoInput.required = true;
            } else {
                videoForm.style.display = 'none';
                videoInput.required = false;
                videoInput.value = '';
            }
        }

        categorySelect.addEventListener('change', toggleVideoForm);
        toggleVideoForm();
    });
</script>

@endsection