<div id="modal-form-view-faq-{{ $item->uuid }}" class="modal fade" tabindex="-1"
    aria-labelledby="modal-form-view-faq-{{ $item->uuid }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-view-faq-{{ $item->uuid }}-label">Detail FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light-primary text-primary">{{ $item->kategori }}</span>
                    <span class="badge {{ $item->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="badge bg-light">Urutan {{ $item->urutan }}</span>
                </div>

                <div class="mb-3">
                    <div class="small text-muted mb-1">Pertanyaan</div>
                    <div class="fw-semibold text-break">{{ $item->pertanyaan }}</div>
                </div>

                <div>
                    <div class="small text-muted mb-1">Jawaban</div>
                    <div class="text-break" style="white-space: pre-line">{{ $item->jawaban }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
