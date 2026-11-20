@extends('layouts.app')
@section('title', 'Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Konsultasi" page="Layanan" active="Pertanyaan Masuk" route="{{ route('konsultasi.index') }}" />
@endsection

<section class="section">
    <div class="alert alert-primary alert-dismissible mb-3 fade show position-relative" role="alert">
        <div class="d-flex">
            <i class="bx bx-message-detail text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>
            <div class="text-white">
                <strong>Manajemen Konsultasi</strong><br>
                Kelola pertanyaan, jadwal konsultasi, dan status calon pelanggan pernikahan.
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100"><div class="card-body py-3"><div class="small text-muted">Total Konsultasi</div><div class="fs-4 fw-semibold">{{ number_format($stats['total']) }}</div></div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100"><div class="card-body py-3"><div class="small text-muted">Menunggu</div><div class="fs-4 fw-semibold text-warning">{{ number_format($stats['pending']) }}</div></div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100"><div class="card-body py-3"><div class="small text-muted">Dikonfirmasi</div><div class="fs-4 fw-semibold text-primary">{{ number_format($stats['confirmed']) }}</div></div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100"><div class="card-body py-3"><div class="small text-muted">Selesai</div><div class="fs-4 fw-semibold text-success">{{ number_format($stats['completed']) }}</div></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="fw-normal mb-0 text-body">Pertanyaan Masuk</h4>
                @can('konsultasi.index')
                    <a href="{{ route('konsultasi.create') }}" class="btn btn-primary btn-md">
                        <i class="bi bi-plus-lg"></i> Tambah Konsultasi
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('konsultasi.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="search" name="q" class="form-control" placeholder="Cari nama, WhatsApp, atau venue" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\Consultation::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="method" class="form-select">
                        <option value="">Semua metode</option>
                        @foreach (\App\Models\Consultation::METHOD_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected($method === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:45px;">No.</th>
                            <th>Nama Calon Pengantin</th>
                            <th>WhatsApp</th>
                            <th>Rencana Pernikahan</th>
                            <th>Konsultasi</th>
                            <th>Status</th>
                            <th>Calon Pelanggan</th>
                            <th>Sumber</th>
                            <th style="width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultations as $item)
                            <tr>
                                <td>{{ $consultations->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->full_name }}</div>
                                    @if ($item->venue)
                                        <div class="small text-muted">{{ $item->venue }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->whatsapp }}</td>
                                <td>
                                    @if ($item->wedding_date)
                                        {{ $item->wedding_date->format('d M Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $item->method_label }}</div>
                                    @if ($item->consultation_date)
                                        <div class="small text-muted">{{ $item->consultation_date->format('d M Y') }} {{ $item->consultation_time ? \Illuminate\Support\Carbon::parse($item->consultation_time)->format('H:i') : '' }}</div>
                                    @endif
                                </td>
                                <td><span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span></td>
                                <td><span class="badge {{ $item->lead_status_badge_class }}">{{ $item->lead_status_label }}</span></td>
                                <td>{{ $item->source }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('konsultasi.show', $item->uuid) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                        @can('konsultasi.index')
                                            <a href="{{ route('konsultasi.edit', $item->uuid) }}" class="btn btn-sm btn-success" title="Ubah"><i class="bi bi-pencil-square"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="confirmDelete('{{ $item->uuid }}')"><i class="bi bi-trash"></i></button>
                                            <form id="deleteForm_{{ $item->uuid }}" action="{{ route('konsultasi.destroy', $item->uuid) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada konsultasi masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $consultations->links() }}
        </div>
    </div>
</section>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus data konsultasi ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + id).submit();
            }
        });
    }
</script>
@endsection
