@php
$consultation = $consultation ?? new \App\Models\Consultation();
@endphp

<form action="{{ $action }}" method="POST" class="konsultasi-form">
    @csrf
    @if ($method ?? null)
    @method($method)
    @endif

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

    <div class="konsultasi-layout">
        <div class="konsultasi-content">
            <div class="card border-0 shadow-sm form-card">
                <div class="card-header form-card-header">
                    <span class="form-card-icon bg-primary bg-opacity-10 text-primary"><i class="bx bxs-user-detail"></i></span>
                    <div>
                        <h5 class="form-card-title">Data Calon Pelanggan</h5>
                        <p class="form-card-subtitle">Identitas utama dan rencana pernikahan calon pelanggan.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-grid">
                        <div class="span-12 span-md-4">
                            <label for="full_name" class="form-label fw-semibold">Nama Calon Pasangan <span class="text-danger">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $consultation->full_name) }}" maxlength="150" placeholder="Contoh: Rani & Dimas" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="whatsapp" class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" id="whatsapp" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $consultation->whatsapp) }}" maxlength="20" placeholder="08xxxxxxxxxx" required>
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $consultation->email) }}" maxlength="255" placeholder="nama@contoh.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="source" class="form-label fw-semibold">Informasi dari mana <span class="text-danger">*</span></label>
                            <select id="source" name="source" class="form-select @error('source') is-invalid @enderror" required>
                                <option value="">Pilih sumber</option>
                                @foreach (\App\Models\Consultation::SOURCE_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', $consultation->source ?? 'website') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Sumber pertama kali calon mengenal Sagita.</div>
                            @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="wedding_date" class="form-label fw-semibold">Rencana Tanggal Pernikahan</label>
                            <input type="date" id="wedding_date" name="wedding_date" class="form-control @error('wedding_date') is-invalid @enderror" value="{{ old('wedding_date', optional($consultation->wedding_date)->format('Y-m-d')) }}">
                            @error('wedding_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="guest_estimate" class="form-label fw-semibold">Estimasi Tamu</label>
                            <select id="guest_estimate" name="guest_estimate" class="form-select @error('guest_estimate') is-invalid @enderror">
                                <option value="">Pilih estimasi</option>
                                @foreach (\App\Models\Consultation::GUEST_ESTIMATES as $estimate)
                                <option value="{{ $estimate }}" @selected(old('guest_estimate', $consultation->guest_estimate) === $estimate)>{{ $estimate }}</option>
                                @endforeach
                            </select>
                            @error('guest_estimate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="package_id" class="form-label fw-semibold">Paket Diminati</label>
                            <select id="package_id" name="package_id" class="form-select @error('package_id') is-invalid @enderror">
                                <option value="">Pilih paket</option>
                                @foreach ($packages as $package)
                                <option value="{{ $package->id }}" @selected((string) old('package_id', $consultation->package_id) === (string) $package->id)>{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="venue" class="form-label fw-semibold">Lokasi / Venue</label>
                            <input type="text" id="venue" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue', $consultation->venue) }}" maxlength="255" placeholder="Contoh: Gedung Graha Krida, Jakarta">
                            @error('venue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="wedding_concept" class="form-label fw-semibold">Konsep Pernikahan</label>
                            <textarea id="wedding_concept" name="wedding_concept" rows="2" class="form-control @error('wedding_concept') is-invalid @enderror" placeholder="Tema, warna, atau gaya dekorasi.">{{ old('wedding_concept', $consultation->wedding_concept) }}</textarea>
                            @error('wedding_concept')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm form-card">
                <div class="card-header form-card-header">
                    <span class="form-card-icon bg-info bg-opacity-10 text-info"><i class="bx bx-calendar-check"></i></span>
                    <div>
                        <h5 class="form-card-title">Jadwal & Pipeline</h5>
                        <p class="form-card-subtitle">Atur jadwal konsultasi dan status tindak lanjut.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-grid">
                        <div class="span-12 span-md-4">
                            <label for="consultation_date" class="form-label fw-semibold">Tanggal Konsultasi</label>
                            <input type="date" id="consultation_date" name="consultation_date" class="form-control @error('consultation_date') is-invalid @enderror" value="{{ old('consultation_date', optional($consultation->consultation_date)->format('Y-m-d')) }}">
                            @error('consultation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="consultation_time" class="form-label fw-semibold">Jam Konsultasi</label>
                            <input type="time" id="consultation_time" name="consultation_time" class="form-control @error('consultation_time') is-invalid @enderror" value="{{ old('consultation_time', $consultation->consultation_time ? \Illuminate\Support\Carbon::parse($consultation->consultation_time)->format('H:i') : '') }}">
                            @error('consultation_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="consultation_method" class="form-label fw-semibold">Metode</label>
                            <select id="consultation_method" name="consultation_method" class="form-select @error('consultation_method') is-invalid @enderror">
                                <option value="">Pilih metode</option>
                                @foreach (\App\Models\Consultation::METHOD_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('consultation_method', $consultation->consultation_method) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('consultation_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="consultation_status" class="form-label fw-semibold">Status Konsultasi <span class="text-danger">*</span></label>
                            <select id="consultation_status" name="consultation_status" class="form-select @error('consultation_status') is-invalid @enderror" required>
                                @foreach (\App\Models\Consultation::STATUS_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('consultation_status', $consultation->consultation_status ?? 'Pending') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('consultation_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="lead_status" class="form-label fw-semibold">Status Calon Pelanggan <span class="text-danger">*</span></label>
                            <select id="lead_status" name="lead_status" class="form-select @error('lead_status') is-invalid @enderror" required>
                                @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                                <option value="{{ $value }}" @selected(old('lead_status', $consultation->lead_status ?? 'New') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('lead_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12 span-md-4">
                            <label for="follow_up_at" class="form-label fw-semibold">Jadwal Tindak Lanjut</label>
                            <input type="datetime-local" id="follow_up_at" name="follow_up_at" class="form-control @error('follow_up_at') is-invalid @enderror" value="{{ old('follow_up_at', optional($consultation->follow_up_at)->format('Y-m-d\TH:i')) }}">
                            <div class="form-text">Kosongkan jika belum dijadwalkan.</div>
                            @error('follow_up_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="konsultasi-aside">
            <div class="card border-0 shadow-sm form-card">
                <div class="card-header form-card-header">
                    <span class="form-card-icon bg-warning bg-opacity-10 text-warning"><i class="bx bx-user-check"></i></span>
                    <div>
                        <h5 class="form-card-title">Penanggung Jawab & Catatan</h5>
                        <p class="form-card-subtitle">Tentukan admin yang menangani dan catat hasil tindak lanjut.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-grid">
                        <div class="span-12">
                            <label for="handled_by" class="form-label fw-semibold">Admin yang Menangani</label>
                            <select id="handled_by" name="handled_by" class="form-select @error('handled_by') is-invalid @enderror">
                                <option value="">Belum ditentukan</option>
                                @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}" @selected((string) old('handled_by', $consultation->handled_by) === (string) $admin->id)>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            @error('handled_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12">
                            <label for="converted_to_booking_at" class="form-label fw-semibold">Waktu Konversi ke Booking</label>
                            <input type="datetime-local" id="converted_to_booking_at" name="converted_to_booking_at" class="form-control @error('converted_to_booking_at') is-invalid @enderror" value="{{ old('converted_to_booking_at', optional($consultation->converted_to_booking_at)->format('Y-m-d\TH:i')) }}">
                            <div class="form-text">Isi jika calon sudah dikonversi menjadi booking.</div>
                            @error('converted_to_booking_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="span-12">
                            <label for="admin_notes" class="form-label fw-semibold">Catatan Tim</label>
                            <textarea id="admin_notes" name="admin_notes" rows="3" class="form-control @error('admin_notes') is-invalid @enderror" placeholder="Catatan hasil telepon atau status negosiasi.">{{ old('admin_notes', $consultation->admin_notes) }}</textarea>
                            @error('admin_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-card-actions d-flex gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('konsultasi.index') }}" class="btn btn-light flex-fill">Batal</a>
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-lg me-1"></i> {{ $submitLabel ?? 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</form>

<style>
    .konsultasi-form .konsultasi-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    .konsultasi-form .konsultasi-content {
        display: flex;
        flex-direction: column;
        gap: 0;
        min-width: 0;
    }

    .konsultasi-form .konsultasi-aside {
        min-width: 0;
    }

    .konsultasi-form .form-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 18px 16px;
    }

    .konsultasi-form .form-grid>* {
        min-width: 0;
        align-self: start;
    }

    .konsultasi-form .span-12 {
        grid-column: span 12 / span 12;
    }

    .konsultasi-form .form-card {
        overflow: hidden;
    }

    .konsultasi-form .form-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        background: transparent;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .konsultasi-form .konsultasi-content .form-card:first-child {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .konsultasi-form .konsultasi-content .form-card+.form-card {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .konsultasi-form .konsultasi-content .form-card-header {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }

    .konsultasi-form .konsultasi-content .card-body {
        padding-top: 16px !important;
        padding-bottom: 16px !important;
    }

    .konsultasi-form .form-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .konsultasi-form .form-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .konsultasi-form .form-card-subtitle {
        margin: 2px 0 0;
        font-size: 12px;
        color: var(--bs-secondary-color);
    }

    .konsultasi-form .form-label {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .konsultasi-form .form-control,
    .konsultasi-form .form-select {
        min-height: 42px;
        font-size: 13px;
    }

    .konsultasi-form textarea.form-control {
        min-height: 74px;
        resize: vertical;
    }

    .konsultasi-form .form-text {
        font-size: 11px;
    }

    .konsultasi-form .form-card-actions .btn {
        min-height: 42px;
        font-weight: 600;
    }

    @media (min-width: 768px) {
        .konsultasi-form .span-md-4 {
            grid-column: span 4 / span 4;
        }
    }

    @media (min-width: 1200px) {
        .konsultasi-form .konsultasi-layout {
            grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
        }
    }

    @media (min-width: 1400px) {
        .konsultasi-form .konsultasi-layout {
            grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
        }
    }
</style>