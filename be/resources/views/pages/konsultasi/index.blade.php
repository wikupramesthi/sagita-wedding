@extends('layouts.app')
@section('title', 'Monitoring Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Monitoring Konsultasi" page="Konsultasi" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

<section class="section">
    <div class="alert alert-info border-0 shadow-sm d-none d-sm-flex align-items-start gap-3 mb-3">
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
            ['label' => 'SLA Terlewat', 'value' => $stats['sla_breach'], 'icon' => 'bi bi-alarm', 'class' => 'red', 'route' => route('konsultasi.index', ['sla' => 'breach'])],
            ['label' => 'SLA Mendekat', 'value' => $stats['sla_warning'], 'icon' => 'bi bi-stopwatch', 'class' => 'yellow', 'route' => route('konsultasi.index', ['sla' => 'warning'])],
            ['label' => 'Terkonversi', 'value' => $stats['converted'], 'icon' => 'bi bi-check2-circle', 'class' => 'green', 'route' => route('konsultasi.index', ['lead' => 'Converted'])],
            ['label' => 'Tingkat Konversi', 'value' => $stats['conversion_rate'].'%', 'icon' => 'bi bi-graph-up-arrow', 'class' => 'turquoise', 'route' => route('konsultasi.index')],
        ];
    @endphp
    <details class="konsultasi-mobile-fold" open>
        <summary><i class="bi bi-grid me-1"></i> Ringkasan lead</summary>
        <div class="konsultasi-stat-grid mb-3">
            @foreach ($leadStats as $stat)
                <div>
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
    </details>

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
        <div class="col-12 col-xl-7 d-none d-xl-block">
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
        <div class="col-12 col-xl-5 d-none d-xl-block">
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
                <details class="konsultasi-mobile-fold konsultasi-toolbar-fold" open>
                    <summary><i class="bi bi-lightning-charge me-1"></i> Aksi cepat</summary>
                    <div class="d-flex flex-wrap align-items-center konsultasi-toolbar" role="group" aria-label="Aksi konsultasi">
                        <a href="{{ route('konsultasi.index', request()->query()) }}" class="btn btn-sm btn-soft-blue btn-konsultasi active" aria-current="page"><i class="bi bi-list"></i> Tabel</a>
                        <a href="{{ route('konsultasi.kanban', request()->query()) }}" class="btn btn-sm btn-soft-indigo btn-konsultasi"><i class="bi bi-columns-gap"></i> Kanban</a>
                        <a href="{{ route('konsultasi.calendar', request()->query()) }}" class="btn btn-sm btn-soft-cyan btn-konsultasi"><i class="bi bi-calendar3"></i> Kalender</a>
                        @can('konsultasi.import')
                            <a href="{{ route('konsultasi.import') }}" class="btn btn-sm btn-soft-purple btn-konsultasi"><i class="bi bi-file-earmark-arrow-up"></i> Impor</a>
                        @endcan
                        @can('konsultasi.merge')
                            <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-sm btn-soft-warning btn-konsultasi"><i class="bi bi-diagram-3"></i> Duplikat</a>
                        @endcan
                        @can('konsultasi.export')
                            <a href="{{ route('konsultasi.export', request()->query()) }}" class="btn btn-sm btn-soft-success btn-konsultasi"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                            <a href="{{ route('konsultasi.pdf-preview', request()->query()) }}" class="btn btn-sm btn-soft-danger btn-konsultasi"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                        @endcan
                        <div class="dropdown d-none d-sm-block">
                            <button type="button" class="btn btn-sm btn-light btn-konsultasi" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Pilih kolom tabel"><i class="bi bi-layout-three-columns me-1"></i> Kolom</button>
                            <ul class="dropdown-menu dropdown-menu-end p-2">
                                <li class="dropdown-header p-0 px-2 small">Tampilkan kolom</li>
                                <li><label class="dropdown-item-text small d-flex align-items-center gap-2"><input type="checkbox" data-konsultasi-column="contact" checked> Kontak</label></li>
                                <li><label class="dropdown-item-text small d-flex align-items-center gap-2"><input type="checkbox" data-konsultasi-column="plan" checked> Rencana</label></li>
                                <li><label class="dropdown-item-text small d-flex align-items-center gap-2"><input type="checkbox" data-konsultasi-column="consult" checked> Konsultasi</label></li>
                            </ul>
                        </div>
                        <a href="{{ route('konsultasi.create') }}" class="btn btn-sm btn-primary btn-konsultasi"><i class="bi bi-plus-circle"></i> Tambah</a>
                    </div>
                </details>
            @endcan
        </div>

        <div class="card-body">
            <details class="konsultasi-mobile-fold" open>
                <summary><i class="bi bi-funnel me-1"></i> Filter, pencarian & aksi massal</summary>
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
                    <label for="filter-sla" class="form-label mb-1 small text-muted">SLA respons</label>
                    <select id="filter-sla" name="sla" class="form-select">
                        <option value="">Semua SLA</option>
                        @foreach (\App\Models\Consultation::SLA_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['sla'] === $value)>{{ $label }}</option>
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
                    <a href="{{ route('konsultasi.index', ['all' => 1]) }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </div>
            </form>

            @if ($activeFilters->isNotEmpty())
                <div class="d-flex flex-wrap align-items-center gap-1 mb-3 konsultasi-active-filters">
                    <span class="small text-muted me-1"><i class="bi bi-funnel me-1"></i> Filter aktif:</span>
                    @foreach ($activeFilters as $chip)
                        @php
                            $chipQuery = array_diff_key(request()->query(), [$chip['key'] => null]);
                        @endphp
                        <a href="{{ route('konsultasi.index', $chipQuery) }}" class="badge rounded-pill bg-soft-primary text-primary d-inline-flex align-items-center gap-1" title="Hapus filter {{ $chip['label'] }}">
                            <span class="text-truncate" style="max-width:150px">{{ $chip['label'] }}: {{ $chip['value'] }}</span>
                            <i class="bi bi-x"></i>
                        </a>
                    @endforeach
                    <a href="{{ route('konsultasi.index', ['all' => 1]) }}" class="small text-decoration-none ms-1">Hapus semua</a>
                </div>
            @endif

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="small text-muted"><i class="bi bi-bookmark me-1"></i> Filter tersimpan:</span>
                @forelse ($savedFilters as $saved)
                    <span class="badge bg-light text-dark border d-inline-flex align-items-center gap-1">
                        <a href="{{ route('konsultasi.saved-filters.apply', $saved) }}" class="text-decoration-none text-dark">{{ $saved->name }}</a>
                        @if ($saved->is_default)
                            <i class="bi bi-star-fill text-warning" title="Filter default"></i>
                        @endif
                        <form method="POST" action="{{ route('konsultasi.saved-filters.default', $saved) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="is_default" value="{{ $saved->is_default ? 0 : 1 }}">
                            <button type="submit" class="btn btn-link btn-sm p-0 text-warning" aria-label="{{ $saved->is_default ? 'Nonaktifkan default' : 'Jadikan default' }} {{ $saved->name }}"><i class="bi {{ $saved->is_default ? 'bi-star-fill' : 'bi-star' }}"></i></button>
                        </form>
                        <form method="POST" action="{{ route('konsultasi.saved-filters.destroy', $saved) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm p-0 text-danger" aria-label="Hapus filter {{ $saved->name }}"><i class="bi bi-x"></i></button>
                        </form>
                    </span>
                @empty
                    <span class="small text-muted">Belum ada</span>
                @endforelse
                <form method="POST" action="{{ route('konsultasi.saved-filters.store') }}" class="d-flex flex-wrap gap-1 ms-auto align-items-center">
                    @csrf
                    <input type="hidden" name="filters" value="{{ json_encode($filters) }}">
                    <input type="text" name="name" class="form-control form-control-sm" style="width:150px" placeholder="Nama filter" maxlength="100" required>
                    <label class="small text-muted d-flex align-items-center gap-1 mb-0">
                        <input type="checkbox" name="is_default" value="1"> Default
                    </label>
                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-bookmark-plus me-1"></i> Simpan</button>
                </form>
            </div>

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
            </details>

            <div class="table-responsive konsultasi-table-shell" id="konsultasiTableShell">
                <table class="table table-hover align-middle mb-0 lead-table">
                    <colgroup>
                        @can('konsultasi.index')
                            <col class="col-check">
                        @endcan
                        <col class="col-no">
                        <col class="col-customer">
                        <col class="col-contact">
                        <col class="col-plan">
                        <col class="col-consult">
                        <col class="col-pipeline">
                        <col class="col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            @can('konsultasi.index')
                                <th class="col-check">
                                    <input class="form-check-input" type="checkbox" id="konsultasi-select-all" aria-label="Pilih semua data">
                                </th>
                            @endcan
                            <th class="col-no">#</th>
                            <th class="col-customer">Calon Pelanggan</th>
                            <th class="col-contact">Kontak</th>
                            <th class="col-plan">Rencana</th>
                            <th class="col-consult">Konsultasi</th>
                            <th class="col-pipeline">Pipeline</th>
                            <th class="col-action text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultations as $index => $item)
                            <tr class="{{ $item->follow_up_state === 'overdue' ? 'table-danger-subtle' : '' }}">
                                @can('konsultasi.index')
                                    <td class="col-check">
                                        <input class="form-check-input konsultasi-row-check" type="checkbox" name="ids[]" value="{{ $item->uuid }}" form="konsultasi-bulk-form" aria-label="Pilih {{ $item->full_name }}">
                                    </td>
                                @endcan
                                <td class="col-no text-muted small">{{ $consultations->firstItem() + $index }}</td>
                                <td class="col-customer">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="lead-avatar">{{ $item->initials }}</span>
                                        <div class="min-w-0">
                                            <a href="{{ route('konsultasi.show', $item->uuid) }}" class="fw-semibold text-decoration-none text-dark">{{ $item->full_name }}</a>
                                            <div class="small text-muted"><i class="{{ $item->source_icon }} me-1"></i>{{ $item->source_label }}</div>
                                        </div>
                                    </div>
                                    <form id="pic-form-{{ $item->uuid }}" action="{{ route('konsultasi.update-lead', $item->uuid) }}" method="POST" class="mt-2">
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
                                <td class="col-contact">
                                    <a href="{{ route('konsultasi.whatsapp', $item) }}" target="_blank" rel="noopener" class="small text-decoration-none"><i class="bx bxl-whatsapp text-success"></i> {{ $item->whatsapp }}</a>
                                    @if ($item->email)
                                        <div class="small text-muted text-truncate">{{ $item->email }}</div>
                                    @endif
                                </td>
                                <td class="col-plan">
                                    <div class="small fw-semibold">{{ $item->wedding_date?->translatedFormat('d M Y') ?? 'Tanggal belum diisi' }}</div>
                                    <div class="small text-muted">{{ $item->guest_estimate ? $item->guest_estimate.' tamu' : 'Tamu belum diisi' }}</div>
                                    @if ($item->venue)
                                        <div class="small text-muted text-truncate">{{ $item->venue }}</div>
                                    @endif
                                </td>
                                <td class="col-consult">
                                    <div class="small">{{ $item->package?->name ?? 'Belum pilih paket' }}</div>
                                    <span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span>
                                    <div class="small text-muted mt-1">{{ $item->method_label }}</div>
                                </td>
                                <td class="col-pipeline">
                                    <form id="lead-form-{{ $item->uuid }}" action="{{ route('konsultasi.update-lead', $item->uuid) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="lead_status" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Status lead {{ $item->full_name }}">
                                            @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                                                <option value="{{ $value }}" @selected($item->lead_status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="d-flex align-items-center gap-1 mt-1 konsultasi-followup-control">
                                            <input type="date" name="follow_up_at" value="{{ $item->follow_up_at?->format('Y-m-d') }}" class="form-control form-control-sm" aria-label="Tanggal tindak lanjut {{ $item->full_name }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Simpan tanggal tindak lanjut"><i class="bi bi-check2"></i></button>
                                        </div>
                                        <span class="badge konsultasi-followup-badge {{ $item->follow_up_badge_class }} mt-2" data-state="{{ $item->follow_up_state }}">{{ $item->follow_up_label }}</span>
                                        <div class="small text-muted konsultasi-followup-date">{{ $item->follow_up_date_label }}</div>
                                        <div class="mt-2"><span class="badge konsultasi-sla-badge {{ $item->sla_badge_class }}" data-state="{{ $item->sla_state }}" title="Target respons: {{ $item->sla_deadline_label }}">{{ $item->sla_label }}</span></div>
                                    </form>
                                </td>
                                <td class="col-action text-end">
                                    <div class="dropdown konsultasi-action-menu">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="konsultasiAction_{{ $item->uuid }}" data-bs-toggle="dropdown" data-bs-container="body" data-bs-boundary="viewport" aria-expanded="false" title="Aksi">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="konsultasiAction_{{ $item->uuid }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('konsultasi.show', $item->uuid) }}"><i class="bi bi-eye me-2 text-info"></i> Detail</a>
                                            </li>
                                            @can('konsultasi.index')
                                                <li>
                                                    <form action="{{ route('konsultasi.remind', $item->uuid) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"><i class="bi bi-bell me-2 text-warning"></i> Kirim pengingat</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('konsultasi.edit', $item->uuid) }}"><i class="bi bi-pencil-square me-2 text-success"></i> Ubah data</a>
                                                </li>
                                                @can('konsultasi.delete')
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal_{{ $item->uuid }}"><i class="bi bi-trash me-2"></i> Hapus</button>
                                                    </li>
                                                @endcan
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->can('konsultasi.index') ? 8 : 7 }}" class="text-center py-5 text-muted">Belum ada calon pelanggan yang cocok dengan filter.</td>
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

