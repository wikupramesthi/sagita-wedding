@extends('layouts.app')
@section('title', 'Monitoring Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Monitoring Konsultasi" page="Data Prospek" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

<section class="section">
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3 mb-3">
        <i class="bx bx-user-check fs-4 mt-1"></i>
        <div>
            <strong>Pipeline Calon Pelanggan</strong>
            <div class="small">Pantau calon konsumen yang sudah mengisi formulir konsultasi, prioritaskan yang paling serius, lalu tindak lanjut hingga booking.</div>
        </div>
    </div>

    @php
        $leadStats = [
            ['label' => 'Total Lead', 'value' => $stats['total'], 'icon' => 'bi bi-people', 'class' => 'purple', 'route' => route('konsultasi.index')],
            ['label' => 'Lead Baru', 'value' => $stats['new'], 'icon' => 'bi bi-person-plus', 'class' => 'blue', 'route' => route('konsultasi.index', ['lead' => 'New'])],
            ['label' => 'Lead Panas', 'value' => $stats['hot'], 'icon' => 'bi bi-fire', 'class' => 'red', 'route' => route('konsultasi.index', ['lead' => 'Interested'])],
            ['label' => 'Follow-up Terlewat', 'value' => $stats['overdue'], 'icon' => 'bi bi-clock-history', 'class' => 'yellow', 'route' => route('konsultasi.index', ['follow_up' => 'overdue'])],
            ['label' => 'Terkonversi', 'value' => $stats['converted'], 'icon' => 'bi bi-check2-circle', 'class' => 'green', 'route' => route('konsultasi.index', ['lead' => 'Converted'])],
            ['label' => 'Tingkat Konversi', 'value' => $stats['conversion_rate'].'%', 'icon' => 'bi bi-graph-up-arrow', 'class' => 'turquoise', 'route' => route('konsultasi.index')],
        ];
    @endphp
    <div class="row g-3 mb-3">
        @foreach ($leadStats as $stat)
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stats-icon {{ $stat['class'] }}"><i class="{{ $stat['icon'] }}"></i></div>
                        <div class="min-w-0 flex-grow-1">
                            <h6 class="text-muted font-semibold mb-0 text-truncate">{{ $stat['label'] }}</h6>
                            <a href="{{ $stat['route'] }}" class="text-decoration-none">
                                <h4 class="font-extrabold mb-0">{{ $stat['value'] }}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (session('error'))
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-3 mb-3">
            <i class="bx bx-error-circle fs-4 mt-1"></i>
            <div class="min-w-0 flex-grow-1">
                <strong class="d-block">Perhatian</strong>
                <div class="small">{{ session('error') }}</div>
                @if (session('duplicate_uuid'))
                    <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-sm btn-warning mt-2"><i class="bi bi-diagram-3 me-1"></i> Lihat & Gabungkan Duplikat</a>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-7">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h4 class="card-title h6 mb-0"><i class="bi bi-pie-chart me-2"></i> Konversi per Sumber</h4>
                    <span class="small text-muted">{{ $stats['converted'] }} dari {{ $stats['total'] }} lead</span>
                </div>
                <div class="card-body">
                    <div class="konsultasi-source-analytics">
                        @foreach ($stats['source_conversion'] as $source => $detail)
                            @if ($detail['total'] > 0)
                                <div class="konsultasi-source-row">
                                    <div class="min-w-0 d-flex justify-content-between gap-2">
                                        <span class="small fw-semibold text-truncate"><i class="bx {{ \App\Models\User::SOURCE_ICONS[$source] ?? 'bx-help-circle' }} me-1"></i>{{ $detail['label'] }}</span>
                                        <span class="small text-muted flex-shrink-0">{{ $detail['converted'] }}/{{ $detail['total'] }} · {{ $detail['rate'] }}%</span>
                                    </div>
                                    <div class="progress mt-1" style="height:6px" role="progressbar" aria-label="Konversi {{ $detail['label'] }}" aria-valuenow="{{ $detail['rate'] }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success" style="width: {{ $detail['rate'] }}%"></div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h4 class="card-title h6 mb-0"><i class="bi bi-hourglass-split me-2"></i> Usia Lead Aktif</h4>
                </div>
                <div class="card-body">
                    <div class="konsultasi-aging-grid">
                        @foreach (\App\Models\Consultation::AGING_LABELS as $key => $label)
                            <div class="konsultasi-aging-item">
                                <div class="fw-extrabold h5 mb-0">{{ $stats['aging'][$key] }}</div>
                                <div class="small text-muted">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div>
                <h4 class="fw-normal mb-0 text-body">Daftar Calon Pelanggan</h4>
                <div class="small text-muted">Klik status atau penanggung jawab pada baris untuk memperbarui pipeline.</div>
            </div>
            @can('konsultasi.index')
                <div class="d-flex flex-wrap align-items-center konsultasi-toolbar" role="group" aria-label="Aksi konsultasi">
                    <a href="{{ route('konsultasi.index', request()->query()) }}" class="btn btn-sm btn-primary btn-konsultasi active" aria-current="page"><i class="bi bi-list"></i> Tabel</a>
                    <a href="{{ route('konsultasi.kanban', request()->query()) }}" class="btn btn-sm btn-soft-secondary btn-konsultasi"><i class="bi bi-columns-gap"></i> Kanban</a>
                    <a href="{{ route('konsultasi.calendar', request()->query()) }}" class="btn btn-sm btn-soft-secondary btn-konsultasi"><i class="bi bi-calendar3"></i> Kalender</a>
                    <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-sm btn-soft-warning btn-konsultasi"><i class="bi bi-diagram-3"></i> Duplikat</a>
                    <a href="{{ route('konsultasi.export', request()->query()) }}" class="btn btn-sm btn-soft-success btn-konsultasi"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                    <a href="{{ route('konsultasi.pdf-preview', request()->query()) }}" class="btn btn-sm btn-soft-danger btn-konsultasi"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                    <a href="{{ route('konsultasi.create') }}" class="btn btn-sm btn-primary btn-konsultasi"><i class="bi bi-plus-circle"></i> Tambah</a>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('konsultasi.index') }}" class="row g-2 align-items-end mb-3">
                <div class="col-12 col-md-4">
                    <label for="filter-q" class="form-label mb-1 small text-muted">Cari calon pelanggan</label>
                    <input type="search" id="filter-q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nama, email, WhatsApp, venue, konsep...">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-status" class="form-label mb-1 small text-muted">Status konsultasi</label>
                    <select id="filter-status" name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\Consultation::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-lead" class="form-label mb-1 small text-muted">Status lead</label>
                    <select id="filter-lead" name="lead" class="form-select">
                        <option value="">Semua lead</option>
                        @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['lead'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-follow-up" class="form-label mb-1 small text-muted">Tindak lanjut</label>
                    <select id="filter-follow-up" name="follow_up" class="form-select">
                        <option value="">Semua</option>
                        @foreach (\App\Models\Consultation::FOLLOW_UP_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['follow_up'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-source" class="form-label mb-1 small text-muted">Informasi dari mana</label>
                    <select id="filter-source" name="source" class="form-select">
                        <option value="">Semua sumber</option>
                        @foreach ($sources as $value => $label)
                            <option value="{{ $value }}" @selected($filters['source'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-start" class="form-label mb-1 small text-muted">Dari tanggal</label>
                    <input type="date" id="filter-start" name="start_date" value="{{ $filters['start_date'] }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-end" class="form-label mb-1 small text-muted">Sampai tanggal</label>
                    <input type="date" id="filter-end" name="end_date" value="{{ $filters['end_date'] }}" class="form-control">
                </div>
                <div class="col-12 col-md-4 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                    @if (collect($filters)->filter()->isNotEmpty())
                        <span class="badge bg-soft-primary text-primary">Filter aktif</span>
                    @endif
                </div>
            </form>

            @can('konsultasi.index')
                <form id="konsultasi-bulk-form" method="POST" action="{{ route('konsultasi.bulk') }}" class="konsultasi-bulk-toolbar d-flex flex-wrap gap-2 align-items-center mb-3">
                    @csrf
                    <span class="small text-muted me-auto"><i class="bi bi-check2-square me-1"></i> <span id="konsultasi-selected-count">0</span> data dipilih</span>
                    <select name="action" id="konsultasi-bulk-action" class="form-select form-select-sm w-auto" required>
                        <option value="">Aksi massal...</option>
                        <option value="lead">Ubah status lead</option>
                        <option value="pic">Tugaskan penanggung jawab</option>
                        <option value="follow-up-done">Tandai follow-up selesai</option>
                        <option value="delete">Hapus data</option>
                    </select>
                    <select name="lead_status" id="konsultasi-bulk-lead" class="form-select form-select-sm w-auto d-none" aria-label="Status lead massal">
                        @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="handled_by" id="konsultasi-bulk-pic" class="form-select form-select-sm w-auto d-none" aria-label="Penanggung jawab massal">
                        <option value="">Belum ditugaskan</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary" id="konsultasi-bulk-submit" disabled><i class="bi bi-lightning-charge me-1"></i> Terapkan</button>
                </form>
            @endcan

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 lead-table">
                    <thead>
                        <tr>
                            @can('konsultasi.index')
                                <th style="width: 36px;">
                                    <input class="form-check-input" type="checkbox" id="konsultasi-select-all" aria-label="Pilih semua data">
                                </th>
                            @endcan
                            <th style="width: 40px;">#</th>
                            <th>Calon Pelanggan</th>
                            <th>Kontak</th>
                            <th>Rencana</th>
                            <th>Konsultasi</th>
                            <th>Prioritas</th>
                            <th>Pipeline</th>
                            <th>Penanggung Jawab</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultations as $index => $item)
                            <tr class="{{ $item->follow_up_state === 'overdue' ? 'table-danger-subtle' : '' }}">
                                @can('konsultasi.index')
                                    <td>
                                        <input class="form-check-input konsultasi-row-check" type="checkbox" name="ids[]" value="{{ $item->uuid }}" form="konsultasi-bulk-form" aria-label="Pilih {{ $item->full_name }}">
                                    </td>
                                @endcan
                                <td class="text-muted small">{{ $consultations->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="lead-avatar">{{ $item->initials }}</span>
                                        <div class="min-w-0">
                                            <a href="{{ route('konsultasi.show', $item->uuid) }}" class="fw-semibold text-decoration-none text-dark">{{ $item->full_name }}</a>
                                            <div class="small text-muted"><i class="{{ $item->source_icon }} me-1"></i>{{ $item->source_label }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ $item->whatsapp_link }}?text={{ rawurlencode($item->whatsapp_message) }}" target="_blank" rel="noopener" class="small text-decoration-none"><i class="bx bxl-whatsapp text-success"></i> {{ $item->whatsapp }}</a>
                                    @if ($item->email)
                                        <div class="small text-muted text-truncate" style="max-width: 190px;">{{ $item->email }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $item->wedding_date?->translatedFormat('d M Y') ?? 'Tanggal belum diisi' }}</div>
                                    <div class="small text-muted">{{ $item->guest_estimate ? $item->guest_estimate.' tamu' : 'Tamu belum diisi' }}</div>
                                    @if ($item->venue)
                                        <div class="small text-muted text-truncate" style="max-width: 190px;">{{ $item->venue }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $item->package?->name ?? 'Belum pilih paket' }}</div>
                                    <span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span>
                                    <div class="small text-muted mt-1">{{ $item->method_label }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->lead_priority_badge_class }}"><i class="{{ $item->lead_priority_icon }} me-1"></i>{{ $item->lead_priority }}</span>
                                    <div class="small text-muted mt-1">Skor {{ $item->lead_score }}</div>
                                </td>
                                <td>
                                    <form id="lead-form-{{ $item->uuid }}" action="{{ route('konsultasi.update-lead', $item->uuid) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="lead_status" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Status lead {{ $item->full_name }}">
                                            @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                                                <option value="{{ $value }}" @selected($item->lead_status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <span class="badge {{ $item->follow_up_badge_class }} mt-2">{{ $item->follow_up_label }}</span>
                                        <div class="small text-muted">{{ $item->follow_up_date_label }}</div>
                                    </form>
                                </td>
                                <td>
                                    <form id="pic-form-{{ $item->uuid }}" action="{{ route('konsultasi.update-lead', $item->uuid) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="handled_by" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Penanggung jawab {{ $item->full_name }}">
                                            <option value="">Belum ditugaskan</option>
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}" @selected($item->handled_by === $admin->id)>{{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('konsultasi.show', $item->uuid) }}" class="btn btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                        @can('konsultasi.index')
                                            <form action="{{ route('konsultasi.remind', $item->uuid) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning text-dark" title="Kirim pengingat"><i class="bi bi-bell"></i></button>
                                            </form>
                                            <a href="{{ route('konsultasi.edit', $item->uuid) }}" class="btn btn-success" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                            <button type="button" class="btn btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal_{{ $item->uuid }}"><i class="bi bi-trash"></i></button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->can('konsultasi.index') ? 10 : 9 }}" class="text-center py-5 text-muted">Belum ada calon pelanggan yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $consultations->links() }}
            </div>
        </div>
    </div>
