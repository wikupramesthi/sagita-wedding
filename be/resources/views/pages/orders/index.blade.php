@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="Pesanan" page="Operasional" active="Daftar Pesanan"
    route="{{ route('orders.index') }}" />
@endsection

@php
$statusLabels = [
'pending' => 'Menunggu verifikasi',
'partially_paid' => 'DP terverifikasi',
'paid' => 'Lunas',
'failed' => 'Gagal',
'expired' => 'Kedaluwarsa',
'cancelled' => 'Dibatalkan',
];
$statusHints = [
'pending' => 'Pembayaran belum diverifikasi admin.',
'partially_paid' => 'DP sudah diterima, sisa pembayaran belum lunas.',
'paid' => 'Pembayaran sudah lunas.',
'failed' => 'Pembayaran gagal.',
'expired' => 'Batas waktu pembayaran habis.',
'cancelled' => 'Pesanan dibatalkan.',
];
$viewMode = in_array(request()->input('view'), ['table', 'cards', 'kanban'], true) ? request()->input('view') : 'table';
$viewQuery = request()->except('view', 'page');
$tableUrl = route('orders.index', array_merge($viewQuery, ['view' => 'table']));
$cardsUrl = route('orders.index', array_merge($viewQuery, ['view' => 'cards']));
$kanbanUrl = route('orders.index', array_merge($viewQuery, ['view' => 'kanban']));
$datePresets = [
    'today' => ['label' => 'Hari ini', 'start' => now()->toDateString(), 'end' => now()->toDateString()],
    '7d' => ['label' => '7 hari', 'start' => now()->copy()->subDays(6)->toDateString(), 'end' => now()->toDateString()],
    '30d' => ['label' => '30 hari', 'start' => now()->copy()->subDays(29)->toDateString(), 'end' => now()->toDateString()],
    'month' => ['label' => 'Bulan ini', 'start' => now()->copy()->startOfMonth()->toDateString(), 'end' => now()->toDateString()],
];
$activePreset = null;
foreach ($datePresets as $presetKey => $preset) {
    if (($filters['start_date'] ?? '') === $preset['start'] && ($filters['end_date'] ?? '') === $preset['end']) {
        $activePreset = $presetKey;
        break;
    }
}
$chipMap = [
    'search' => ['label' => 'Cari', 'value' => $filters['search'] ?? ''],
    'status' => ['label' => 'Status', 'value' => isset($statusLabels[$filters['status'] ?? '']) ? $statusLabels[$filters['status']] : ''],
    'proof' => ['label' => 'Bukti', 'value' => ['uploaded' => 'Sudah diunggah', 'missing' => 'Belum diunggah'][$filters['proof'] ?? ''] ?? ''],
    'start_date' => ['label' => 'Dari', 'value' => $filters['start_date'] ?? ''],
    'end_date' => ['label' => 'Sampai', 'value' => $filters['end_date'] ?? ''],
];
$activeChips = array_filter($chipMap, fn ($chip) => (string) $chip['value'] !== '');
@endphp