@can('konsultasi.delete')
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

@if (session('undo_token'))
    <div class="konsultasi-undo-toast" id="konsultasiUndoToast" role="status" aria-live="polite">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-arrow-counterclockwise fs-5 text-warning"></i>
            <div class="min-w-0 flex-grow-1">
                <div class="small fw-semibold">{{ session('undo_message') }}</div>
                <div class="small text-muted">Klik urungkan dalam <span id="konsultasiUndoCountdown">5</span> detik.</div>
            </div>
            <form method="POST" action="{{ route('konsultasi.bulk-undo') }}">
                @csrf
                <input type="hidden" name="undo_token" value="{{ session('undo_token') }}">
                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-arrow-counterclockwise me-1"></i> Urungkan</button>
            </form>
            <button type="button" class="btn-close" data-konsultasi-undo-close aria-label="Tutup"></button>
        </div>
    </div>
@endif

@push('after-style')
<style>
    #main-content .konsultasi-mobile-fold > summary {
        display: none;
    }

    #main-content .konsultasi-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    #main-content .konsultasi-stat-grid > div {
        min-width: 0;
    }

    #main-content .konsultasi-stat-grid .stats-icon i {
        font-size: .9rem;
    }

    @media (min-width: 768px) {
        #main-content .konsultasi-stat-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

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

    #main-content .konsultasi-toolbar .btn-soft-blue,
    #main-content .konsultasi-toolbar .btn-soft-indigo,
    #main-content .konsultasi-toolbar .btn-soft-cyan,
    #main-content .konsultasi-toolbar .btn-soft-purple,
    #main-content .konsultasi-toolbar .btn-soft-warning,
    #main-content .konsultasi-toolbar .btn-soft-success,
    #main-content .konsultasi-toolbar .btn-soft-danger {
        border-width: 1px;
        border-style: solid;
    }

    #main-content .konsultasi-toolbar .btn-soft-blue {
        background: #e8f1ff;
        border-color: #bcd4ff;
        color: #1d5fbf;
    }

    #main-content .konsultasi-toolbar .btn-soft-blue:hover,
    #main-content .konsultasi-toolbar .btn-soft-blue.active {
        background: #d7e7ff;
        border-color: #8fb8f5;
        color: #124a99;
    }

    #main-content .konsultasi-toolbar .btn-soft-indigo {
        background: #eeebff;
        border-color: #d0c9ff;
        color: #574bb5;
    }

    #main-content .konsultasi-toolbar .btn-soft-indigo:hover {
        background: #e2dcff;
        border-color: #b7acff;
        color: #403697;
    }

    #main-content .konsultasi-toolbar .btn-soft-cyan {
        background: #e3f7fa;
        border-color: #b8e5ec;
        color: #087f92;
    }

    #main-content .konsultasi-toolbar .btn-soft-cyan:hover {
        background: #d2eff5;
        border-color: #96d8e5;
        color: #056577;
    }

    #main-content .konsultasi-toolbar .btn-soft-purple {
        background: #f4ebff;
        border-color: #dec7fb;
        color: #8445ad;
    }

    #main-content .konsultasi-toolbar .btn-soft-purple:hover {
        background: #ecdcfc;
        border-color: #c9adf5;
        color: #68348c;
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

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-blue {
        background: rgba(13, 110, 253, .16);
        border-color: #345b96;
        color: #a9c9ff;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-indigo {
        background: rgba(93, 88, 214, .16);
        border-color: #57509e;
        color: #c5bfff;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-cyan {
        background: rgba(8, 145, 178, .16);
        border-color: #2f7280;
        color: #9fe3ef;
    }

    [data-bs-theme="dark"] #main-content .konsultasi-toolbar .btn-soft-purple {
        background: rgba(144, 77, 190, .16);
        border-color: #724c8f;
        color: #ddb8f5;
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

    @media (min-width: 1200px) {
        #main-content .konsultasi-stat-grid .card-body {
            gap: .5rem;
            padding: .55rem .65rem;
        }

        #main-content .konsultasi-stat-grid .stats-icon {
            width: 30px;
            height: 30px;
            min-width: 30px;
            min-height: 30px;
            border-radius: 8px;
            font-size: .75rem;
        }

        #main-content .konsultasi-stat-grid .stats-icon i {
            font-size: .75rem;
        }

        #main-content .konsultasi-stat-grid h6 {
            font-size: .68rem;
        }

        #main-content .konsultasi-stat-grid h4 {
            font-size: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        #main-content .konsultasi-toolbar .btn-konsultasi {
            flex: 1 1 auto;
        }

        #main-content .konsultasi-mobile-fold {
            margin-bottom: .35rem;
            border: 1px solid var(--bs-border-color, #e9ecef);
            border-radius: 8px;
            background: rgba(var(--bs-secondary-bg-rgb, 248 249 250), .45);
        }

        #main-content .konsultasi-mobile-fold > summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .35rem .6rem;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            list-style: none;
        }

        #main-content .konsultasi-mobile-fold > summary::-webkit-details-marker {
            display: none;
        }

        #main-content .konsultasi-mobile-fold[open] > summary {
            border-bottom: 1px solid var(--bs-border-color, #e9ecef);
        }

        #main-content .konsultasi-mobile-fold[open] {
            padding: .75rem;
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

    #main-content .konsultasi-active-filters .badge {
        font-size: .75rem;
        font-weight: 500;
        padding: .35em .6em;
    }

    #main-content .konsultasi-followup-control input[type="date"] {
        min-width: 0;
        width: 100%;
        padding: .2rem .35rem;
        font-size: .75rem;
    }

    #main-content .konsultasi-followup-control > .btn {
        padding: .2rem .4rem;
        line-height: 1;
    }

    #main-content .konsultasi-table-shell.hide-contact .col-contact,
    #main-content .konsultasi-table-shell.hide-plan .col-plan,
    #main-content .konsultasi-table-shell.hide-consult .col-consult {
        display: none;
        width: 0 !important;
    }

    #main-content .konsultasi-table-shell.hide-contact .col-customer { width: 28%; }
    #main-content .konsultasi-table-shell.hide-contact .col-pipeline { width: 24%; }
    #main-content .konsultasi-table-shell.hide-contact .col-action { width: 8%; }

    .konsultasi-undo-toast {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1080;
        max-width: min(420px, calc(100vw - 2rem));
        padding: .75rem 1rem;
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 12px;
        background: var(--bs-body-bg, #fff);
        box-shadow: 0 12px 32px rgba(0, 0, 0, .16);
    }

    #main-content .table-responsive .lead-table {
        width: 100% !important;
        min-width: 0 !important;
        table-layout: fixed;
    }

    #main-content .konsultasi-table-shell {
        overflow: visible !important;
    }

    #main-content .lead-table .form-check-input {
        margin: 0 !important;
    }

    #main-content .lead-table .badge {
        display: inline-flex;
        align-items: center;
        font-size: .72rem !important;
        line-height: 1.2 !important;
        padding: .3em .5em !important;
        white-space: nowrap !important;
    }

    #main-content .lead-table .konsultasi-followup-badge[data-state="upcoming"],
    #main-content .lead-table .konsultasi-sla-badge[data-state="safe"] {
        background-color: #d7e5d3 !important;
        color: #304b35 !important;
        border: 1px solid #bfd2b9;
    }

    #main-content .lead-table .konsultasi-followup-badge[data-state="today"],
    #main-content .lead-table .konsultasi-sla-badge[data-state="warning"] {
        background-color: #f2dfb9 !important;
        color: #674718 !important;
        border: 1px solid #e6ca91;
    }

    #main-content .lead-table .konsultasi-followup-badge[data-state="overdue"],
    #main-content .lead-table .konsultasi-sla-badge[data-state="breach"] {
        background-color: #efd0cc !important;
        color: #73332e !important;
        border: 1px solid #dfb4ae;
    }

    #main-content .lead-table .konsultasi-followup-badge[data-state="done"],
    #main-content .lead-table .konsultasi-sla-badge[data-state="done"] {
        background-color: #d7e5d3 !important;
        color: #304b35 !important;
        border: 1px solid #bfd2b9;
    }

    #main-content .lead-table .konsultasi-followup-badge[data-state="none"],
    #main-content .lead-table .konsultasi-sla-badge[data-state="met"] {
        background-color: #e8ddd0 !important;
        color: #554333 !important;
        border: 1px solid #d8c6b3;
    }

    #main-content .lead-table .konsultasi-followup-date {
        margin-top: .3rem;
        font-size: .75rem;
        line-height: 1.35;
    }

    @media (min-width: 576px) {
        #main-content .lead-table .col-check { width: 3%; }
        #main-content .lead-table .col-no { width: 3%; }
        #main-content .lead-table .col-customer { width: 23%; }
        #main-content .lead-table .col-contact { width: 15%; }
        #main-content .lead-table .col-plan { width: 15%; }
        #main-content .lead-table .col-consult { width: 14%; }
        #main-content .lead-table .col-pipeline { width: 20%; }
        #main-content .lead-table .col-action { width: 7%; }
    }

    #main-content .lead-table th,
    #main-content .lead-table td {
        padding: .5rem .45rem;
        overflow-wrap: anywhere;
        vertical-align: middle;
    }

    #main-content .lead-table .text-truncate {
        max-width: 100% !important;
    }

    #main-content .lead-table .konsultasi-action-menu > .btn {
        padding: .25rem .5rem;
    }

    #main-content .lead-table .dropdown-menu {
        min-width: 11rem;
        padding: .3rem;
    }

    #main-content .lead-table .dropdown-menu form {
        margin: 0;
    }

    #main-content .lead-table .dropdown-item {
        display: flex;
        align-items: center;
        width: 100%;
        padding: .4rem .6rem;
        border-radius: .35rem;
        font-size: .8125rem;
        text-align: left;
    }

    #main-content .table-responsive .lead-table .form-select-sm {
        min-width: 0 !important;
        width: 100% !important;
    }

    @media (max-width: 575.98px) {
        #main-content .section {
            padding-top: .5rem;
        }

        #main-content .card-header,
        #main-content .card-body {
            padding: .6rem .75rem;
        }

        #main-content .konsultasi-stat-grid .card-body {
            gap: .5rem;
            padding: .6rem;
        }

        #main-content .konsultasi-stat-grid .stats-icon {
            width: 34px;
            height: 34px;
            min-width: 34px;
            min-height: 34px;
        }

        #main-content .konsultasi-stat-grid .stats-icon i {
            font-size: .8rem;
        }

        #main-content .konsultasi-stat-grid h6 {
            font-size: .75rem;
        }

        #main-content .konsultasi-stat-grid h4 {
            font-size: 1.1rem;
        }

        .konsultasi-aging-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .konsultasi-bulk-toolbar > * {
            flex: 1 1 100% !important;
            width: 100% !important;
        }

        .konsultasi-undo-toast {
            left: .75rem;
            right: .75rem;
            bottom: .75rem;
            max-width: none;
        }

        #main-content .lead-table .col-contact,
        #main-content .lead-table .col-plan,
        #main-content .lead-table .col-consult {
            display: none;
        }

        #main-content .lead-table col.col-contact,
        #main-content .lead-table col.col-plan,
        #main-content .lead-table col.col-consult {
            width: 0 !important;
        }

        #main-content .lead-table .col-no { display: none; }
        #main-content .lead-table col.col-no { width: 0 !important; }
        #main-content .lead-table col.col-check { width: 8% !important; }
        #main-content .lead-table col.col-customer { width: 30% !important; }
        #main-content .lead-table col.col-pipeline { width: 50% !important; }
        #main-content .lead-table col.col-action { width: 12% !important; }

        #main-content .card-header .small.text-muted {
            display: none;
        }

        #main-content .lead-table th,
        #main-content .lead-table td {
            padding: .25rem .25rem;
        }

        #main-content .lead-table .form-select-sm {
            padding: .25rem 1.5rem .25rem .35rem;
            font-size: .75rem;
        }

        #main-content .lead-table .konsultasi-action-menu > .btn {
            padding: .2rem .3rem;
        }
    }
