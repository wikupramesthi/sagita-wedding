@extends('layouts.app')

@section('title', 'Paket')

@section('breadcrumb')
<x-breadcrumb
    title="Paket"
    page="Paket"
    active="Semua Paket"
    route="{{ route('packages.index') }}" />
@endsection

@php
$statusLabels = ['active' => 'Aktif', 'inactive' => 'Nonaktif'];
$publishLabels = ['published' => 'Dipublikasikan', 'draft' => 'Draft'];
$popularLabels = ['1' => 'Ya', '0' => 'Tidak'];
$quotaLabels = ['limited' => 'Terbatas', 'unlimited' => 'Tanpa batas'];
$durationLabels = ['day' => 'hari', 'week' => 'minggu', 'month' => 'bulan', 'year' => 'tahun'];

$viewMode = in_array(request()->input('view'), ['table', 'cards'], true) ? request()->input('view') : 'table';
$viewQuery = request()->except('view', 'page');
$tableUrl = route('packages.index', array_merge($viewQuery, ['view' => 'table']));
$cardsUrl = route('packages.index', array_merge($viewQuery, ['view' => 'cards']));
$hasViewParam = request()->has('view');

$sort = in_array($sort, ['name', 'price', 'guest_capacity', 'is_active', 'is_popular', 'is_published', 'created_at'], true)
    ? $sort
    : 'created_at';
$direction = in_array($direction, ['asc', 'desc'], true) ? $direction : ($sort === 'name' || $sort === 'price' ? 'asc' : 'desc');
$sortQuery = request()->except('sort', 'direction', 'page');
$sortParams = function (string $key) use ($sort, $direction, $sortQuery, $viewMode): array {
    $nextDirection = $sort === $key
        ? ($direction === 'asc' ? 'desc' : 'asc')
        : (in_array($key, ['name', 'price'], true) ? 'asc' : 'desc');

    return array_merge($sortQuery, [
        'sort' => $key,
        'direction' => $nextDirection,
        'view' => $viewMode,
    ]);
};
$sortIcon = fn (string $key): string => $sort === $key
    ? ($direction === 'asc' ? 'bi-arrow-up-short' : 'bi-arrow-down-short')
    : 'bi-arrow-down-up';

$canBulkUpdate = (bool) auth()->user()?->can('package.update');
$canBulkDelete = (bool) auth()->user()?->can('package.destroy');
$canBulk = $canBulkUpdate || $canBulkDelete;

$detailMeta = [
    'editUrl' => route('packages.edit', '__UUID__'),
    'quotationUrl' => route('packages.quotation', ['package' => '__UUID__']),
    'downloadUrl' => route('packages.quotation.download', ['package' => '__UUID__']),
    'toggleUrl' => route('packages.toggle-status', '__UUID__'),
];

$statCards = [
    ['key' => 'total', 'label' => 'Total Paket', 'icon' => 'bx bx-package', 'tone' => 'primary', 'value' => $stats['total']],
    ['key' => 'active', 'label' => 'Aktif', 'icon' => 'bx bx-check-circle', 'tone' => 'success', 'value' => $stats['active']],
    ['key' => 'inactive', 'label' => 'Nonaktif', 'icon' => 'bx bx-pause-circle', 'tone' => 'secondary', 'value' => $stats['inactive']],
    ['key' => 'published', 'label' => 'Dipublikasikan', 'icon' => 'bx bx-send', 'tone' => 'info', 'value' => $stats['published']],
    ['key' => 'popular', 'label' => 'Paling Dipilih', 'icon' => 'bx bxs-star', 'tone' => 'violet', 'value' => $stats['popular']],
];

$chipMap = [
    'search' => ['label' => 'Cari', 'value' => $search],
    'is_active' => ['label' => 'Status', 'value' => $statusLabels[$is_active] ?? ''],
    'publish' => ['label' => 'Publikasi', 'value' => $publishLabels[$publish] ?? ''],
    'is_popular' => ['label' => 'Paling Dipilih', 'value' => $popularLabels[$is_popular] ?? ''],
    'quota_type' => ['label' => 'Kuota', 'value' => $quotaLabels[$quota_type] ?? ''],
];
$activeChips = array_filter($chipMap, fn ($chip) => (string) $chip['value'] !== '');
@endphp

@section('content')

