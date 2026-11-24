<div id="modal-form-add-faq" class="modal fade" tabindex="-1" aria-labelledby="modal-form-add-faq-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('faq.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-add-faq-label">Tambah FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if ($draftQuestion !== '')
                    <div class="alert alert-info small" role="status">
                        Isi jawaban berdasarkan kebijakan Sagita yang telah diverifikasi sebelum mengaktifkan FAQ ini.
                    </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="faq-pertanyaan-create" class="form-label mb-1">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" id="faq-pertanyaan-create" name="pertanyaan"
                                class="form-control @error('pertanyaan') is-invalid @enderror"
                                value="{{ old('pertanyaan', $draftQuestion) }}" placeholder="Contoh: Bagaimana cara memesan paket?"
                                maxlength="255" required>
                            @error('pertanyaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="faq-kategori-create" class="form-label mb-1">Kategori <span class="text-danger">*</span></label>
                            <select id="faq-kategori-create" name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(old('kategori', $defaultKategori) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="faq-locale-create" class="form-label mb-1">Bahasa <span class="text-danger">*</span></label>
                            <select id="faq-locale-create" name="locale" class="form-select @error('locale') is-invalid @enderror" required>
                                <option value="id" @selected(old('locale', $locale) === 'id')>Bahasa Indonesia</option>
                                <option value="en" @selected(old('locale', $locale) === 'en')>English</option>
                            </select>
                            @error('locale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label for="faq-urutan-create" class="form-label mb-1">Urutan <span class="text-danger">*</span></label>
                            <input type="number" id="faq-urutan-create" name="urutan" min="1"
                                class="form-control @error('urutan') is-invalid @enderror"
                                value="{{ old('urutan', 1) }}" required>
                            @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label for="faq-status-create" class="form-label mb-1">Status <span class="text-danger">*</span></label>
                            <select id="faq-status-create" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected(old('status', $draftQuestion !== '' ? 'inactive' : 'active') === 'active')>Aktif</option>
                                <option value="inactive" @selected(old('status', $draftQuestion !== '' ? 'inactive' : 'active') === 'inactive')>Nonaktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="faq-jawaban-create" class="form-label mb-1">Jawaban <span class="text-danger">*</span></label>
                            <textarea id="faq-jawaban-create" name="jawaban" rows="5"
                                class="form-control @error('jawaban') is-invalid @enderror"
                                placeholder="Tulis jawaban yang jelas dan singkat..." required>{{ old('jawaban') }}</textarea>
                            @error('jawaban')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
