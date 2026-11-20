@extends('layouts.app')
@section('title', 'Acara')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Acara" page="Acara" active="Semua Acara" route="{{ route('events.index') }}" />
@endsection

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white"> {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white"> {{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- Statistic cards: numbers follow the active filter --}}
    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-calendar-event"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Acara</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['published']) }}</div><div class="ml-stat-label">Diterbitkan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-edit"></i></div><div><div class="ml-stat-value">{{ number_format($stats['draft']) }}</div><div class="ml-stat-label">Draf</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['cancelled']) }}</div><div class="ml-stat-label">Dibatalkan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-check-double"></i></div><div><div class="ml-stat-value">{{ number_format($stats['completed']) }}</div><div class="ml-stat-label">Selesai</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="{{ route('events.index') }}" class="d-flex flex-wrap align-items-end gap-2">
                    <div><label class="form-label small mb-0">Cari</label><input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Cari nama acara..." style="min-width:180px;" autocomplete="off"></div>
                    <div><label class="form-label small mb-0">Status</label><select name="status" class="form-select form-select-sm" style="min-width:150px;"><option value="">Semua</option><option value="draft" @selected($status==='draft')>Draf</option><option value="published" @selected($status==='published')>Diterbitkan</option><option value="cancelled" @selected($status==='cancelled')>Dibatalkan</option><option value="completed" @selected($status==='completed')>Selesai</option></select></div>
                    <div><label class="form-label small mb-0">Dari</label><input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ $tanggal_mulai }}"></div>
                    <div><label class="form-label small mb-0">Sampai</label><input type="date" name="tanggal_selesai" class="form-control form-control-sm" value="{{ $tanggal_selesai }}"></div>
                    <div class="d-flex gap-1"><button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel"></i> Filter</button><a href="{{ route('events.index') }}" class="btn btn-sm btn-light">Atur Ulang</a></div>
                </form>
                @can('events.store')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form-add-events">
                    <i class="bi bi-plus-lg"></i> Tambah Acara
                </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Daftar Acara</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($stats['total']) }} acara</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th><th>Gambar</th><th>Nama Acara</th><th>Tanggal</th><th class="hide-xs">Waktu</th><th class="hide-sm">Lokasi</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="rounded" style="width:60px;height:42px;object-fit:cover;" loading="lazy">
                                @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted" style="width:60px;height:42px;"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td><span class="fw-semibold d-block" style="font-size:0.9rem;">{{ Str::limit($item->judul, 50) }}</span></td>
                            <td><small>{{ $item->tanggal?->locale('id')->translatedFormat('d M Y') ?? '-' }}</small></td>
                            <td class="hide-xs">
                                <small>
                                    @if ($item->waktu_mulai)
                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                    @if ($item->waktu_selesai)
                                    - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                    @endif
                                    @else
                                    -
                                    @endif
                                </small>
                            </td>
                            <td class="hide-sm"><small>{{ Str::limit($item->lokasi ?? '-', 30) }}</small></td>
                            <td>
                                @if ($item->status === 'published')
                                <span class="badge bg-success">Diterbitkan</span>
                                @elseif ($item->status === 'draft')
                                <span class="badge bg-secondary">Draf</span>
                                @elseif ($item->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                                @elseif ($item->status === 'completed')
                                <span class="badge bg-primary">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('events.update')
                                    <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-events-{{ $item->uuid }}" title="Ubah" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                                    @include('pages.event.modal-edit')
                                    @endcan
                                    @can('events.destroy')
                                    <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Hapus" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <form id="deleteForm_{{ $item->uuid }}" action="{{ route('events.destroy', $item->uuid) }}" method="POST" class="d-none">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada acara yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($items->hasPages())
            <div class="card-footer py-2 px-3">
                {{ $items->links('pagination::minimal') }}
            </div>
            @endif
        </div>
    </div>
</section>
<!-- / Content -->

@include('pages.event.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus permanen dan tidak dapat dikembalikan. Apakah Anda yakin ingin menghapusnya?',
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