</section>

@can('konsultasi.index')
    @foreach ($consultations as $item)
        <div class="modal fade" id="deleteModal_{{ $item->uuid }}" tabindex="-1" aria-labelledby="deleteModalLabel_{{ $item->uuid }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel_{{ $item->uuid }}">Hapus Konsultasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Yakin ingin menghapus data <strong>{{ $item->full_name }}</strong>?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('konsultasi.destroy', $item->uuid) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endcan

@push('after-style')
<style>
    #main-content .konsultasi-toolbar {
        gap: .5rem;
    }

    #main-content .konsultasi-toolbar .btn-konsultasi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        min-height: 34px;
        padding: .375rem .85rem;
        font-size: .8125rem;
        font-weight: 500;
        line-height: 1.2;
        white-space: nowrap;
    }

    #main-content .konsultasi-toolbar .btn-primary {
        box-shadow: 0 6px 16px rgba(110, 91, 66, .22);
    }

    #main-content .konsultasi-toolbar .btn-soft-secondary,
    #main-content .konsultasi-toolbar .btn-soft-warning,
    #main-content .konsultasi-toolbar .btn-soft-success,
    #main-content .konsultasi-toolbar .btn-soft-danger {
        border-width: 1px;
        border-style: solid;
    }

    #main-content .konsultasi-toolbar .btn-soft-secondary {
        background: var(--modern-primary-soft, #f5f0ea);
        border-color: #e3d8c8;
        color: var(--modern-primary, #6e5b42);
    }

    #main-content .konsultasi-toolbar .btn-soft-secondary:hover {
        background: #efe6dc;
        border-color: #d6c7b4;
        color: var(--modern-primary-600, #524330);
    }

    #main-content .konsultasi-toolbar .btn-soft-warning {
        background: #fbf3e4;
        border-color: #ecd9b3;
        color: #8a6523;
    }

    #main-content .konsultasi-toolbar .btn-soft-warning:hover {
        background: #f7ead2;
        border-color: #e1c795;
        color: #6f5016;
    }

    #main-content .konsultasi-toolbar .btn-soft-success {
        background: #edf6f1;
        border-color: #c9e2d5;
        color: #2f6f52;
    }

    #main-content .konsultasi-toolbar .btn-soft-success:hover {
        background: #dfeee5;
        border-color: #b3d7c3;
        color: #235941;
    }

    #main-content .konsultasi-toolbar .btn-soft-danger {
        background: #fbeded;
        border-color: #ecc9cc;
        color: #9a4a50;
    }

    #main-content .konsultasi-toolbar .btn-soft-danger:hover {
        background: #f7dfe1;
        border-color: #e3b4b9;
        color: #813a40;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-secondary {
        background: rgba(166, 129, 79, .14);
        border-color: #6b5840;
        color: #e0cdb2;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-warning {
        background: rgba(214, 158, 46, .14);
        border-color: #7c5a1f;
        color: #f0cb8e;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-success {
        background: rgba(74, 172, 130, .14);
        border-color: #356550;
        color: #9ad9bb;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-danger {
        background: rgba(191, 90, 101, .14);
        border-color: #71404a;
        color: #eab0b6;
    }

    @media (max-width: 575.98px) {
        #main-content .konsultasi-toolbar .btn-konsultasi {
            flex: 1 1 auto;
        }
    }

    .konsultasi-source-analytics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px 18px;
    }

    .konsultasi-source-row {
        min-width: 0;
    }

    .konsultasi-aging-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
    }

    .konsultasi-aging-item {
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 10px;
        padding: 10px 8px;
        text-align: center;
        min-width: 0;
    }

    .konsultasi-bulk-toolbar {
        border: 1px dashed var(--bs-border-color, #e9ecef);
        border-radius: 10px;
        padding: 8px 10px;
        background: var(--bs-tertiary-bg, rgba(0, 0, 0, .02));
    }

    #main-content .table-responsive .lead-table {
        min-width: 1180px !important;
    }

    #main-content .table-responsive .lead-table .form-select-sm {
        min-width: 0 !important;
        width: 100% !important;
    }

    @media (max-width: 575.98px) {
        .konsultasi-aging-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .konsultasi-bulk-toolbar > * {
            flex: 1 1 100% !important;
            width: 100% !important;
        }

        #main-content .table-responsive .lead-table th:nth-child(3),
        #main-content .table-responsive .lead-table td:nth-child(3),
        #main-content .table-responsive .lead-table th:nth-child(4),
        #main-content .table-responsive .lead-table td:nth-child(4),
        #main-content .table-responsive .lead-table th:nth-child(5),
        #main-content .table-responsive .lead-table td:nth-child(5),
        #main-content .table-responsive .lead-table th:nth-child(6),
        #main-content .table-responsive .lead-table td:nth-child(6),
        #main-content .table-responsive .lead-table th:nth-child(9),
        #main-content .table-responsive .lead-table td:nth-child(9) {
            display: none;
        }
    }
