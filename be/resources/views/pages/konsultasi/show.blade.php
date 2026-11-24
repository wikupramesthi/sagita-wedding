@extends('layouts.app')
@section('title', 'Detail Calon Pelanggan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Detail Calon Pelanggan" page="Konsultasi" active="Konsultasi" route="{{ route('konsultasi.show', $consultation->uuid) }}" />
@endsection

<section class="section konsultasi-detail">
    <div class="card lead-hero-card mb-3">
        <div class="card-body lead-hero-body">
            <div class="lead-hero-main">
                <span class="lead-hero-avatar">{{ $consultation->initials }}</span>
                <div class="lead-hero-identity">
                    <span class="lead-hero-kicker">Calon pelanggan</span>
                    <h4 class="lead-hero-name">{{ $consultation->full_name }}</h4>
                    <div class="lead-hero-meta">
                        <span><i class="bx bx-calendar me-1"></i>Masuk {{ $consultation->created_at->translatedFormat('d F Y H:i') }}</span>
                        <span><i class="{{ $consultation->source_icon }} me-1"></i>{{ $consultation->source_label }}</span>
                        <span><i class="bi bi-clock-history me-1"></i>Diperbarui {{ $consultation->updated_at->translatedFormat('d M Y H:i') }}</span>
                    </div>
                    <div class="lead-hero-badges">
                        <span class="badge {{ $consultation->lead_priority_badge_class }}"><i class="{{ $consultation->lead_priority_icon }} me-1"></i>Prioritas {{ $consultation->lead_priority }}</span>
                        <span class="badge {{ $consultation->lead_status_badge_class }}">{{ $consultation->lead_status_label }}</span>
                        <span class="badge {{ $consultation->sla_badge_class }}" title="Target respons: {{ $consultation->sla_deadline_label }}">{{ $consultation->sla_label }}</span>
                    </div>
                </div>
            </div>
            <div class="lead-hero-actions">
                @if ($consultation->converted_order_uuid)
                    <a href="{{ route('orders.show', $consultation->converted_order_uuid) }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-right me-1"></i> Lihat Pesanan
                    </a>
                @else
                    <a href="{{ route('orders.create', ['consultation' => $consultation->uuid]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Buat Pesanan
                    </a>
                @endif
                <a href="{{ route('konsultasi.whatsapp', $consultation->uuid) }}" target="_blank" rel="noopener" class="btn btn-success">
                    <i class="bx bxl-whatsapp me-1"></i> Hubungi WhatsApp
                </a>
                @can('konsultasi.index')
                    <a href="{{ route('konsultasi.edit', $consultation->uuid) }}" class="btn btn-light">
                        <i class="bi bi-pencil-square me-1"></i> Ubah Data
                    </a>
                @endcan
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots me-1"></i> Lainnya
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end lead-action-menu">
                        @can('konsultasi.export')
                            <li>
                                <a class="dropdown-item" href="{{ route('konsultasi.pdf-preview', ['uuid' => $consultation->uuid]) }}">
                                    <i class="bi bi-file-earmark-pdf me-2"></i> Preview PDF
                                </a>
                            </li>
                        @endcan
                        @if ($consultation->consultation_date)
                            <li>
                                <a class="dropdown-item" href="{{ route('konsultasi.calendar-ics', $consultation) }}">
                                    <i class="bi bi-download me-2"></i> Unduh ICS
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ $consultation->google_calendar_url }}" target="_blank" rel="noopener">
                                    <i class="bx bxl-google me-2"></i> Google Calendar
                                </a>
                            </li>
                        @endif
                        @can('konsultasi.index')
                            <li>
                                <form action="{{ route('konsultasi.remind', $consultation->uuid) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-bell me-2"></i> Kirim Pengingat
                                    </button>
                                </form>
                            </li>
                            @can('konsultasi.merge')
                                <li>
                                    <a class="dropdown-item" href="{{ route('konsultasi.duplicates') }}">
                                        <i class="bi bi-diagram-3 me-2"></i> Cek Duplikat
                                    </a>
                                </li>
                            @endcan
                        @endcan
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('konsultasi.index') }}">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke daftar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-12 col-xl-8">
            <div class="card lead-section-card mb-3">
                <div class="card-header lead-section-header">
                    <span class="lead-section-icon"><i class="bi bi-person-lines-fill"></i></span>
                    <div>
                        <h5>Profil Calon Pelanggan</h5>
                        <small>Identitas dan informasi kontak</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="lead-detail-grid">
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Nama Lengkap</div>
                            <div class="lead-detail-value">{{ $consultation->full_name }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Nomor WhatsApp</div>
                            <div class="lead-detail-value">{{ $consultation->whatsapp }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Email</div>
                            <div class="lead-detail-value">{{ $consultation->email ?: '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Sumber Informasi</div>
                            <div class="lead-detail-value"><i class="{{ $consultation->source_icon }} me-1"></i>{{ $consultation->source_label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card lead-section-card mb-3">
                <div class="card-header lead-section-header">
                    <span class="lead-section-icon"><i class="bi bi-heart"></i></span>
                    <div>
                        <h5>Rencana Pernikahan</h5>
                        <small>Detail acara dan preferensi paket</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="lead-detail-grid">
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Tanggal Pernikahan</div>
                            <div class="lead-detail-value">{{ $consultation->wedding_date?->translatedFormat('d F Y') ?? '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Estimasi Tamu</div>
                            <div class="lead-detail-value">{{ $consultation->guest_estimate ?: '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Lokasi / Venue</div>
                            <div class="lead-detail-value">{{ $consultation->venue ?: '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Paket yang Diminati</div>
                            <div class="lead-detail-value">{{ $consultation->package?->name ?? 'Belum memilih paket' }}</div>
                        </div>
                        <div class="lead-detail-item is-wide">
                            <div class="lead-detail-label">Konsep Pernikahan</div>
                            @if ($consultation->wedding_concept)
                                <div class="lead-detail-value is-preline">{{ $consultation->wedding_concept }}</div>
                            @else
                                <div class="lead-detail-value text-muted">—</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card lead-section-card">
                <div class="card-header lead-section-header">
                    <span class="lead-section-icon"><i class="bi bi-calendar-check"></i></span>
                    <div>
                        <h5>Jadwal & Catatan Konsultasi</h5>
                        <small>Penjadwalan, penanggung jawab, dan catatan internal</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="lead-detail-grid">
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Tanggal Konsultasi</div>
                            <div class="lead-detail-value">{{ $consultation->consultation_date?->translatedFormat('d F Y') ?? '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Jam Konsultasi</div>
                            <div class="lead-detail-value">{{ $consultation->consultation_time ?: '—' }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Metode</div>
                            <div class="lead-detail-value">{{ $consultation->method_label }}</div>
                        </div>
                        <div class="lead-detail-item">
                            <div class="lead-detail-label">Penanggung Jawab</div>
                            <div class="lead-detail-value">{{ $consultation->handler?->name ?? 'Belum ditugaskan' }}</div>
                        </div>
                        <div class="lead-detail-item is-wide">
                            <div class="lead-detail-label">Status Konsultasi</div>
                            <div class="mt-1"><span class="badge {{ $consultation->status_badge_class }}">{{ $consultation->status_label }}</span></div>
                        </div>
                        <div class="lead-detail-item is-wide">
                            <div class="lead-detail-label">Catatan Tim</div>
                            @if ($consultation->admin_notes)
                                <div class="lead-detail-value is-preline">{{ $consultation->admin_notes }}</div>
                            @else
                                <div class="lead-detail-value text-muted">Belum ada catatan.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card lead-journey-card">
                <div class="lead-journey-head">
                    <div class="lead-journey-heading">
                        <span class="lead-journey-icon"><i class="bi bi-signpost-split"></i></span>
                        <div>
                            <h5>Riwayat Lead</h5>
                            <small>Perjalanan calon pelanggan dari masuk hingga konversi</small>
                        </div>
                    </div>
                    <div class="lead-journey-progress-summary">
                        <div class="lead-journey-progress-value">{{ $consultation->lead_journey_progress }}%</div>
                        <div class="lead-journey-progress-label">{{ $consultation->lead_journey_done }} dari {{ count($consultation->lead_journey) }} tahap</div>
                    </div>
                </div>
                <div class="lead-journey-bar" role="progressbar" aria-label="Progres perjalanan lead" aria-valuenow="{{ $consultation->lead_journey_progress }}" aria-valuemin="0" aria-valuemax="100">
                    <span style="width: {{ $consultation->lead_journey_progress }}%"></span>
                </div>
                <div class="card-body lead-journey-body">
                    <ol class="lead-journey-steps">
                        @foreach ($consultation->lead_journey as $step)
                            <li class="lead-journey-step is-{{ $step['state'] }}">
                                <span class="lead-journey-step-icon"><i class="bx {{ $step['icon'] }}"></i></span>
                                <div class="lead-journey-step-body">
                                    <strong>{{ $step['title'] }}</strong>
                                    <time>{{ $step['time'] }}</time>
                                    <p>{{ $step['description'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                    <div class="lead-journey-next is-{{ $consultation->lead_journey_next_action['tone'] }}">
                        <span class="lead-journey-next-icon"><i class="bx bx-bolt-circle"></i></span>
                        <div>
                            <strong>Langkah berikutnya</strong>
                            <div>{{ $consultation->lead_journey_next_action['message'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            @can('konsultasi.index')
                <div class="card lead-section-card mb-3">
                    <div class="card-header lead-section-header">
                        <span class="lead-section-icon"><i class="bi bi-diagram-3"></i></span>
                        <div>
                            <h5>Perbarui Pipeline</h5>
                            <small>Status lead dan tindak lanjut</small>
                        </div>
                    </div>
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

            <div class="card lead-section-card mb-3">
                <div class="card-header lead-section-header">
                    <span class="lead-section-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h5>Skor Ketertarikan</h5>
                        <small>Indikator kualitas lead</small>
                    </div>
                </div>
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

            <div class="card lead-section-card mb-3">
                <div class="card-header lead-section-header d-flex justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="lead-section-icon"><i class="bi bi-clock-history"></i></span>
                        <div>
                            <h5>Riwayat Perubahan</h5>
                            <small>Aktivitas dan perubahan data</small>
                        </div>
                    </div>
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

        </div>
    </div>
</section>

@push('after-style')
<style>
    .lead-hero-body {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem 1.5rem;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.35rem;
    }

    .lead-hero-main {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
        flex: 1 1 420px;
    }

    .lead-hero-identity {
        min-width: 0;
    }

    .lead-hero-kicker {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--bs-secondary-color, #6c757d);
        margin-bottom: 0.15rem;
    }

    .lead-hero-name {
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.25;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .lead-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem 1rem;
        margin-top: 0.45rem;
        font-size: 0.8rem;
        color: var(--bs-secondary-color, #6c757d);
    }

    .lead-hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.65rem;
    }

    .lead-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        flex: 0 1 auto;
    }

    .lead-action-menu {
        min-width: 220px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .lead-action-menu form {
        margin: 0;
    }

    .lead-action-menu .dropdown-item {
        white-space: normal;
        padding: 0.5rem 1rem;
    }

    .lead-section-card {
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 1rem;
        overflow: hidden;
    }

    #main-content .card .lead-section-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.15rem;
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, 0.02));
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }

    .lead-section-icon {
        width: 36px;
        height: 36px;
        flex: 0 0 auto;
        border-radius: 10px;
        display: grid;
        place-items: center;
        background: rgba(94, 114, 228, 0.12);
        color: #5e72e4;
        font-size: 1rem;
    }

    .lead-section-header h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .lead-section-header small {
        display: block;
        margin-top: 0.1rem;
        font-size: 0.75rem;
        color: var(--bs-secondary-color, #6c757d);
    }

    .lead-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem 1.25rem;
    }

    .lead-detail-item {
        min-width: 0;
        padding-bottom: 0.85rem;
        border-bottom: 1px dashed var(--bs-border-color, #e9ecef);
    }

    .lead-detail-item.is-wide {
        grid-column: 1 / -1;
        border-bottom: 0;
        padding-bottom: 0;
    }

    .lead-detail-item:nth-last-child(1) {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .lead-detail-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--bs-secondary-color, #6c757d);
        margin-bottom: 0.3rem;
    }

    .lead-detail-value {
        font-size: 0.95rem;
        font-weight: 500;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .lead-detail-value.is-preline {
        white-space: pre-line;
    }

    .konsultasi-history {
        max-height: 360px;
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
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, 0.04));
        color: var(--bs-secondary-color, #6c757d);
        text-decoration: line-through;
    }

    .konsultasi-history-value.new {
        background: var(--modern-primary-soft, #f5f0ea);
        color: var(--modern-primary, #6e5b42);
        font-weight: 600;
    }

    @media (max-width: 575.98px) {
        .lead-hero-body {
            align-items: flex-start;
            padding: 1rem;
        }

        .lead-hero-main {
            align-items: flex-start;
            flex-basis: 100%;
            gap: 0.75rem;
        }

        .lead-hero-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            font-size: 1.05rem;
        }

        .lead-hero-name {
            font-size: 1.15rem;
        }

        .lead-hero-meta {
            font-size: 0.75rem;
        }

        .lead-hero-actions {
            flex-basis: 100%;
        }

        .lead-hero-actions .btn {
            flex: 1 1 auto;
        }

        .lead-section-header {
            padding: 0.75rem 1rem;
        }

        .lead-detail-grid {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }

        .lead-detail-item {
            border-bottom: 1px dashed var(--bs-border-color, #e9ecef);
            padding-bottom: 0.85rem;
        }

        .lead-detail-item.is-wide {
            grid-column: auto;
        }

        .lead-detail-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }
    }
</style>
@endpush
@endsection
