@extends('layouts.app')

@section('title', $draft ? 'Ubah Draf Penawaran' : ($previous ? 'Buat Revisi Penawaran' : 'Buat Penawaran Khusus'))

@section('content')
@php
    $formItems = old('items', $defaultItems);
    if ($formItems === []) {
        $formItems = [['name' => '', 'description' => '', 'quantity' => 1, 'unit_price' => 0]];
    }
    $selectedUserUuid = old('user_uuid', $draft?->user_uuid ?? $previous?->user_uuid ?? $sourceRequest?->user_uuid);
    $selectedBaseOptionUuid = old('based_option_uuid', $draft?->based_package_option_uuid ?? $previous?->based_package_option_uuid ?? $selectedOption?->uuid);
    $linkedRecipient = $draft || $previous || $sourceRequest;
    $initialTitle = $draft?->title ?? $previous?->title ?? ($selectedOption ? $selectedOption->package->name.' — '.$selectedOption->name : ($sourceRequest ? 'Penawaran khusus — '.$sourceRequest->user->name : ''));
@endphp
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $draft ? 'Ubah Draf Penawaran' : ($previous ? 'Buat Revisi Penawaran' : 'Buat Penawaran Khusus') }}</h4>
            <p class="text-muted mb-0">
                Draf hanya terlihat oleh admin. Pelanggan baru menerima penawaran setelah Anda meninjau dan mengirimnya.
            </p>
        </div>
        <a href="{{ route('package-offers.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <form method="POST" action="{{ $draft ? route('package-offers.update', $draft->uuid) : ($previous ? route('package-offers.revise', $previous->uuid) : route('package-offers.store')) }}" id="custom-offer-form">
        @csrf
        @if ($draft)
        @method('PUT')
        @endif
        @if ($linkedRecipient)
        <input type="hidden" name="user_uuid" value="{{ $selectedUserUuid }}">
        @endif
        @if ($sourceRequest)
        <input type="hidden" name="source_request_uuid" value="{{ $sourceRequest->uuid }}">
        @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white fw-semibold">Penerima dan acara</div>
                    <div class="card-body">
                        @if ($linkedRecipient)
                        @if ($previous)
                        <input type="hidden" name="previous_offer_uuid" value="{{ $previous->uuid }}">
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Pengguna tujuan</label>
                            <div class="form-control bg-light">{{ $draft?->user?->name ?? $previous?->user?->name ?? $sourceRequest?->user?->name }} · {{ $draft?->user?->email ?? $previous?->user?->email ?? $sourceRequest?->user?->email }}</div>
                        </div>
                        @else
                        <div class="mb-3">
                            <label for="user_uuid" class="form-label">Pilih pengguna <span class="text-danger">*</span></label>
                            <select id="user_uuid" name="user_uuid" class="form-select @error('user_uuid') is-invalid @enderror" required>
                                <option value="">Pilih pengguna</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->uuid }}" @selected($selectedUserUuid === $user->uuid)>{{ $user->name }} · {{ $user->email }}</option>
                                @endforeach
                            </select>
                            @error('user_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="based_option_uuid" class="form-label">Paket dasar <span class="text-muted">(opsional)</span></label>
                            <select id="based_option_uuid" name="based_option_uuid" class="form-select @error('based_option_uuid') is-invalid @enderror">
                                <option value="">Mulai dari kebutuhan kosong</option>
                                @foreach ($baseOptions as $option)
                                @php
                                $optionFinalPrice = $option->discount_price !== null && $option->discount_price < $option->price ? $option->discount_price : $option->price;
                                @endphp
                                <option
                                    value="{{ $option->uuid }}"
                                    data-item-name="{{ $option->package->name }} — {{ $option->name }}"
                                    data-item-description="{{ $option->package->description }}"
                                    data-item-price="{{ $optionFinalPrice }}"
                                    data-title="{{ $option->package->name }} — {{ $option->name }}"
                                    @selected($selectedBaseOptionUuid === $option->uuid)>
                                    {{ $option->package->name }} — {{ $option->name }} (Rp {{ number_format($optionFinalPrice, 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                            @error('based_option_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Pilih opsi untuk mengisi satu item awal. Anda tetap dapat mengubah atau menambah layanan.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="title" class="form-label">Nama penawaran <span class="text-danger">*</span></label>
                                <input id="title" name="title" value="{{ old('title', $initialTitle) }}" class="form-control @error('title') is-invalid @enderror" maxlength="255" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="event_date" class="form-label">Tanggal acara <span class="text-danger">*</span></label>
                                <input id="event_date" type="date" name="event_date" value="{{ old('event_date', $draft?->event_date?->toDateString() ?? $previous?->event_date?->toDateString() ?? $sourceRequest?->event_date?->toDateString()) }}" min="{{ now()->toDateString() }}" class="form-control @error('event_date') is-invalid @enderror" required>
                                @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="guest_count" class="form-label">Jumlah tamu</label>
                                <input id="guest_count" type="number" name="guest_count" min="1" value="{{ old('guest_count', $draft?->guest_count ?? $previous?->guest_count ?? $sourceRequest?->guest_count) }}" class="form-control @error('guest_count') is-invalid @enderror">
                                @error('guest_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="venue_note" class="form-label">Lokasi acara</label>
                                <input id="venue_note" name="venue_note" value="{{ old('venue_note', $draft?->venue_note ?? $previous?->venue_note ?? $sourceRequest?->venue_note) }}" class="form-control @error('venue_note') is-invalid @enderror" maxlength="1000">
                                @error('venue_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Ringkasan kebutuhan dan penawaran</label>
                                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="5000">{{ old('description', $draft?->description ?? $previous?->description ?? $sourceRequest?->request_details) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="customer_note" class="form-label">Catatan untuk pelanggan</label>
                                <textarea id="customer_note" name="customer_note" rows="2" class="form-control @error('customer_note') is-invalid @enderror" maxlength="5000">{{ old('customer_note', $draft?->customer_note ?? $previous?->customer_note) }}</textarea>
                                @error('customer_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="admin_note" class="form-label">Catatan internal admin</label>
                                <textarea id="admin_note" name="admin_note" rows="2" class="form-control @error('admin_note') is-invalid @enderror" maxlength="5000">{{ old('admin_note', $draft?->admin_note ?? $previous?->admin_note) }}</textarea>
                                @error('admin_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @if ($previous?->revision_note)
                            <div class="col-12">
                                <div class="alert alert-info mb-0"><strong>Permintaan revisi pelanggan:</strong> {{ $previous->revision_note }}</div>
                            </div>
                            @endif
                            @if ($sourceRequest)
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <strong>Permintaan pelanggan:</strong> {{ $sourceRequest->request_details }}
                                    @if ($sourceRequest->budget_min !== null || $sourceRequest->budget_max !== null)
                                    <div class="mt-1">Anggaran:
                                        @if ($sourceRequest->budget_min !== null && $sourceRequest->budget_max !== null)
                                        Rp {{ number_format($sourceRequest->budget_min, 0, ',', '.') }} – Rp {{ number_format($sourceRequest->budget_max, 0, ',', '.') }}
                                        @elseif ($sourceRequest->budget_min !== null)
                                        mulai Rp {{ number_format($sourceRequest->budget_min, 0, ',', '.') }}
                                        @else
                                        hingga Rp {{ number_format($sourceRequest->budget_max, 0, ',', '.') }}
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2">
                        <span class="fw-semibold">Rincian layanan</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-offer-item"><i class="bi bi-plus-lg me-1"></i>Tambah layanan</button>
                    </div>
                    <div class="card-body">
                        @error('items')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        <div id="offer-items" class="vstack gap-3">
                            @foreach ($formItems as $index => $item)
                            <div class="border rounded-3 p-3 offer-item-row">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Layanan <span class="offer-item-number">{{ $loop->iteration }}</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-offer-item" aria-label="Hapus layanan"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama layanan</label>
                                        <input name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" class="form-control @error("items.$index.name") is-invalid @enderror" maxlength="255" required>
                                        @error("items.$index.name")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" min="1" max="999" class="form-control @error("items.$index.quantity") is-invalid @enderror" required>
                                        @error("items.$index.quantity")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Harga satuan (Rp)</label>
                                        <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] ?? 0 }}" min="0" class="form-control offer-item-price @error("items.$index.unit_price") is-invalid @enderror" required>
                                        @error("items.$index.unit_price")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Keterangan layanan</label>
                                        <textarea name="items[{{ $index }}][description]" rows="2" class="form-control" maxlength="2000">{{ $item['description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <template id="offer-item-template">
                            <div class="border rounded-3 p-3 offer-item-row">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Layanan <span class="offer-item-number"></span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-offer-item" aria-label="Hapus layanan"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama layanan</label>
                                        <input name="items[__INDEX__][name]" class="form-control" maxlength="255" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="items[__INDEX__][quantity]" value="1" min="1" max="999" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Harga satuan (Rp)</label>
                                        <input type="number" name="items[__INDEX__][unit_price]" value="0" min="0" class="form-control offer-item-price" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Keterangan layanan</label>
                                        <textarea name="items[__INDEX__][description]" rows="2" class="form-control" maxlength="2000"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 1rem;">
                    <div class="card-header bg-white fw-semibold">Harga dan ketentuan</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Diskon khusus (Rp)</label>
                            <input id="discount_amount" type="number" name="discount_amount" min="0" value="{{ old('discount_amount', $draft?->discount_amount ?? $previous?->discount_amount ?? 0) }}" class="form-control @error('discount_amount') is-invalid @enderror">
                            @error('discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="deposit_percentage" class="form-label">DP awal (%)</label>
                            <input id="deposit_percentage" type="number" name="deposit_percentage" min="1" max="100" value="{{ old('deposit_percentage', $draft?->deposit_percentage ?? $previous?->deposit_percentage ?? 30) }}" class="form-control @error('deposit_percentage') is-invalid @enderror" required>
                            @error('deposit_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="payment_terms_note" class="form-label">Ketentuan pembayaran</label>
                            <textarea id="payment_terms_note" name="payment_terms_note" rows="3" class="form-control @error('payment_terms_note') is-invalid @enderror" maxlength="5000">{{ old('payment_terms_note', $draft?->payment_terms_note ?? $previous?->payment_terms_note) }}</textarea>
                            @error('payment_terms_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="border-top pt-3 mb-3 d-flex justify-content-between fw-semibold">
                            <span>Perkiraan total</span>
                            <span id="offer-total">Rp 0</span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i>{{ $draft ? 'Simpan Perubahan Draf' : 'Simpan Draf' }}
                        </button>
                        <p class="small text-muted mt-2 mb-0">Pelanggan belum menerima notifikasi. Setelah disimpan, tinjau pratinjau dan pilih tombol kirim.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(() => {
    const container = document.getElementById('offer-items');
    const template = document.getElementById('offer-item-template');
    const baseOption = document.getElementById('based_option_uuid');
    const titleInput = document.getElementById('title');
    const discountInput = document.getElementById('discount_amount');
    const totalNode = document.getElementById('offer-total');

    const refresh = () => {
        const subtotal = [...container.querySelectorAll('.offer-item-row')].reduce((sum, row) => {
            const quantity = Number(row.querySelector('[name$="[quantity]"]').value || 0);
            const price = Number(row.querySelector('[name$="[unit_price]"]').value || 0);
            return sum + quantity * price;
        }, 0);
        const discount = Number(discountInput.value || 0);
        totalNode.textContent = 'Rp ' + Math.max(0, subtotal - discount).toLocaleString('id-ID');
        container.querySelectorAll('.offer-item-number').forEach((node, index) => {
            node.textContent = String(index + 1);
        });
    };

    document.getElementById('add-offer-item').addEventListener('click', () => {
        const index = Math.max(0, ...[...container.querySelectorAll('[name^="items["]')].map((input) => {
            const match = input.name.match(/^items\[(\d+)\]/);
            return match ? Number(match[1]) + 1 : 0;
        }));
        const fragment = template.content.cloneNode(true);
        fragment.querySelectorAll('[name]').forEach((input) => {
            input.name = input.name.replace(/__INDEX__/g, String(index));
        });
        container.appendChild(fragment);
        refresh();
    });

    container.addEventListener('click', (event) => {
        const button = event.target.closest('.remove-offer-item');
        if (!button) return;
        const rows = container.querySelectorAll('.offer-item-row');
        if (rows.length === 1) {
            rows[0].querySelectorAll('input, textarea').forEach((input) => {
                if (input.name.endsWith('[quantity]')) input.value = '1';
                else if (input.name.endsWith('[unit_price]')) input.value = '0';
                else input.value = '';
            });
        } else {
            button.closest('.offer-item-row').remove();
        }
        refresh();
    });

    container.addEventListener('input', refresh);
    discountInput.addEventListener('input', refresh);
    baseOption.addEventListener('change', () => {
        const option = baseOption.selectedOptions[0];
        if (!option?.value) return;
        if (!titleInput.value.trim()) titleInput.value = option.dataset.title || '';
        const rows = container.querySelectorAll('.offer-item-row');
        if (rows.length === 1 && !rows[0].querySelector('[name$="[name]"]').value.trim()) {
            rows[0].querySelector('[name$="[name]"]').value = option.dataset.itemName || '';
            rows[0].querySelector('[name$="[description]"]').value = option.dataset.itemDescription || '';
            rows[0].querySelector('[name$="[unit_price]"]').value = option.dataset.itemPrice || '0';
        }
        refresh();
    });

    refresh();
})();
</script>
@endsection
