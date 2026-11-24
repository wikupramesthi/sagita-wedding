<!-- Modal ubah data -->
<div id="modal-form-edit-testimonial-{{ $item->uuid }}" class="modal fade modal-form-testimonial-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-testimonial-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('testimonial.update', $item->uuid) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-testimonial-{{ $item->uuid }}-label">Ubah Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"> </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="foto-{{ $item->uuid }}" class="mb-2">Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto-{{ $item->uuid }}"
                            placeholder="Foto" name="foto" value="{{ old('foto') }}" accept="image/*">
                        @error('foto')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        @if ($item->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->foto))
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="img-thumbnail mt-2"
                            style="max-width: 150px;">
                        @elseif ($item->foto)
                        <div class="small text-muted mt-2">Foto saat ini tidak tersedia.</div>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama-{{ $item->uuid }}" class="mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama-{{ $item->uuid }}"
                            placeholder="Nama Lengkap" name="nama" value="{{ old('nama', $item->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="jabatan-{{ $item->uuid }}" class="mb-2">Bergabung Sejak <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan-{{ $item->uuid }}"
                            placeholder="Bergabung Sejak" name="jabatan"
                            value="{{ old('jabatan', $item->jabatan) }}">
                        @error('jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="isi_testimoni-{{ $item->uuid }}" class="mb-2">Isi Testimoni <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('isi_testimoni') is-invalid @enderror" id="isi_testimoni-{{ $item->uuid }}"
                            placeholder="Isi Testimoni" name="isi_testimoni" required>{{ old('isi_testimoni', $item->isi_testimoni) }}</textarea>
                        @error('isi_testimoni')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="urutan-{{ $item->uuid }}" class="mb-2">Urutan <span class="text-danger">*Pastikan urutan bersifat unik</span></label>
                        <input type="number" class="form-control @error('urutan') is-invalid @enderror" id="urutan-{{ $item->uuid }}"
                            placeholder="Urutan" name="urutan" value="{{ old('urutan', $item->urutan) }}" required>
                        @error('urutan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="is_active-{{ $item->uuid }}" class="mb-2">Status <span class="text-danger">*</span></label>
                        <select name="is_active" id="is_active-{{ $item->uuid }}"
                            class="form-control @error('is_active') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="active"
                                {{ (isset($item) && $item->is_active == 'active') || old('is_active') == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ (isset($item) && $item->is_active == 'inactive') || old('is_active') == 'inactive' ? 'selected' : '' }}>
                                Nonaktif</option>
                        </select>
                        @error('is_active')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ">Perbarui</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->