</style>
@endpush

@push('after-script')
<script>
    (function () {
        var form = document.getElementById('konsultasi-bulk-form');
        if (!form) return;

        var selectAll = document.getElementById('konsultasi-select-all');
        var checks = Array.prototype.slice.call(document.querySelectorAll('.konsultasi-row-check'));
        var count = document.getElementById('konsultasi-selected-count');
        var submit = document.getElementById('konsultasi-bulk-submit');
        var action = document.getElementById('konsultasi-bulk-action');
        var lead = document.getElementById('konsultasi-bulk-lead');
        var pic = document.getElementById('konsultasi-bulk-pic');

        function refresh() {
            var selected = checks.filter(function (checkbox) { return checkbox.checked; });
            if (count) count.textContent = selected.length;
            if (submit) submit.disabled = selected.length === 0 || !action.value;
            if (selectAll) selectAll.checked = selected.length > 0 && selected.length === checks.length;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checks.forEach(function (checkbox) { checkbox.checked = selectAll.checked; });
                refresh();
            });
        }

        checks.forEach(function (checkbox) { checkbox.addEventListener('change', refresh); });

        if (action) {
            action.addEventListener('change', function () {
                if (lead) lead.classList.toggle('d-none', action.value !== 'lead');
                if (pic) pic.classList.toggle('d-none', action.value !== 'pic');
                if (lead) lead.required = action.value === 'lead';
                if (pic) pic.required = false;
                refresh();
            });
        }

        form.addEventListener('submit', function (event) {
            var selected = checks.filter(function (checkbox) { return checkbox.checked; });
            if (!selected.length || !action.value) {
                event.preventDefault();
                return;
            }

            if (action.value === 'delete' && !window.confirm('Hapus ' + selected.length + ' data konsultasi yang dipilih?')) {
                event.preventDefault();
            }
        });

        refresh();
    })();
</script>
@endpush
@endsection
