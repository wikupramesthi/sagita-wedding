@extends('layouts.app')
@section('title', 'Kalender Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Kalender Konsultasi" page="Data Prospek" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

@push('after-style')
<style>
    .konsultasi-calendar-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
    }

    .konsultasi-calendar-grid {
        min-width: 760px;
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 6px;
    }

    .konsultasi-calendar-weekday {
        text-align: center;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--bs-secondary-color, #6c757d);
        padding: 6px 0;
    }

    .konsultasi-calendar-day {
        min-height: 112px;
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 10px;
        padding: 8px;
        background: var(--bs-body-bg, #fff);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .konsultasi-calendar-day.is-muted {
        opacity: .55;
    }

    .konsultasi-calendar-day.is-selected {
        border-color: var(--bs-primary, #5e72e4);
        box-shadow: 0 0 0 2px rgba(94, 114, 228, .16);
    }

    .konsultasi-calendar-day.is-today .konsultasi-calendar-number {
        background: var(--bs-primary, #5e72e4);
        color: #fff;
        border-radius: 999px;
        padding: 1px 7px;
    }

    .konsultasi-calendar-day-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 4px;
        color: inherit;
        text-decoration: none;
        font-weight: 700;
        font-size: .85rem;
    }

    .konsultasi-calendar-event {
        display: block;
        border-radius: 6px;
        padding: 4px 6px;
        font-size: .72rem;
        line-height: 1.25;
        background: rgba(94, 114, 228, .1);
        color: var(--bs-primary, #5e72e4);
        text-decoration: none;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .konsultasi-calendar-event:hover {
        background: rgba(94, 114, 228, .18);
    }

    .konsultasi-calendar-detail {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 10px;
    }

    .konsultasi-calendar-detail-item {
        border: 1px solid var(--bs-border-color, #e9ecef);
        border-radius: 10px;
        padding: 12px;
        min-width: 0;
    }

    @media (max-width: 575.98px) {
        .konsultasi-calendar-day {
            min-height: 96px;
            padding: 6px;
        }

        .konsultasi-calendar-detail {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<section class="section">
    <div class="card">
        <div class="card-header d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
            <div class="min-w-0">
                <h4 class="fw-normal mb-1 text-body">Kalender Konsultasi</h4>
                <div class="small text-muted">Jadwal diambil dari tanggal konsultasi pada data calon pelanggan. Klik tanggal untuk melihat detail.</div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a href="{{ route('konsultasi.index', request()->query()) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-list me-1"></i> Tabel</a>
                    <a href="{{ route('konsultasi.kanban', request()->query()) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-columns-gap me-1"></i> Kanban</a>
                    <a href="{{ route('konsultasi.calendar', request()->query()) }}" class="btn btn-sm btn-primary active"><i class="bi bi-calendar3 me-1"></i> Kalender</a>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('konsultasi.duplicates') }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-diagram-3 me-1"></i> Cek Duplikat</a>
                <a href="{{ route('konsultasi.export', request()->query()) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</a>
                <a href="{{ route('konsultasi.pdf-preview', request()->query()) }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF</a>
                <a href="{{ route('konsultasi.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Konsultasi</a>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                <a href="{{ route('konsultasi.calendar', ['year' => $prevMonth->year, 'month' => $prevMonth->month, 'date' => $selectedDate]) }}" class="btn btn-sm btn-light"><i class="bi bi-chevron-left me-1"></i> {{ $prevMonth->translatedFormat('F Y') }}</a>
                <h5 class="h6 mb-0">{{ $monthStart->translatedFormat('F Y') }}</h5>
                <a href="{{ route('konsultasi.calendar', ['year' => $nextMonth->year, 'month' => $nextMonth->month, 'date' => $selectedDate]) }}" class="btn btn-sm btn-light">{{ $nextMonth->translatedFormat('F Y') }} <i class="bi bi-chevron-right ms-1"></i></a>
            </div>

            <div class="konsultasi-calendar-wrap">
                <div class="konsultasi-calendar-grid">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                        <div class="konsultasi-calendar-weekday">{{ $weekday }}</div>
                    @endforeach

                    @foreach ($weeks as $week)
                        @foreach ($week as $day)
                            <div class="konsultasi-calendar-day {{ $day['in_month'] ? '' : 'is-muted' }} {{ $day['is_today'] ? 'is-today' : '' }} {{ $day['key'] === $selectedDate ? 'is-selected' : '' }}">
                                <a href="{{ route('konsultasi.calendar', ['year' => $year, 'month' => $month, 'date' => $day['key']]) }}" class="konsultasi-calendar-day-link">
                                    <span class="konsultasi-calendar-number">{{ $day['date']->day }}</span>
                                    @if ($day['items']->isNotEmpty())
                                        <span class="badge bg-primary rounded-pill">{{ $day['items']->count() }}</span>
                                    @endif
                                </a>

                                @foreach ($day['items']->take(2) as $item)
                                    <a href="{{ route('konsultasi.show', $item->uuid) }}" class="konsultasi-calendar-event" title="{{ $item->full_name }}">
                                        {{ $item->consultation_time ? \Carbon\Carbon::parse($item->consultation_time)->format('H:i') : '--:--' }} {{ $item->full_name }}
                                    </a>
                                @endforeach

                                @if ($day['items']->count() > 2)
                                    <a href="{{ route('konsultasi.calendar', ['year' => $year, 'month' => $month, 'date' => $day['key']]) }}" class="small text-decoration-none">+{{ $day['items']->count() - 2 }} lainnya</a>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="h6 mb-0">Jadwal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, j F Y') }}</h5>
                    <div class="small text-muted">{{ $selectedItems->count() }} konsultasi pada tanggal ini.</div>
                </div>
            </div>

            <div class="konsultasi-calendar-detail">
            @forelse ($selectedItems as $item)
                    <div class="konsultasi-calendar-detail-item">
                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
                            <div class="min-w-0">
                                <div class="fw-semibold">{{ $item->full_name }}</div>
                                <div class="small text-muted">{{ $item->consultation_time ? \Carbon\Carbon::parse($item->consultation_time)->format('H:i') : 'Jam belum dijadwalkan' }} · {{ $item->method_label }}</div>
                            </div>
                            <span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span>
                        </div>
                        <div class="small mt-2"><i class="bx {{ $item->source_icon }} me-1"></i> {{ $item->source_label }}</div>
                        <div class="small text-muted">Paket: {{ $item->package->name ?? '—' }} · PIC: {{ $item->handler->name ?? '—' }}</div>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('konsultasi.show', $item->uuid) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> Detail</a>
                            <a href="{{ $item->whatsapp_link }}?text={{ rawurlencode($item->whatsapp_message) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success"><i class="bx bxl-whatsapp me-1"></i> WhatsApp</a>
                        </div>
                    </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x d-block fs-1 mb-2"></i>
                    <div class="fw-semibold">Tidak ada konsultasi pada tanggal ini.</div>
                    <div class="small">Pilih tanggal lain pada kalender atau tambah jadwal baru.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
