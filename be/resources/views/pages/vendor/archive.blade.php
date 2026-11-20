@extends('layouts.app')
@section('title', 'Arsip Vendor')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Arsip Vendor" page="Vendor" active="Arsip Vendor" route="{{ route('vendor.archive') }}" />
@endsection

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3 mt-3" role="alert">
        <span class="alert-text text-white">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3 mt-3" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3 px-3">
            <form method="GET" action="{{ route('vendor.archive') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label small mb-1" for="archive-search">Cari vendor</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="archive-search" name="search" value="{{ $search }}" class="form-control" placeholder="Nama, email, atau nomor HP" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-success px-3"><i class="bi bi-funnel me-1"></i> Cari</button>
                        <a href="{{ route('vendor.archive') }}" class="btn btn-sm btn-light px-3"><i class="bi bi-arrow-counterclockwise me-1"></i> Atur Ulang</a>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-md-auto">
                    <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Vendor</a>
                </div>
            </form>
        </div>
    </div>

    <form id="restore-form" action="{{ route('vendor.restore') }}" method="POST">
        @csrf
        <div class="card shadow-sm">
            <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0">Data Terarsip</h6>
                    <span class="badge bg-warning-subtle text-warning">{{ number_format($vendors->total()) }} arsip</span>
                </div>
                @can('vendor.update')
                <button type="button" id="restore-selected" class="btn btn-sm btn-success" disabled>
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Pulihkan Terpilih
                    <span id="selected-count" class="badge bg-light text-success ms-1">0</span>
                </button>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            @can('vendor.update')
                            <th style="width:44px;"><input type="checkbox" id="select-all" class="form-check-input" aria-label="Pilih semua"></th>
                            @endcan
                            <th style="width:52px;">No.</th>
                            <th>Vendor</th>
                            <th style="min-width:170px;">Kategori</th>
                            <th style="min-width:180px;">Kontak</th>
                            <th style="width:150px;">Diarsipkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $vendor)
                        <tr>
                            @can('vendor.update')
                            <td><input type="checkbox" name="uuid[]" value="{{ $vendor->uuid }}" class="form-check-input row-check" aria-label="Pilih {{ $vendor->name }}"></td>
                            @endcan
                            <td class="text-muted">{{ $vendors->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($vendor->avatar)
                                    <img src="{{ asset('storage/' . $vendor->avatar) }}" alt="{{ $vendor->name }}" class="rounded-circle flex-shrink-0" style="width:40px;height:40px;object-fit:cover;" loading="lazy">
                                    @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary flex-shrink-0 fw-semibold" style="width:40px;height:40px;">{{ strtoupper(substr($vendor->name, 0, 1)) }}</span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $vendor->name }}</div>
                                        <div class="small text-muted">{{ $vendor->pengalaman ?: 'Pengalaman belum diisi' }}</div>
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
                                <div class="small"><i class="bi bi-telephone text-muted me-1"></i>{{ $vendor->no_hp ?: '-' }}</div>
                                <div class="small hide-sm"><i class="bi bi-envelope text-muted me-1"></i>{{ $vendor->email }}</div>
                            </td>
                            <td><span class="small">{{ $vendor->deleted_at ? $vendor->deleted_at->format('d M Y H:i') : '-' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('vendor.update') ? 6 : 5 }}" class="text-center py-5">
                                <i class="bx bx-archive d-block mb-2 text-muted" style="font-size:2rem;"></i>
                                <div class="fw-semibold">Tidak ada vendor di arsip</div>
                                <div class="small text-muted mt-1">Vendor yang dihapus sementara akan muncul di halaman ini.</div>
                                <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-primary mt-3">Lihat Daftar Vendor</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($vendors->hasPages())
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $vendors->firstItem() }}–{{ $vendors->lastItem() }} dari {{ number_format($vendors->total()) }} arsip
                </small>
                {{ $vendors->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </form>
</section>

<script>
    const selectAll = document.getElementById('select-all');
    const rowChecks = Array.from(document.querySelectorAll('.row-check'));
    const restoreButton = document.getElementById('restore-selected');
    const selectedCount = document.getElementById('selected-count');

    function syncSelection() {
        const count = rowChecks.filter((checkbox) => checkbox.checked).length;
        if (selectedCount) selectedCount.textContent = count;
        if (restoreButton) restoreButton.disabled = count === 0;
        if (selectAll) selectAll.checked = rowChecks.length > 0 && count === rowChecks.length;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            rowChecks.forEach((checkbox) => { checkbox.checked = this.checked; });
            syncSelection();
        });
    }

    rowChecks.forEach((checkbox) => checkbox.addEventListener('change', syncSelection));

    if (restoreButton) {
        restoreButton.addEventListener('click', function () {
            const count = rowChecks.filter((checkbox) => checkbox.checked).length;
            if (!count) return;

            Swal.fire({
                title: 'Pulihkan vendor terpilih?',
                html: `<strong>${count}</strong> vendor akan dikembalikan ke daftar vendor aktif.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pulihkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form').submit();
                }
            });
        });
    }
</script>
@endsection
