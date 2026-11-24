@if (session('success'))
    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
@endif

@if ($order->documents->isNotEmpty())
    @php
        $documentLabels = ['contract' => 'Kontrak', 'invoice' => 'Invoice', 'receipt' => 'Tanda Terima'];
    @endphp
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-3' : 'card-header bg-white fw-semibold' }}">Kontrak & dokumen pembayaran</div>
        <div class="{{ $compact ? '' : 'card-body' }} d-flex flex-wrap gap-2">
            @foreach ($order->documents as $document)
                @php
                    $documentLabel = $documentLabels[$document->type] ?? ucfirst((string) $document->type);
                @endphp
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-primary"
                        href="{{ route('orders.documents.preview', [$order->uuid, $document->uuid]) }}"
                        target="_blank"
                        rel="noopener">
                        <i class="bi bi-file-earmark-pdf me-1"></i>{{ $documentLabel }}
                        <span class="small text-muted">· {{ $document->issued_at?->format('d M Y') }}</span>
                    </a>
                    <a class="btn btn-outline-primary"
                        href="{{ route('orders.documents.download', [$order->uuid, $document->uuid]) }}"
                        target="_blank"
                        rel="noopener"
                        aria-label="Unduh {{ $documentLabel }}">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                @if ($document->type === 'contract')
                    @if ($document->accepted_at)
                        <div class="alert alert-success w-100 mb-0 py-2 small">
                            <i class="bi bi-check-circle me-1"></i>
                            @if ($isAdmin)
                                Pelanggan menyetujui kontrak{{ $document->acceptedBy?->name ? ' ('.$document->acceptedBy->name.')' : '' }}
                                pada {{ $document->accepted_at->translatedFormat('d F Y, H:i') }}.
                            @else
                                Kontrak disetujui pada {{ $document->accepted_at->translatedFormat('d F Y, H:i') }}.
                                Salinan versi yang disetujui telah tersimpan. Catatan ini bukan tanda tangan digital tersertifikasi.
                            @endif
                        </div>
                    @elseif (! $isAdmin)
                        <form method="POST" action="{{ route('orders.documents.accept', [$order->uuid, $document->uuid]) }}" class="w-100 border rounded-3 p-3">
                            @csrf
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="agreement" value="1" id="contract-agreement-{{ $document->uuid }}" required>
                                <label class="form-check-label small" for="contract-agreement-{{ $document->uuid }}">
                                    Saya telah membaca dan menyetujui isi kontrak layanan ini.
                                </label>
                            </div>
                            <div class="small text-muted mb-2">
                                Persetujuan dan salinan kontrak yang disetujui akan dicatat pada pesanan ini.
                                Catatan ini bukan tanda tangan digital tersertifikasi.
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>Setujui kontrak
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning w-100 mb-0 py-2 small">
                            <i class="bi bi-hourglass-split me-1"></i>Menunggu persetujuan kontrak dari pelanggan.
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
@endif

@if (! $isAdmin && in_array($order->status, ['partially_paid', 'paid'], true) && $order->event_date && $order->event_date->lt(today()))
    @php
        $feedback = $order->feedback;
    @endphp
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-3' : 'card-header bg-white fw-semibold' }}">Bagaimana pengalaman Anda?</div>
        <div class="{{ $compact ? '' : 'card-body' }}">
            <p class="small text-muted">Setelah acara selesai, masukan Anda membantu kami meningkatkan layanan berikutnya.</p>
            <form method="POST" action="{{ route('orders.feedback.store', $order->uuid) }}" class="d-grid gap-2">
                @csrf
                <label class="form-label small fw-semibold" for="feedback-rating-{{ $order->uuid }}">Penilaian</label>
                <select class="form-select form-select-sm" id="feedback-rating-{{ $order->uuid }}" name="rating" required>
                    <option value="">Pilih penilaian</option>
                    @foreach ([5 => 'Sangat puas', 4 => 'Puas', 3 => 'Cukup puas', 2 => 'Kurang puas', 1 => 'Tidak puas'] as $rating => $label)
                        <option value="{{ $rating }}" @selected((string) old('rating', $feedback?->rating) === (string) $rating)>{{ $rating }} — {{ $label }}</option>
                    @endforeach
                </select>
                <label class="form-label small fw-semibold" for="feedback-comment-{{ $order->uuid }}">Catatan (opsional)</label>
                <textarea class="form-control form-control-sm" id="feedback-comment-{{ $order->uuid }}" name="comment" rows="3" maxlength="2000" placeholder="Ceritakan hal yang berjalan baik atau yang perlu ditingkatkan.">{{ old('comment', $feedback?->comment) }}</textarea>
                @if ($feedback)
                    <div class="small text-muted">Penilaian terakhir dikirim {{ $feedback->submitted_at?->translatedFormat('d F Y, H:i') }}. Anda masih dapat memperbaruinya.</div>
                @endif
                <button type="submit" class="btn btn-sm btn-primary justify-self-start">
                    <i class="bi bi-send me-1"></i>{{ $feedback ? 'Perbarui masukan' : 'Kirim masukan' }}
                </button>
            </form>
        </div>
    </div>
