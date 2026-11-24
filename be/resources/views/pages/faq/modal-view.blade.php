<div id="modal-form-view-faq-{{ $item->uuid }}" class="modal fade faq-detail-modal" tabindex="-1"
    aria-labelledby="modal-form-view-faq-{{ $item->uuid }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-view-faq-{{ $item->uuid }}-label">Detail FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="faq-detail-meta">
                    <span class="badge bg-light-primary text-primary">{{ $item->kategori }}</span>
                    <span class="badge bg-light">{{ $item->locale === 'id' ? 'Bahasa Indonesia' : 'English' }}</span>
                    <span class="badge {{ $item->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="badge bg-light">Urutan {{ $item->urutan }}</span>
                </div>

                <div class="faq-detail-content">
                    <section class="faq-detail-field">
                        <div class="faq-detail-label">Pertanyaan</div>
                        <p class="faq-detail-question text-break mb-0">{{ $item->pertanyaan }}</p>
                    </section>

                    <section class="faq-detail-field">
                        <div class="faq-detail-label">Jawaban</div>
                        <p class="faq-detail-answer text-break mb-0">{{ $item->jawaban }}</p>
                    </section>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
