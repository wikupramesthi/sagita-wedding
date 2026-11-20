@extends('layouts.app')
@section('title', 'Detail Calon Pelanggan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Detail Calon Pelanggan" page="Data Prospek" active="Konsultasi" route="{{ route('konsultasi.show', $consultation->uuid) }}" />
@endsection

<section class="section">
    <div class="card lead-hero-card mb-3">
        <div class="card-body d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
            <div class="d-flex align-items-center gap-3">
                <span class="lead-hero-avatar">{{ $consultation->initials }}</span>
                <div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <h4 class="fw-semibold mb-0 text-body">{{ $consultation->full_name }}</h4>
                        <span class="badge {{ $consultation->lead_priority_badge_class }}"><i class="{{ $consultation->lead_priority_icon }} me-1"></i>Prioritas {{ $consultation->lead_priority }}</span>
                        <span class="badge {{ $consultation->lead_status_badge_class }}">{{ $consultation->lead_status_label }}</span>
                        <span class="badge {{ $consultation->status_badge_class }}">{{ $consultation->status_label }}</span>
                    </div>
                    <div class="small text-muted mt-2">
                        <i class="bx bx-calendar me-1"></i> Masuk {{ $consultation->created_at->translatedFormat('d F Y H:i') }}
                        <span class="mx-2">•</span>
                        <i class="{{ $consultation->source_icon }} me-1"></i> Informasi dari mana: {{ $consultation->source_label }}
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('konsultasi.pdf-preview', ['uuid' => $consultation->uuid]) }}" class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF</a>
                <a href="{{ $consultation->whatsapp_link }}?text={{ rawurlencode($consultation->whatsapp_message) }}" target="_blank" rel="noopener" class="btn btn-success"><i class="bx bxl-whatsapp me-1"></i> Hubungi WhatsApp</a>
                @can('konsultasi.index')
                    <form action="{{ route('konsultasi.remind', $consultation->uuid) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning"><i class="bi bi-bell me-1"></i> Kirim Pengingat</button>
                    </form>
                    <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-outline-secondary"><i class="bi bi-diagram-3 me-1"></i> Cek Duplikat</a>
                    <a href="{{ route('konsultasi.edit', $consultation->uuid) }}" class="btn btn-light"><i class="bi bi-pencil-square me-1"></i> Ubah Data</a>
                @endcan
                <a href="{{ route('konsultasi.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card h-100 mb-3">
                <div class="card-header"><h5 class="fw-normal mb-0 text-body">Profil Calon Pelanggan</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Nama Lengkap</div>
                            <div class="lead-detail-value">{{ $consultation->full_name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Nomor WhatsApp</div>
                            <div class="lead-detail-value">{{ $consultation->whatsapp }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Email</div>
                            <div class="lead-detail-value">{{ $consultation->email ?: '—' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Informasi dari mana</div>
                            <div class="lead-detail-value"><i class="{{ $consultation->source_icon }} me-1"></i>{{ $consultation->source_label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card h-100 mb-3">
                <div class="card-header"><h5 class="fw-normal mb-0 text-body">Rencana Pernikahan</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Tanggal Pernikahan</div>
                            <div class="lead-detail-value">{{ $consultation->wedding_date?->translatedFormat('d F Y') ?? '—' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Estimasi Tamu</div>
                            <div class="lead-detail-value">{{ $consultation->guest_estimate ?: '—' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Lokasi / Venue</div>
                            <div class="lead-detail-value">{{ $consultation->venue ?: '—' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Paket yang Diminati</div>
                            <div class="lead-detail-value">{{ $consultation->package?->name ?? 'Belum memilih paket' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="lead-detail-label">Konsep Pernikahan</div>
                            @if ($consultation->wedding_concept)
                                <div class="lead-detail-value" style="white-space: pre-line;">{{ $consultation->wedding_concept }}</div>
                            @else
                                <div class="lead-detail-value text-muted">—</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-header"><h5 class="fw-normal mb-0 text-body">Jadwal & Catatan Konsultasi</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="lead-detail-label">Tanggal Konsultasi</div>
                            <div class="lead-detail-value">{{ $consultation->consultation_date?->translatedFormat('d F Y') ?? '—' }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="lead-detail-label">Jam Konsultasi</div>
                            <div class="lead-detail-value">{{ $consultation->consultation_time ?: '—' }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="lead-detail-label">Metode</div>
                            <div class="lead-detail-value">{{ $consultation->method_label }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Penanggung Jawab</div>
                            <div class="lead-detail-value">{{ $consultation->handler?->name ?? 'Belum ditugaskan' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="lead-detail-label">Status Konsultasi</div>
                            <div class="mt-1"><span class="badge {{ $consultation->status_badge_class }}">{{ $consultation->status_label }}</span></div>
                        </div>
                        <div class="col-12">
                            <div class="lead-detail-label">Catatan Tim</div>
                            @if ($consultation->admin_notes)
                                <div class="lead-detail-value" style="white-space: pre-line;">{{ $consultation->admin_notes }}</div>
                            @else
                                <div class="lead-detail-value text-muted">Belum ada catatan.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            @can('konsultasi.index')
                <div class="card mb-3">
                    <div class="card-header"><h5 class="fw-normal mb-0 text-body">Perbarui Pipeline</h5></div>
                    <div class="card-body">
                        <form action="{{ route('konsultasi.update-lead', $consultation->uuid) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="lead_status" class="form-label small text-muted">Status Calon Pelanggan</label>
                                <select id="lead_status" name="lead_status" class="form-select" required>
                                    @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" @selected($consultation->lead_status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="handled_by" class="form-label small text-muted">Penanggung Jawab</label>
                                <select id="handled_by" name="handled_by" class="form-select">
                                    <option value="">Belum ditugaskan</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" @selected($consultation->handled_by === $admin->id)>{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="follow_up_at" class="form-label small text-muted">Jadwal Tindak Lanjut</label>
                                <input type="datetime-local" id="follow_up_at" name="follow_up_at" value="{{ $consultation->follow_up_at?->format('Y-m-d\TH:i') }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Simpan Pipeline</button>
                        </form>
                    </div>
                </div>
            @endcan

            <div class="card mb-3">
                <div class="card-header"><h5 class="fw-normal mb-0 text-body">Skor Ketertarikan</h5></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <span class="lead-score-value">{{ $consultation->lead_score }}/10</span>
                        <span class="badge {{ $consultation->lead_priority_badge_class }}">{{ $consultation->lead_priority }}</span>
                    </div>
                    <div class="progress lead-score-progress" role="progressbar" aria-label="Skor ketertarikan" aria-valuenow="{{ min($consultation->lead_score * 10, 100) }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar {{ $consultation->lead_priority === 'Tinggi' ? 'bg-danger' : ($consultation->lead_priority === 'Sedang' ? 'bg-warning' : 'bg-secondary') }}" style="width: {{ min($consultation->lead_score * 10, 100) }}%"></div>
                    </div>
                    <div class="small text-muted mt-2">Skor dihitung dari status lead, kelengkapan rencana, paket, jadwal konsultasi, dan status konsultasi.</div>
                    <div class="lead-score-checks mt-3">
                        <div class="{{ $consultation->lead_status ? 'text-success' : 'text-muted' }}"><i class="bx bx-check-circle me-1"></i> Status lead terisi</div>
                        <div class="{{ $consultation->wedding_date ? 'text-success' : 'text-muted' }}"><i class="bx bx-check-circle me-1"></i> Tanggal pernikahan</div>
                        <div class="{{ $consultation->package_id ? 'text-success' : 'text-muted' }}"><i class="bx bx-check-circle me-1"></i> Paket diminati</div>
                        <div class="{{ $consultation->consultation_date ? 'text-success' : 'text-muted' }}"><i class="bx bx-check-circle me-1"></i> Jadwal konsultasi</div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h5 class="fw-normal mb-0 text-body">Riwayat Perubahan</h5>
                    <span class="badge bg-light text-body">{{ count($history) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($history === [])
                        <div class="p-4 small text-muted">Belum ada perubahan yang tercatat.</div>
                    @else
                        <div class="konsultasi-history">
                            @foreach ($history as $entry)
                                <div class="konsultasi-history-item">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge {{ $entry['badge'] }}">{{ $entry['event_label'] }}</span>
                                        <span class="small text-muted ms-auto">{{ $entry['time'] }}</span>
                                    </div>
                                    <div class="small fw-semibold mt-2">{{ $entry['actor'] }}</div>
                                    @foreach ($entry['changes'] as $change)
                                        <div class="konsultasi-history-change">
                                            <div class="small text-muted">{{ $change['label'] }}</div>
                                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                                <span class="konsultasi-history-value old">{{ $change['old'] }}</span>
                                                <i class="bi bi-arrow-right small text-muted"></i>
                                                <span class="konsultasi-history-value new">{{ $change['new'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="fw-normal mb-0 text-body">Riwayat Lead</h5></div>
                <div class="card-body">
                    <ul class="lead-timeline">
                        <li>
                            <span class="lead-timeline-icon bg-primary"><i class="bx bx-user-plus"></i></span>
                            <div><strong>Formulir diterima</strong><div class="small text-muted">{{ $consultation->created_at->translatedFormat('d M Y H:i') }}</div></div>
                        </li>
                        <li>
                            <span class="lead-timeline-icon {{ $consultation->follow_up_state === 'overdue' ? 'bg-danger' : 'bg-info text-dark' }}"><i class="bx bx-phone-call"></i></span>
                            <div>
                                <strong>Tindak lanjut: {{ $consultation->follow_up_label }}</strong>
                                <div class="small text-muted">{{ $consultation->follow_up_date_label }}</div>
                            </div>
                        </li>
                        <li>
                            <span class="lead-timeline-icon {{ $consultation->converted_to_booking_at ? 'bg-success' : 'bg-secondary' }}"><i class="bx bx-check-double"></i></span>
                            <div>
                                <strong>Konversi booking</strong>
                                <div class="small text-muted">{{ $consultation->converted_to_booking_at?->translatedFormat('d M Y H:i') ?? 'Belum dikonversi' }}</div>
                            </div>
                        </li>
                        <li>
                            <span class="lead-timeline-icon bg-light text-dark"><i class="bx bx-refresh"></i></span>
                            <div><strong>Terakhir diperbarui</strong><div class="small text-muted">{{ $consultation->updated_at->translatedFormat('d M Y H:i') }}</div></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@push('after-style')
<style>
    .konsultasi-history {
        max-height: 420px;
        overflow-y: auto;
    }

    .konsultasi-history-item {
        padding: 14px 18px;
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }

    .konsultasi-history-item:last-child {
        border-bottom: 0;
    }

    .konsultasi-history-change {
        margin-top: 10px;
        padding-left: 10px;
        border-left: 2px solid var(--bs-border-color, #e9ecef);
    }

    .konsultasi-history-value {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        min-width: 0;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .konsultasi-history-value.old {
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, .04));
        color: var(--bs-secondary-color, #6c757d);
        text-decoration: line-through;
    }

    .konsultasi-history-value.new {
        background: var(--modern-primary-soft, #f5f0ea);
        color: var(--modern-primary, #6e5b42);
        font-weight: 600;
    }
</style>
@endpush
@endsection
