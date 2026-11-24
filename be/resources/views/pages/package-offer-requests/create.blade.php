@extends('layouts.app')

@section('title', 'Ajukan Paket Khusus')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('package-offer-requests.mine') }}" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke permintaan saya
        </a>
        <h3 class="fw-bold mt-2 mb-1">Ceritakan kebutuhan acara Anda</h3>
        <p class="text-muted mb-0">Tim Sagita akan meninjau kebutuhan ini dan menyiapkan penawaran khusus yang hanya dapat Anda lihat.</p>
    </div>

    <form method="POST" action="{{ route('package-offer-requests.store') }}" class="card">
        @csrf
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
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between gap-2">
            <a href="{{ route('package-offer-requests.mine') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Kirim Permintaan</button>
        </div>
    </form>
</div>
@endsection