@endif

@if ($isAdmin && $order->feedback)
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-3' : 'card-header bg-white fw-semibold' }}">Masukan pelanggan</div>
        <div class="{{ $compact ? '' : 'card-body' }}">
            <div class="fw-semibold">{{ $order->feedback->rating }} / 5</div>
            <div class="small text-muted mb-2">Dikirim {{ $order->feedback->submitted_at?->translatedFormat('d F Y, H:i') }}</div>
            <div class="text-break">{{ $order->feedback->comment ?: 'Tidak ada catatan tambahan.' }}</div>
        </div>
    </div>
@endif

@php
    $checklistDone = $order->checklistItems->where('status', 'completed')->count();
    $checklistTotal = $order->checklistItems->count();
@endphp
<div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
    <div class="{{ $compact ? '' : 'card-header bg-white' }}">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div class="fw-semibold">Checklist persiapan</div>
            <span class="badge text-bg-light">{{ $checklistDone }}/{{ $checklistTotal }} selesai</span>
        </div>
    </div>
    <div class="{{ $compact ? 'mt-3' : 'card-body' }}">
            @foreach ($order->checklistItems as $item)
                <div class="py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    @if ($isAdmin)
                        <div class="d-flex align-items-start gap-2">
                            <form method="POST" action="{{ route('orders.checklist.update', [$order->uuid, $item->uuid]) }}" class="flex-grow-1">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12 col-md-5">
                                        <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" maxlength="255" required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="date" name="due_at" class="form-control form-control-sm" value="{{ optional($item->due_at)->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" @selected($item->status === 'pending')>Pending</option>
                                            <option value="completed" @selected($item->status === 'completed')>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary" aria-label="Simpan checklist">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                        <button type="submit" form="checklist-delete-{{ $item->uuid }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus checklist ini?')" aria-label="Hapus checklist">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </div>
                                @if ($item->description)<div class="small text-muted mt-1">{{ $item->description }}</div>@endif
                            </form>
                            <form id="checklist-delete-{{ $item->uuid }}" method="POST" action="{{ route('orders.checklist.destroy', [$order->uuid, $item->uuid]) }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @else
                        <div class="d-flex align-items-start gap-2">
                            <form method="POST" action="{{ route('orders.checklist.update', [$order->uuid, $item->uuid]) }}">
                                @csrf
                                <input type="hidden" name="status" value="{{ $item->status === 'completed' ? 'pending' : 'completed' }}">
                                <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" aria-label="{{ $item->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                    <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                </button>
                            </form>
                            <div class="flex-grow-1">
                                <div class="small fw-semibold {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</div>
                                @if ($item->description)<div class="small text-muted">{{ $item->description }}</div>@endif
                                @if ($item->due_at)<div class="small text-muted">Target {{ $item->due_at->translatedFormat('d M Y') }}</div>@endif
                            </div>
                            @if ($item->status === 'completed')<span class="badge text-bg-success">Selesai</span>@endif
                        </div>
                    @endif
                </div>
            @endforeach
        @if ($checklistTotal === 0)
            <div class="small text-muted">Belum ada tugas persiapan untuk pesanan ini.</div>
        @endif
    </div>
</div>

@if ($isAdmin)
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-3' : 'card-header bg-white fw-semibold' }}">Tambah checklist persiapan</div>
        <div class="{{ $compact ? '' : 'card-body' }}">
            <form method="POST" action="{{ route('orders.checklist.store', $order->uuid) }}">
                @csrf
                <div class="row g-2">
                    <div class="col-12 col-md-5">
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Nama checklist" maxlength="255" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="due_at" class="form-control form-control-sm" aria-label="Target checklist">
                    </div>
                    <div class="col-12 col-md-2">
                        <textarea name="description" class="form-control form-control-sm" rows="1" placeholder="Catatan"></textarea>
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

@if ($order->changeRequests->isNotEmpty())
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-2' : 'card-header bg-white fw-semibold' }}">Riwayat permintaan perubahan</div>
        <div class="{{ $compact ? '' : 'card-body' }}">
            @foreach ($order->changeRequests as $change)
                <div class="d-flex justify-content-between gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="small">
                        @if ($change->change_type === 'event_date')
                            Ubah tanggal ke {{ $change->requested_event_date?->translatedFormat('d M Y') }}
                        @else
                            Tambah {{ $change->addon?->name ?? 'Add-on' }} ×{{ $change->addon_quantity }}
                        @endif
                        <div class="text-muted">{{ $change->created_at?->translatedFormat('d M Y') }}@if($change->admin_note) · {{ $change->admin_note }}@endif</div>
                    </div>
                    <span class="badge {{ $change->status === 'pending' ? 'text-bg-warning' : ($change->status === 'approved' ? 'text-bg-success' : 'text-bg-secondary') }} align-self-start">{{ ucfirst($change->status) }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if (!$isAdmin && $order->event_date && !$order->event_date->isPast() && in_array($order->status, ['pending', 'partially_paid', 'paid'], true))
    @php($hasPendingChange = $order->changeRequests->contains('status', 'pending'))
    <div class="{{ $compact ? 'app-card p-3 mt-3' : 'card border-0 shadow-sm mb-4' }}">
        <div class="{{ $compact ? 'h6 fw-semibold mb-2' : 'card-header bg-white fw-semibold' }}">Ajukan perubahan pesanan</div>
        <div class="{{ $compact ? '' : 'card-body' }}">
            @if ($hasPendingChange)
                <div class="alert alert-warning small mb-0">Permintaan sebelumnya masih menunggu persetujuan admin.</div>
            @else
                <form method="POST" action="{{ route('orders.change-requests.store', $order->uuid) }}" class="d-grid gap-2">
                    @csrf
                    <label class="small fw-semibold" for="change-type-{{ $order->uuid }}">Jenis perubahan</label>
                    <select class="form-select form-select-sm" id="change-type-{{ $order->uuid }}" name="change_type" required>
                        <option value="event_date">Ubah tanggal acara</option>
                        @if ($availableAddons->isNotEmpty())<option value="addon">Tambah layanan tambahan</option>@endif
                    </select>
                    <div class="change-date-fields">
                        <label class="small" for="requested-date-{{ $order->uuid }}">Tanggal baru</label>
                        <input type="date" id="requested-date-{{ $order->uuid }}" class="form-control form-control-sm" name="requested_event_date" min="{{ today()->addDay()->toDateString() }}" value="{{ old('requested_event_date') }}">
                    </div>
                    @if ($availableAddons->isNotEmpty())
                        <div class="change-addon-fields d-none">
                            <label class="small" for="addon-{{ $order->uuid }}">Layanan tambahan</label>
                            <select class="form-select form-select-sm mb-2" id="addon-{{ $order->uuid }}" name="addon_uuid">
                                @foreach ($availableAddons as $addon)
                                    <option value="{{ $addon->uuid }}">{{ $addon->name }} · Rp {{ number_format((int) $addon->final_price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                            <label class="small" for="addon-quantity-{{ $order->uuid }}">Jumlah tambahan</label>
                            <input type="number" id="addon-quantity-{{ $order->uuid }}" class="form-control form-control-sm" name="addon_quantity" min="1" max="99" value="1">
                            <div class="form-text">Harga akhir akan dihitung ulang oleh admin saat persetujuan.</div>
                        </div>
                    @else
                        <div class="change-addon-fields d-none small text-muted">Tidak ada add-on aktif untuk paket ini.</div>
                    @endif
                    <label class="small" for="customer-note-{{ $order->uuid }}">Catatan (opsional)</label>
                    <textarea id="customer-note-{{ $order->uuid }}" class="form-control form-control-sm" name="customer_note" rows="2" maxlength="1000"></textarea>
                    <button class="btn btn-sm btn-primary"><i class="bi bi-send me-1"></i>Kirim permintaan</button>
                </form>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const type = document.getElementById('change-type-{{ $order->uuid }}');
            if (!type) return;
            const dateFields = type.closest('form').querySelector('.change-date-fields');
            const addonFields = type.closest('form').querySelector('.change-addon-fields');
            const sync = () => {
                const isAddon = type.value === 'addon';
                dateFields.classList.toggle('d-none', isAddon);
                addonFields.classList.toggle('d-none', !isAddon);
                dateFields.querySelector('input').required = !isAddon;
                addonFields.querySelectorAll('select,input').forEach(input => input.required = isAddon);
            };
            type.addEventListener('change', sync);
            sync();
        });
    </script>
@endif
