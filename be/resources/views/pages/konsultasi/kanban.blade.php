@extends('layouts.app')
@section('title', 'Pipeline Kanban Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Pipeline Kanban Konsultasi" page="Konsultasi" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

@push('after-style')
<style>
    .konsultasi-kanban {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr));
        align-items: start;
        gap: 1rem;
        padding: .25rem 0 1rem;
    }

    .konsultasi-kanban .kanban-column {
        min-width: 0;
        max-width: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: .75rem;
        overflow: hidden;
    }

    .konsultasi-kanban .kanban-column-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
        padding: .75rem .875rem;
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }

    .konsultasi-kanban .kanban-column-body {
        display: grid;
        align-content: start;
        gap: .75rem;
        padding: .75rem;
        min-width: 0;
    }

    .konsultasi-kanban .kanban-card {
        min-width: 0;
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: .6rem;
        padding: .75rem;
    }

    .konsultasi-kanban .kanban-avatar {
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        background-color: rgba(105, 108, 255, .12);
        color: #696cff;
    }

    .konsultasi-kanban .kanban-card a,
    .konsultasi-kanban .kanban-card .small {
        overflow-wrap: anywhere;
    }

    .konsultasi-kanban .kanban-card select {
        max-width: 100%;
    }

    .konsultasi-kanban .kanban-empty {
        border: 1px dashed var(--bs-border-color, #e9ecef);
        border-radius: .6rem;
        padding: 1rem .75rem;
    }

    .konsultasi-kanban .kanban-stat-icon {
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: .6rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(105, 108, 255, .12);
        color: #696cff;
    }

    @media (max-width: 767.98px) {
        .konsultasi-kanban {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            overflow-x: visible;
            margin-inline: 0;
            padding-inline: 0;
        }

        .konsultasi-kanban .kanban-column {
            flex: auto;
            width: 100%;
            min-width: 0;
            max-width: none;
        }
    }
</style>
@endpush

<section class="section">
    <div class="card mb-3">
        <div class="card-body d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
            <div class="min-w-0">
                <h4 class="fw-normal mb-1 text-body">Pipeline Kanban Konsultasi</h4>
                <div class="small text-muted">Pantau setiap calon pelanggan berdasarkan status lead, lalu perbarui pipeline langsung dari kartu.</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <div class="btn-group" role="group" aria-label="Mode tampilan">
                    <a href="{{ route('konsultasi.index', request()->query()) }}" class="btn btn-outline-secondary"><i class="bi bi-list-ul me-1"></i> Tabel</a>
                    <a href="{{ url()->current() }}" class="btn btn-primary active" aria-current="page"><i class="bi bi-kanban me-1"></i> Kanban</a>
                    <a href="{{ route('konsultasi.calendar', request()->query()) }}" class="btn btn-outline-secondary"><i class="bi bi-calendar3 me-1"></i> Kalender</a>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-top-0 pt-0">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('konsultasi.pdf-preview', request()->query()) }}" class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF</a>
                @can('konsultasi.index')
                    <a href="{{ route('konsultasi.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Konsultasi</a>
                @endcan
                <a href="{{ route('konsultasi.export', request()->query()) }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</a>
                <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-outline-warning"><i class="bi bi-files me-1"></i> Cek Duplikat</a>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 py-2">
                    <span class="kanban-stat-icon"><i class="bi bi-people"></i></span>
                    <div class="min-w-0">
                        <div class="small text-muted text-truncate">Total Lead</div>
                        <div class="fw-bold">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 py-2">
                    <span class="kanban-stat-icon"><i class="bi bi-fire"></i></span>
                    <div class="min-w-0">
                        <div class="small text-muted text-truncate">Lead Panas</div>
                        <div class="fw-bold">{{ $stats['hot'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 py-2">
                    <span class="kanban-stat-icon"><i class="bi bi-clock-history"></i></span>
                    <div class="min-w-0">
                        <div class="small text-muted text-truncate">Follow-up Terlewat</div>
                        <div class="fw-bold">{{ $stats['overdue'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-2 py-2">
                    <span class="kanban-stat-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div class="min-w-0">
                        <div class="small text-muted text-truncate">Tingkat Konversi</div>
                        <div class="fw-bold">{{ $stats['conversion_rate'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4 col-xl-3">
                    <label for="kanban-q" class="form-label mb-1 small text-muted">Cari calon pelanggan</label>
                    <input type="search" id="kanban-q" name="q" value="{{ $filters['q'] }}" class="form-control form-control-sm" placeholder="Nama, email, WhatsApp...">
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <label for="kanban-lead" class="form-label mb-1 small text-muted">Status lead</label>
                    <select id="kanban-lead" name="lead" class="form-select form-select-sm">
                        <option value="">Semua lead</option>
                        @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['lead'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <label for="kanban-follow-up" class="form-label mb-1 small text-muted">Tindak lanjut</label>
                    <select id="kanban-follow-up" name="follow_up" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach (\App\Models\Consultation::FOLLOW_UP_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($filters['follow_up'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <label for="kanban-source" class="form-label mb-1 small text-muted">Sumber</label>
                    <select id="kanban-source" name="source" class="form-select form-select-sm">
                        <option value="">Semua sumber</option>
                        @foreach ($sources as $value => $label)
                            <option value="{{ $value }}" @selected($filters['source'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-xl-3 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
                    @if (collect($filters)->filter()->isNotEmpty())
                        <span class="badge bg-soft-primary text-primary">Filter aktif</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="konsultasi-kanban">
        @foreach ($columns as $key => $column)
            <section class="kanban-column" aria-label="{{ $column['label'] }}">
                <header class="kanban-column-header">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        <h6 class="fw-semibold mb-0 text-body text-truncate">{{ $column['label'] }}</h6>
                        @php
                            $wipLimit = $wipLimits[$key] ?? null;
                            $wipFull = $wipLimit !== null && $column['items']->count() > $wipLimit;
                        @endphp
                        <span class="badge {{ $wipFull ? 'bg-danger' : 'bg-light text-dark' }}" title="{{ $wipLimit ? 'WIP maksimal '.$wipLimit : 'Tanpa batas WIP' }}">
                            {{ $column['items']->count() }}{{ $wipLimit ? '/'.$wipLimit : '' }}
                        </span>
                    </div>
                </header>
                <div class="kanban-column-body">
                    @forelse ($column['items'] as $item)
                        <article class="kanban-card">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <span class="kanban-avatar">{{ $item->initials }}</span>
                                <div class="min-w-0 flex-grow-1">
                                    <a href="{{ route('konsultasi.show', $item->uuid) }}" class="fw-semibold text-decoration-none text-dark d-block">{{ $item->full_name }}</a>
                                    <div class="small text-muted"><i class="bx {{ $item->source_icon }} me-1"></i>{{ $item->source_label }}</div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                <span class="badge {{ $item->lead_priority_badge_class }}"><i class="bx {{ $item->lead_priority_icon }} me-1"></i>{{ $item->lead_score }}/10</span>
                                <span class="badge {{ $item->follow_up_badge_class }}">{{ $item->follow_up_label }}</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <a href="{{ route('konsultasi.whatsapp', $item) }}" target="_blank" rel="noopener" class="btn btn-sm btn-success">
                                    <i class="bx bxl-whatsapp me-1"></i> WA
                                </a>
                                <form action="{{ route('konsultasi.update-lead', $item->uuid) }}" method="POST" class="min-w-0 flex-grow-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="lead_status" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Status lead {{ $item->full_name }}">
                                        @foreach (\App\Models\Consultation::LEAD_STATUS_LABELS as $value => $label)
                                            <option value="{{ $value }}" @selected($item->lead_status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <a href="{{ route('konsultasi.show', $item->uuid) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                            </div>
                            @can('konsultasi.index')
                                <div class="d-flex gap-1 mt-2">
                                    <form action="{{ route('konsultasi.reorder', $item) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <input type="hidden" name="direction" value="up">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100" title="Naik"><i class="bi bi-arrow-up"></i></button>
                                    </form>
                                    <form action="{{ route('konsultasi.reorder', $item) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <input type="hidden" name="direction" value="down">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100" title="Turun"><i class="bi bi-arrow-down"></i></button>
                                    </form>
                                </div>
                            @endcan
                        </article>
                    @empty
                        <div class="kanban-empty text-center small text-muted"><i class="bi bi-inbox d-block fs-4 mb-1"></i>Belum ada lead di kolom ini.</div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</section>
@endsection
