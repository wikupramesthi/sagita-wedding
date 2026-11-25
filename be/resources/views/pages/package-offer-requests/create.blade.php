@extends('layouts.app')

@section('title', 'Ajukan Paket Khusus')

@section('content')
<div class="container-fluid py-3 offer-module-page">
    <section class="analytics-hero mb-4" aria-labelledby="request-create-title">
        <div class="analytics-hero__copy">
            <p class="analytics-hero__eyebrow"><i class="bi bi-chat-square-heart me-2" aria-hidden="true"></i>Permintaan privat</p>
            <h1 id="request-create-title">Ceritakan kebutuhan acara Anda</h1>
            <p class="analytics-hero__subtitle">Tim Sagita akan meninjau kebutuhan ini dan menyiapkan penawaran khusus yang hanya dapat Anda lihat.</p>
        </div>
        <div class="offer-module-actions"><a href="{{ route('package-offer-requests.mine') }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke permintaan saya</a></div>
        <div class="analytics-hero__decoration" aria-hidden="true"><i class="bi bi-pencil-square"></i></div>
    </section>

    <form method="POST" action="{{ route('package-offer-requests.store') }}" enctype="multipart/form-data" class="card shadow-sm border-0 offer-form-card">
        @csrf
        <div class="card-header bg-transparent"><h2 class="h6 fw-bold mb-0"><i class="bi bi-calendar-heart me-2 text-primary" aria-hidden="true"></i>Detail acara dan kebutuhan</h2></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="event_date" class="form-label">Tanggal acara</label>
                    <input id="event_date" type="date" name="event_date" min="{{ now()->toDateString() }}" value="{{ old('event_date') }}" class="form-control @error('event_date') is-invalid @enderror">
                    @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="guest_count" class="form-label">Perkiraan jumlah tamu</label>
                    <input id="guest_count" type="number" name="guest_count" min="1" max="100000" value="{{ old('guest_count') }}" class="form-control @error('guest_count') is-invalid @enderror">
                    @error('guest_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="venue_note" class="form-label">Lokasi atau kota acara</label>
                    <input id="venue_note" name="venue_note" maxlength="1000" value="{{ old('venue_note') }}" class="form-control @error('venue_note') is-invalid @enderror" placeholder="Contoh: Jakarta Selatan">
                    @error('venue_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="budget_min" class="form-label">Anggaran minimum (Rp)</label>
                    <input id="budget_min" type="number" name="budget_min" min="0" max="1000000000000" value="{{ old('budget_min') }}" class="form-control @error('budget_min') is-invalid @enderror">
                    @error('budget_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="budget_max" class="form-label">Anggaran maksimum (Rp)</label>
                    <input id="budget_max" type="number" name="budget_max" min="0" max="1000000000000" value="{{ old('budget_max') }}" class="form-control @error('budget_max') is-invalid @enderror">
                    @error('budget_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="request_details" class="form-label">Kebutuhan dan permintaan khusus <span class="text-danger">*</span></label>
                    <textarea id="request_details" name="request_details" rows="7" maxlength="5000" minlength="20" required class="form-control @error('request_details') is-invalid @enderror" placeholder="Ceritakan layanan yang dibutuhkan, konsep acara, hal yang penting bagi Anda, dan permintaan lainnya.">{{ old('request_details') }}</textarea>
                    @error('request_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Jelaskan sedetail mungkin agar penawaran yang dibuat lebih sesuai.</div>
                </div>
                <div class="col-12">
                    <label for="attachments" class="form-label">Lampiran pendukung <span class="text-muted">(opsional)</span></label>
                    <input id="attachments" type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror">
                    @error('attachments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('attachments.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Maksimal 5 berkas JPG, PNG, atau PDF; ukuran setiap berkas paling besar 10 MB.</div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between gap-2">
            <a href="{{ route('package-offer-requests.mine') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1" aria-hidden="true"></i>Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1" aria-hidden="true"></i>Kirim Permintaan</button>
        </div>
    </form>
</div>
@endsection
