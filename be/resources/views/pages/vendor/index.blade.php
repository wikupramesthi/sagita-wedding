@extends('layouts.app')
@section('title', 'Vendor & Mitra')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Vendor & Mitra" page="Vendor & Mitra" active="Semua Vendor" route="{{ route('vendor.index') }}" />
@endsection

@php
$periodeLabel = [
'bulan_ini' => 'Bulan ini',
'30_hari' => '30 hari terakhir',
'tahun_ini' => 'Tahun ini',
][$periode] ?? null;
$sortLabel = [
'terlama' => 'Terlama',
'nama_asc' => 'Nama A-Z',
'nama_desc' => 'Nama Z-A',
'bergabung_terbaru' => 'Bergabung terbaru',
][$sort] ?? null;
$categoryLabel = $vendors_categories->firstWhere('uuid', $vendorsCategoryUuid)?->name;
@endphp

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-user-voice"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['total']) }}</div>
                            <div class="ml-stat-label">Total Vendor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-info"><i class="bx bx-phone"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['with_phone']) }}</div>
                            <div class="ml-stat-label">Punya Nomor HP</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-category"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['categories_active']) }}</div>
                            <div class="ml-stat-label">Kategori Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-user-plus"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['new_month']) }}</div>
                            <div class="ml-stat-label">Bergabung Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3 px-3">
            <form method="GET" action="{{ route('vendor.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small mb-1" for="vendor-search">Cari vendor</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="vendor-search" name="search" value="{{ $search }}" class="form-control" placeholder="Nama, email, atau nomor HP" autocomplete="off">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1" for="vendor-category">Kategori</label>
                    <select id="vendor-category" name="vendors_category" class="form-select form-select-sm">
                        <option value="">Semua kategori</option>
                        @foreach ($vendors_categories as $vendors_category)
                        <option value="{{ $vendors_category->uuid }}" @selected($vendorsCategoryUuid===$vendors_category->uuid)>{{ $vendors_category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1" for="vendor-periode">Periode bergabung</label>
                    <select id="vendor-periode" name="periode" class="form-select form-select-sm">
                        <option value="semua" @selected($periode==='semua' )>Semua</option>
                        <option value="bulan_ini" @selected($periode==='bulan_ini' )>Bulan ini</option>
                        <option value="30_hari" @selected($periode==='30_hari' )>30 hari</option>
                        <option value="tahun_ini" @selected($periode==='tahun_ini' )>Tahun ini</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1" for="vendor-sort">Urutkan</label>
                    <select id="vendor-sort" name="sort" class="form-select form-select-sm">
                        <option value="terbaru" @selected($sort==='terbaru' )>Terbaru ditambahkan</option>
                        <option value="terlama" @selected($sort==='terlama' )>Terlama ditambahkan</option>
                        <option value="bergabung_terbaru" @selected($sort==='bergabung_terbaru' )>Bergabung terbaru</option>
                        <option value="nama_asc" @selected($sort==='nama_asc' )>Nama A-Z</option>
                        <option value="nama_desc" @selected($sort==='nama_desc' )>Nama Z-A</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1" for="vendor-start">Dari tanggal</label>
                    <input type="date" id="vendor-start" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                </div>
                <div class="col-12 col-md-5">
                    <div class="d-flex align-items-end gap-2">
                        <div class="flex-grow-1">
                            <label class="form-label small mb-1" for="vendor-end">Sampai tanggal</label>
                            <input type="date" id="vendor-end" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-success px-2 text-nowrap"><i class="bi bi-funnel"></i> Terapkan</button>
                        <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-light px-2 text-nowrap"><i class="bi bi-arrow-counterclockwise"></i> Atur Ulang</a>
                    </div>
                </div>
            </form>

            @if ($search || $vendorsCategoryUuid || $periodeLabel || $startDate || $endDate || $sortLabel)
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                <span class="small text-muted me-1">Filter aktif:</span>
                @if ($search)<span class="badge bg-light text-dark border">Pencarian: {{ $search }}</span>@endif
                @if ($categoryLabel)<span class="badge bg-light text-dark border">Kategori: {{ $categoryLabel }}</span>@endif
                @if ($periodeLabel)<span class="badge bg-light text-dark border">Periode: {{ $periodeLabel }}</span>@endif
                @if ($startDate)<span class="badge bg-light text-dark border">Dari: {{ $startDate }}</span>@endif
                @if ($endDate)<span class="badge bg-light text-dark border">Sampai: {{ $endDate }}</span>@endif
                @if ($sortLabel)<span class="badge bg-light text-dark border">Urutan: {{ $sortLabel }}</span>@endif
                <a href="{{ route('vendor.index') }}" class="badge bg-danger-subtle text-danger">Hapus semua <i class="bi bi-x"></i></a>
            </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Data Vendor</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($users->total()) }} hasil</span>
            </div>
            <div class="d-flex gap-2">
                @can('vendor.store')
                <a href="{{ route('vendor.archive') }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-archive"></i> Arsip
                    @if ($archivedCount)
                    <span class="badge bg-warning text-dark ms-1">{{ $archivedCount }}</span>
                    @endif
                </a>
                <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Vendor
                </a>
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width:52px;">No.</th>
                        <th>Vendor</th>
                        <th style="min-width:170px;">Kategori</th>
                        <th style="min-width:190px;">Kontak</th>
                        <th style="min-width:140px;">Pengalaman</th>
                        <th style="width:130px;">Bergabung</th>
                        <th style="width:130px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $vendor)
                    <tr>
                        <td class="text-muted">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($vendor->avatar)
                                <img src="{{ asset('storage/' . $vendor->avatar) }}" alt="{{ $vendor->name }}" class="rounded-circle flex-shrink-0" style="width:42px;height:42px;object-fit:cover;" loading="lazy">
                                @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary flex-shrink-0 fw-semibold" style="width:42px;height:42px;">{{ strtoupper(substr($vendor->name, 0, 1)) }}</span>
                                @endif
                                <div class="min-w-0">
                                    <div class="fw-semibold text-dark d-flex align-items-center gap-1 flex-wrap">
                                        {{ $vendor->name }}
                                        @if ($vendor->created_at && $vendor->created_at->gt(now()->subDays(30)))
                                        <span class="badge bg-success-subtle text-success">Baru</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse ($vendor->vendors_categories as $vendors_category)
                                <span class="badge bg-primary-subtle text-primary">{{ $vendors_category->name }}</span>
                                @empty
                                <span class="text-muted">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1 small">
                                <i class="bi bi-telephone text-muted"></i>
                                @if ($vendor->no_hp)
                                <span>{{ $vendor->no_hp }}</span>
                                <button type="button" class="btn btn-link btn-sm p-0 js-copy" data-copy="{{ $vendor->no_hp }}" title="Salin nomor HP" aria-label="Salin nomor HP {{ $vendor->name }}"><i class="bi bi-copy"></i></button>
                                @else
                                <span class="text-muted">Belum ada nomor HP</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-1 small hide-sm mt-1">
                                <i class="bi bi-envelope text-muted"></i>
                                <span class="text-truncate" style="max-width:190px;">{{ $vendor->email }}</span>
                                <button type="button" class="btn btn-link btn-sm p-0 js-copy" data-copy="{{ $vendor->email }}" title="Salin email" aria-label="Salin email {{ $vendor->name }}"><i class="bi bi-copy"></i></button>
                            </div>
                        </td>
                        <td>
                            <span class="small">{{ $vendor->pengalaman ?: '-' }}</span>
                        </td>
                        <td>
                            <span class="small">{{ $vendor->is_active ? \Carbon\Carbon::parse($vendor->is_active)->format('d M Y') : '-' }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                @can('vendor.update')
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-form-view-vendor-{{ $vendor->uuid }}" title="Detail" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></button>
                                @include('pages.vendor.modal-view')
                                <a href="{{ route('vendor.edit', $vendor->uuid) }}" title="Ubah" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('vendor.destroy')
                                <button type="button" onclick="showSweetAlert('{{ $vendor->uuid }}', this.dataset.name)" data-name="{{ $vendor->name }}" title="Hapus" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                <form id="deleteForm_{{ $vendor->uuid }}" action="{{ route('vendor.destroy', $vendor->uuid) }}" method="POST" class="d-none">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bx bx-search-alt-2 d-block mb-2 text-muted" style="font-size:2rem;"></i>
                            <div class="fw-semibold">Tidak ada vendor ditemukan</div>
                            <div class="small text-muted mt-1">Coba ubah kata kunci atau filter yang digunakan.</div>
                            @can('vendor.store')
                            <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary mt-3"><i class="bi bi-plus-lg"></i> Tambah Vendor</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
        <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ number_format($users->total()) }} vendor
            </small>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</section>

<script>
    function showSweetAlert(getId, name = 'vendor ini') {
        Swal.fire({
            title: 'Hapus vendor?',
            html: `Data <strong>${name}</strong> akan dihapus permanen dan tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }

    document.addEventListener('click', async function(event) {
        const button = event.target.closest('.js-copy');
        if (!button) return;

        const value = button.dataset.copy || '';
        if (!value) return;

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(value);
            } else {
                const input = document.createElement('textarea');
                input.value = value;
                input.style.position = 'fixed';
                input.style.opacity = '0';
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                input.remove();
            }

            const original = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check2"></i>';
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil disalin',
                    showConfirmButton: false,
                    timer: 1200,
                    timerProgressBar: true
                });
            }
            setTimeout(() => {
                button.innerHTML = original;
            }, 1400);
        } catch (error) {
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal menyalin',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    });
</script>
@endsection