<section class="section package-page">

    <div class="row mb-4 g-3 ml-stats package-stats">
        @foreach ($statCards as $stat)
        <div class="col-6 col-md-4 col-xl">
            <div class="card package-stat-card stat-{{ $stat['tone'] }} h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-{{ $stat['tone'] }}"><i class="bx {{ $stat['icon'] }}"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stat['value']) }}</div>
                            <div class="ml-stat-label">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card package-toolbar-card mb-4">
        <div class="package-toolbar-head d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="package-toolbar-icon"><i class="bi bi-sliders2"></i></span>
                <div>
                    <h6 class="package-toolbar-title mb-0">Pencarian &amp; Filter</h6>
                    <div class="package-toolbar-subtitle">
                        Sesuaikan daftar paket berdasarkan status, publikasi, dan kuota.
                        <span class="package-shortcut-hint">Tekan <kbd>/</kbd> untuk cari</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-group btn-group-sm package-view-switch" role="group" aria-label="Tampilan daftar paket">
                    <a href="{{ $tableUrl }}" id="btn-package-table" class="btn btn-outline-secondary {{ $viewMode === 'table' ? 'active' : '' }}">
                        <i class="bi bi-table me-1"></i>Tabel
                    </a>
                    <a href="{{ $cardsUrl }}" id="btn-package-cards" class="btn btn-outline-secondary {{ $viewMode === 'cards' ? 'active' : '' }}">
                        <i class="bi bi-grid me-1"></i>Kartu
                    </a>
                </div>
                <span class="package-toolbar-badge"><i class="bi bi-box me-1"></i>{{ number_format($packages->total()) }} hasil</span>
            </div>
        </div>

        <div class="package-toolbar-body">
            <form action="{{ route('packages.index') }}" method="GET" class="package-filter-grid">
                <input type="hidden" name="view" id="package-view-input" value="{{ $viewMode }}">

                <div class="package-filter-field field-search">
                    <label class="package-filter-label" for="package-search">Cari paket</label>
                    <input id="package-search" type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Nama paket..." minlength="2" autocomplete="off">
                    <div class="small text-warning mt-1 d-none" id="package-search-hint">
                        Ketik minimal 2 karakter untuk mencari paket.
                    </div>
                </div>

                <div class="package-filter-field">
                    <label class="package-filter-label" for="package-status">Status</label>
                    <select id="package-status" name="is_active" class="form-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected($is_active === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="package-filter-field">
                    <label class="package-filter-label" for="package-publish">Publikasi</label>
                    <select id="package-publish" name="publish" class="form-select">
                        <option value="">Semua publikasi</option>
                        @foreach ($publishLabels as $value => $label)
                        <option value="{{ $value }}" @selected($publish === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="package-filter-field">
                    <label class="package-filter-label" for="package-popular">Paling Dipilih</label>
                    <select id="package-popular" name="is_popular" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($popularLabels as $value => $label)
                        <option value="{{ $value }}" @selected($is_popular === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="package-filter-field">
                    <label class="package-filter-label" for="package-quota">Kuota</label>
                    <select id="package-quota" name="quota_type" class="form-select">
                        <option value="">Semua kuota</option>
                        @foreach ($quotaLabels as $value => $label)
                        <option value="{{ $value }}" @selected($quota_type === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="package-filter-actions">
                    <div class="package-sort-control">
                        <label class="package-filter-label mb-0" for="package-sort">Urutkan</label>
                        <select id="package-sort" name="sort" class="form-select form-select-sm">
                            <option value="created_at" @selected($sort === 'created_at')>Terbaru</option>
                            <option value="name" @selected($sort === 'name')>Nama paket</option>
                            <option value="price" @selected($sort === 'price')>Harga mulai</option>
                            <option value="guest_capacity" @selected($sort === 'guest_capacity')>Kapasitas tamu</option>
                            <option value="is_active" @selected($sort === 'is_active')>Status</option>
                            <option value="is_popular" @selected($sort === 'is_popular')>Paling dipilih</option>
                            <option value="is_published" @selected($sort === 'is_published')>Publikasi</option>
                        </select>
                    </div>

                    <div class="package-sort-control package-sort-direction">
                        <label class="package-filter-label mb-0" for="package-direction">Arah</label>
                        <select id="package-direction" name="direction" class="form-select form-select-sm">
                            <option value="asc" @selected($direction === 'asc')>Menaik</option>
                            <option value="desc" @selected($direction === 'desc')>Menurun</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel me-1"></i>Terapkan filter</button>
                    <a href="{{ route('packages.index', ['view' => $viewMode]) }}" class="btn btn-sm btn-light">Atur ulang</a>

                    <div class="package-per-page">
                        <label class="package-filter-label mb-0" for="package-per-page">Per halaman</label>
                        <select id="package-per-page" name="per_page" class="form-select form-select-sm">
                            @foreach ([10, 25, 50, 100] as $option)
                            <option value="{{ $option }}" @selected((int) $perPage === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    @can('package.update')
                    <a href="{{ route('packages.export', request()->except('page')) }}" class="btn btn-sm btn-outline-secondary ms-lg-auto">
                        <i class="bi bi-download me-1"></i>Ekspor CSV
                    </a>
                    @endcan
                    @can('package.store')
                    <a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Paket
                    </a>
                    @endcan
                </div>
            </form>

            @if ($searchTooShort)
            <div class="alert alert-info py-2 px-3 mb-0 mt-3 small" role="status">
                Masukkan minimal 2 karakter untuk mencari paket.
            </div>
            @endif

            @if ($activeChips)
            <div class="package-active-filters">
                <span class="package-active-label">Filter aktif</span>
                @foreach ($activeChips as $key => $chip)
                <a href="{{ route('packages.index', array_merge(request()->except($key, 'page'), ['view' => $viewMode])) }}"
                    class="package-filter-chip">
                    {{ $chip['label'] }}: <b>{{ $chip['value'] }}</b><i class="bi bi-x ms-1"></i>
                </a>
                @endforeach
                <a href="{{ route('packages.index', ['view' => $viewMode]) }}" class="package-chip-clear">Hapus semua</a>
            </div>
            @endif
        </div>
    </div>

    @if ($canBulk)
    <form id="package-bulk-form" method="POST" action="{{ route('packages.bulk') }}" class="package-bulk-bar d-none">
        @csrf
        <input type="hidden" name="action" id="package-bulk-action" value="">
        <div id="package-bulk-ids"></div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="package-bulk-icon"><i class="bi bi-check2-square"></i></span>
            <div class="me-auto">
                <div class="package-bulk-title"><span id="package-bulk-count">0</span> paket dipilih</div>
                <div class="package-bulk-subtitle">Terapkan tindakan ke semua paket yang dipilih.</div>
            </div>
            @if ($canBulkUpdate)
            <button type="button" class="btn btn-sm btn-success" onclick="runPackageBulk('activate')">
                <i class="bi bi-check-circle me-1"></i>Aktifkan
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="runPackageBulk('deactivate')">
                <i class="bi bi-pause-circle me-1"></i>Nonaktifkan
            </button>
            @endif
            @if ($canBulkDelete)
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="runPackageBulk('delete')">
                <i class="bi bi-trash me-1"></i>Hapus
            </button>
            @endif
            <button type="button" class="btn btn-sm btn-light" id="package-bulk-clear">Batalkan</button>
        </div>
    </form>
    @endif

    @if ($lowQuotaOptions->isNotEmpty())
    <div class="package-alert package-alert-warning" role="status">
        <span class="package-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
        <div class="package-alert-copy">
            <div class="package-alert-title">Kuota menipis</div>
            <div class="package-alert-text">
                @foreach ($lowQuotaOptions->take(3) as $lowQuota)
                <span><b>{{ $lowQuota['package'] }}</b> Â· {{ $lowQuota['option'] }}: {{ $lowQuota['quota'] }} tersisa</span>
                @endforeach
                @if ($lowQuotaOptions->count() > 3)
                <span>dan {{ $lowQuotaOptions->count() - 3 }} opsi lainnya.</span>
                @endif
            </div>
        </div>
        <span class="badge bg-warning-subtle text-warning-emphasis">{{ $lowQuotaOptions->count() }} opsi</span>
    </div>
    @endif

    <div class="card" id="package-table-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h5 class="card-title mb-0">Daftar Paket</h5>
                <div class="text-muted small">Kelola paket, opsi harga, fitur, dan layanan tambahan.</div>
            </div>
            <span class="badge bg-primary-subtle text-primary">{{ number_format($packages->total()) }} hasil</span>
        </div>

        <div class="card-body p-0" id="package-table-view">
            <div class="table-responsive">
                <table class="table security-table package-table mb-0">
                    <colgroup>
                        @if ($canBulk)<col class="pkg-col-check">@endif
                        <col class="pkg-col-name">
                        <col class="pkg-col-options">
                        <col class="pkg-col-features">
                        <col class="pkg-col-popular">
                        <col class="pkg-col-status">
                        <col class="pkg-col-published">
                        <col class="pkg-col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            @if ($canBulk)
                            <th class="pkg-col-check"><input class="form-check-input package-select-all" type="checkbox" id="package-select-all" aria-label="Pilih semua paket"></th>
                            @endif
                            <th class="pkg-col-name"><a class="package-sort-link {{ $sort === 'name' ? 'active' : '' }}" href="{{ route('packages.index', $sortParams('name')) }}">Paket <i class="bi {{ $sortIcon('name') }}"></i></a></th>
                            <th class="pkg-col-options"><a class="package-sort-link {{ $sort === 'price' ? 'active' : '' }}" href="{{ route('packages.index', $sortParams('price')) }}">Opsi <i class="bi {{ $sortIcon('price') }}"></i></a></th>
                            <th class="pkg-col-features hide-md">Fitur</th>
                            <th class="pkg-col-popular hide-md text-center"><a class="package-sort-link {{ $sort === 'is_popular' ? 'active' : '' }}" href="{{ route('packages.index', $sortParams('is_popular')) }}">Paling Dipilih <i class="bi {{ $sortIcon('is_popular') }}"></i></a></th>
                            <th class="pkg-col-status"><a class="package-sort-link {{ $sort === 'is_active' ? 'active' : '' }}" href="{{ route('packages.index', $sortParams('is_active')) }}">Status <i class="bi {{ $sortIcon('is_active') }}"></i></a></th>
                            <th class="pkg-col-published hide-lg"><a class="package-sort-link {{ $sort === 'is_published' ? 'active' : '' }}" href="{{ route('packages.index', $sortParams('is_published')) }}">Publikasi <i class="bi {{ $sortIcon('is_published') }}"></i></a></th>
                            <th class="pkg-col-action text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($packages as $package)
                        <tr>
                            @if ($canBulk)
                            <td class="pkg-col-check"><input class="form-check-input package-checkbox" type="checkbox" value="{{ $package->uuid }}" aria-label="Pilih {{ $package->name }}"></td>
                            @endif
                            <td class="pkg-col-name">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($package->cover_url)
                                    <img src="{{ $package->cover_url }}" alt="" class="pkg-cover">
                                    @else
                                    <span class="pkg-cover-fallback"><i class="bi bi-image"></i></span>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate pkg-name">{{ $package->name }}</div>
                                        <small class="log-muted">
                                            @if ($package->guest_capacity){{ number_format($package->guest_capacity, 0, ',', '.') }} tamu @endif
                                            @if ($package->guest_capacity && $package->event_duration_hours)·@endif
                                            @if ($package->event_duration_hours){{ $package->event_duration_hours }} jam @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="pkg-col-options">
                                @forelse ($package->options->take(2) as $option)
                                <div class="pkg-option {{ !$loop->last ? 'mb-1 pb-1 border-bottom' : '' }}">
                                    <span class="fw-semibold small">{{ $option->name }}</span>
                                    <div class="log-muted">
                                        @if ($option->discount_price !== null && $option->discount_price < $option->price)
                                        <span class="text-success fw-semibold">Rp {{ number_format($option->discount_price, 0, ',', '.') }}</span>
                                        @else
                                        Rp {{ number_format($option->price, 0, ',', '.') }}
                                        @endif
                                        · {{ $option->quota }}
                                    </div>
                                </div>
                                @empty
                                <span class="log-muted">Belum ada opsi</span>
                                @endforelse
                                @if ($package->options->count() > 2)
                                <small class="text-primary">+{{ $package->options->count() - 2 }} opsi</small>
                                @endif
                            </td>
                            <td class="pkg-col-features hide-md">
                                @if ($package->features->isNotEmpty())
                                <button type="button" class="btn btn-sm btn-light border package-feature-btn"
                                    data-package="{{ e($package->name) }}"
                                    data-features='@json($package->features->pluck("feature")->values())'>
                                    {{ $package->features->count() }} fitur
                                </button>
                                @else
                                <span class="log-muted">-</span>
                                @endif
                            </td>
                            <td class="pkg-col-popular hide-md text-center">
                                @if ($package->is_popular)
                                <span class="badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-star-fill me-1"></i>Ya</span>
                                @else
                                <span class="log-muted">—</span>
                                @endif
                            </td>
                            <td class="pkg-col-status">
                                @can('package.update')
                                <button type="button" class="package-status-toggle badge border-0 {{ $package->is_active === 'active' ? 'bg-success' : 'bg-secondary' }}"
                                    data-toggle-status data-uuid="{{ $package->uuid }}" data-field="is_active" title="Ubah status">
                                    {{ $package->is_active === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </button>
                                @else
                                <span class="badge {{ $package->is_active === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $package->is_active === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                @endcan
                            </td>
                            <td class="pkg-col-published hide-lg">
                                @can('package.update')
                                <button type="button" class="package-status-toggle badge border-0 {{ $package->is_published ? 'bg-info-subtle text-info-emphasis' : 'bg-light text-dark' }}"
                                    data-toggle-status data-uuid="{{ $package->uuid }}" data-field="is_published" title="Ubah publikasi">
                                    {{ $package->is_published ? 'Publik' : 'Draft' }}
                                </button>
                                @else
                                <span class="badge {{ $package->is_published ? 'bg-info-subtle text-info-emphasis' : 'bg-light text-dark' }}">{{ $package->is_published ? 'Publik' : 'Draft' }}</span>
                                @endcan
                            </td>
                            <td class="pkg-col-action text-end">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" data-bs-container="body" data-bs-boundary="viewport" aria-expanded="false" title="Aksi">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><button type="button" class="dropdown-item" onclick="openPackageDetail('{{ $package->uuid }}')"><i class="bi bi-eye me-2"></i>Detail cepat</button></li>
                                        <li><button type="button" class="dropdown-item" onclick="previewPackageQuotation('{{ $package->uuid }}')"><i class="bi bi-file-earmark-text me-2"></i>Pratinjau Penawaran</button></li>
                                        <li><a href="{{ route('packages.preview', $package->uuid) }}" target="_blank" rel="noopener" class="dropdown-item"><i class="bi bi-globe2 me-2"></i>Pratinjau Publik</a></li>
                                        @can('package.store')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><form method="POST" action="{{ route('packages.duplicate', $package->uuid) }}" id="duplicateForm_{{ $package->uuid }}">@csrf<button type="submit" class="dropdown-item"><i class="bi bi-files me-2"></i>Duplikat</button></form></li>
                                        @endcan
                                        @can('package.update')
                                        <li><a class="dropdown-item" href="{{ route('packages.edit', $package->uuid) }}"><i class="bi bi-pencil me-2"></i>Ubah</a></li>
                                        @endcan
                                        @can('package.destroy')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button type="button" class="dropdown-item text-danger" onclick="confirmDelete('deleteForm_{{ $package->uuid }}')"><i class="bi bi-trash me-2"></i>Hapus</button></li>
                                        <form id="deleteForm_{{ $package->uuid }}" action="{{ route('packages.destroy', $package->uuid) }}" method="POST" class="d-none">@method('DELETE')@csrf</form>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $canBulk ? 8 : 7 }}" class="text-center py-5">
                                <div class="order-empty-state">
                                    <span class="order-empty-icon"><i class="bi {{ $activeChips ? 'bi-funnel' : 'bi-box-seam' }}"></i></span>
                                    <h6 class="mt-3 mb-1">{{ $activeChips ? 'Tidak ada hasil untuk filter ini' : 'Belum ada paket' }}</h6>
                                    <p class="text-muted small mb-3">{{ $activeChips ? 'Coba ubah kata kunci atau hapus sebagian filter.' : 'Tambahkan paket pertama untuk mulai mengelola layanan wedding organizer.' }}</p>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        @if ($activeChips)<a href="{{ route('packages.index', ['view' => $viewMode]) }}" class="btn btn-sm btn-light">Hapus filter</a>@endif
                                        @can('package.store')<a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Paket</a>@endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="card package-cards-card d-none" id="package-card-view">
        <div class="card-header package-list-header py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="package-list-icon"><i class="bi bi-grid"></i></span>
                <div>
                    <h6 class="mb-0">Daftar Paket</h6>
                    <div class="package-list-subtitle">Tampilan kartu untuk meninjau paket lebih cepat.</div>
                </div>
            </div>
            <span class="badge bg-primary-subtle text-primary">{{ number_format($stats['total']) }} paket</span>
        </div>

        <div class="card-body p-3">
            <div class="package-skeleton package-skeleton-cards d-none" aria-hidden="true">
                <div class="package-skeleton-row"></div>
                <div class="package-skeleton-row"></div>
                <div class="package-skeleton-row"></div>
            </div>
            <div class="row g-3 package-cards-grid">
                @forelse ($packages as $package)
                @php
                $startingPrice = $package->options->min(fn ($option) => $option->discount_price ?? $option->price);
                @endphp
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card h-100 package-item-card">
                        <div class="package-card-cover">
                            @if ($package->cover_url)
                            <img src="{{ $package->cover_url }}" alt="Foto {{ $package->name }}" loading="lazy">
                            @else
                            <span class="package-card-cover-empty"><i class="bi bi-image"></i></span>
                            @endif
                            <div class="package-card-badges">
                                @if ($package->is_popular)
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Paling Dipilih</span>
                                @endif
                                @if ($package->is_published)
                                <span class="badge bg-info-subtle text-info-emphasis">Dipublikasikan</span>
                                @else
                                <span class="badge bg-light text-dark">Draft</span>
                                @endif
                            </div>
                            @if ($canBulk)
                            <label class="package-card-check" title="Pilih paket">
                                <input class="form-check-input package-checkbox" type="checkbox" value="{{ $package->uuid }}"
                                    aria-label="Pilih {{ $package->name }}">
                            </label>
                            @endif
                        </div>

                        <div class="package-card-content">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <h6 class="package-card-title mb-0">{{ \Illuminate\Support\Str::limit($package->name, 44) }}</h6>
                                @can('package.update')
                                <button type="button"
                                    class="package-status-toggle badge border-0 {{ $package->is_active === 'active' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}"
                                    data-toggle-status
                                    data-uuid="{{ $package->uuid }}"
                                    data-field="is_active"
                                    title="Klik untuk mengubah status">
                                    <i class="bi bi-arrow-repeat me-1"></i>{{ $package->is_active === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </button>
                                @else
                                <span class="badge {{ $package->is_active === 'active' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $package->is_active === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                @endcan
                            </div>

                            @if ($package->description)
                            <p class="package-card-desc mb-2">{{ \Illuminate\Support\Str::limit($package->description, 90) }}</p>
                            @endif

                            <div class="package-card-meta">
                                @if ($package->guest_capacity)
                                <span><i class="bi bi-people me-1"></i>{{ number_format($package->guest_capacity, 0, ',', '.') }} tamu</span>
                                @endif
                                @if ($package->event_duration_hours)
                                <span><i class="bi bi-clock me-1"></i>{{ $package->event_duration_hours }} jam</span>
                                @endif
                                <span><i class="bi bi-list-ol me-1"></i>{{ $package->options->count() }} opsi</span>
                            </div>

                            <div class="package-card-price">
                                <span>Harga mulai</span>
                                <strong>{{ $startingPrice !== null ? 'Rp '.number_format($startingPrice, 0, ',', '.') : '-' }}</strong>
                            </div>

                            <div class="package-card-section">
                                <span class="package-card-section-label">Fitur</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse ($package->features->take(3) as $feature)
                                    <span class="badge bg-light text-dark">{{ $feature->feature }}</span>
                                    @empty
                                    <span class="text-muted">Belum ada fitur</span>
                                    @endforelse
                                    @if ($package->features->count() > 3)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">+{{ $package->features->count() - 3 }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="package-card-section">
                                <span class="package-card-section-label">Layanan Tambahan</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse ($package->addons->take(2) as $addon)
                                    <span class="badge bg-info-subtle text-info-emphasis">{{ $addon->name }}</span>
                                    @empty
                                    <span class="text-muted">Belum ada layanan tambahan</span>
                                    @endforelse
                                    @if ($package->addons->count() > 2)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">+{{ $package->addons->count() - 2 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="package-card-footer">
                            <button type="button" class="btn btn-sm btn-outline-primary flex-1"
                                onclick="openPackageDetail('{{ $package->uuid }}')">
                                <i class="bi bi-eye me-1"></i>Detail
                            </button>
                            @can('package.update')
                            <a href="{{ route('packages.edit', $package->uuid) }}" class="btn btn-sm btn-light border flex-1">
                                <i class="bi bi-pencil me-1"></i>Ubah
                            </a>
                            @endcan
                            @can('package.destroy')
                            <button type="button" class="btn btn-sm btn-outline-danger flex-1"
                                onclick="confirmDelete('cardDeleteForm_{{ $package->uuid }}')">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                            <form id="cardDeleteForm_{{ $package->uuid }}" action="{{ route('packages.destroy', $package->uuid) }}" method="POST" class="d-none">
                                @method('DELETE')
                                @csrf
                            </form>
                            @endcan
                        </div>
                        <div class="package-card-quick-actions">
                            <button type="button" class="btn btn-sm btn-light" onclick="previewPackageQuotation('{{ $package->uuid }}')">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Penawaran
                            </button>
                            <a href="{{ route('packages.preview', $package->uuid) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light">
                                <i class="bi bi-globe2 me-1"></i>Pratinjau
                            </a>
                            @can('package.store')
                            <button type="submit" form="duplicateForm_{{ $package->uuid }}" class="btn btn-sm btn-light">
                                <i class="bi bi-files me-1"></i>Duplikat
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="package-empty">
                        <div class="package-empty-state">
                            <span class="package-empty-icon"><i class="bi {{ $activeChips ? 'bi-funnel' : 'bi-box-seam' }}"></i></span>
                            <h6 class="mt-3 mb-1">{{ $activeChips ? 'Tidak ada hasil untuk filter ini' : 'Belum ada paket' }}</h6>
                            <p class="text-muted small mb-3">
                                {{ $activeChips ? 'Coba ubah kata kunci atau hapus sebagian filter.' : 'Tambahkan paket pertama untuk mulai mengelola layanan wedding organizer.' }}
                            </p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                @if ($activeChips)
                                <a href="{{ route('packages.index', ['view' => $viewMode]) }}" class="btn btn-sm btn-light">Hapus filter</a>
                                @endif
                                @can('package.store')
                                <a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Paket
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="card package-list-footer">
        <div class="card-body py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="package-pagination-icon"><i class="bi bi-list-check"></i></span>
                <div>
                    <div class="package-pagination-title">
                        Menampilkan {{ $packages->firstItem() ?? 0 }}&ndash;{{ $packages->lastItem() ?? 0 }} dari {{ number_format($packages->total()) }} paket
                    </div>
                    <div class="package-pagination-subtitle">
                        Halaman {{ number_format($packages->currentPage()) }} dari {{ number_format($packages->lastPage()) }}
                    </div>
                </div>
            </div>

            @if ($packages->hasPages())
            <nav class="package-pagination" aria-label="Navigasi halaman paket">
                {{ $packages->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
            @else
            <span class="badge bg-light text-dark">Semua paket pada satu halaman</span>
            @endif
        </div>
    </div>

    <div class="modal fade" id="packageFeatureModal" tabindex="-1" aria-labelledby="packageFeatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-3 px-4">
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="packageFeatureModalLabel">Fitur Paket</h6>
                        <div class="text-muted small" id="packageFeatureModalSubtitle"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <ul class="list-group list-group-flush" id="packageFeatureModalList"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="packageQuotationModal" tabindex="-1" aria-labelledby="packageQuotationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content package-quotation-modal">
                <div class="modal-header py-3 px-4">
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="packageQuotationModalLabel">Pratinjau Penawaran</h6>
                        <div class="package-list-subtitle">Periksa penawaran sebelum mengunduh atau membagikannya.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="package-quotation-frame" title="Pratinjau penawaran paket" class="package-quotation-frame"></iframe>
                </div>
                <div class="modal-footer py-2 px-4">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="package-quotation-download" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                        <i class="bi bi-download me-1"></i>Unduh PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="package-drawer-backdrop d-none" id="package-drawer-backdrop"></div>

    <aside class="package-drawer d-none" id="package-drawer" role="dialog" aria-modal="true" aria-label="Detail paket">
        <div class="package-drawer-head">
            <div>
                <h6 class="package-drawer-title mb-0" id="package-drawer-title">Detail Paket</h6>
                <div class="package-drawer-subtitle" id="package-drawer-subtitle">Ringkasan lengkap paket</div>
            </div>
            <button type="button" class="btn-close" id="package-drawer-close" aria-label="Tutup"></button>
        </div>
        <div class="package-drawer-body" id="package-drawer-body"></div>
        <div class="package-drawer-footer">
            <button type="button" class="btn btn-sm btn-light" id="package-drawer-dismiss">Tutup</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="package-drawer-quotation">
                <i class="bi bi-file-earmark-pdf me-1"></i>Penawaran
            </button>
            <a href="#" class="btn btn-sm btn-outline-secondary" id="package-drawer-preview" target="_blank" rel="noopener">
                <i class="bi bi-globe2 me-1"></i>Pratinjau
            </a>
            @can('package.update')
            <a href="#" class="btn btn-sm btn-primary" id="package-drawer-edit">
                <i class="bi bi-pencil me-1"></i>Ubah Paket
            </a>
            @endcan
        </div>
    </aside>

    <script type="application/json" id="package-detail-data">@json($packages->items())</script>
    <script type="application/json" id="package-detail-meta">@json($detailMeta)</script>
</section>

<style>
    .package-page .package-alert { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding: 12px 16px; border: 1px solid #fedf89; border-radius: 14px; background: #fffaf0; }
    .package-page .package-alert-icon { width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; color: #b54708; background: #fef0c7; font-size: 16px; }
    .package-page .package-alert-copy { min-width: 0; flex: 1; }
    .package-page .package-alert-title { color: #93370d; font-size: 12px; font-weight: 700; }
    .package-page .package-alert-text { display: flex; flex-wrap: wrap; gap: 4px 12px; margin-top: 2px; color: #7a2e0e; font-size: 11px; }
    .package-page .package-skeleton { display: grid; gap: 10px; padding: 18px; }
    .package-page .package-skeleton-table .package-skeleton-row { height: 44px; }
    .package-page .package-skeleton-cards { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
    .package-page .package-skeleton-cards .package-skeleton-row { height: 160px; }
    .package-page .package-skeleton-row { border-radius: 10px; background: linear-gradient(90deg, #f2f4f7 25%, #e9edf3 50%, #f2f4f7 75%); background-size: 200% 100%; animation: package-skeleton 1.2s infinite linear; }
    @keyframes package-skeleton { from { background-position: 200% 0; } to { background-position: -200% 0; } }
    .package-page #package-table-view.is-loading .table-responsive,
    .package-page #package-card-view.is-loading .package-cards-grid { opacity: .35; pointer-events: none; }
    .package-page .package-status-toggle { cursor: pointer; transition: transform .15s ease, filter .15s ease; }
    .package-page .package-status-toggle:hover { transform: translateY(-1px); filter: brightness(.96); }
    .package-page .package-empty-state { padding: 10px 0; }
    .package-page .package-empty-state p { max-width: 360px; margin-inline: auto; }
    .package-page .package-card-quick-actions { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 16px 16px; }
    .package-page .package-card-quick-actions .btn { flex: 1 1 auto; font-size: 11px; }
    .package-page .package-quotation-modal { overflow: hidden; border-radius: 16px; }
    .package-page .package-quotation-frame { width: 100%; height: min(62vh, 700px); border: 0; background: #f2f4f7; }
    @media (prefers-reduced-motion: reduce) {
        .package-page .package-skeleton-row { animation: none; }
    }

    .package-page .package-stat-card { position: relative; overflow: hidden; border-radius: 16px; border-color: #eef0f4; transition: transform .18s ease, box-shadow .18s ease; }
    .package-page .package-stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,.1); }
    .package-page .package-stat-card::after { content: ''; position: absolute; inset: auto 0 0; height: 3px; background: var(--stat-accent, #4b6b52); }
    .package-page .stat-primary { --stat-accent: #4b6b52; }
    .package-page .stat-success { --stat-accent: #10b981; }
    .package-page .stat-secondary { --stat-accent: #98a2b3; }
    .package-page .stat-info { --stat-accent: #0ea5e9; }
    .package-page .stat-violet { --stat-accent: #6b8f6f; }

    .package-page .package-toolbar-card { border-radius: 16px; border: 1px solid #eef0f4; box-shadow: 0 8px 24px rgba(16,24,40,.05); overflow: hidden; }
    .package-page .package-toolbar-head { padding: 16px 20px; border-bottom: 1px solid #eef0f4; background: linear-gradient(90deg, #fbfcff, #fff); }
    .package-page .package-toolbar-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; color: #4b6b52; background: #eef3ee; font-size: 18px; }
    .package-page .package-toolbar-title { font-size: 15px; font-weight: 700; color: #101828; }
    .package-page .package-toolbar-subtitle { font-size: 12px; color: #98a2b3; }
    .package-page .package-shortcut-hint { margin-left: 6px; color: #98a2b3; font-weight: 400; }
    .package-page .package-shortcut-hint kbd { padding: 1px 5px; border: 1px solid #d0d5dd; border-bottom-width: 2px; border-radius: 5px; background: #fff; color: #344054; font-family: inherit; font-size: 10px; font-weight: 600; line-height: 1.2; }
    .package-page .package-toolbar-badge { font-size: 12px; font-weight: 600; color: #4b6b52; background: #eef3ee; border-radius: 999px; padding: 6px 12px; white-space: nowrap; }
    .package-page .package-toolbar-body { padding: 20px; }
    .package-page .package-view-switch .btn.active { color: #fff; background-color: #4b6b52; border-color: #4b6b52; }

    .package-page .package-filter-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 12px 14px; align-items: end; padding: 16px; border: 1px solid #eef0f4; border-radius: 14px; background: #fbfcff; }
    .package-page .package-filter-grid .field-search { grid-column: span 4; }
    .package-page .package-filter-grid .package-filter-field { grid-column: span 2; min-width: 0; }
    .package-page .package-filter-grid .package-filter-actions { grid-column: 1 / -1; display: flex; flex-wrap: wrap; align-items: center; gap: 8px; padding-top: 4px; }
    .package-page .package-filter-label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: #667085; }
    .package-page .package-filter-grid .form-control,
    .package-page .package-filter-grid .form-select { font-size: 13px; height: 38px; border-color: #e4e7ec; border-radius: 10px; }
    .package-page .package-filter-actions .btn { white-space: nowrap; }
    .package-page .package-per-page { display: flex; align-items: center; gap: 8px; }
    .package-page .package-per-page .form-select { width: auto; min-width: 84px; }
    .package-page .package-sort-control { display: flex; align-items: center; gap: 8px; }
    .package-page .package-sort-control .form-select { width: auto; min-width: 140px; }
    .package-page .package-sort-direction .form-select { min-width: 100px; }

    .package-page .package-bulk-bar {
        position: relative;
        z-index: 1;
        margin: 0 0 14px;
        padding: 10px 14px;
        border: 1px solid #d0d5dd;
        border-radius: 12px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 12px 28px rgba(16,24,40,.12);
        backdrop-filter: blur(8px);
    }
    .package-page .package-bulk-icon { width: 32px; height: 32px; border-radius: 10px; display: grid; place-items: center; color: #4b6b52; background: #eef3ee; }
    .package-page .package-bulk-title { font-size: 12px; font-weight: 700; color: #101828; }
    .package-page .package-bulk-subtitle { font-size: 11px; color: #667085; }

    .package-page .package-active-filters { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 14px; }
    .package-page .package-active-label { font-size: 12px; font-weight: 700; color: #475467; }
    .package-page .package-filter-chip { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border: 1px solid #d8e6da; border-radius: 999px; background: #fff; color: #4b6b52; font-size: 11px; font-weight: 500; text-decoration: none; }
    .package-page .package-filter-chip:hover { background: #eef3ee; color: #4b6b52; }
    .package-page .package-chip-clear { font-size: 11px; font-weight: 600; color: #98a2b3; text-decoration: underline; }
    .package-page .package-chip-clear:hover { color: #f04438; }

    .package-page .package-list-header { border-bottom: 1px solid #eef0f4; background: #fff; }
    .package-page .package-list-icon { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; color: #4b6b52; background: #eef3ee; font-size: 16px; }
    .package-page .package-list-header h6 { font-size: 14px; font-weight: 700; color: #101828; }
    .package-page .package-list-subtitle { font-size: 11px; color: #98a2b3; }
    .package-page .package-table-card,
    .package-page #package-table-card,
    .package-page .package-cards-card { border-radius: 14px; border-color: #eef0f4; }
    .package-page .package-cards-card { overflow: hidden; }
    .package-page .package-table { width: 100%; table-layout: fixed; font-size: .875rem; }
    .package-page .package-table th { font-size: .75rem; letter-spacing: .03em; text-transform: uppercase; white-space: nowrap; background: #f8f9fa; }
    .package-page .package-table td { vertical-align: middle; }
    .package-page .pkg-col-check { width: 36px; }
    .package-page .pkg-col-name { width: 24%; }
    .package-page .pkg-col-options { width: 28%; }
    .package-page .pkg-col-features { width: 9%; }
    .package-page .package-feature-btn { font-size: 12px; padding: .2rem .5rem; white-space: nowrap; }
    .package-page .pkg-col-popular { width: 10%; }
    .package-page .pkg-col-status { width: 9%; }
    .package-page .pkg-col-published { width: 9%; }
    .package-page .pkg-col-action { width: 56px; }
    .package-page .pkg-cover { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
    .package-page .pkg-cover-fallback { width: 40px; height: 40px; display: grid; place-items: center; border: 1px dashed #d0d5dd; border-radius: 8px; background: #f9fafb; color: #98a2b3; flex-shrink: 0; font-size: 14px; }
    .package-page .pkg-name { max-width: 180px; }
    .package-page .package-sort-link { color: inherit; text-decoration: none; white-space: nowrap; }
    .package-page .package-sort-link:hover,
    .package-page .package-sort-link.active { color: #4b6b52; }
    .package-page .package-status-toggle { cursor: pointer; }
    .package-page .package-pagination-title { font-size: 12px; font-weight: 700; color: #344054; }
    .package-page .package-pagination-subtitle { font-size: 11px; color: #98a2b3; }
    .package-page .package-pagination .pagination { margin: 0; gap: 4px; flex-wrap: wrap; }
    .package-page .package-pagination .page-item .page-link {
        min-width: 34px;
        padding: .3rem .55rem;
        border: 1px solid #e4e7ec;
        border-radius: 9px;
        color: #475467;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        box-shadow: none;
        transition: all .15s ease;
    }
    .package-page .package-pagination .page-item .page-link:hover { border-color: #4b6b52; color: #4b6b52; background: #f6faf7; }
    .package-page .package-pagination .page-item.active .page-link { border-color: #4b6b52; background: #4b6b52; color: #fff; box-shadow: 0 4px 10px rgba(75,107,82,.28); }
    .package-page .package-pagination .page-item.disabled .page-link { border-color: #eef0f4; background: #fafbfc; color: #b2b7c3; }

    .package-page .package-item-card { border-color: #e4e7ec; border-radius: 14px; overflow: hidden; display: flex; flex-direction: column; transition: transform .18s ease, box-shadow .18s ease; }
    .package-page .package-item-card:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(16,24,40,.09); }
    .package-page .package-card-cover { position: relative; height: 120px; overflow: hidden; background: linear-gradient(120deg, #eef3ee, #f5fffb); }
    .package-page .package-card-cover img { width: 100%; height: 100%; object-fit: cover; }
    .package-page .package-card-cover-empty { width: 100%; height: 100%; display: grid; place-items: center; font-size: 26px; color: #b2b7c3; }
    .package-page .package-card-badges { position: absolute; top: 10px; left: 10px; display: flex; flex-wrap: wrap; gap: 6px; }
    .package-page .package-card-badges .badge { box-shadow: 0 4px 10px rgba(16,24,40,.12); }
    .package-page .package-card-check { position: absolute; top: 10px; right: 10px; z-index: 2; width: 24px; height: 24px; display: grid; place-items: center; border-radius: 7px; background: rgba(255,255,255,.94); box-shadow: 0 4px 10px rgba(16,24,40,.14); cursor: pointer; }
    .package-page .package-card-check .form-check-input { margin: 0; cursor: pointer; }
    .package-page .package-item-card.is-selected { border-color: #4b6b52; box-shadow: 0 0 0 2px rgba(75,107,82,.2); }
    .package-page .package-card-content { padding: 14px; color: #475467; font-size: 13px; }
    .package-page .package-card-title { font-size: 14px; font-weight: 700; color: #101828; }
    .package-page .package-card-desc { font-size: 12px; color: #667085; line-height: 1.5; }
    .package-page .package-card-meta { display: flex; flex-wrap: wrap; gap: 6px 12px; margin-bottom: 12px; color: #667085; font-size: 11px; }
    .package-page .package-card-meta span { display: inline-flex; align-items: center; }
    .package-page .package-card-price { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; padding: 10px 12px; border: 1px solid #eef0f4; border-radius: 10px; background: #f8fafc; font-size: 12px; color: #667085; }
    .package-page .package-card-price strong { font-size: 15px; color: #101828; }
    .package-page .package-card-section { margin-top: 12px; }
    .package-page .package-card-section-label { display: block; margin-bottom: 6px; font-size: 10px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #98a2b3; }
    .package-page .package-card-footer { display: flex; gap: 8px; margin-top: auto; padding: 10px 14px; border-top: 1px solid #eef0f4; background: #fcfcfe; }
    .package-page .flex-1 { flex: 1; }

    .package-drawer-backdrop { position: fixed; inset: 0; z-index: 1040; background: rgba(16,24,40,.42); backdrop-filter: blur(2px); }
    .package-drawer { position: fixed; top: 0; right: 0; bottom: 0; z-index: 1050; width: min(480px, 100vw); display: flex; flex-direction: column; background: #fff; box-shadow: -16px 0 40px rgba(16,24,40,.16); transform: translateX(100%); transition: transform .22s ease; }
    .package-drawer.show { transform: translateX(0); }
    .package-page .package-drawer-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid #eef0f4; }
    .package-page .package-drawer-title { font-size: 15px; font-weight: 700; color: #101828; }
    .package-page .package-drawer-subtitle { font-size: 11px; color: #98a2b3; }
    .package-page .package-drawer-body { flex: 1; overflow-y: auto; padding: 18px 20px; background: #fcfcfe; }
    .package-page .package-drawer-footer { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; padding: 14px 20px; border-top: 1px solid #eef0f4; background: #fff; }
    .package-page .package-drawer-footer .btn { flex: 1 1 auto; }
    .package-page .package-drawer-gallery h6 { margin-bottom: 10px; }
    .package-page .package-drawer-cover { width: 100%; height: 150px; object-fit: cover; border-radius: 12px; border: 1px solid #eef0f4; }
    .package-page .package-drawer-gallery { margin-top: 0; }
    .package-page .package-drawer-gallery-strip { display: flex; gap: 8px; margin-top: 8px; }
    .package-page .package-drawer-thumb { width: 52px; height: 52px; display: grid; place-items: center; overflow: hidden; border: 1px solid #d0d5dd; border-radius: 10px; background: #f9fafb; color: #667085; }
    .package-page .package-drawer-thumb.active { border-color: #4b6b52; box-shadow: 0 0 0 2px #eef3ee; }
    .package-page .package-drawer-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .package-page .package-drawer-cover-empty { height: 90px; display: grid; place-items: center; border: 1px dashed #d0d5dd; border-radius: 12px; color: #98a2b3; background: #f9fafb; font-size: 22px; }
    .package-page .package-drawer-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
    .package-page .package-drawer-description { margin-top: 12px; font-size: 13px; line-height: 1.6; color: #475467; }
    .package-page .package-drawer-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
    .package-page .package-drawer-metric { padding: 10px 12px; border: 1px solid #eef0f4; border-radius: 10px; background: #fff; }
    .package-page .package-drawer-metric-label { font-size: 10px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #98a2b3; }
    .package-page .package-drawer-metric-value { margin-top: 4px; font-size: 14px; font-weight: 700; color: #101828; }
    .package-page .package-drawer-section { margin-top: 20px; }
    .package-page .package-drawer-section h6 { font-size: 12px; font-weight: 700; color: #344054; text-transform: uppercase; letter-spacing: .04em; }
    .package-page .package-drawer-list { list-style: none; margin: 10px 0 0; padding: 0; display: grid; gap: 8px; }
    .package-page .package-drawer-list li { padding: 10px 12px; border: 1px solid #eef0f4; border-radius: 10px; background: #fff; font-size: 12px; color: #475467; }
    .package-page .package-drawer-list strong { display: block; color: #101828; font-size: 13px; }
    .package-page .package-drawer-empty { margin-top: 8px; font-size: 12px; color: #98a2b3; }
    body.package-drawer-open { overflow: hidden; }

    @media (max-width: 1599.98px) {
        .package-page .hide-xl { display: none; }
    }

    @media (max-width: 1399.98px) {
        .package-page .hide-lg { display: none; }
    }

    @media (max-width: 1199.98px) {
        .package-page .package-filter-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .package-page .package-filter-grid .field-search { grid-column: span 6; }
        .package-page .package-filter-grid .package-filter-field { grid-column: span 3; }
        .package-page .hide-md { display: none; }
    }

    @media (max-width: 991.98px) {
        .package-page .hide-xs { display: none; }
    }

    @media (max-width: 575.98px) {
        .package-page .package-toolbar-head,
        .package-page .package-toolbar-body { padding: 14px; }
        .package-page .package-filter-grid { grid-template-columns: 1fr; padding: 12px; }
        .package-page .package-filter-grid .field-search,
        .package-page .package-filter-grid .package-filter-field,
        .package-page .package-filter-grid .package-filter-actions { grid-column: 1 / -1; }
        .package-page .package-per-page { width: 100%; justify-content: space-between; }
        .package-page .package-sort-control { width: 100%; justify-content: space-between; }
        .package-page .package-sort-control .form-select { width: 65%; }
        .package-page .package-card-footer { flex-wrap: wrap; }
        .package-page .package-card-footer .btn { flex: 1 1 45%; padding: .25rem .5rem; font-size: 12px; }
        .package-page .package-pagination { width: 100%; }
        .package-page .package-pagination .pagination { justify-content: center; }
        .package-page .package-list-footer .card-body { gap: 12px; }
        .package-page .package-drawer-grid { grid-template-columns: 1fr; }
    }
</style>

<script>
    function confirmDelete(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Paket ini akan dihapus permanen dan tidak dapat dikembalikan. Apakah Anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    const packageBulkForm = document.getElementById('package-bulk-form');
    const packageBulkBar = document.querySelector('.package-bulk-bar');
    const packageBulkIds = document.getElementById('package-bulk-ids');
    const packageBulkCount = document.getElementById('package-bulk-count');
    const packageBulkAction = document.getElementById('package-bulk-action');
    const packageSelectAll = document.getElementById('package-select-all');

    function setPackageView(view, updateUrl = true) {
        const showCards = view === 'cards';
        const tableCard = document.getElementById('package-table-card');
        const cardView = document.getElementById('package-card-view');
        const tableButton = document.getElementById('btn-package-table');
        const cardButton = document.getElementById('btn-package-cards');
        const viewInput = document.getElementById('package-view-input');

        tableCard?.classList.toggle('d-none', showCards);
        cardView?.classList.toggle('d-none', !showCards);
        tableButton?.classList.toggle('active', !showCards);
        cardButton?.classList.toggle('active', showCards);

        if (viewInput) {
            viewInput.value = view;
        }

        if (updateUrl) {
            const url = new URL(window.location.href);
            url.searchParams.set('view', view);
            history.replaceState(null, '', url.toString());
        }

        syncPackageSelection();

    const hasViewParam = {{ $hasViewParam ? 'true' : 'false' }};
    if (!hasViewParam && window.innerWidth < 992) {
        setPackageView('cards', false);
    }

    const packageSearch = document.getElementById('package-search');
    const packageSearchHint = document.getElementById('package-search-hint');

    function updateSearchHint() {
        if (!packageSearch || !packageSearchHint) return;
        const length = packageSearch.value.trim().length;
        packageSearchHint.classList.toggle('d-none', length === 0 || length >= 2);
        packageSearch.classList.toggle('is-invalid', length === 1);
    }

    packageSearch?.addEventListener('input', updateSearchHint);
    updateSearchHint();

    document.addEventListener('keydown', function (event) {
        if (event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey) return;
        const target = event.target;
        const tag = target && target.tagName ? target.tagName.toLowerCase() : '';
        if (tag === 'input' || tag === 'textarea' || tag === 'select' || (target && target.isContentEditable)) return;

        event.preventDefault();
        if (packageSearch) {
            packageSearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => packageSearch.focus(), 200);
        }
    });

    function activePackageBoxes() {
        const selectors = [
            '#package-table-card:not(.d-none) .package-checkbox',
            '#package-card-view:not(.d-none) .package-checkbox'
        ];

        return Array.from(document.querySelectorAll(selectors.join(',')));
    }

    function allPackageBoxes() {
        return Array.from(document.querySelectorAll('.package-checkbox'));
    }

    function syncPackageSelection() {
        const boxes = allPackageBoxes();
        const checked = boxes.filter((box) => box.checked);

        boxes.forEach((box) => {
            box.closest('tr')?.classList.toggle('table-active', box.checked);
            box.closest('.package-item-card')?.classList.toggle('is-selected', box.checked);
        });

        const activeBoxes = activePackageBoxes();
        if (packageSelectAll) {
            packageSelectAll.checked = activeBoxes.length > 0 && activeBoxes.every((box) => box.checked);
            packageSelectAll.indeterminate = activeBoxes.some((box) => box.checked) && !packageSelectAll.checked;
        }

        packageBulkBar?.classList.toggle('d-none', checked.length === 0);

        if (packageBulkCount) {
            packageBulkCount.textContent = checked.length;
        }

        if (packageBulkIds) {
            packageBulkIds.innerHTML = '';
            checked.forEach((box) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = box.value;
                packageBulkIds.appendChild(input);
            });
        }
    }

    allPackageBoxes().forEach((box) => box.addEventListener('change', syncPackageSelection));

    packageSelectAll?.addEventListener('change', function () {
        activePackageBoxes().forEach((box) => {
            box.checked = packageSelectAll.checked;
        });
        syncPackageSelection();
    });

    document.getElementById('package-bulk-clear')?.addEventListener('click', function () {
        allPackageBoxes().forEach((box) => {
            box.checked = false;
        });
        syncPackageSelection();
    });

    function submitPackageBulk(action) {
        if (!packageBulkForm) return;

        syncPackageSelection();

        if (packageBulkAction) {
            packageBulkAction.value = action;
        }

        if (packageBulkForm.requestSubmit) {
            packageBulkForm.requestSubmit();
        } else {
            packageBulkForm.submit();
        }
    }

    function runPackageBulk(action) {
        const checked = allPackageBoxes().filter((box) => box.checked);
        if (checked.length === 0) return;

        if (action !== 'delete') {
            submitPackageBulk(action);
            return;
        }

        Swal.fire({
            title: 'Hapus paket terpilih?',
            text: checked.length + ' paket akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitPackageBulk('delete');
            }
        });
    }

    syncPackageSelection();

    const packageDetailData = document.getElementById('package-detail-data');
    const packageDetailMeta = document.getElementById('package-detail-meta');
    const packageDrawer = document.getElementById('package-drawer');
    const packageDrawerBackdrop = document.getElementById('package-drawer-backdrop');
    const packageDrawerBody = document.getElementById('package-drawer-body');
    const packageDrawerTitle = document.getElementById('package-drawer-title');
    const packageDrawerSubtitle = document.getElementById('package-drawer-subtitle');
    const packageDrawerEdit = document.getElementById('package-drawer-edit');
    const packageDrawerPreview = document.getElementById('package-drawer-preview');
    const packageDrawerQuotation = document.getElementById('package-drawer-quotation');
    const packageQuotationFrame = document.getElementById('package-quotation-frame');
    const packageQuotationDownload = document.getElementById('package-quotation-download');

    let packageDetailItems = [];
    let packageEditUrlTemplate = '';
    let packageQuotationUrlTemplate = '';
    let packageQuotationDownloadTemplate = '';
    let packageStatusUrlTemplate = '';

    try {
        packageDetailItems = JSON.parse(packageDetailData?.textContent || '[]');
    } catch (error) {
        packageDetailItems = [];
    }

    try {
        const meta = JSON.parse(packageDetailMeta?.textContent || '{}');
        packageEditUrlTemplate = meta.editUrl || '';
        packageQuotationUrlTemplate = meta.quotationUrl || '';
        packageQuotationDownloadTemplate = meta.downloadUrl || '';
        packageStatusUrlTemplate = meta.toggleUrl || '';
    } catch (error) {
        packageEditUrlTemplate = '';
        packageQuotationUrlTemplate = '';
        packageQuotationDownloadTemplate = '';
        packageStatusUrlTemplate = '';
    }

    const packageDetailMap = new Map(packageDetailItems.map((item) => [item.uuid, item]));

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (character) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character];
        });
    }

    function formatRupiah(value) {
        const number = Number(value);
        return Number.isFinite(number) ? 'Rp ' + number.toLocaleString('id-ID') : '-';
    }

    function drawerMetric(label, value) {
        return '<div class="package-drawer-metric">'
            + '<div class="package-drawer-metric-label">' + escapeHtml(label) + '</div>'
            + '<div class="package-drawer-metric-value">' + escapeHtml(value) + '</div>'
            + '</div>';
    }

    function drawerList(items, renderer) {
        if (!items || !items.length) {
            return '<div class="package-drawer-empty">Belum ada data.</div>';
        }

        return '<ul class="package-drawer-list">' + items.map(renderer).join('') + '</ul>';
    }

    function optionPriceHtml(option) {
        const price = Number(option.price || 0);
        const discount = option.discount_price === null || option.discount_price === undefined
            ? null
            : Number(option.discount_price);

        if (discount !== null && discount < price) {
            return '<span class="text-decoration-line-through me-1">' + formatRupiah(price) + '</span>'
                + '<span class="fw-bold text-success">' + formatRupiah(discount) + '</span>';
        }

        return '<span class="fw-bold">' + formatRupiah(price) + '</span>';
    }

    function openPackageDetail(uuid) {
        const item = packageDetailMap.get(uuid);
        if (!item || !packageDrawer || !packageDrawerBody) return;

        const options = item.options || [];
        const features = item.features || [];
        const addons = item.addons || [];
        const blackoutDates = item.blackout_dates || item.blackoutDates || [];
        const promos = item.promos || [];
        const prices = options
            .map((option) => Number(option.discount_price ?? option.price))
            .filter((value) => Number.isFinite(value));
        const startingPrice = prices.length > 0 ? Math.min(...prices) : null;
        const createdAt = item.created_at
            ? new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
            : '-';

        if (packageDrawerTitle) {
            packageDrawerTitle.textContent = item.name;
        }
        if (packageDrawerSubtitle) {
            packageDrawerSubtitle.textContent = 'Dibuat ' + createdAt;
        }
        if (packageDrawerEdit && packageEditUrlTemplate) {
            packageDrawerEdit.href = packageEditUrlTemplate.replace('__UUID__', uuid);
        }
        if (packageDrawerPreview) {
            packageDrawerPreview.href = '{{ route('packages.preview', '__UUID__') }}'.replace('__UUID__', uuid);
        }
        if (packageDrawerQuotation) {
            packageDrawerQuotation.dataset.uuid = uuid;
        }

        let html = '';

        html += '<div class="package-drawer-section package-drawer-gallery"><h6>Galeri Foto</h6>';
        if (item.cover_url) {
            html += '<a href="' + escapeHtml(item.cover_url) + '" target="_blank" rel="noopener">'
                + '<img src="' + escapeHtml(item.cover_url) + '" alt="Foto ' + escapeHtml(item.name)
                + '" class="package-drawer-cover"></a>'
                + '<div class="package-drawer-gallery-strip">'
                + '<a href="' + escapeHtml(item.cover_url) + '" target="_blank" rel="noopener" class="package-drawer-thumb active">'
                + '<img src="' + escapeHtml(item.cover_url) + '" alt="Foto utama ' + escapeHtml(item.name) + '"></a>'
                + '<a href="' + escapeHtml(item.cover_url) + '" target="_blank" rel="noopener" class="package-drawer-thumb">'
                + '<i class="bi bi-box-arrow-up-right"></i></a>'
                + '</div>';
        } else {
            html += '<div class="package-drawer-cover-empty"><i class="bi bi-image"></i></div>'
                + '<div class="package-drawer-empty">Belum ada foto paket.</div>';
        }
        html += '</div>';

        html += '<div class="package-drawer-badges">';
        html += item.is_active === 'active'
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>';
        html += item.is_published
            ? '<span class="badge bg-info-subtle text-info-emphasis">Dipublikasikan</span>'
            : '<span class="badge bg-light text-dark">Draft</span>';
        if (item.is_popular) {
            html += '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Paling Dipilih</span>';
        }
        html += '</div>';

        if (item.description) {
            html += '<p class="package-drawer-description">' + escapeHtml(item.description) + '</p>';
        }

        html += '<div class="package-drawer-grid">';
        html += drawerMetric('Harga mulai', startingPrice !== null ? formatRupiah(startingPrice) : '-');
        html += drawerMetric('Kapasitas', item.guest_capacity ? Number(item.guest_capacity).toLocaleString('id-ID') + ' tamu' : '-');
        html += drawerMetric('Durasi acara', item.event_duration_hours ? item.event_duration_hours + ' jam' : '-');
        html += drawerMetric('Lead time', item.lead_time_days !== null && item.lead_time_days !== undefined ? item.lead_time_days + ' hari' : '-');
        html += drawerMetric('Jumlah opsi', options.length + ' opsi');
        html += drawerMetric('Publikasi', item.is_published ? 'Dipublikasikan' : 'Draft');
        html += '</div>';

        if (item.venue_note) {
            html += '<div class="package-drawer-section"><h6>Catatan Venue</h6>'
                + '<p class="package-drawer-description">' + escapeHtml(item.venue_note) + '</p></div>';
        }

        html += '<div class="package-drawer-section"><h6>Opsi Paket</h6>'
            + drawerList(options, function (option) {
                const unit = { day: 'hari', week: 'minggu', month: 'bulan', year: 'tahun' }[option.duration_unit] || option.duration_unit || '';
                return '<li><strong>' + escapeHtml(option.name) + '</strong>'
                    + optionPriceHtml(option)
                    + '<div class="small text-muted mt-1">Kuota ' + escapeHtml(option.quota)
                    + ' Â· ' + escapeHtml(option.duration) + ' ' + escapeHtml(unit) + '</div></li>';
            })
            + '</div>';

        html += '<div class="package-drawer-section"><h6>Fitur</h6>'
            + drawerList(features, function (feature) {
                return '<li>' + escapeHtml(feature.feature) + '</li>';
            })
            + '</div>';

        html += '<div class="package-drawer-section"><h6>Layanan Tambahan</h6>'
            + drawerList(addons, function (addon) {
                return '<li><strong>' + escapeHtml(addon.name) + '</strong>'
                    + (addon.price !== null && addon.price !== undefined ? formatRupiah(addon.price) : '')
                    + (addon.description ? '<div class="small text-muted mt-1">' + escapeHtml(addon.description) + '</div>' : '')
                    + '</li>';
            })
            + '</div>';

        html += '<div class="package-drawer-section"><h6>Tanggal Terkunci</h6>'
            + drawerList(blackoutDates, function (date) {
                return '<li>' + escapeHtml(date.date)
                    + (date.reason ? ' Â· ' + escapeHtml(date.reason) : '') + '</li>';
            })
            + '</div>';

        html += '<div class="package-drawer-section"><h6>Promo</h6>'
            + drawerList(promos, function (promo) {
                return '<li><strong>' + escapeHtml(promo.code) + '</strong>'
                    + ' Â· ' + escapeHtml(promo.type) + ' ' + escapeHtml(promo.value)
                    + (promo.is_active ? ' Â· Aktif' : ' Â· Nonaktif') + '</li>';
            })
            + '</div>';

        packageDrawerBody.innerHTML = html;
        packageDrawerBackdrop?.classList.remove('d-none');
        packageDrawer.classList.remove('d-none');
        document.body.classList.add('package-drawer-open');
        requestAnimationFrame(function () {
            packageDrawer.classList.add('show');
        });
    }

    function previewPackageQuotation(uuid) {
        if (!uuid || !packageQuotationUrlTemplate || !packageQuotationDownloadTemplate) return;

        if (packageQuotationFrame) {
            packageQuotationFrame.src = packageQuotationUrlTemplate.replace('__UUID__', uuid);
        }
        if (packageQuotationDownload) {
            packageQuotationDownload.href = packageQuotationDownloadTemplate.replace('__UUID__', uuid);
        }

        const modalElement = document.getElementById('packageQuotationModal');
        if (modalElement && window.bootstrap) {
            window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
    }

    function packageQuotationUrl(uuid) {
        return packageQuotationUrlTemplate.replace('__UUID__', uuid);
    }

    async function togglePackageStatus(button) {
        const uuid = button?.dataset.uuid;
        const field = button?.dataset.field;
        if (!uuid || !field || !packageStatusUrlTemplate) return;

        button.disabled = true;
        button.classList.add('is-busy');

        try {
            const response = await fetch(packageStatusUrlTemplate.replace('__UUID__', uuid), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ field: field }),
            });

            if (!response.ok) throw new Error('Gagal memperbarui status.');

            const data = await response.json();
            const active = data.value === 'active' || data.value === true;
            const classes = field === 'is_active'
                ? (active ? 'bg-success' : 'bg-secondary')
                : (active ? 'bg-info-subtle text-info-emphasis' : 'bg-light text-dark');

            button.className = 'package-status-toggle badge border-0 ' + classes;
            button.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>' + escapeHtml(data.label);

            const item = packageDetailMap.get(uuid);
            if (item) item[field] = data.value;

            document.querySelectorAll('[data-toggle-status][data-uuid="' + uuid + '"][data-field="' + field + '"]').forEach(function (other) {
                if (other === button) return;
                other.className = button.className;
                other.innerHTML = button.innerHTML;
            });
        } catch (error) {
            window.alert(error.message || 'Gagal memperbarui status.');
        } finally {
            button.disabled = false;
            button.classList.remove('is-busy');
        }
    }

    const packageFeatureModal = document.getElementById('packageFeatureModal');
    const packageFeatureModalTitle = document.getElementById('packageFeatureModalLabel');
    const packageFeatureModalSubtitle = document.getElementById('packageFeatureModalSubtitle');
    const packageFeatureModalList = document.getElementById('packageFeatureModalList');

    function openPackageFeatures(button) {
        if (!packageFeatureModal || !packageFeatureModalList) return;
        let features = [];
        try { features = JSON.parse(button.dataset.features || '[]'); } catch (e) { features = []; }
        packageFeatureModalTitle.textContent = 'Fitur Paket';
        packageFeatureModalSubtitle.textContent = button.dataset.package || '';
        packageFeatureModalList.innerHTML = features.length
            ? features.map((f) => '<li class="list-group-item px-0 d-flex gap-2"><i class="bi bi-check-circle-fill text-success mt-1"></i><span>' + escapeHtml(f) + '</span></li>').join('')
            : '<li class="list-group-item px-0 text-muted">Belum ada fitur.</li>';
        if (window.bootstrap) window.bootstrap.Modal.getOrCreateInstance(packageFeatureModal).show();
    }

    document.addEventListener('click', function (event) {
        const featureBtn = event.target.closest('.package-feature-btn');
        if (featureBtn) { openPackageFeatures(featureBtn); return; }
        const toggle = event.target.closest('[data-toggle-status]');
        if (toggle) togglePackageStatus(toggle);
    });

    const packageFilterForm = document.querySelector('.package-filter-grid');
    packageFilterForm?.addEventListener('submit', function () {
        const isCards = !document.getElementById('package-table-card')?.classList.contains('d-none');
        const target = isCards
            ? document.getElementById('package-table-view')
            : document.getElementById('package-card-view');

        target?.classList.add('is-loading');
        target?.querySelector('.package-skeleton')?.classList.remove('d-none');
    });

    document.getElementById('package-drawer-quotation')?.addEventListener('click', function () {
        closePackageDetail();
        previewPackageQuotation(this.dataset.uuid);
    });

    function closePackageDetail() {
        if (!packageDrawer) return;

        packageDrawer.classList.remove('show');
        setTimeout(function () {
            packageDrawer.classList.add('d-none');
            packageDrawerBackdrop?.classList.add('d-none');
            document.body.classList.remove('package-drawer-open');
        }, 220);
    }

    document.getElementById('package-drawer-close')?.addEventListener('click', closePackageDetail);
    document.getElementById('package-drawer-dismiss')?.addEventListener('click', closePackageDetail);
    packageDrawerBackdrop?.addEventListener('click', closePackageDetail);

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        if (document.querySelector('.modal.show')) return;
        closePackageDetail();
    });
</script>

@endsection






