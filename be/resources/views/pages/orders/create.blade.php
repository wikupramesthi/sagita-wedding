@extends('layouts.app')
@section('title', 'Buat Pesanan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Buat Pesanan" page="Pesanan" active="Buat" route="{{ route('orders.create') }}" />
@endsection

<section class="section">
    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="d-flex align-items-start gap-2">
            <i class="bx bx-error-circle fs-5 mt-1"></i>
            <div>
                <strong>Periksa kembali isian Anda.</strong>
                <ul class="mb-0 mt-2 ps-3 small">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="package">
        @if ($consultation)
        <input type="hidden" name="consultation_uuid" value="{{ $consultation->uuid }}">
        @endif

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-1">Detail Pesanan</h5>
                        <p class="text-muted small mb-0">Buat pesanan paket dari lead yang sudah masuk.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @if ($consultation)
                            <div class="col-12 col-md-6">
                                <label for="user_uuid" class="form-label fw-semibold">Pelanggan</label>
                                <select id="user_uuid" name="user_uuid" class="form-select @error('user_uuid') is-invalid @enderror">
                                    <option value="">Gunakan akun admin saat ini</option>
                                    @foreach ($users as $candidate)
                                    <option value="{{ $candidate->uuid }}" @selected(old('user_uuid', $matchedUser?->uuid) === $candidate->uuid)>{{ $candidate->name }} — {{ $candidate->email ?: $candidate->no_hp }}</option>
                                    @endforeach
                                </select>
                                @error('user_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endif

                            <div class="col-12 col-md-6">
                                <label for="package_option_uuid" class="form-label fw-semibold">Paket <span class="text-danger">*</span></label>
                                <select id="package_option_uuid" name="package_option_uuid" class="form-select @error('package_option_uuid') is-invalid @enderror" required>
                                    <option value="">Pilih paket</option>
                                    @foreach ($packageOptions as $option)
                                    <option value="{{ $option->uuid }}" @selected(old('package_option_uuid', $selectedOption?->uuid) === $option->uuid)>
                                        {{ $option->package?->name }} — {{ $option->name }} ({{ 'Rp'.number_format((int) $option->final_price, 0, ',', '.') }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('package_option_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="event_date" class="form-label fw-semibold">Tanggal Acara <span class="text-danger">*</span></label>
                                <input type="date" id="event_date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', optional($consultation?->wedding_date)->format('Y-m-d')) }}" required>
                                @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="payment_scheme" class="form-label fw-semibold">Skema Pembayaran</label>
                                <select id="payment_scheme" name="payment_scheme" class="form-select @error('payment_scheme') is-invalid @enderror">
                                    <option value="no_dp" @selected(old('payment_scheme') === 'no_dp')>Tanpa DP</option>
                                    <option value="dp30" @selected(old('payment_scheme') === 'dp30')>DP 30%</option>
                                    <option value="dp50" @selected(old('payment_scheme') === 'dp50')>DP 50%</option>
                                    <option value="full" @selected(old('payment_scheme') === 'full')>Bayar Lunas</option>
                                </select>
                                @error('payment_scheme')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                @if ($addons->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-1">Layanan Tambahan</h5>
                        <p class="text-muted small mb-0">Opsional. Kosongkan bila tidak diperlukan.</p>
                    </div>
                    <div class="card-body p-4">
                        @foreach ($addons as $addon)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="addon_uuids[]" value="{{ $addon->uuid }}" id="addon-{{ $addon->uuid }}">
                            <label class="form-check-label small" for="addon-{{ $addon->uuid }}">
                                {{ $addon->name }} — Rp{{ number_format((int) $addon->final_price, 0, ',', '.') }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-1">Booking Venue</h5>
                        <p class="text-muted small mb-0">Opsional. Bisa diperbarui dari detail pesanan.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="venue_uuid" class="form-label fw-semibold">Venue</label>
                                <select id="venue_uuid" name="venue_uuid" class="form-select @error('venue_uuid') is-invalid @enderror">
                                    <option value="">Belum ditentukan</option>
                                    @foreach ($venues as $venue)
                                    <option value="{{ $venue->uuid }}" @selected(old('venue_uuid', $selectedVenue?->uuid) === $venue->uuid)>{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                                @error('venue_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="venue_booking_status" class="form-label fw-semibold">Status Booking</label>
                                <select id="venue_booking_status" name="venue_booking_status" class="form-select @error('venue_booking_status') is-invalid @enderror">
                                    <option value="none" @selected(old('venue_booking_status') === 'none')>Belum dipesan</option>
                                    <option value="requested" @selected(old('venue_booking_status') === 'requested')>Diajukan</option>
                                    <option value="confirmed" @selected(old('venue_booking_status') === 'confirmed')>Terkonfirmasi</option>
                                    <option value="cancelled" @selected(old('venue_booking_status') === 'cancelled')>Dibatalkan</option>
                                </select>
                                @error('venue_booking_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="venue_booking_at" class="form-label fw-semibold">Tanggal Booking</label>
                                <input type="datetime-local" id="venue_booking_at" name="venue_booking_at" class="form-control @error('venue_booking_at') is-invalid @enderror" value="{{ old('venue_booking_at') }}">
                                @error('venue_booking_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="venue_booking_amount" class="form-label fw-semibold">Nominal Booking</label>
                                <input type="number" step="0.01" min="0" id="venue_booking_amount" name="venue_booking_amount" class="form-control @error('venue_booking_amount') is-invalid @enderror" value="{{ old('venue_booking_amount') }}">
                                @error('venue_booking_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label for="venue_booking_note" class="form-label fw-semibold">Catatan</label>
                                <textarea id="venue_booking_note" name="venue_booking_note" rows="2" class="form-control @error('venue_booking_note') is-invalid @enderror">{{ old('venue_booking_note') }}</textarea>
                                @error('venue_booking_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                @if ($consultation)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-1">Ringkasan Lead</h5>
                        <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($consultation->uuid, 12) }}</p>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0 small">
                            <dt class="col-5 text-muted">Nama</dt>
                            <dd class="col-7 mb-2">{{ $consultation->full_name }}</dd>
                            <dt class="col-5 text-muted">WhatsApp</dt>
                            <dd class="col-7 mb-2">{{ $consultation->whatsapp }}</dd>
                            <dt class="col-5 text-muted">Email</dt>
                            <dd class="col-7 mb-2">{{ $consultation->email ?: '-' }}</dd>
                            <dt class="col-5 text-muted">Tanggal Nikah</dt>
                            <dd class="col-7 mb-2">{{ optional($consultation->wedding_date)->format('d M Y') ?: '-' }}</dd>
                            <dt class="col-5 text-muted">Venue</dt>
                            <dd class="col-7 mb-2">{{ $consultation->venue ?: '-' }}</dd>
                            <dt class="col-5 text-muted">Paket</dt>
                            <dd class="col-7 mb-2">{{ $consultation->package?->name ?: ($selectedOption?->package?->name ?? '-') }}</dd>
                            <dt class="col-5 text-muted">Status Lead</dt>
                            <dd class="col-7 mb-0">{{ $consultation->lead_status }}</dd>
                        </dl>
                    </div>
                </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
