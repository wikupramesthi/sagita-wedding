@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="Riwayat Pemesanan" page="Pesanan Saya" active="Daftar Pesanan"
    route="{{ route('orders.index') }}" />
@endsection

@php
$statusLabels = [
'pending' => 'Menunggu',
'paid' => 'Lunas',
'failed' => 'Gagal',
'expired' => 'Kedaluwarsa',
'cancelled' => 'Dibatalkan',
];
$viewMode = request()->input('view') === 'kanban' ? 'kanban' : 'table';
$viewQuery = request()->except('view', 'page');
$tableUrl = route('orders.index', array_merge($viewQuery, ['view' => 'table']));
$kanbanUrl = route('orders.index', array_merge($viewQuery, ['view' => 'kanban']));
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
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if (session('undo_token'))
    <div class="alert alert-info alert-dismissible mb-3 fade show order-undo-bar d-flex flex-wrap align-items-center justify-content-between gap-2" role="alert">
        <span class="alert-text">{{ session('undo_message') }}</span>
        <div class="d-flex align-items-center gap-2">
            <form method="POST" action="{{ route('orders.bulk-undo') }}">
                @csrf
                <input type="hidden" name="undo_token" value="{{ session('undo_token') }}">
                <button type="submit" class="btn btn-sm btn-light"><i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan</button>
            </form>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    </div>
    @endif

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
                    <div class="order-revenue-label">Pendapatan (Lunas)</div>
                    <div class="order-revenue-value">Rp {{ number_format($stats->revenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-4 order-revenue-meta">
                <div>
                    <div class="order-revenue-meta-label">Pesanan lunas</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->paid ?? 0) }}</div>
                </div>
                <div>
                    <div class="order-revenue-meta-label">Total pesanan</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->total ?? 0) }}</div>
                </div>
                <div>
                    <div class="order-revenue-meta-label">Menunggu verifikasi</div>
                    <div class="order-revenue-meta-value">{{ number_format($stats->pending ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if (($queueCounts['all'] ?? 0) > 0)
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && empty($filters['proof'])) ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="bi bi-hourglass-split me-1"></i>Menunggu
            <span class="badge bg-dark ms-1">{{ number_format($queueCounts['all']) }}</span>
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending', 'proof' => 'uploaded']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && ($filters['proof'] ?? '') === 'uploaded') ? 'btn-info' : 'btn-outline-info' }}">
            <i class="bi bi-file-earmark-check me-1"></i>Bukti masuk
            <span class="badge bg-dark ms-1">{{ number_format($queueCounts['uploaded']) }}</span>
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending', 'proof' => 'missing']) }}"
            class="btn btn-sm {{ (($filters['status'] ?? '') === 'pending' && ($filters['proof'] ?? '') === 'missing') ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="bi bi-file-earmark-x me-1"></i>Belum upload
            <span class="badge bg-dark ms-1">{{ number_format($queueCounts['missing']) }}</span>
        </a>
    </div>
    @endif

    <div class="card order-toolbar-card mb-4">
        <div class="order-toolbar-head d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="order-toolbar-icon"><i class="bi bi-sliders2"></i></span>
                <div>
                    <h6 class="order-toolbar-title mb-0">Pencarian & Filter</h6>
                    <div class="order-toolbar-subtitle">Sesuaikan daftar pesanan sesuai antrean, status, dan periode.</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm order-view-switch" role="group" aria-label="Tampilan daftar pesanan">
                    <a href="{{ $tableUrl }}" class="btn btn-outline-secondary {{ $viewMode === 'table' ? 'active' : '' }}">
                        <i class="bi bi-table me-1"></i>Tabel
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
                        @foreach ([10, 25, 50] as $perPageOption)
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
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('orders.saved-filters.apply', $savedFilter) }}"
                            class="btn btn-outline-primary text-nowrap">
                            <i class="bi bi-funnel me-1"></i>{{ $savedFilter->name }}
                            @if ($savedFilter->is_default)<i class="bi bi-star-fill ms-1 text-warning"></i>@endif
                        </a>
                        <form method="POST" action="{{ route('orders.saved-filters.default', $savedFilter) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" title="Jadikan bawaan"><i class="bi bi-star"></i></button>
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
                <a href="{{ route('orders.report', request()->query()) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="bi bi-file-text me-1"></i>Laporan
                </a>
                <a href="{{ route('orders.exportCsv', request()->query()) }}" class="btn btn-sm btn-outline-success text-nowrap">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
                </a>
                <a href="{{ route('orders.exportPdf', request()->query()) }}" class="btn btn-sm btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </a>
                <a href="{{ route('orders.audit', request()->query()) }}" class="btn btn-sm btn-outline-dark text-nowrap">
                    <i class="bi bi-clock-history me-1"></i>Audit
                </a>
            </div>
            @endif
        </div>

        <div class="card-body orders-card-body" id="ordersCardBody">
            <div class="orders-table-tools">
                <div class="orders-table-tools-info">
                    <span class="orders-table-tools-icon"><i class="bi bi-list-ul"></i></span>
                    <div>
                        <div class="orders-table-tools-title">Tabel pesanan</div>
                        <div class="orders-table-tools-subtitle">
                            {{ $isAdmin ? 'Semua pesanan keanggotaan dan status pembayaran.' : 'Pesanan keanggotaan Anda dan status pembayaran.' }}
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
                        <button type="button" class="btn btn-sm btn-success" id="bulkApprove">
                            <i class="bi bi-check-lg me-1"></i>Setujui
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="bulkReject">
                            <i class="bi bi-x-lg me-1"></i>Tolak
                        </button>
                    </div>
                </div>
            </form>
            <form id="singleRejectForm" method="POST" action="">
                @csrf
                <input type="hidden" name="admin_note" id="singleRejectNote" value="">
            </form>
            @endif

            @if ($viewMode === 'table')
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
                            <td>
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
                                    <span class="d-block fw-semibold">{{ $order->package?->name ?? '-' }}</span>
                                @if ($order->packageOption?->name)
                                <small class="log-muted">{{ $order->packageOption->name }}</small>
                                @endif
                                <span class="order-amount d-block">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                            </td>
                            <td data-col="status" data-label="Status">
                                @php
                                $statusTone = match ($order->status) {
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    default => 'muted',
                                };
                                @endphp
                                <span class="order-status-pill tone-{{ $statusTone }}">
                                    <span class="order-status-dot"></span>{{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td data-col="proof" data-label="Bukti">
                                @if ($order->proof_image_path)
                                <a href="{{ route('orders.proof.show', $order->uuid) }}" target="_blank"
                                    class="order-proof-thumb" title="Lihat bukti transfer {{ $order->order_number }}">
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
                                            @endif
                                            @if ($isAdmin && $order->proof_image_path)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('orders.proof.show', $order->uuid) }}" target="_blank">
                                                    <i class="bi bi-file-earmark-image me-2 text-primary"></i>Lihat bukti
                                                </a>
                                            </li>
                                            @endif
                                            @if ($isAdmin && $order->status === 'pending')
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
                        <tr>
                            <td colspan="{{ $isAdmin ? 9 : 7 }}" class="text-center py-5">
                                <div class="order-empty-state">
                                    <span class="order-empty-icon"><i class="bi bi-inbox"></i></span>
                                    <h6 class="mt-3 mb-1">Belum ada pesanan yang cocok</h6>
                                    <p class="text-muted small mb-3">Coba longgarkan filter atau kembali ke tampilan semua pesanan.</p>
                                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Atur Ulang Filter
                                    </a>
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
                            <div class="order-kanban-meta">{{ $order->package?->name ?? '-' }}</div>
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
                            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                        </div>
                        <div class="orders-pagination-subtitle">
                            Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }}
                        </div>
                    </div>
                </div>
                <nav class="orders-pagination-links" aria-label="Navigasi halaman pesanan">
                    {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</section>

@push('after-style')
<style>
    .order-stats .order-stat-card { position: relative; overflow: hidden; border-radius: 16px; transition: transform .18s ease, box-shadow .18s ease; }
    .order-stats .order-stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,.1); }
    .order-stats .order-stat-card::after { content: ''; position: absolute; inset: auto 0 0; height: 3px; background: var(--stat-accent, #5b7cfa); }
    .order-stats .stat-total { --stat-accent: #5b7cfa; }
    .order-stats .stat-pending { --stat-accent: #f59e0b; }
    .order-stats .stat-paid { --stat-accent: #10b981; }
    .order-stats .stat-failed { --stat-accent: #ef4444; }
    .order-revenue-card { border-radius: 16px; border: 1px solid #dfe7ff; background: linear-gradient(110deg, #f7f9ff 0%, #fff 58%, #f5fffb 100%); box-shadow: 0 8px 24px rgba(16,24,40,.05); }
    .order-revenue-icon { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; font-size: 22px; color: #fff; background: linear-gradient(135deg, #5b7cfa, #7c5cfa); box-shadow: 0 8px 16px rgba(91,124,250,.25); }
    .order-revenue-label { font-size: 12px; letter-spacing: .04em; text-transform: uppercase; color: #667085; font-weight: 600; }
    .order-revenue-value { font-size: 24px; font-weight: 700; color: #101828; letter-spacing: -.02em; }
    .order-revenue-meta-label { font-size: 11px; color: #98a2b3; }
    .order-revenue-meta-value { font-size: 15px; font-weight: 650; color: #344054; }
    .order-undo-bar { border-radius: 14px; border: 1px solid #b9d7ff; }
    .order-toolbar-card { border-radius: 16px; border: 1px solid #eef0f4; box-shadow: 0 8px 24px rgba(16,24,40,.05); overflow: hidden; }
    .order-toolbar-head { padding: 16px 20px; border-bottom: 1px solid #eef0f4; background: linear-gradient(90deg, #fbfcff, #fff); }
    .order-toolbar-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; color: #5b7cfa; background: #eef2ff; font-size: 18px; }
    .order-toolbar-title { font-size: 15px; font-weight: 700; color: #101828; }
    .order-toolbar-subtitle { font-size: 12px; color: #98a2b3; }
    .order-toolbar-badge { font-size: 12px; font-weight: 600; color: #3538cd; background: #eef2ff; border-radius: 999px; padding: 6px 12px; }
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
    .order-saved-count { font-size: 11px; font-weight: 700; color: #5b7cfa; background: #fff; border: 1px solid #dfe7ff; border-radius: 999px; padding: 2px 8px; }
    .order-saved-hint { font-size: 12px; font-weight: 400; color: #98a2b3; }
    .order-saved-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .order-saved-filters .btn-group form { margin: 0; }
    .order-saved-filters .btn-group .btn { border-color: #d0d5dd; border-radius: 0; font-size: 12px; }
    .order-saved-filters .btn-group > .btn:first-child { border-radius: 8px 0 0 8px; }
    .order-saved-filters .btn-group > .btn:last-child { border-radius: 0 8px 8px 0; }
    .order-saved-empty { font-size: 12px; color: #98a2b3; margin-bottom: 14px; }
    .order-save-row { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; padding-top: 12px; border-top: 1px solid #e4e7ec; }
    .order-save-row > .security-filter-label { width: 100%; margin: 0; font-size: 12px; font-weight: 600; color: #667085; }
    .order-save-row .form-control { width: 240px; border-color: #e4e7ec; border-radius: 10px; }
    .order-save-check { margin-bottom: 7px; }
    .order-save-check .form-check-label { font-size: 12px; color: #667085; }

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
        background: #5b7cfa;
        border-color: #5b7cfa;
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
        background: #eef2ff;
        color: #3538cd;
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
        color: #5b7cfa;
        background: #eef2ff;
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
        border: 1px solid #dfe7ff;
        border-radius: 12px;
        background: linear-gradient(100deg, #f7f9ff, #fff);
    }

    .orders-bulk-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #3538cd;
        background: #eef2ff;
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
        border-color: #5b7cfa;
        background: #5b7cfa;
        color: #fff;
    }

    .orders-pagination .page-item.disabled .page-link {
        color: #c4cbd5;
        background: #f8f9fb;
    }

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
        .orders-pagination-links { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .orders-skeleton-row { animation: none; }
        .order-stats .order-stat-card { transition: none; }
    }
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

@push('after-script')
<script>
    (function() {
        var checkAll = document.getElementById('checkAll');
        var rows = Array.prototype.slice.call(document.querySelectorAll('.row-check'));
        var count = document.getElementById('bulkCount');
        var bulkForm = document.getElementById('bulkForm');
        var bulkAction = document.getElementById('bulkAction');
        var bulkNote = document.getElementById('bulkNote');
        var singleRejectForm = document.getElementById('singleRejectForm');
        var singleRejectNote = document.getElementById('singleRejectNote');
        var rejectModalEl = document.getElementById('rejectModal');
        var rejectModal = rejectModalEl && window.bootstrap ? new bootstrap.Modal(rejectModalEl) : null;
        var rejectReason = document.getElementById('rejectReason');
        var rejectTarget = document.getElementById('rejectTarget');
        var rejectConfirm = document.getElementById('rejectConfirm');
        var pendingSingleAction = null;
        var saveFilterButton = document.getElementById('saveFilterButton');
        var saveFilterForm = document.getElementById('saveFilterForm');
        var saveFilterName = document.getElementById('saveFilterName');
        var saveFilterNameInput = document.getElementById('saveFilterNameInput');
        var saveFilterPayload = document.getElementById('saveFilterPayload');

        if (saveFilterButton && saveFilterForm && saveFilterName && saveFilterPayload) {
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
            if (count) {
                count.textContent = selected ?
                    selected + ' pesanan dipilih untuk tindakan massal.' :
                    'Pilih pesanan menunggu untuk tindakan massal.';
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

        function openRejectModal(targetText, action) {
            if (!rejectModal || !rejectReason) {
                var reason = prompt(targetText + ' Alasan penolakan wajib diisi.');
                if (!reason || !reason.trim()) {
                    alert('Alasan penolakan wajib diisi.');
                    return;
                }
                if (action) {
                    singleRejectForm.action = action;
                    singleRejectNote.value = reason.trim();
                    singleRejectForm.submit();
                } else {
                    bulkNote.disabled = false;
                    bulkNote.value = reason.trim();
                    bulkAction.value = 'reject';
                    bulkForm.submit();
                }
                return;
            }
            pendingSingleAction = action || null;
            rejectTarget.textContent = targetText;
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

        document.querySelectorAll('.js-reject-single').forEach(function(button) {
            button.addEventListener('click', function() {
                var dropdown = button.closest('.dropdown');
                var toggle = dropdown ? dropdown.querySelector('[data-bs-toggle="dropdown"]') : null;

                if (toggle && window.bootstrap) {
                    bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
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
                    singleRejectForm.action = pendingSingleAction;
                    singleRejectNote.value = reason;
                    singleRejectForm.submit();
                    return;
                }
                bulkNote.disabled = false;
                bulkNote.value = reason;
                bulkAction.value = 'reject';
                bulkForm.submit();
            });
        }

        if (rejectModalEl) {
            rejectModalEl.addEventListener('hidden.bs.modal', function() {
                pendingSingleAction = null;
                rejectReason.value = '';
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

        var columnToggles = Array.prototype.slice.call(document.querySelectorAll('.order-col-toggle'));

        function applyColumns(hiddenColumns) {
            document.querySelectorAll('[data-col]:not(.order-col-toggle)').forEach(function(cell) {
                cell.style.display = hiddenColumns.indexOf(cell.dataset.col) >= 0 ? 'none' : '';
            });
            columnToggles.forEach(function(toggle) {
                toggle.checked = hiddenColumns.indexOf(toggle.dataset.col) < 0;
            });
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
            document.execCommand('copy');
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
        var quickViewModal = quickViewModalEl && window.bootstrap ? new bootstrap.Modal(quickViewModalEl) : null;
        var quickViewTitle = document.getElementById('quickViewTitle');
        var quickViewBody = document.getElementById('quickViewBody');

        document.querySelectorAll('.js-quick-view').forEach(function(button) {
            button.addEventListener('click', function() {
                if (!quickViewModal || !quickViewBody) return;
                var dropdown = button.closest('.dropdown');
                var toggle = dropdown ? dropdown.querySelector('[data-bs-toggle="dropdown"]') : null;
                if (toggle && window.bootstrap) {
                    bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
                }
                quickViewTitle.textContent = 'Pratinjau ' + (button.dataset.number || 'pesanan');
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
    })();
</script>
@endpush

@endsection