</style>
@endpush

@push('after-script')
<script>
    (function () {
        if (window.matchMedia('(max-width: 575.98px)').matches) {
            document.querySelectorAll('.konsultasi-mobile-fold').forEach(function (fold) { fold.open = false; });
        }

        var tableShell = document.getElementById('konsultasiTableShell');
        var columnBoxes = Array.prototype.slice.call(document.querySelectorAll('[data-konsultasi-column]'));
        var storedColumns = {};

        if (tableShell && columnBoxes.length) {
            try {
                storedColumns = JSON.parse(localStorage.getItem('konsultasi.columns') || '{}') || {};
            } catch (error) {
                storedColumns = {};
            }

            columnBoxes.forEach(function (box) {
                var key = box.getAttribute('data-konsultasi-column');

                if (storedColumns[key] !== undefined) {
                    box.checked = storedColumns[key] !== false;
                }

                var apply = function () {
                    tableShell.classList.toggle('hide-' + key, !box.checked);
                };

                apply();

                box.addEventListener('change', function () {
                    apply();
                    storedColumns[key] = box.checked;
                    localStorage.setItem('konsultasi.columns', JSON.stringify(storedColumns));
                });
            });
        }

        var search = document.getElementById('filter-q');

        document.addEventListener('keydown', function (event) {
            if (event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey || !search) return;

            var target = event.target;

            if (target instanceof Element && (target.matches('input, textarea, select') || target.isContentEditable)) return;

            event.preventDefault();

            var fold = search.closest('.konsultasi-mobile-fold');

            if (fold && !fold.open) fold.open = true;

            search.focus();
        });

        var toast = document.getElementById('konsultasiUndoToast');

        if (toast) {
            var seconds = 5;
            var countdown = document.getElementById('konsultasiUndoCountdown');
            var timer = window.setInterval(function () {
                seconds -= 1;

                if (countdown) countdown.textContent = String(Math.max(seconds, 0));

                if (seconds <= 0) {
                    window.clearInterval(timer);
                    toast.remove();
                }
            }, 1000);
            var closeToast = toast.querySelector('[data-konsultasi-undo-close]');

            if (closeToast) {
                closeToast.addEventListener('click', function () {
                    window.clearInterval(timer);
                    toast.remove();
                });
            }
        }

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
