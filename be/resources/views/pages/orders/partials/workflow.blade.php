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
            @endforeach
        </div>
    </div>
@endif

@if ($order->checklistItems->isNotEmpty())
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
                <div class="d-flex align-items-start gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    @if ($isAdmin)
                        <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} mt-1"></i>
                    @else
                        <form method="POST" action="{{ route('orders.checklist.update', [$order->uuid, $item->uuid]) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $item->status === 'completed' ? 'pending' : 'completed' }}">
                            <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" aria-label="{{ $item->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                            </button>
                        </form>
                    @endif
                    <div class="flex-grow-1">
                        <div class="small fw-semibold {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</div>
                        @if ($item->description)<div class="small text-muted">{{ $item->description }}</div>@endif
                        @if ($item->due_at)<div class="small text-muted">Target {{ $item->due_at->translatedFormat('d M Y') }}</div>@endif
                    </div>
                    @if ($item->status === 'completed')<span class="badge text-bg-success">Selesai</span>@endif
                </div>
            @endforeach
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
