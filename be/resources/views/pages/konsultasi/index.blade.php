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

    <div class="card">
        <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div>
                <h4 class="fw-normal mb-0 text-body">Daftar Calon Pelanggan</h4>
                <div class="small text-muted">Klik status atau penanggung jawab pada baris untuk memperbarui pipeline.</div>
            </div>
            @can('konsultasi.index')
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('konsultasi.pdf-preview', request()->query()) }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF
                    </a>
                    <a href="{{ route('konsultasi.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Konsultasi
                    </a>
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

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 lead-table">
                    <thead>
                        <tr>
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
                                    <a href="{{ $item->whatsapp_link }}" target="_blank" rel="noopener" class="small text-decoration-none"><i class="bx bxl-whatsapp text-success"></i> {{ $item->whatsapp }}</a>
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
                                        <input type="hidden" name="handled_by" value="{{ $item->handled_by }}">
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
                                        <input type="hidden" name="lead_status" value="{{ $item->lead_status }}">
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
                                            <a href="{{ route('konsultasi.edit', $item->uuid) }}" class="btn btn-success" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                            <button type="button" class="btn btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal_{{ $item->uuid }}"><i class="bi bi-trash"></i></button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">Belum ada calon pelanggan yang cocok dengan filter.</td>
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
@endsection