<section class="section">
    <div class="row g-3 ml-stats order-stats">
        <div class="col-6 col-xl-3">
            <div class="card order-stat-card stat-total h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-receipt"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->total ?? 0) }}</div>
                            <div class="ml-stat-label">Total Pesanan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card order-stat-card stat-pending h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-hourglass"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->pending ?? 0) }}</div>
                            <div class="ml-stat-label">Menunggu Verifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card order-stat-card stat-paid h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->paid ?? 0) }}</div>
                            <div class="ml-stat-label">Lunas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card order-stat-card stat-failed h-100">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-x-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->others ?? 0) }}</div>
                            <div class="ml-stat-label">Gagal / Kedaluwarsa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card order-revenue-card mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="order-revenue-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="order-revenue-label">Pendapatan terverifikasi</div>
                    <div class="order-revenue-value">Rp {{ number_format($stats->revenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-4 order-revenue-meta">
                <div>
                    <div class="order-revenue-meta-label">Lunas / DP masuk</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->paid ?? 0) }} / {{ number_format($stats->partially_paid ?? 0) }}</div>
                </div>
                <div>
                    <div class="order-revenue-meta-label">Total pesanan</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->total ?? 0) }}</div>
                </div>
                <div>
                    <div class="order-revenue-meta-label">Belum dibayar</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->pending ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if (($queueCounts['all'] ?? 0) > 0)
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && empty($filters['proof'])) ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="bi bi-hourglass-split me-1"></i>Perlu verifikasi
            <span class="badge ms-1">{{ number_format($queueCounts['all']) }}</span>
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending', 'proof' => 'uploaded']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && ($filters['proof'] ?? '') === 'uploaded') ? 'btn-info' : 'btn-outline-info' }}">
            <i class="bi bi-file-earmark-check me-1"></i>Bukti diunggah
            <span class="badge ms-1">{{ number_format($queueCounts['uploaded']) }}</span>
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending', 'proof' => 'missing']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && ($filters['proof'] ?? '') === 'missing') ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="bi bi-file-earmark-x me-1"></i>Belum ada bukti
            <span class="badge ms-1">{{ number_format($queueCounts['missing']) }}</span>
        </a>
    </div>
    @endif

    <div class="card order-toolbar-card mb-4">
        <div class="order-toolbar-head d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="order-toolbar-icon"><i class="bi bi-sliders2"></i></span>
                <div>
                    <h6 class="order-toolbar-title mb-0">Pencarian & Filter</h6>
                    <div class="order-toolbar-subtitle">
                        Sesuaikan daftar pesanan sesuai antrean, status, dan periode.
                        <span class="order-shortcut-hint">Tekan <kbd>/</kbd> untuk cari</span>
                    </div>
                    <div class="order-kpi-strip" aria-label="Ringkasan perhatian">
                        <span class="order-kpi-item is-warning"><i class="bi bi-alarm me-1"></i><b>{{ number_format($queueCounts['sla'] ?? 0) }}</b> menunggu &gt;24 jam</span>
                        <span class="order-kpi-item is-info"><i class="bi bi-person-check me-1"></i><b>{{ number_format($queueCounts['claimed'] ?? 0) }}</b> sedang diproses</span>
                        <span class="order-kpi-item is-success"><i class="bi bi-file-earmark-check me-1"></i><b>{{ number_format($queueCounts['uploaded'] ?? 0) }}</b> bukti diunggah</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm order-view-switch" role="group" aria-label="Tampilan daftar pesanan">
                    <a href="{{ $tableUrl }}" class="btn btn-outline-secondary {{ $viewMode === 'table' ? 'active' : '' }}">
                        <i class="bi bi-table me-1"></i>Tabel
                    </a>
                    <a href="{{ $cardsUrl }}" class="btn btn-outline-secondary {{ $viewMode === 'cards' ? 'active' : '' }}">
                        <i class="bi bi-grid me-1"></i>Kartu
                    </a>
                    <a href="{{ $kanbanUrl }}" class="btn btn-outline-secondary {{ $viewMode === 'kanban' ? 'active' : '' }}">
                        <i class="bi bi-kanban me-1"></i>Kanban
                    </a>
                </div>
                <span class="order-toolbar-badge"><i class="bi bi-list-check me-1"></i>{{ number_format($orders->total()) }} hasil</span>
            </div>
        </div>

        <div class="order-toolbar-body">
            <form method="GET" action="{{ route('orders.index') }}" class="security-filter-form order-filter-grid">
                @if ($isAdmin)
                <div class="order-filter-field field-search">
                    <label class="security-filter-label" for="filterSearch">Cari pesanan</label>
                    <input type="text" name="search" id="filterSearch" class="form-control"
                        placeholder="No. pesanan, nama, atau email" minlength="2"
                        value="{{ $filters['search'] ?? '' }}">
                    <div class="small text-warning mt-1 d-none" id="searchLengthHint">
                        Ketik minimal 2 karakter untuk mencari pesanan.
                    </div>
                </div>
                @endif
                <div class="order-filter-field">
                    <label class="security-filter-label" for="filterStatus">Status</label>
                    <select name="status" id="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '' )===$value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="order-filter-field">
                    <label class="security-filter-label" for="filterProof">Bukti Transfer</label>
                    <select name="proof" id="filterProof" class="form-select">
                        <option value="">Semua</option>
                        <option value="uploaded" @selected(($filters['proof'] ?? '' )==='uploaded')>Sudah diunggah</option>
                        <option value="missing" @selected(($filters['proof'] ?? '' )==='missing')>Belum diunggah</option>
                    </select>
                </div>
                <div class="order-filter-field">
                    <label class="security-filter-label" for="filterPerPage">Per halaman</label>
                    <select name="per_page" id="filterPerPage" class="form-select">
                        @foreach ([10, 25, 50, 100] as $perPageOption)
                        <option value="{{ $perPageOption }}" @selected(($filters['per_page'] ?? 10)===$perPageOption)>{{ $perPageOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="order-filter-field">
                    <label class="security-filter-label" for="filterSort">Urutkan</label>
                    <select name="sort" id="filterSort" class="form-select">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest')==='latest')>Terbaru</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '')==='oldest')>Terlama</option>
                        <option value="amount_desc" @selected(($filters['sort'] ?? '')==='amount_desc')>Nominal tertinggi</option>
                        <option value="amount_asc" @selected(($filters['sort'] ?? '')==='amount_asc')>Nominal terendah</option>
                    </select>
                </div>
                <div class="order-filter-field field-date">
                    <label class="security-filter-label" for="filterStart">Dari Tanggal</label>
                    <input type="date" name="start_date" id="filterStart" class="form-control"
                        value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="order-filter-field field-date">
                    <label class="security-filter-label" for="filterEnd">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="filterEnd" class="form-control"
                        value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="order-filter-actions">
                    <button type="submit" class="btn btn-success"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise me-1"></i>Atur Ulang</a>
                </div>
            </form>

            @if ($searchTooShort)
            <div class="alert alert-warning py-2 px-3 mt-3 mb-0" id="searchTooShortAlert">
                <i class="bi bi-exclamation-triangle me-1"></i>Kata kunci pencarian minimal 2 karakter. Hasil tidak ditampilkan sampai kata kunci diperpanjang.
            </div>
            @endif

            <div class="order-date-presets" aria-label="Preset periode">
                <span class="order-date-presets-label"><i class="bi bi-calendar-range me-1"></i>Periode cepat:</span>
                @foreach ($datePresets as $presetKey => $preset)
                <button type="button" class="order-date-preset {{ $activePreset === $presetKey ? 'active' : '' }}"
                    data-start="{{ $preset['start'] }}" data-end="{{ $preset['end'] }}">
                    {{ $preset['label'] }}
                </button>
                @endforeach
                <span class="order-date-presets-hint">Preset akan langsung memperbarui daftar.</span>
            </div>

            @if ($activeChips)
            <div class="order-filter-chips" aria-label="Filter aktif">
                <span class="order-filter-chips-label">Filter aktif:</span>
                @foreach ($activeChips as $key => $chip)
                <span class="order-filter-chip">
                    {{ $chip['label'] }}: {{ $chip['value'] }}
                    <a href="{{ route('orders.index', array_merge(request()->except('page'), [$key => null])) }}"
                        aria-label="Hapus filter {{ $chip['label'] }}">×</a>
                </span>
                @endforeach
                <a href="{{ route('orders.index') }}" class="order-filter-chip-clear">Hapus semua</a>
            </div>
            @endif

            <div class="order-saved-panel">
                <div class="order-saved-head">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bookmarks text-primary"></i>
                        <span>Filter Tersimpan</span>
                        <span class="order-saved-count">{{ $savedFilters->count() }}</span>
                    </div>
                    <span class="order-saved-hint">Simpan kombinasi filter yang sering dipakai.</span>
                </div>

                @if ($savedFilters->isNotEmpty())
                <div class="order-saved-filters">
                    @foreach ($savedFilters as $savedFilter)
                    <div class="btn-group btn-group-sm order-saved-filter-group" role="group">
                        <a href="{{ route('orders.saved-filters.apply', $savedFilter) }}"
                            class="btn btn-outline-primary text-nowrap">
                            @if ($savedFilter->pinned_at)<i class="bi bi-pin-angle-fill me-1 text-warning"></i>@endif
                            <i class="bi bi-funnel me-1"></i>{{ $savedFilter->name }}
                            @if ($savedFilter->is_default)<i class="bi bi-star-fill ms-1 text-warning"></i>@endif
                        </a>
                        <form method="POST" action="{{ route('orders.saved-filters.pin', $savedFilter) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary"
                                title="{{ $savedFilter->pinned_at ? 'Lepaskan sematan' : 'Sematkan filter' }}">
                                <i class="bi bi-pin-angle{{ $savedFilter->pinned_at ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('orders.saved-filters.move', $savedFilter) }}">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="btn btn-outline-secondary" title="Naik"><i class="bi bi-arrow-up"></i></button>
                        </form>
                        <form method="POST" action="{{ route('orders.saved-filters.move', $savedFilter) }}">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="btn btn-outline-secondary" title="Turun"><i class="bi bi-arrow-down"></i></button>
                        </form>
                        <form method="POST" action="{{ route('orders.saved-filters.default', $savedFilter) }}">
                            @csrf
                            <input type="hidden" name="is_default" value="{{ $savedFilter->is_default ? 0 : 1 }}">
                            <button type="submit" class="btn btn-outline-warning"
                                title="{{ $savedFilter->is_default ? 'Hapus bawaan' : 'Jadikan bawaan' }}">
                                <i class="bi bi-star{{ $savedFilter->is_default ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('orders.saved-filters.destroy', $savedFilter) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Hapus filter"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="order-saved-empty">Belum ada filter tersimpan. Buat pertama di bawah.</div>
                @endif

                <div class="order-save-row">
                    <label class="security-filter-label" for="saveFilterName">Nama filter baru</label>
                    <input type="text" id="saveFilterName" class="form-control form-control-sm"
                        placeholder="Misal: Antrean bukti masuk">
                    <div class="form-check order-save-check">
                        <input class="form-check-input" type="checkbox" id="saveFilterDefault"
                            form="saveFilterForm" name="is_default" value="1">
                        <label class="form-check-label" for="saveFilterDefault">Jadikan bawaan</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="saveFilterButton">
                        <i class="bi bi-bookmark-plus me-1"></i>Simpan Filter
                    </button>
                </div>
            </div>

            <form id="saveFilterForm" method="POST" action="{{ route('orders.saved-filters.store') }}" class="d-none">
                @csrf
                <input type="hidden" name="name" id="saveFilterNameInput">
                <input type="hidden" name="filters" id="saveFilterPayload">
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Daftar Pesanan</h5>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($orders->total()) }} pesanan</span>
            </div>
            @if ($isAdmin)
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('orders.change-requests.index') }}" class="btn btn-sm btn-outline-primary text-nowrap">
                    <i class="bi bi-arrow-left-right me-1"></i>Permintaan perubahan
                </a>
                <a href="{{ route('orders.analytics') }}" class="btn btn-sm btn-outline-primary text-nowrap">
                    <i class="bi bi-graph-up me-1"></i>Analitik pemesanan
                </a>
                <a href="{{ route('orders.workflow-settings.edit') }}" class="btn btn-sm btn-outline-primary text-nowrap">
                    <i class="bi bi-sliders me-1"></i>Template kontrak & checklist
                </a>
                <a href="{{ route('orders.report', request()->query()) }}" data-export-link class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="bi bi-file-text me-1"></i>Laporan
                </a>
                <a href="{{ route('orders.exportCsv', request()->query()) }}" data-export-link class="btn btn-sm btn-outline-success text-nowrap">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
                </a>
                <a href="{{ route('orders.exportPdf', request()->query()) }}" data-export-link class="btn btn-sm btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </a>
                <a href="{{ route('orders.audit', request()->query()) }}" data-export-link class="btn btn-sm btn-outline-dark text-nowrap">
                    <i class="bi bi-clock-history me-1"></i>Audit
                </a>
            </div>
            @endif
        </div>

        <div class="order-sticky-bar d-none" id="orderStickyBar" aria-label="Aksi cepat pesanan">
            <div class="d-flex align-items-center gap-2 min-w-0">
                <span class="order-sticky-icon"><i class="bi bi-lightning-charge"></i></span>
                <div class="min-w-0">
                    <div class="order-sticky-title">Aksi cepat</div>
                    <div class="order-sticky-subtitle text-truncate" id="stickyBulkCount">Pilih pesanan menunggu untuk tindakan massal.</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-light" id="stickyFilter"><i class="bi bi-funnel me-1"></i>Filter</button>
                @if ($isAdmin)
                <button type="button" class="btn btn-sm btn-outline-info" id="stickyRemind"><i class="bi bi-alarm me-1"></i>SLA</button>
                <button type="button" class="btn btn-sm btn-success" id="stickyApprove"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="stickyReject"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                @endif
            </div>
        </div>

        <div class="card-body orders-card-body {{ $viewMode === 'cards' ? 'view-cards' : '' }}" id="ordersCardBody">
            <div class="orders-table-tools">
                <div class="orders-table-tools-info">
                    <span class="orders-table-tools-icon"><i class="bi bi-list-ul"></i></span>
                    <div>
                        <div class="orders-table-tools-title">
                            {{ $viewMode === 'cards' ? 'Kartu pesanan' : ($viewMode === 'kanban' ? 'Papan antrean' : 'Tabel pesanan') }}
                        </div>
                        <div class="orders-table-tools-subtitle">
                            {{ $viewMode === 'cards' ? 'Ringkasan setiap pesanan dalam satu kartu agar mudah dipindai.' : 'Semua pesanan paket pernikahan beserta status pembayarannya.' }}
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="orderColumnMenu"
                            data-bs-toggle="dropdown" data-bs-container="body" aria-expanded="false" title="Pilih kolom">
                            <i class="bi bi-columns-gaps me-1"></i>Kolom
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-3 order-column-menu" aria-labelledby="orderColumnMenu">
                            <li><label class="order-column-option"><input type="checkbox" class="order-col-toggle" data-col="customer" checked> Pelanggan</label></li>
                            <li><label class="order-column-option"><input type="checkbox" class="order-col-toggle" data-col="package" checked> Paket</label></li>
                                                        <li><label class="order-column-option"><input type="checkbox" class="order-col-toggle" data-col="status" checked> Status</label></li>
                            <li><label class="order-column-option"><input type="checkbox" class="order-col-toggle" data-col="proof" checked> Bukti</label></li>
                            <li><label class="order-column-option"><input type="checkbox" class="order-col-toggle" data-col="date" checked> Tanggal</label></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="orderDensityButton"
                        aria-pressed="false" title="Ubah kepadatan tabel">
                        <i class="bi bi-list me-1"></i>Ringkas
                    </button>
                </div>
            </div>

            <div id="ordersSkeleton" class="orders-skeleton d-none" aria-hidden="true">
                <div class="orders-skeleton-row"></div>
                <div class="orders-skeleton-row"></div>
                <div class="orders-skeleton-row"></div>
                <div class="orders-skeleton-row"></div>
            </div>

            @if ($isAdmin)
            <form id="bulkForm" method="POST" action="{{ route('orders.bulk') }}">
                @csrf
                <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
                <input type="hidden" name="action" id="bulkAction" value="">
                <input type="hidden" name="admin_note" id="bulkNote" value="">
                <div class="orders-bulk-bar">
                    <div class="d-flex align-items-center gap-3">
                        <span class="orders-bulk-icon"><i class="bi bi-check2-square"></i></span>
                        <div>
                            <div class="orders-bulk-title">Tindakan massal</div>
                            <div class="orders-bulk-subtitle" id="bulkCount">Pilih pesanan menunggu untuk tindakan massal.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="orders-bulk-limit d-none d-lg-inline">Maks. 50 pesanan</span>
                        <button type="button" class="btn btn-sm btn-outline-info" id="bulkRemind"
                            title="Kirim pengingat SLA untuk pesanan terpilih. Jika tidak ada yang dipilih, kirim ke semua yang menunggu lebih dari 24 jam.">
                            <i class="bi bi-alarm me-1"></i>Pengingat SLA
                        </button>
                        <button type="button" class="btn btn-sm btn-success" id="bulkApprove">
                            <i class="bi bi-check-lg me-1"></i>Setujui
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="bulkReject">
                            <i class="bi bi-x-lg me-1"></i>Tolak
                        </button>
                    </div>
                </div>
            </form>
            <form id="remindSlaForm" method="POST" action="{{ route('orders.remind-sla') }}">
                @csrf
                <div id="remindSlaIds"></div>
            </form>
            <form id="singleRejectForm" method="POST" action="">
                @csrf
                <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
                <input type="hidden" name="admin_note" id="singleRejectNote" value="">
            </form>
            @endif

            @if (in_array($viewMode, ['table', 'cards'], true))
            <div class="table-responsive orders-table-wrap">
                <table class="table security-table orders-table">
                    <colgroup>
                        @if ($isAdmin)<col class="col-check">@endif
                        <col class="col-order">
                        @if ($isAdmin)<col class="col-customer">@endif
                        <col class="col-package">
                        <col class="col-status">
                        <col class="col-proof">
                        <col class="col-date">
                        <col class="col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            @if ($isAdmin)
                            <th style="width:36px;">
                                <input class="form-check-input" type="checkbox" id="checkAll" aria-label="Pilih semua">
                            </th>
                            @endif
                            <th>Pesanan</th>
                            @if ($isAdmin)
                            <th data-col="customer">Pelanggan</th>
                            @endif
                            <th data-col="package">Paket</th>
                            <th data-col="status">Status</th>
                            <th data-col="proof">Bukti</th>
                            <th data-col="date">Tanggal</th>
                                <th class="text-end order-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($orders as $order)
                        <tr>
                            @if ($isAdmin)
                            <td class="order-check-cell">
                                @if ($order->status === 'pending')
                                <input class="form-check-input row-check" type="checkbox" name="ids[]" form="bulkForm"
                                    value="{{ $order->uuid }}" aria-label="Pilih {{ $order->order_number }}">
                                @endif
                            </td>
                            @endif
                            <td data-label="Pesanan">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="d-block fw-semibold">{{ $order->order_number }}</span>
                                    <button type="button" class="btn btn-link btn-sm p-0 js-copy-order" data-copy="{{ $order->order_number }}"
                                        aria-label="Salin nomor {{ $order->order_number }}" title="Salin nomor pesanan">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                                @if ($isAdmin)
                                <td class="order-detail" data-col="customer" data-label="Pelanggan">
                                    <span class="d-block fw-semibold">{{ $order->user?->name ?? '-' }}</span>
                                    <small class="log-muted">{{ $order->user?->email ?? '-' }}</small>
                                </td>
                                @endif
                                <td class="order-detail" data-col="package" data-label="Paket">
                                    <span class="d-block fw-semibold">{{ $order->customPackageOffer?->title ?? $order->package?->name ?? '-' }}</span>
                                @if ($order->customPackageOffer)
                                <small class="log-muted">Penawaran khusus · {{ $order->customPackageOfferOption?->label ?? 'Versi '.$order->customPackageOffer->version }}</small>
                                @endif
                                @if ($order->packageOption?->name)
                                <small class="log-muted">{{ $order->packageOption->name }}</small>
                                @endif
                                <span class="order-amount d-block">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                            </td>
                            <td data-col="status" data-label="Status">
                                @php
                                $statusTone = match ($order->status) {
                                    'paid' => 'success',
                                    'partially_paid' => 'info',
                                    'pending' => 'warning',
                                    'failed', 'expired', 'cancelled' => 'danger',
                                    default => 'muted',
                                };
                                @endphp
                                <span class="order-status-pill tone-{{ $statusTone }}" title="{{ $statusHints[$order->status] ?? 'Status pesanan.' }}">
                                    <span class="order-status-dot"></span>{{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                                @if ($isAdmin && $order->status === 'pending')
                                <div class="order-flags">
                                    @if ($order->assigned_admin_uuid)
                                    <span class="order-flag is-claimed"><i class="bi bi-person-check me-1"></i>Diklaim</span>
                                    @endif
                                    @if ($order->created_at && $order->created_at->diffInHours() >= 24)
                                    <span class="order-flag is-sla"><i class="bi bi-alarm me-1"></i>&gt;24 jam</span>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td data-col="proof" data-label="Bukti">
                                @if ($order->proof_image_path)
                                <a href="{{ route('orders.proof.show', $order->uuid) }}" target="_blank"
                                    class="order-proof-thumb js-proof-preview"
                                    data-proof-src="{{ route('orders.proof.show', $order->uuid) }}"
                                    data-proof-title="Bukti transfer {{ $order->order_number }}"
                                    title="Lihat bukti transfer {{ $order->order_number }}">
                                    <img src="{{ route('orders.proof.show', $order->uuid) }}" alt="Bukti transfer">
                                </a>
                                @else
                                <span class="log-muted">—</span>
                                @endif
                            </td>
                            <td data-col="date" data-label="Tanggal">
                                <span class="d-block">{{ $order->created_at?->translatedFormat('d F Y') ?? '-' }}</span>
                                <small class="log-muted">{{ $order->created_at?->format('H:i') ?? '' }}</small>
                                    @if ($order->status === 'pending' && $order->created_at)
                                    <small class="d-block {{ $order->created_at->diffInHours() >= 24 ? 'text-danger fw-semibold' : 'log-muted' }}">
                                        Menunggu {{ $order->created_at->diffForHumans() }}
                                    </small>
                                    <span class="order-aging {{ $order->created_at->diffInHours() >= 48 ? 'is-critical' : ($order->created_at->diffInHours() >= 24 ? 'is-late' : '') }}">
                                        {{ $order->aging_bucket }}
                                    </span>
                                    @endif
                            </td>
                                <td class="text-end order-actions">
                                    @if ($isAdmin && $order->status === 'pending')
                                    <div class="order-next-action">
                                        <i class="bi bi-arrow-right-circle me-1"></i>
                                        @if (!$order->proof_image_path)
                                        Langkah: minta bukti transfer
                                        @elseif ($order->assigned_admin_uuid)
                                        Langkah: tinjau bukti lalu setujui
                                        @else
                                        Langkah: ambil alih lalu verifikasi
                                        @endif
                                    </div>
                                    @endif
                                    <div class="dropdown order-action-menu">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="orderAction_{{ $order->uuid }}" data-bs-toggle="dropdown"
                                            data-bs-container="body" data-bs-boundary="viewport"
                                            aria-expanded="false" title="Aksi">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            aria-labelledby="orderAction_{{ $order->uuid }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('orders.show', $order->uuid) }}">
                                                    <i class="bi bi-eye me-2 text-info"></i>Detail
                                                </a>
                                            </li>
                                            @if ($isAdmin)
                                            <li>
                                                <button type="button" class="dropdown-item js-quick-view"
                                                    data-action="{{ route('orders.quick-view', $order->uuid) }}"
                                                    data-number="{{ $order->order_number }}">
                                                    <i class="bi bi-arrows-fullscreen me-2 text-primary"></i>Pratinjau cepat
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item js-activity"
                                                    data-action="{{ route('orders.activity', $order->uuid) }}"
                                                    data-number="{{ $order->order_number }}">
                                                    <i class="bi bi-clock-history me-2 text-info"></i>Aktivitas
                                                </button>
                                            </li>
                                            @endif
                                            @if ($isAdmin && $order->proof_image_path)
                                            <li>
                                                <a class="dropdown-item js-proof-preview"
                                                    href="{{ route('orders.proof.show', $order->uuid) }}"
                                                    data-proof-src="{{ route('orders.proof.show', $order->uuid) }}"
                                                    data-proof-title="Bukti transfer {{ $order->order_number }}">
                                                    <i class="bi bi-file-earmark-image me-2 text-primary"></i>Lihat bukti
                                                </a>
                                            </li>
                                            @endif
                                            @if ($isAdmin && in_array($order->status, ['pending','partially_paid']))
                                            <li>
                                                @if (!$order->assigned_admin_uuid)
                                                <form method="POST" action="{{ route('orders.claim', $order->uuid) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-person-plus me-2 text-primary"></i>Ambil Alih
                                                    </button>
                                                </form>
                                                @else
                                                <span class="dropdown-item-text small text-muted">
                                                    <i class="bi bi-person-check me-2"></i>Sudah diambil alih
                                                </span>
                                                @endif
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('orders.approve', $order->uuid) }}">
                                                    @csrf
                                                    <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
                                                    <button type="submit" class="dropdown-item text-success"
                                                        onclick="return confirm('Setujui pesanan {{ $order->order_number }}?')">
                                                        <i class="bi bi-check-lg me-2"></i>Setujui
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger js-reject-single"
                                                    data-action="{{ route('orders.reject', $order->uuid) }}"
                                                    data-number="{{ $order->order_number }}">
                                                    <i class="bi bi-x-lg me-2"></i>Tolak
                                                </button>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                        </tr>
                        @empty
                        <tr class="order-empty-row">
                            <td colspan="{{ $isAdmin ? 9 : 7 }}" class="text-center py-5">
                                <div class="order-empty-state">
                                    <span class="order-empty-icon"><i class="bi {{ $activeChips ? 'bi-funnel' : 'bi-inbox' }}"></i></span>
                                    <h6 class="mt-3 mb-1">{{ $activeChips ? 'Tidak ada hasil untuk filter ini' : 'Belum ada pesanan' }}</h6>
                                    <p class="text-muted small mb-3">
                                        {{ $activeChips ? 'Hapus sebagian filter atau reset semua untuk melihat pesanan lain.' : 'Pesanan baru akan muncul di sini setelah dibuat.' }}
                                    </p>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Atur Ulang Filter
                                        </a>
                                        @if ($isAdmin && !empty($queueCounts['all']))
                                        <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-hourglass-split me-1"></i>Lihat Antrean
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            @php
            $kanbanColumns = [
                ['key' => 'pending', 'label' => 'Menunggu Verifikasi', 'icon' => 'hourglass-split', 'tone' => 'warning', 'items' => $orders->getCollection()->where('status', 'pending')],
                ['key' => 'partially_paid', 'label' => 'DP Terverifikasi', 'icon' => 'wallet2', 'tone' => 'info', 'items' => $orders->getCollection()->where('status', 'partially_paid')],
                ['key' => 'paid', 'label' => 'Lunas', 'icon' => 'check-circle', 'tone' => 'success', 'items' => $orders->getCollection()->where('status', 'paid')],
                ['key' => 'failed', 'label' => 'Gagal / Dibatalkan', 'icon' => 'x-circle', 'tone' => 'danger', 'items' => $orders->getCollection()->whereIn('status', ['failed', 'expired', 'cancelled'])],
            ];
            @endphp
            <div class="order-kanban" aria-label="Papan antrean pesanan">
                @foreach ($kanbanColumns as $column)
                <section class="order-kanban-column kanban-{{ $column['tone'] }}">
                    <header class="order-kanban-head">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-{{ $column['icon'] }}"></i>
                            <span>{{ $column['label'] }}</span>
                        </div>
                        <span class="order-kanban-count">{{ number_format($column['items']->count()) }}</span>
                    </header>
                    <div class="order-kanban-list">
                        @forelse ($column['items'] as $order)
                        <article class="order-kanban-card">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <span class="fw-semibold small">{{ $order->order_number }}</span>
                                <button type="button" class="btn btn-link btn-sm p-0 js-copy-order" data-copy="{{ $order->order_number }}"
                                    aria-label="Salin nomor {{ $order->order_number }}">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                            <div class="order-kanban-meta">{{ $order->user?->name ?? '-' }}</div>
                            <div class="order-kanban-meta">{{ $order->customPackageOffer?->title ?? $order->package?->name ?? '-' }}</div>
                            @if ($isAdmin && $order->status === 'pending')
                            <div class="order-flags mt-2">
                                @if ($order->assigned_admin_uuid)
                                <span class="order-flag is-claimed"><i class="bi bi-person-check me-1"></i>Diklaim</span>
                                @endif
                                @if ($order->created_at && $order->created_at->diffInHours() >= 24)
                                <span class="order-flag is-sla"><i class="bi bi-alarm me-1"></i>SLA &gt;24 jam</span>
                                @endif
                            </div>
                            @endif
                            <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                                <span class="fw-bold small">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                                @if ($isAdmin && $order->status === 'pending')
                                <label class="order-kanban-check">
                                    <input type="checkbox" class="row-check" name="ids[]" form="bulkForm" value="{{ $order->uuid }}"
                                        aria-label="Pilih {{ $order->order_number }}"> Pilih
                                </label>
                                @endif
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-outline-primary flex-fill">Detail</a>
                                @if ($isAdmin)
                                <button type="button" class="btn btn-sm btn-outline-secondary js-quick-view"
                                    data-action="{{ route('orders.quick-view', $order->uuid) }}"
                                    data-number="{{ $order->order_number }}" aria-label="Pratinjau {{ $order->order_number }}">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info js-activity"
                                    data-action="{{ route('orders.activity', $order->uuid) }}"
                                    data-number="{{ $order->order_number }}" aria-label="Aktivitas {{ $order->order_number }}">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @endif
                            </div>
                        </article>
                        @empty
                        <div class="order-kanban-empty">Tidak ada pesanan pada tahap ini.</div>
                        @endforelse
                    </div>
                </section>
                @endforeach
            </div>
            @endif

            @if ($orders->hasPages())
            <div class="orders-pagination">
                <div class="orders-pagination-info">
                    <span class="orders-pagination-icon"><i class="bi bi-list-ol"></i></span>
                    <div>
                        <div class="orders-pagination-title">
                            Menampilkan {{ number_format($orders->firstItem()) }}–{{ number_format($orders->lastItem()) }} dari {{ number_format($orders->total()) }} pesanan
                        </div>
                        <div class="orders-pagination-subtitle">
                            Halaman {{ number_format($orders->currentPage()) }} dari {{ number_format($orders->lastPage()) }}
                        </div>
                    </div>
                </div>
                <div class="orders-pagination-controls">
                    <div class="orders-pagination-tools">
                        <label class="orders-pagination-label" for="ordersPerPageQuick">Per halaman</label>
                        <select id="ordersPerPageQuick" class="form-select form-select-sm" aria-label="Jumlah pesanan per halaman">
                            @foreach ([10, 25, 50, 100] as $perPageOption)
                            <option value="{{ $perPageOption }}" @selected(($filters['per_page'] ?? 10)===$perPageOption)>{{ $perPageOption }}</option>
                            @endforeach
                        </select>
                        <div class="orders-pagination-jump">
                            <label class="orders-pagination-label" for="ordersPageJump">Halaman</label>
                            <input type="number" id="ordersPageJump" class="form-control form-control-sm"
                                min="1" max="{{ $orders->lastPage() }}" value="{{ $orders->currentPage() }}"
                                aria-label="Nomor halaman">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="ordersPageGo">Pergi</button>
                        </div>
                    </div>
                    <nav class="orders-pagination-links" aria-label="Navigasi halaman pesanan">
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@push('after-style')
<style>
    .order-stats .order-stat-card { position: relative; overflow: hidden; border-radius: 16px; transition: transform .18s ease, box-shadow .18s ease; }
    .order-stats .order-stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,.1); }
    .order-stats .order-stat-card::after { content: ''; position: absolute; inset: auto 0 0; height: 3px; background: var(--stat-accent, #4b6b52); }
    .order-stats .stat-total { --stat-accent: #4b6b52; }
    .order-stats .stat-pending { --stat-accent: #f59e0b; }
    .order-stats .stat-paid { --stat-accent: #10b981; }
    .order-stats .stat-failed { --stat-accent: #ef4444; }
    .order-revenue-card { border-radius: 16px; border: 1px solid #d8e6da; background: linear-gradient(110deg, #f7f9ff 0%, #fff 58%, #f5fffb 100%); box-shadow: 0 8px 24px rgba(16,24,40,.05); }
    .order-revenue-icon { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; font-size: 22px; color: #fff; background: linear-gradient(135deg, #4b6b52, #6b8f6f); box-shadow: 0 8px 16px rgba(75,107,82,.25); }
    .order-revenue-label { font-size: 12px; letter-spacing: .04em; text-transform: uppercase; color: #667085; font-weight: 600; }
    .order-revenue-value { font-size: 24px; font-weight: 700; color: #101828; letter-spacing: -.02em; }
    .order-revenue-meta-label { font-size: 11px; color: #98a2b3; }
    .order-revenue-meta-value { font-size: 15px; font-weight: 650; color: #344054; }
    .order-undo-bar { border-radius: 14px; border: 1px solid #b9d7ff; }
    .order-toolbar-card { border-radius: 16px; border: 1px solid #eef0f4; box-shadow: 0 8px 24px rgba(16,24,40,.05); overflow: hidden; }
    .order-toolbar-head { padding: 16px 20px; border-bottom: 1px solid #eef0f4; background: linear-gradient(90deg, #fbfcff, #fff); }
    .order-toolbar-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; color: #4b6b52; background: #eef3ee; font-size: 18px; }
    .order-toolbar-title { font-size: 15px; font-weight: 700; color: #101828; }
    .order-toolbar-subtitle { font-size: 12px; color: #98a2b3; }
    .order-toolbar-badge { font-size: 12px; font-weight: 600; color: #4b6b52; background: #eef3ee; border-radius: 999px; padding: 6px 12px; }
    .order-toolbar-body { padding: 20px; }
    .order-filter-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 12px 14px; align-items: end; padding: 16px; border: 1px solid #eef0f4; border-radius: 14px; background: #fbfcff; }
    .order-filter-grid .field-search { grid-column: span 4; }
    .order-filter-grid .order-filter-field { grid-column: span 2; min-width: 0; }
    .order-filter-grid .field-date { grid-column: span 4; }
    .order-filter-grid .order-filter-actions { grid-column: span 4; display: flex; justify-content: flex-start; align-items: end; gap: 8px; }
    .order-filter-grid .security-filter-label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: #667085; }
    .order-filter-grid .form-control, .order-filter-grid .form-select { font-size: 13px; height: 38px; border-color: #e4e7ec; border-radius: 10px; }
    .order-saved-panel { margin-top: 20px; padding: 16px; border-radius: 14px; border: 1px dashed #d0d5dd; background: #f8fafc; }
    .order-saved-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; font-size: 13px; font-weight: 700; color: #344054; }
    .order-saved-count { font-size: 11px; font-weight: 700; color: #4b6b52; background: #fff; border: 1px solid #d8e6da; border-radius: 999px; padding: 2px 8px; }
    .order-saved-hint { font-size: 12px; font-weight: 400; color: #98a2b3; }
    .order-saved-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .order-saved-filters .btn-group form { margin: 0; }
    .order-saved-filters .btn-group .btn { border-color: #d0d5dd; border-radius: 0; font-size: 12px; }
    .order-saved-filters .btn-group > .btn:first-child { border-radius: 8px 0 0 8px; }
    .order-saved-filters .btn-group > .btn:last-child { border-radius: 0 8px 8px 0; }
    .order-saved-filters .btn-group > form:last-child .btn { border-radius: 0 8px 8px 0; }
    .order-saved-empty { font-size: 12px; color: #98a2b3; margin-bottom: 14px; }
    .order-save-row { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; padding-top: 12px; border-top: 1px solid #e4e7ec; }
    .order-save-row > .security-filter-label { width: 100%; margin: 0; font-size: 12px; font-weight: 600; color: #667085; }
    .order-save-row .form-control { width: 240px; border-color: #e4e7ec; border-radius: 10px; }
    .order-save-check { margin-bottom: 7px; }
    .order-save-check .form-check-label { font-size: 12px; color: #667085; }
    .order-shortcut-hint { margin-left: 6px; color: #98a2b3; font-weight: 400; }
    .order-shortcut-hint kbd { padding: 1px 5px; border: 1px solid #d0d5dd; border-bottom-width: 2px; border-radius: 5px; background: #fff; color: #344054; font-family: inherit; font-size: 10px; font-weight: 600; line-height: 1.2; }
    .order-kpi-strip { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .order-kpi-item { display: inline-flex; align-items: center; gap: 2px; padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .order-kpi-item b { font-variant-numeric: tabular-nums; }
    .order-kpi-item.is-warning { color: #b54708; background: #fffaeb; }
    .order-kpi-item.is-info { color: #175cd3; background: #eff8ff; }
    .order-kpi-item.is-success { color: #027a48; background: #ecfdf3; }
    .order-date-presets { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-top: 12px; }
    .order-date-presets-label { font-size: 12px; font-weight: 700; color: #475467; }
    .order-date-preset { padding: 5px 10px; border: 1px solid #d0d5dd; border-radius: 999px; background: #fff; color: #475467; font-size: 11px; font-weight: 600; }
    .order-date-preset:hover { border-color: #4b6b52; color: #4b6b52; }
    .order-date-preset.active { border-color: #4b6b52; background: #eef3ee; color: #4b6b52; }
    .order-date-presets-hint { font-size: 11px; color: #98a2b3; }
    .order-sticky-bar {
        position: sticky;
        top: 8px;
        z-index: 1030;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin: 0 0 14px;
        padding: 10px 14px;
        border: 1px solid #d0d5dd;
        border-radius: 12px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 12px 28px rgba(16,24,40,.12);
        backdrop-filter: blur(8px);
    }
    .order-sticky-icon { width: 32px; height: 32px; border-radius: 10px; display: grid; place-items: center; color: #b54708; background: #fffaeb; }
    .order-sticky-title { font-size: 12px; font-weight: 700; color: #101828; }
    .order-sticky-subtitle { max-width: 320px; font-size: 11px; color: #667085; }

    @media (max-width: 1199.98px) {
        .order-filter-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .order-filter-grid .field-search, .order-filter-grid .order-filter-actions { grid-column: span 6; }
        .order-filter-grid .order-filter-field, .order-filter-grid .field-date { grid-column: span 3; }
        .order-filter-grid .order-filter-actions { justify-content: flex-start; }
    }

    @media (max-width: 575.98px) {
        .order-filter-grid { grid-template-columns: 1fr; padding: 12px; }
        .order-filter-grid .order-filter-field, .order-filter-grid .field-search, .order-filter-grid .field-date, .order-filter-grid .order-filter-actions { grid-column: 1 / -1; }
        .order-save-row .form-control { width: 100%; }
    }

    .orders-table {
        min-width: 1020px;
        margin-bottom: 0;
    }

    .orders-table th {
        background: #f8f9fa;
        font-size: .75rem;
        letter-spacing: .03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .orders-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .orders-table .order-actions {
        width: 150px;
        padding-right: 1rem !important;
    }

    .orders-table .order-actions form {
        display: block;
        margin: 0;
    }

    .orders-table .order-action-menu > .btn {
        padding: .25rem .5rem;
    }

    .orders-table .dropdown-menu {
        min-width: 11rem;
        padding: .3rem;
    }

    .orders-table .dropdown-item {
        border-radius: .45rem;
    }

    .orders-table .order-detail {
        white-space: normal;
        min-width: 170px;
    }

    .table-responsive > .orders-table {
        overflow: visible;
    }

    .order-view-switch .btn.active {
        background: #4b6b52;
        border-color: #4b6b52;
        color: #fff;
    }

    .order-filter-chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
    }

    .order-filter-chips-label {
        font-size: 12px;
        font-weight: 700;
        color: #667085;
    }

    .order-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eef3ee;
        color: #4b6b52;
        font-size: 12px;
        font-weight: 600;
    }

    .order-filter-chip a {
        color: inherit;
        font-size: 16px;
        line-height: 1;
        text-decoration: none;
    }

    .order-filter-chip-clear {
        font-size: 12px;
        color: #667085;
    }

    .order-column-menu {
        min-width: 190px;
    }

    .order-column-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 4px;
        font-size: 13px;
        color: #344054;
        cursor: pointer;
    }

    .orders-table-tools {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
        padding: 12px 14px;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        background: #fbfcff;
    }

    .orders-table-tools-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .orders-table-tools-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #4b6b52;
        background: #eef3ee;
    }

    .orders-table-tools-title {
        font-size: 13px;
        font-weight: 700;
        color: #101828;
    }

    .orders-table-tools-subtitle {
        font-size: 11px;
        color: #98a2b3;
    }

    .orders-bulk-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px 14px;
        border: 1px solid #d8e6da;
        border-radius: 12px;
        background: linear-gradient(100deg, #f7f9ff, #fff);
    }

    .orders-bulk-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #4b6b52;
        background: #eef3ee;
    }

    .orders-bulk-title {
        font-size: 13px;
        font-weight: 700;
        color: #101828;
    }

    .orders-bulk-subtitle {
        font-size: 11px;
        color: #667085;
    }

    .orders-bulk-limit {
        font-size: 11px;
        color: #98a2b3;
    }

    .orders-table {
        min-width: 1180px;
    }

    .orders-table col.col-check { width: 40px; }
    .orders-table col.col-order { width: 210px; }
    .orders-table col.col-customer { width: 200px; }
    .orders-table col.col-package { width: 190px; }
    .orders-table col.col-amount { width: 150px; }
    .orders-table col.col-status { width: 130px; }
    .orders-table col.col-proof { width: 90px; }
    .orders-table col.col-date { width: 180px; }
    .orders-table col.col-action { width: 100px; }

    .orders-table th {
        padding: .7rem .9rem;
        border-bottom: 1px solid #e4e7ec;
    }

    .orders-table td {
        padding: .85rem .9rem;
        border-bottom: 1px solid #f2f4f7;
    }

    .orders-table tbody tr:hover {
        background: #fbfcff;
    }

    .order-amount {
        font-weight: 700;
        color: #101828;
        font-variant-numeric: tabular-nums;
    }

    .order-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .order-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .order-status-pill.tone-success { color: #027a48; background: #ecfdf3; }
    .order-status-pill.tone-info { color: #175cd3; background: #eff8ff; }
    .order-status-pill.tone-warning { color: #b54708; background: #fffaeb; }
    .order-status-pill.tone-danger { color: #b42318; background: #fef3f2; }
    .order-status-pill.tone-muted { color: #475467; background: #f2f4f7; }

    .order-proof-thumb {
        display: inline-block;
        width: 44px;
        height: 44px;
        border-radius: 8px;
        border: 1px solid #e4e7ec;
        overflow: hidden;
    }

    .order-proof-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .order-aging {
        display: inline-block;
        margin-top: 5px;
        padding: 2px 7px;
        border-radius: 6px;
        background: #f2f4f7;
        color: #667085;
        font-size: 10px;
        font-weight: 700;
    }

    .order-aging.is-late { background: #fffaeb; color: #b54708; }
    .order-aging.is-critical { background: #fef3f2; color: #b42318; }
    .order-flags { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 6px; }
    .order-flag { display: inline-flex; align-items: center; padding: 3px 7px; border-radius: 6px; font-size: 10px; font-weight: 700; white-space: nowrap; }
    .order-flag.is-claimed { color: #175cd3; background: #eff8ff; }
    .order-flag.is-sla { color: #b42318; background: #fef3f2; }
    .order-next-action { margin-bottom: 6px; font-size: 10px; font-weight: 600; color: #667085; white-space: normal; }

    .orders-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #eef0f4;
    }

    .orders-pagination-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .orders-pagination-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #475467;
        background: #f2f4f7;
    }

    .orders-pagination-title {
        font-size: 12px;
        font-weight: 700;
        color: #344054;
    }

    .orders-pagination-subtitle {
        font-size: 11px;
        color: #98a2b3;
    }

    .orders-pagination .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin: 0;
    }

    .orders-pagination .page-link {
        min-width: 32px;
        padding: .35rem .6rem;
        border: 1px solid #e4e7ec;
        border-radius: 8px !important;
        color: #475467;
        font-size: 12px;
        text-align: center;
    }

    .orders-pagination .page-item.active .page-link {
        border-color: #4b6b52;
        background: #4b6b52;
        color: #fff;
    }

    .orders-pagination .page-item.disabled .page-link {
        color: #c4cbd5;
        background: #f8f9fb;
    }

    .orders-pagination-controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
    }
    .orders-pagination-tools {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        padding: 6px 8px;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        background: #fcfcfd;
    }
    .orders-pagination-label { font-size: 11px; font-weight: 600; color: #667085; }
    .orders-pagination-tools .form-select,
    .orders-pagination-tools .form-control { width: auto; min-width: 58px; font-size: 12px; }
    .orders-pagination-tools .form-control { min-width: 62px; }
    .orders-pagination-jump { display: flex; align-items: center; gap: 6px; }
    .orders-pagination-links .pagination { justify-content: flex-end; }

    .orders-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .orders-card-body.table-compact .orders-table th,
    .orders-card-body.table-compact .orders-table td {
        padding-top: .35rem;
        padding-bottom: .35rem;
        font-size: .82rem;
        line-height: 1.25;
    }

    .orders-card-body.table-compact .order-proof-thumb {
        width: 32px;
        height: 32px;
    }

    .orders-skeleton {
        display: grid;
        gap: 10px;
        margin-bottom: 16px;
    }

    .orders-skeleton-row {
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(90deg, #f2f4f7 25%, #e9edf3 50%, #f2f4f7 75%);
        background-size: 200% 100%;
        animation: order-skeleton 1.2s infinite linear;
    }

    @keyframes order-skeleton {
        from { background-position: 200% 0; }
        to { background-position: -200% 0; }
    }

    .orders-card-body.is-loading .orders-table-wrap,
    .orders-card-body.is-loading .order-kanban {
        opacity: .45;
        pointer-events: none;
    }

    .order-empty-state {
        max-width: 320px;
        margin: 0 auto;
    }

    .order-empty-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: inline-grid;
        place-items: center;
        font-size: 24px;
        color: #98a2b3;
        background: #f2f4f7;
    }

    .order-kanban {
        display: grid;
        grid-template-columns: repeat(3, minmax(240px, 1fr));
        gap: 16px;
        align-items: start;
    }

    .order-kanban-column {
        border: 1px solid #eef0f4;
        border-radius: 16px;
        background: #f8fafc;
        overflow: hidden;
    }

    .order-kanban-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 700;
        background: #fff;
        border-bottom: 1px solid #eef0f4;
    }

    .order-kanban-head i { font-size: 16px; }
    .kanban-warning .order-kanban-head { color: #b54708; }
    .kanban-info .order-kanban-head { color: #175cd3; }
    .kanban-success .order-kanban-head { color: #027a48; }
    .kanban-danger .order-kanban-head { color: #b42318; }

    .order-kanban-count {
        min-width: 28px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #f2f4f7;
        color: #475467;
        text-align: center;
        font-size: 11px;
    }

    .order-kanban-list {
        display: grid;
        gap: 10px;
        padding: 12px;
    }

    .order-kanban-card {
        padding: 14px;
        border: 1px solid #e4e7ec;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(16, 24, 40, .04);
    }

    .order-kanban-meta {
        font-size: 12px;
        color: #667085;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .order-kanban-check {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: #667085;
    }

    .order-kanban-empty {
        padding: 24px 12px;
        text-align: center;
        font-size: 12px;
        color: #98a2b3;
    }

    .order-quick-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
        color: #98a2b3;
    }

    .order-quick-proof {
        width: 88px;
        height: 88px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e4e7ec;
    }

    .order-action-menu .btn:focus-visible,
    .order-view-switch .btn:focus-visible,
    .js-quick-view:focus-visible,
    .js-copy-order:focus-visible {
        outline: 3px solid rgba(91, 124, 250, .35);
        outline-offset: 2px;
    }

    .orders-card-body.view-cards .table-responsive { overflow: visible; }
    .orders-card-body.view-cards .orders-table { min-width: 0; border: 0; }
    .orders-card-body.view-cards .orders-table thead { display: none; }
    .orders-card-body.view-cards .orders-table,
    .orders-card-body.view-cards .orders-table tbody { display: block; width: 100%; }
    .orders-card-body.view-cards .orders-table tbody {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
        padding: 2px;
    }
    .orders-card-body.view-cards .orders-table tr {
        position: relative;
        display: grid;
        grid-template-columns: 1fr;
        align-content: start;
        width: 100%;
        margin: 0;
        padding: 16px;
        border: 1px solid #e4e7ec;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(16,24,40,.05);
    }
    .orders-card-body.view-cards .orders-table td {
        display: block;
        width: 100%;
        padding: .35rem 0;
        border: 0;
        white-space: normal;
        text-align: left !important;
        vertical-align: top;
    }
    .orders-card-body.view-cards .orders-table td[data-label]::before {
        content: attr(data-label);
        display: block;
        margin-bottom: .2rem;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #98a2b3;
    }
    .orders-card-body.view-cards .orders-table .order-check-cell {
        position: absolute;
        top: 14px;
        right: 14px;
        width: auto;
        padding: 0;
    }
    .orders-card-body.view-cards .orders-table td[data-label="Pesanan"] {
        padding: 0 30px 12px 0;
        border-bottom: 1px solid #eef0f4;
    }
    .orders-card-body.view-cards .orders-table td[data-label="Pesanan"]::before { display: none; }
    .orders-card-body.view-cards .orders-table td[data-label="Pesanan"] .fw-semibold { font-size: 1rem; }
    .orders-card-body.view-cards .orders-table td[data-label="Pesanan"] .js-copy-order { color: #98a2b3; }
    .orders-card-body.view-cards .orders-table .order-detail small,
    .orders-card-body.view-cards .orders-table .order-amount { font-size: .82rem; }
    .orders-card-body.view-cards .orders-table td[data-col="status"] .order-flags {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        margin-top: .45rem;
    }
    .orders-card-body.view-cards .orders-table td[data-col="proof"] { padding-top: .2rem; padding-bottom: .2rem; }
    .orders-card-body.view-cards .orders-table .order-proof-thumb img {
        width: 52px;
        height: 52px;
        object-fit: cover;
    }
    .orders-card-body.view-cards .orders-table .order-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .6rem;
        margin-top: .5rem;
        padding-top: .75rem;
        border-top: 1px solid #eef0f4;
    }
    .orders-card-body.view-cards .orders-table .order-actions::before { display: none; }
    .orders-card-body.view-cards .orders-table .order-next-action { flex: 1 1 100%; margin-bottom: .25rem; }
    .orders-card-body.view-cards .orders-table tr.order-empty-row { grid-column: 1 / -1; box-shadow: none; background: transparent; border: 0; }

    @media (max-width: 991.98px) {
        .order-kanban { grid-template-columns: 1fr; }
    }

    @media (max-width: 575.98px) {
        .orders-table thead { display: none; }
        .orders-table, .orders-table tbody, .orders-table tr, .orders-table td { display: block; width: 100%; }
        .orders-table tr {
            margin-bottom: 12px;
            padding: 10px 14px;
            border: 1px solid #e4e7ec;
            border-radius: 12px;
            background: #fff;
        }
        .orders-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: .45rem 0;
            border: 0;
            white-space: normal;
            text-align: left !important;
        }
        .orders-table td[data-label]::before {
            content: attr(data-label);
            flex: 0 0 84px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            color: #98a2b3;
        }
        .orders-table .order-actions { width: 100%; }
        .orders-table .dropdown-menu { min-width: 12rem; }
        .orders-pagination { align-items: flex-start; flex-direction: column; }
        .orders-pagination-controls { justify-content: flex-start; width: 100%; }
        .orders-pagination-links { width: 100%; }
        .orders-pagination-links .pagination { justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .orders-skeleton-row { animation: none; }
        .order-stats .order-stat-card { transition: none; }
    }

    .order-activity-summary {
        padding: .9rem;
        border: 1px solid var(--bs-border-color);
        border-radius: .75rem;
        background: rgba(var(--bs-secondary-bg-rgb), .5);
    }
    .order-activity-proof-img {
        width: 100%;
        max-height: 240px;
        object-fit: contain;
        border: 1px solid var(--bs-border-color);
        border-radius: .65rem;
        background: var(--bs-body-bg);
    }
    .order-activity-timeline { display: flex; flex-direction: column; gap: .8rem; }
    .order-activity-item { position: relative; display: flex; gap: .7rem; padding-left: .2rem; }
    .order-activity-item:not(:last-child)::before {
        content: "";
        position: absolute;
        left: .42rem;
        top: .9rem;
        bottom: -.8rem;
        width: 1px;
        background: var(--bs-border-color);
    }
    .order-activity-dot {
        width: .6rem;
        height: .6rem;
        margin-top: .28rem;
        border-radius: 50%;
        background: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), .14);
        flex: 0 0 auto;
    }
    .order-proof-preview-img { width: 100%; max-height: 78vh; object-fit: contain; border-radius: .6rem; }
    .offcanvas.order-activity-offcanvas { width: min(440px, 100vw); }
</style>
@endpush

@if ($isAdmin)
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2" id="rejectTarget">Pilih pesanan yang ingin ditolak.</p>
                <label class="form-label small fw-semibold" for="rejectReason">Alasan penolakan <span class="text-danger">*</span></label>
                <textarea id="rejectReason" class="form-control" rows="3" required
                    placeholder="mis. Bukti transfer tidak terbaca"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="rejectConfirm">
                    <i class="bi bi-x-lg me-1"></i>Tolak Pesanan
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewTitle">Pratinjau pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="quickViewBody">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
                    Memuat ringkasan pesanan...
                </div>
            </div>
        </div>
    </div>
</div>

@if ($isAdmin)
<div class="offcanvas offcanvas-end order-activity-offcanvas" tabindex="-1" id="activityOffcanvas" aria-labelledby="activityOffcanvasTitle">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title" id="activityOffcanvasTitle">Aktivitas pesanan</h5>
            <div class="small text-muted" id="activityOffcanvasSubtitle">Memuat riwayat...</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body" id="activityOffcanvasBody">
        <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
            Memuat aktivitas...
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="proofPreviewModal" tabindex="-1" aria-labelledby="proofPreviewTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofPreviewTitle">Bukti transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-2 text-center bg-body-tertiary">
                <img id="proofPreviewImage" class="order-proof-preview-img" src="" alt="Bukti transfer">
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    (function() {
        var bs = window.bootstrap;
        var checkAll = document.getElementById('checkAll');
        var rows = Array.prototype.slice.call(document.querySelectorAll('.row-check'));
        var count = document.getElementById('bulkCount');
        var bulkForm = document.getElementById('bulkForm');
        var bulkAction = document.getElementById('bulkAction');
        var bulkNote = document.getElementById('bulkNote');
        var singleRejectForm = document.getElementById('singleRejectForm');
        var singleRejectNote = document.getElementById('singleRejectNote');
        var rejectModalEl = document.getElementById('rejectModal');
        var rejectModal = rejectModalEl && bs ? new bs.Modal(rejectModalEl) : null;
        var rejectReason = document.getElementById('rejectReason');
        var rejectTarget = document.getElementById('rejectTarget');
        var rejectConfirm = document.getElementById('rejectConfirm');
        var pendingSingleAction = null;
        var saveFilterButton = document.getElementById('saveFilterButton');
        var saveFilterForm = document.getElementById('saveFilterForm');
        var saveFilterName = document.getElementById('saveFilterName');
        var saveFilterNameInput = document.getElementById('saveFilterNameInput');
        var saveFilterPayload = document.getElementById('saveFilterPayload');

        if (saveFilterButton && saveFilterForm && saveFilterName && saveFilterNameInput && saveFilterPayload) {
            saveFilterButton.addEventListener('click', function() {
                var name = saveFilterName.value.trim();

                if (!name) {
                    saveFilterName.focus();
                    return;
                }

                var filterForm = document.querySelector('.security-filter-form');
                var payload = filterForm ? Object.fromEntries(new FormData(filterForm).entries()) : {};

                saveFilterNameInput.value = name;
                saveFilterPayload.value = JSON.stringify(payload);
                saveFilterForm.submit();
            });
        }

        function updateCount() {
            var selected = rows.filter(function(box) {
                return box.checked;
            }).length;
            var countText = selected ?
                selected + ' pesanan dipilih untuk tindakan massal.' :
                'Pilih pesanan menunggu untuk tindakan massal.';
            if (count) {
                count.textContent = countText;
            }
            var stickyBulkCount = document.getElementById('stickyBulkCount');
            if (stickyBulkCount) {
                stickyBulkCount.textContent = countText;
            }
            if (checkAll) {
                checkAll.checked = rows.length > 0 && selected === rows.length;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                rows.forEach(function(box) {
                    box.checked = checkAll.checked;
                });
                updateCount();
            });
        }

        rows.forEach(function(box) {
            box.addEventListener('change', updateCount);
        });

        function selectedCount() {
            return rows.filter(function(box) {
                return box.checked;
            }).length;
        }

        function submitRemindSla() {
            var remindForm = document.getElementById('remindSlaForm');
            var remindIds = document.getElementById('remindSlaIds');
            if (!remindForm || !remindIds) return;

            var selected = rows.filter(function(box) {
                return box.checked;
            }).map(function(box) {
                return box.value;
            });

            var message = selected.length
                ? 'Kirim pengingat SLA ke ' + selected.length + ' pesanan terpilih?'
                : 'Kirim pengingat SLA ke semua pesanan yang menunggu lebih dari 24 jam?';

            if (!confirm(message)) return;

            remindIds.innerHTML = '';
            selected.forEach(function(id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                remindIds.appendChild(input);
            });
            remindForm.submit();
        }

        function openRejectModal(targetText, action) {
            if (!rejectModal || !rejectReason) {
                var reason = prompt(targetText + ' Alasan penolakan wajib diisi.');
                if (!reason || !reason.trim()) {
                    alert('Alasan penolakan wajib diisi.');
                    return;
                }
                if (action) {
                    if (!singleRejectForm || !singleRejectNote) return;
                    singleRejectForm.action = action;
                    singleRejectNote.value = reason.trim();
                    singleRejectForm.submit();
                } else {
                    if (!bulkForm || !bulkAction || !bulkNote) return;
                    bulkNote.disabled = false;
                    bulkNote.value = reason.trim();
                    bulkAction.value = 'reject';
                    bulkForm.submit();
                }
                return;
            }
            pendingSingleAction = action || null;
            if (rejectTarget) {
                rejectTarget.textContent = targetText;
            }
            rejectReason.value = '';
            rejectModal.show();
            setTimeout(function() {
                rejectReason.focus();
            }, 250);
        }

        function submitBulk(action, message) {
            var selected = selectedCount();
            if (!selected) {
                alert('Pilih minimal satu pesanan menunggu.');
                return;
            }
            if (action === 'reject') {
                openRejectModal('Tolak ' + selected + ' pesanan terpilih?', null);
                return;
            }
            if (!confirm(message.replace(':count', selected))) {
                return;
            }
            if (!bulkForm || !bulkAction || !bulkNote) return;
            bulkNote.disabled = action === 'approve';
            bulkAction.value = action;
            bulkForm.submit();
        }

        var approve = document.getElementById('bulkApprove');
        var reject = document.getElementById('bulkReject');
        if (approve) {
            approve.addEventListener('click', function() {
                submitBulk('approve', 'Setujui :count pesanan terpilih?');
            });
        }
        if (reject) {
            reject.addEventListener('click', function() {
                submitBulk('reject', '');
            });
        }

        var stickyApprove = document.getElementById('stickyApprove');
        var stickyReject = document.getElementById('stickyReject');
        if (stickyApprove) {
            stickyApprove.addEventListener('click', function() {
                submitBulk('approve', 'Setujui :count pesanan terpilih?');
            });
        }
        if (stickyReject) {
            stickyReject.addEventListener('click', function() {
                submitBulk('reject', '');
            });
        }

        var bulkRemind = document.getElementById('bulkRemind');
        var stickyRemind = document.getElementById('stickyRemind');
        if (bulkRemind) {
            bulkRemind.addEventListener('click', submitRemindSla);
        }
        if (stickyRemind) {
            stickyRemind.addEventListener('click', submitRemindSla);
        }

        document.querySelectorAll('.js-reject-single').forEach(function(button) {
            button.addEventListener('click', function() {
                var dropdown = button.closest('.dropdown');
                var toggle = dropdown ? dropdown.querySelector('[data-bs-toggle="dropdown"]') : null;

                if (toggle && bs) {
                    bs.Dropdown.getOrCreateInstance(toggle).hide();
                }

                openRejectModal('Tolak ' + button.dataset.number + '?', button.dataset.action);
            });
        });

        if (rejectConfirm) {
            rejectConfirm.addEventListener('click', function() {
                var reason = (rejectReason.value || '').trim();
                if (!reason) {
                    alert('Alasan penolakan wajib diisi.');
                    rejectReason.focus();
                    return;
                }
                if (pendingSingleAction) {
                    if (!singleRejectForm || !singleRejectNote) return;
                    singleRejectForm.action = pendingSingleAction;
                    singleRejectNote.value = reason;
                    singleRejectForm.submit();
                    return;
                }
                if (!bulkForm || !bulkAction || !bulkNote) return;
                bulkNote.disabled = false;
                bulkNote.value = reason;
                bulkAction.value = 'reject';
                bulkForm.submit();
            });
        }

        if (rejectModalEl) {
            rejectModalEl.addEventListener('hidden.bs.modal', function() {
                pendingSingleAction = null;
                if (rejectReason) {
                    rejectReason.value = '';
                }
            });
        }

        var cardBody = document.getElementById('ordersCardBody');
        var skeleton = document.getElementById('ordersSkeleton');
        var filterForm = document.querySelector('.security-filter-form');

        if (filterForm && cardBody && skeleton) {
            filterForm.addEventListener('submit', function() {
                if (!filterForm.checkValidity()) return;
                cardBody.classList.add('is-loading');
                skeleton.classList.remove('d-none');
            });
        }

        document.querySelectorAll('.order-date-preset').forEach(function(button) {
            button.addEventListener('click', function() {
                var start = document.getElementById('filterStart');
                var end = document.getElementById('filterEnd');
                if (!filterForm || !start || !end) return;
                start.value = button.dataset.start || '';
                end.value = button.dataset.end || '';
                if (filterForm.requestSubmit) {
                    filterForm.requestSubmit();
                } else {
                    filterForm.submit();
                }
            });
        });

        var stickyBar = document.getElementById('orderStickyBar');
        var toolbarCard = document.querySelector('.order-toolbar-card');

        function updateStickyBar() {
            if (!stickyBar) return;
            var show = toolbarCard && window.scrollY > (toolbarCard.offsetTop + toolbarCard.offsetHeight - 120);
            stickyBar.classList.toggle('d-none', !show);
        }

        window.addEventListener('scroll', updateStickyBar, { passive: true });
        updateStickyBar();

        function focusFilter() {
            var target = document.getElementById('filterSearch') || document.getElementById('filterStatus');
            if (!target) return;
            if (target.scrollIntoView) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            setTimeout(function() {
                target.focus();
            }, 250);
        }

        var stickyFilter = document.getElementById('stickyFilter');
        if (stickyFilter) {
            stickyFilter.addEventListener('click', focusFilter);
        }

        var filterSearchInput = document.getElementById('filterSearch');
        var searchLengthHint = document.getElementById('searchLengthHint');

        if (filterSearchInput && searchLengthHint) {
            function updateSearchLengthHint() {
                var length = filterSearchInput.value.trim().length;
                searchLengthHint.classList.toggle('d-none', length === 0 || length >= 2);
                filterSearchInput.classList.toggle('is-invalid', length === 1);
            }

            filterSearchInput.addEventListener('input', updateSearchLengthHint);
            updateSearchLengthHint();
        }

        document.addEventListener('keydown', function(event) {
            var target = event.target;
            var tag = target && target.tagName ? target.tagName.toLowerCase() : '';
            if (event.ctrlKey || event.metaKey || event.altKey) return;
            if (tag === 'input' || tag === 'textarea' || tag === 'select' || (target && target.isContentEditable)) return;

            if (event.key === '/') {
                event.preventDefault();
                focusFilter();
                return;
            }

            var key = (event.key || '').toLowerCase();
            if (key === 'a' && checkAll) {
                event.preventDefault();
                checkAll.checked = !checkAll.checked;
                rows.forEach(function(box) { box.checked = checkAll.checked; });
                updateCount();
            }
            if (key === 's' && approve) {
                event.preventDefault();
                approve.click();
            }
        });

        var columnToggles = Array.prototype.slice.call(document.querySelectorAll('.order-col-toggle'));

        function exportColumnsForVisibleColumns() {
            var columns = ['order'];
            var groups = {
                date: ['date'],
                customer: ['customer', 'email'],
                package: ['package', 'option', 'amount'],
                status: ['status'],
                proof: ['proof']
            };

            Object.keys(groups).forEach(function(key) {
                var toggle = document.querySelector('.order-col-toggle[data-col="' + key + '"]');

                if (!toggle || toggle.checked) {
                    groups[key].forEach(function(column) {
                        if (columns.indexOf(column) < 0) {
                            columns.push(column);
                        }
                    });
                }
            });

            return columns;
        }

        function syncExportLinks() {
            var columns = exportColumnsForVisibleColumns().join(',');

            document.querySelectorAll('[data-export-link]').forEach(function(link) {
                var url = new URL(link.href);
                url.searchParams.set('columns', columns);
                link.href = url.toString();
            });
        }

        function applyColumns(hiddenColumns) {
            document.querySelectorAll('[data-col]:not(.order-col-toggle)').forEach(function(cell) {
                cell.style.display = hiddenColumns.indexOf(cell.dataset.col) >= 0 ? 'none' : '';
            });
            columnToggles.forEach(function(toggle) {
                toggle.checked = hiddenColumns.indexOf(toggle.dataset.col) < 0;
            });
            syncExportLinks();
        }

        if (columnToggles.length) {
            var storedColumns = [];
            try {
                storedColumns = JSON.parse(localStorage.getItem('orders.hiddenColumns') || '[]');
            } catch (e) {
                storedColumns = [];
            }
            applyColumns(Array.isArray(storedColumns) ? storedColumns : []);

            columnToggles.forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    var hidden = columnToggles
                        .filter(function(item) { return !item.checked; })
                        .map(function(item) { return item.dataset.col; });
                    applyColumns(hidden);
                    try {
                        localStorage.setItem('orders.hiddenColumns', JSON.stringify(hidden));
                    } catch (e) {}
                });
            });
        }

        var densityButton = document.getElementById('orderDensityButton');

        if (densityButton && cardBody) {
            var compact = false;
            try {
                compact = localStorage.getItem('orders.compact') === '1';
            } catch (e) {}

            function applyDensity(value) {
                cardBody.classList.toggle('table-compact', value);
                densityButton.setAttribute('aria-pressed', value ? 'true' : 'false');
                densityButton.setAttribute('title', value ? 'Kembalikan ke normal' : 'Ubah ke padat');
                densityButton.innerHTML = value
                    ? '<i class="bi bi-list-stars me-1"></i>Normal'
                    : '<i class="bi bi-list me-1"></i>Ringkas';
            }

            applyDensity(compact);

            densityButton.addEventListener('click', function() {
                compact = !compact;
                applyDensity(compact);
                try {
                    localStorage.setItem('orders.compact', compact ? '1' : '0');
                } catch (e) {}
            });
        }

        function copyText(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text);
            }
            var input = document.createElement('textarea');
            input.value = text;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
            } catch (e) {}
            document.body.removeChild(input);
            return Promise.resolve();
        }

        document.querySelectorAll('.js-copy-order').forEach(function(button) {
            button.addEventListener('click', function() {
                copyText(button.dataset.copy || '').then(function() {
                    var icon = button.querySelector('i');
                    if (!icon) return;
                    icon.classList.remove('bi-copy');
                    icon.classList.add('bi-check2');
                    setTimeout(function() {
                        icon.classList.remove('bi-check2');
                        icon.classList.add('bi-copy');
                    }, 1200);
                }).catch(function() {});
            });
        });

        var quickViewModalEl = document.getElementById('quickViewModal');
        var quickViewModal = quickViewModalEl && bs ? new bs.Modal(quickViewModalEl) : null;
        var quickViewTitle = document.getElementById('quickViewTitle');
        var quickViewBody = document.getElementById('quickViewBody');

        document.querySelectorAll('.js-quick-view').forEach(function(button) {
            button.addEventListener('click', function() {
                if (!quickViewModal || !quickViewBody) return;
                var dropdown = button.closest('.dropdown');
                var toggle = dropdown ? dropdown.querySelector('[data-bs-toggle="dropdown"]') : null;
                if (toggle && bs) {
                    bs.Dropdown.getOrCreateInstance(toggle).hide();
                }
                if (quickViewTitle) {
                    quickViewTitle.textContent = 'Pratinjau ' + (button.dataset.number || 'pesanan');
                }
                quickViewBody.innerHTML = '<div class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>Memuat ringkasan pesanan...</div>';
                quickViewModal.show();

                fetch(button.dataset.action, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(response) {
                        if (!response.ok) throw new Error('Gagal memuat pratinjau.');
                        return response.text();
                    })
                    .then(function(html) {
                        quickViewBody.innerHTML = html;
                    })
                    .catch(function() {
                        quickViewBody.innerHTML = '<div class="alert alert-danger mb-0">Pratinjau tidak dapat dimuat. Silakan buka halaman detail.</div>';
                    });
            });
        });

        var activityOffcanvasEl = document.getElementById('activityOffcanvas');
        var activityOffcanvas = activityOffcanvasEl && bs ? new bs.Offcanvas(activityOffcanvasEl) : null;
        var activityOffcanvasBody = document.getElementById('activityOffcanvasBody');
        var activityOffcanvasSubtitle = document.getElementById('activityOffcanvasSubtitle');
        var proofPreviewModalEl = document.getElementById('proofPreviewModal');
        var proofPreviewModal = proofPreviewModalEl && bs ? new bs.Modal(proofPreviewModalEl) : null;
        var proofPreviewImage = document.getElementById('proofPreviewImage');
        var proofPreviewTitle = document.getElementById('proofPreviewTitle');

        document.addEventListener('click', function(event) {
            var target = event.target;
            if (!target || !target.closest) return;

            var activityButton = target.closest('.js-activity');
            if (activityButton && activityOffcanvas && activityOffcanvasBody) {
                var dropdown = activityButton.closest('.dropdown');
                var toggle = dropdown ? dropdown.querySelector('[data-bs-toggle="dropdown"]') : null;
                if (toggle && bs) {
                    bs.Dropdown.getOrCreateInstance(toggle).hide();
                }
                if (activityOffcanvasSubtitle) {
                    activityOffcanvasSubtitle.textContent = activityButton.dataset.number || '';
                }
                activityOffcanvasBody.innerHTML = '<div class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>Memuat aktivitas...</div>';
                activityOffcanvas.show();

                fetch(activityButton.dataset.action, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(response) {
                        if (!response.ok) throw new Error('Gagal memuat aktivitas.');
                        return response.text();
                    })
                    .then(function(html) {
                        activityOffcanvasBody.innerHTML = html;
                    })
                    .catch(function() {
                        activityOffcanvasBody.innerHTML = '<div class="alert alert-danger mb-0">Aktivitas tidak dapat dimuat.</div>';
                    });
                return;
            }

            var proofButton = target.closest('.js-proof-preview');
            if (proofButton && proofPreviewModal && proofPreviewImage) {
                event.preventDefault();
                var src = proofButton.dataset.proofSrc || proofButton.getAttribute('href');
                if (!src) return;
                if (proofPreviewTitle) {
                    proofPreviewTitle.textContent = proofButton.dataset.proofTitle || 'Bukti transfer';
                }
                proofPreviewImage.src = src;
                proofPreviewImage.alt = proofButton.dataset.proofTitle || 'Bukti transfer';
                proofPreviewModal.show();
            }
        });

        if (proofPreviewModalEl) {
            proofPreviewModalEl.addEventListener('hidden.bs.modal', function() {
                if (proofPreviewImage) proofPreviewImage.src = '';
            });
        }

        document.addEventListener('submit', function(event) {
            var form = event.target;

            if (!form || !form.matches || !form.matches('form[data-orders-decision]')) {
                return;
            }

            form.querySelectorAll('button[type="submit"]').forEach(function(button) {
                button.disabled = true;
            });
        }, true);

        function setOrdersQueryParam(name, value) {
            var url = new URL(window.location.href);

            if (value === '' || value === null || typeof value === 'undefined') {
                url.searchParams.delete(name);
            } else {
                url.searchParams.set(name, value);
            }

            window.location.href = url.toString();
        }

        var ordersPerPageQuick = document.getElementById('ordersPerPageQuick');
        var ordersPageJump = document.getElementById('ordersPageJump');
        var ordersPageGo = document.getElementById('ordersPageGo');

        if (ordersPerPageQuick) {
            ordersPerPageQuick.addEventListener('change', function() {
                setOrdersQueryParam('per_page', ordersPerPageQuick.value);
            });
        }

        if (ordersPageJump && ordersPageGo) {
            function goToOrdersPage() {
                var max = parseInt(ordersPageJump.max, 10) || 1;
                var page = parseInt(ordersPageJump.value, 10) || 1;
                page = Math.max(1, Math.min(max, page));
                setOrdersQueryParam('page', page === 1 ? '' : page);
            }

            ordersPageGo.addEventListener('click', goToOrdersPage);
            ordersPageJump.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    goToOrdersPage();
                }
            });
        }
    })();
</script>
@endpush

@endsection
