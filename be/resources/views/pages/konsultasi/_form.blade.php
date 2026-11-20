@php
    $consultation = $consultation ?? new \App\Models\Consultation();
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="full_name" class="form-label">Nama Calon Pengantin / Pasangan <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $consultation->full_name) }}" maxlength="150" required>
            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="whatsapp" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $consultation->whatsapp) }}" maxlength="20" required>
            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="wedding_date" class="form-label">Rencana Tanggal Pernikahan</label>
            <input type="date" class="form-control @error('wedding_date') is-invalid @enderror" id="wedding_date" name="wedding_date" value="{{ old('wedding_date', optional($consultation->wedding_date)->format('Y-m-d')) }}">
            @error('wedding_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="guest_estimate" class="form-label">Estimasi Jumlah Tamu</label>
            <select class="form-select @error('guest_estimate') is-invalid @enderror" id="guest_estimate" name="guest_estimate">
                <option value="">Pilih estimasi tamu</option>
                @foreach (\App\Models\Consultation::GUEST_ESTIMATES as $estimate)
                    <option value="{{ $estimate }}" @selected(old('guest_estimate', $consultation->guest_estimate) === $estimate)>{{ $estimate }}</option>
                @endforeach
            </select>
            @error('guest_estimate')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="package_id" class="form-label">Paket</label>
            <select class="form-select @error('package_id') is-invalid @enderror" id="package_id" name="package_id">
                <option value="">Pilih paket</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" @selected((string) old('package_id', $consultation->package_id) === (string) $package->id)>{{ $package->name }}</option>
                @endforeach
            </select>
            @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label for="venue" class="form-label">Lokasi / Venue</label>
            <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', $consultation->venue) }}" maxlength="255" placeholder="Contoh: Gedung Graha Krida, Jakarta">
            @error('venue')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label for="wedding_concept" class="form-label">Konsep Pernikahan</label>
            <textarea class="form-control @error('wedding_concept') is-invalid @enderror" id="wedding_concept" name="wedding_concept" rows="3">{{ old('wedding_concept', $consultation->wedding_concept) }}</textarea>
            @error('wedding_concept')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="consultation_date" class="form-label">Tanggal Konsultasi</label>
            <input type="date" class="form-control @error('consultation_date') is-invalid @enderror" id="consultation_date" name="consultation_date" value="{{ old('consultation_date', optional($consultation->consultation_date)->format('Y-m-d')) }}">
            @error('consultation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="consultation_time" class="form-label">Jam Konsultasi</label>
            <input type="time" class="form-control @error('consultation_time') is-invalid @enderror" id="consultation_time" name="consultation_time" value="{{ old('consultation_time', $consultation->consultation_time ? \Illuminate\Support\Carbon::parse($consultation->consultation_time)->format('H:i') : '') }}">
            @error('consultation_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="consultation_method" class="form-label">Metode Konsultasi</label>
            <select class="form-select @error('consultation_method') is-invalid @enderror" id="consultation_method" name="consultation_method">
                <option value="">Pilih metode</option>
                @foreach (\App\Models\Consultation::METHOD_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(old('consultation_method', $consultation->consultation_method) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('consultation_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="consultation_status" class="form-label">Status Konsultasi <span class="text-danger">*</span></label>
            <select class="form-select @error('consultation_status') is-invalid @enderror" id="consultation_status" name="consultation_status" required>
                @foreach (\App\Models\Consultation::STATUS_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(old('consultation_status', $consultation->consultation_status ?? 'Pending') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('consultation_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="follow_up_at" class="form-label">Jadwal Tindak Lanjut</label>
            <input type="datetime-local" class="form-control @error('follow_up_at') is-invalid @enderror" id="follow_up_at" name="follow_up_at" value="{{ old('follow_up_at', optional($consultation->follow_up_at)->format('Y-m-d\TH:i')) }}">
            @error('follow_up_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="lead_status" class="form-label">Status Calon Pelanggan <span class="text-danger">*</span></label>
            <select class="form-select @error('lead_status') is-invalid @enderror" id="lead_status" name="lead_status" required>
                @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(old('lead_status', $consultation->lead_status ?? 'New') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('lead_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="source" class="form-label">Sumber Calon Pelanggan <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('source') is-invalid @enderror" id="source" name="source" value="{{ old('source', $consultation->source ?? 'Website') }}" maxlength="50" required>
            @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="converted_to_booking_at" class="form-label">Waktu Konversi ke Booking</label>
            <input type="datetime-local" class="form-control @error('converted_to_booking_at') is-invalid @enderror" id="converted_to_booking_at" name="converted_to_booking_at" value="{{ old('converted_to_booking_at', optional($consultation->converted_to_booking_at)->format('Y-m-d\TH:i')) }}">
            @error('converted_to_booking_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="handled_by" class="form-label">Admin yang Menangani</label>
            <select class="form-select @error('handled_by') is-invalid @enderror" id="handled_by" name="handled_by">
                <option value="">Belum ditentukan</option>
                @foreach ($admins as $admin)
                    <option value="{{ $admin->id }}" @selected((string) old('handled_by', $consultation->handled_by) === (string) $admin->id)>{{ $admin->name }}</option>
                @endforeach
            </select>
            @error('handled_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label for="admin_notes" class="form-label">Catatan Admin</label>
            <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $consultation->admin_notes) }}</textarea>
            @error('admin_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('konsultasi.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">{{ $submitLabel ?? 'Simpan' }}</button>
    </div>
</form>
