<div id="modal-form-edit-faq-{{ $item->uuid }}" class="modal fade" tabindex="-1"
    aria-labelledby="modal-form-edit-faq-{{ $item->uuid }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('faq.update', $item->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-faq-{{ $item->uuid }}-label">Ubah FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="faq-pertanyaan-{{ $item->uuid }}" class="form-label mb-1">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" id="faq-pertanyaan-{{ $item->uuid }}" name="pertanyaan"
                                class="form-control @error('pertanyaan') is-invalid @enderror"
                                value="{{ old('pertanyaan', $item->pertanyaan) }}" maxlength="255" required>
                            @error('pertanyaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="faq-kategori-{{ $item->uuid }}" class="form-label mb-1">Kategori <span class="text-danger">*</span></label>
                            <select id="faq-kategori-{{ $item->uuid }}" name="kategori"
                                class="form-select @error('kategori') is-invalid @enderror" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(old('kategori', $item->kategori) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="faq-locale-{{ $item->uuid }}" class="form-label mb-1">Bahasa <span class="text-danger">*</span></label>
                            <select id="faq-locale-{{ $item->uuid }}" name="locale"
                                class="form-select @error('locale') is-invalid @enderror" required>
                                <option value="id" @selected(old('locale', $item->locale) === 'id')>Bahasa Indonesia</option>
                                <option value="en" @selected(old('locale', $item->locale) === 'en')>English</option>
                            </select>
                            @error('locale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label for="faq-urutan-{{ $item->uuid }}" class="form-label mb-1">Urutan <span class="text-danger">*</span></label>
                            <input type="number" id="faq-urutan-{{ $item->uuid }}" name="urutan" min="1"
                                class="form-control @error('urutan') is-invalid @enderror"
                                value="{{ old('urutan', $item->urutan) }}" required>
                            @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label for="faq-status-{{ $item->uuid }}" class="form-label mb-1">Status <span class="text-danger">*</span></label>
                            <select id="faq-status-{{ $item->uuid }}" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected(old('status', $item->status) === 'active')>Aktif</option>
                                <option value="inactive" @selected(old('status', $item->status) === 'inactive')>Nonaktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="faq-jawaban-{{ $item->uuid }}" class="form-label mb-1">Jawaban <span class="text-danger">*</span></label>
                            <textarea id="faq-jawaban-{{ $item->uuid }}" name="jawaban" rows="5"
                                class="form-control @error('jawaban') is-invalid @enderror" required>{{ old('jawaban', $item->jawaban) }}</textarea>
                            @error('jawaban')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
