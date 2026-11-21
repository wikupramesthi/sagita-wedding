@extends('layouts.app')

@section('title', 'Paket')

@section('breadcrumb')
<x-breadcrumb
    title="Paket"
    page="Paket"
    active="Semua Paket"
    route="{{ route('packages.index') }}" />
@endsection

@section('content')

<section class="section package-page">

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-package"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['total']) }}</div>
                            <div class="ml-stat-label">Total Paket</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['active']) }}</div>
                            <div class="ml-stat-label">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-pause-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['inactive']) }}</div>
                            <div class="ml-stat-label">Nonaktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bxs-star"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['popular']) }}</div>
                            <div class="ml-stat-label">Paling Dipilih</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3 package-filter-card">
        <div class="card-body py-3 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form action="{{ route('packages.index') }}" method="GET" class="package-filter-form">
                    <div>
                        <label class="form-label small mb-1" for="package-search">Cari</label>
                        <input id="package-search" type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Nama paket..." style="min-width:200px;" autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label small mb-1" for="package-status">Status</label>
                        <select id="package-status" name="is_active" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua</option>
                            <option value="active" @selected($is_active === 'active')>Aktif</option>
                            <option value="inactive" @selected($is_active === 'inactive')>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-1" for="package-popular">Paling Dipilih</label>
                        <select id="package-popular" name="is_popular" class="form-select form-select-sm" style="min-width:120px;">
                            <option value="">Semua</option>
                            <option value="1" @selected($is_popular === '1')>Ya</option>
                            <option value="0" @selected($is_popular === '0')>Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-1" for="package-quota">Kuota</label>
                        <select id="package-quota" name="quota_type" class="form-select form-select-sm" style="min-width:140px;">
                            <option value="">Semua</option>
                            <option value="limited" @selected($quota_type === 'limited')>Terbatas</option>
                            <option value="unlimited" @selected($quota_type === 'unlimited')>Tanpa batas</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-1" for="package-per-page">Per halaman</label>
                        <select id="package-per-page" name="per_page" class="form-select form-select-sm" style="min-width:90px;">
                            @foreach ([10, 25, 50, 100] as $option)
                            <option value="{{ $option }}" @selected((int) $perPage === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel me-1"></i>Filter</button>
                        <a href="{{ route('packages.index') }}" class="btn btn-sm btn-light">Atur ulang</a>
                    </div>
                </form>
                @can('package.store')
                <a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Paket</a>
                @endcan
            </div>
            @if ($searchTooShort)
            <div class="alert alert-info py-2 px-3 mb-0 mt-3 small" role="status">
                Masukkan minimal 2 karakter untuk mencari paket.
            </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm package-table-card">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Daftar Paket</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($stats['total']) }} paket</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 package-table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width:52px;">No.</th>
                            <th scope="col">Paket</th>
                            <th scope="col">Opsi</th>
                            <th scope="col" class="hide-xs">Fitur</th>
                            <th scope="col" class="hide-sm">Paling Dipilih</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                        <tr>
                            <td class="text-muted">{{ $packages->firstItem() + $loop->iteration - 1 }}</td>
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    @if ($package->cover_url)
                                    <img src="{{ $package->cover_url }}" alt="Foto {{ $package->name }}" class="package-cover-thumb">
                                    @else
                                    <span class="package-cover-fallback">
                                        <i class="bi bi-image"></i>
                                    </span>
                                    @endif

                                    <div class="package-info-text">
                                        <span class="fw-semibold d-block package-name">{{ \Illuminate\Support\Str::limit($package->name, 40) }}</span>

                                        @if ($package->description)
                                        <small class="text-muted d-block">{{ \Illuminate\Support\Str::limit($package->description, 60) }}</small>
                                        @endif

                                        @if ($package->guest_capacity || $package->event_duration_hours || $package->venue_note)
                                        <div class="package-event-meta">
                                            @if ($package->guest_capacity)
                                            <span><i class="bi bi-people me-1"></i>{{ number_format($package->guest_capacity, 0, ',', '.') }} tamu</span>
                                            @endif

                                            @if ($package->event_duration_hours)
                                            <span><i class="bi bi-clock me-1"></i>{{ $package->event_duration_hours }} jam</span>
                                            @endif

                                            @if ($package->venue_note)
                                            <span><i class="bi bi-geo-alt me-1"></i>{{ \Illuminate\Support\Str::limit($package->venue_note, 40) }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @forelse ($package->options as $option)
                                <div class="package-option">
                                    <div class="fw-semibold">{{ $option->name }}</div>
                                    <div class="small text-muted mt-1">
                                        @if ($option->discount_price !== null && $option->discount_price < $option->price)
                                        <span class="text-decoration-line-through">Rp {{ number_format($option->price, 0, ',', '.') }}</span>
                                        <span class="fw-bold text-success ms-1">Rp {{ number_format($option->discount_price, 0, ',', '.') }}</span>
                                        @else
                                        <span class="fw-bold">Rp {{ number_format($option->price, 0, ',', '.') }}</span>
                                        @endif
                                        <span class="mx-1">·</span>
                                        Kuota: {{ $option->quota }}
                                        <span class="mx-1">·</span>
                                        {{ $option->duration }} {{ ['day' => 'hari', 'week' => 'minggu', 'month' => 'bulan', 'year' => 'tahun'][$option->duration_unit] ?? $option->duration_unit }}
                                    </div>
                                </div>
                                @empty
                                <span class="text-muted">Belum ada opsi</span>
                                @endforelse
                            </td>
                            <td class="hide-xs">
                                @forelse ($package->features as $feature)
                                <span class="badge bg-light text-dark mb-1">{{ $feature->feature }}</span>
                                @empty
                                <span class="text-muted">-</span>
                                @endforelse

                                @if ($package->addons->isNotEmpty())
                                <div class="small text-muted mt-2 mb-1">Layanan tambahan:</div>
                                @foreach ($package->addons as $addon)
                                <span class="badge bg-info-subtle text-info-emphasis mb-1">{{ $addon->name }}</span>
                                @if ($addon->description)
                                <small class="text-muted d-block mb-2">{{ $addon->description }}</small>
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td class="hide-sm">
                                @if ($package->is_popular)
                                <span class="badge bg-warning"><i class="bi bi-star-fill me-1"></i>Paling Dipilih</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($package->is_active === 'active')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                                        <i class="bi bi-three-dots"></i><span class="visually-hidden">Aksi</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @can('package.update')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('packages.edit', $package->uuid) }}">
                                                <i class="bi bi-pencil me-2"></i>Ubah
                                            </a>
                                        </li>
                                        @endcan
                                        @can('package.destroy')
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" onclick="showSweetAlert('{{ $package->uuid }}')">
                                                <i class="bi bi-trash me-2"></i>Hapus
                                            </button>
                                        </li>
                                        <form id="deleteForm_{{ $package->uuid }}" action="{{ route('packages.destroy', $package->uuid) }}" method="POST" class="d-none">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="package-empty">
                                <i class="bi bi-inbox d-block mb-2"></i>
                                <span>Belum ada paket yang cocok.</span>
                                <a href="{{ route('packages.index') }}" class="d-block mt-2 small">Atur ulang filter</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($packages->hasPages())
        <div class="card-footer py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="text-muted small">
                Menampilkan {{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }} dari {{ number_format($stats['total']) }} paket
            </div>
            {{ $packages->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
        @elseif ($stats['total'] > 0)
        <div class="card-footer py-2 px-3 text-muted small">
            Menampilkan {{ number_format($stats['total']) }} paket
        </div>
        @endif
    </div>

</section>

<style>
    .package-page .package-filter-form { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; }
    .package-page .package-filter-form > div { display: flex; flex-direction: column; }
    .package-page .package-filter-form .btn { margin-bottom: 0; }
    .package-page .package-filter-card { border-color: #e4e7ec; }
    .package-page .package-table { font-size: .9rem; }
    .package-page .package-table th { white-space: nowrap; color: #475467; font-weight: 600; }
    .package-page .package-table td { vertical-align: middle; }
    .package-page .package-name { font-size: .9rem; }
    .package-page .package-cover-thumb { width: 52px; height: 52px; object-fit: cover; border-radius: 8px; flex-shrink: 0; border: 1px solid #e4e7ec; }
    .package-page .package-cover-fallback { width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border-radius: 8px; border: 1px dashed #d0d5dd; background: #f9fafb; color: #98a2b3; font-size: 18px; }
    .package-page .package-info-text { min-width: 0; }
    .package-page .package-event-meta { display: flex; flex-wrap: wrap; gap: 4px 10px; margin-top: 5px; color: #667085; font-size: .75rem; }
    .package-page .package-event-meta span { display: inline-flex; align-items: center; }
    .package-page .package-option + .package-option { margin-top: 8px; padding-top: 8px; border-top: 1px solid #f2f4f7; }
    .package-page .package-empty { padding: 44px 16px !important; text-align: center; color: #98a2b3; }
    .package-page .package-empty i { font-size: 28px; }
    .package-page .pagination { margin: 0; }
</style>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Paket ini akan dihapus permanen dan tidak dapat dikembalikan. Apakah Anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>

@endsection
