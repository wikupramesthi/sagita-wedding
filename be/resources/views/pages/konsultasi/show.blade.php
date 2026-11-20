@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Detail Konsultasi" page="Layanan" active="Detail Konsultasi" route="{{ route('konsultasi.show', $consultation->uuid) }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h4 class="fw-normal mb-0 text-body">Detail Pertanyaan Konsultasi</h4>
            <div class="d-flex gap-2">
                @can('konsultasi.index')
                    <a href="{{ route('konsultasi.edit', $consultation->uuid) }}" class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i> Ubah</a>
                @endcan
                <a href="{{ route('konsultasi.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Nama Calon Pengantin / Pasangan</div>
                        <div class="fw-semibold">{{ $consultation->full_name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Nomor WhatsApp</div>
                        <div class="fw-semibold">{{ $consultation->whatsapp }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Rencana Tanggal Pernikahan</div>
                        <div class="fw-semibold">{{ optional($consultation->wedding_date)->format('d M Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Estimasi Jumlah Tamu</div>
                        <div class="fw-semibold">{{ $consultation->guest_estimate ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Paket</div>
                        <div class="fw-semibold">{{ $consultation->package?->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="small text-muted">Lokasi / Venue</div>
                        <div class="fw-semibold">{{ $consultation->venue ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="small text-muted">Konsep Pernikahan</div>
                        <div>{{ $consultation->wedding_concept ? nl2br(e($consultation->wedding_concept)) : '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Tanggal Konsultasi</div>
                        <div class="fw-semibold">{{ optional($consultation->consultation_date)->format('d M Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Jam Konsultasi</div>
                        <div class="fw-semibold">{{ $consultation->consultation_time ? \Illuminate\Support\Carbon::parse($consultation->consultation_time)->format('H:i') : '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Metode Konsultasi</div>
                        <div class="fw-semibold">{{ $consultation->method_label }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Status Konsultasi</div>
                        <div><span class="badge {{ $consultation->status_badge_class }}">{{ $consultation->status_label }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Jadwal Tindak Lanjut</div>
                        <div class="fw-semibold">{{ optional($consultation->follow_up_at)->format('d M Y H:i') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Status Calon Pelanggan</div>
                        <div><span class="badge {{ $consultation->lead_status_badge_class }}">{{ $consultation->lead_status_label }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Sumber Calon Pelanggan</div>
                        <div class="fw-semibold">{{ $consultation->source }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Waktu Konversi ke Booking</div>
                        <div class="fw-semibold">{{ optional($consultation->converted_to_booking_at)->format('d M Y H:i') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Admin yang Menangani</div>
                        <div class="fw-semibold">{{ $consultation->handler?->name ?? 'Belum ditentukan' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Waktu Pendaftaran</div>
                        <div class="fw-semibold">{{ $consultation->created_at?->format('d M Y H:i') }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="small text-muted">Catatan Admin</div>
                        <div>{{ $consultation->admin_notes ? nl2br(e($consultation->admin_notes)) : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
