@extends('layouts.app')
@section('title', 'Master Data')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Master Data" page="Master Data" active="FAQ & Jawaban" route="{{ route('faq.index') }}" />
@endsection

@if (session('success'))
<div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
    <span class="alert-text text-white">{{ session('success') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show" role="alert">
    <span class="alert-text text-white">{{ session('error') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@php
$totalFaq = $counts->sum();
$totalActive = (int) $statusCounts->get('active', 0);
$totalInactive = (int) $statusCounts->get('inactive', 0);
@endphp

<section class="section">
    <div class="alert alert-info alert-dismissible mb-3 fade show position-relative" role="alert">
        <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-question-circle-fill fs-4 flex-shrink-0 mt-1"></i>
            <div class="flex-grow-1">
                Kelola pertanyaan dan jawaban yang ditampilkan di halaman Kontak dan halaman login.
                Pengelompokan FAQ per kategori membantu pengunjung menemukan informasi pernikahan lebih cepat.
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    </div>

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-list-check"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($totalFaq) }}</div>
                            <div class="ml-stat-label">Total FAQ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($totalActive) }}</div>
                            <div class="ml-stat-label">FAQ Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-pause-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($totalInactive) }}</div>
                            <div class="ml-stat-label">FAQ Nonaktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-collection"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ count(array_filter($categories, fn ($name) => $counts->has($name))) }}</div>
                            <div class="ml-stat-label">Kategori Digunakan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <form method="GET" action="{{ route('faq.index') }}" class="d-flex flex-wrap align-items-end gap-2 flex-grow-1">
                    <div style="flex:1 1 240px;min-width:0;">
                        <label class="form-label small mb-1" for="faq-search">Cari</label>
                        <input type="text" id="faq-search" name="q" value="{{ $search }}" class="form-control form-control-sm w-100"
                            placeholder="Cari pertanyaan atau jawaban..." autocomplete="off">
                    </div>
                    <div style="flex:1 1 200px;min-width:0;max-width:260px;">
                        <label class="form-label small mb-1" for="faq-kategori-filter">Kategori</label>
                        <select id="faq-kategori-filter" name="kategori" class="form-select form-select-sm w-100">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($kategori===$category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel me-1"></i>Filter</button>
                        <a href="{{ route('faq.index') }}" class="btn btn-sm btn-light">Atur ulang</a>
                    </div>
                </form>
                @can('faq.store')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form-add-faq">
                    <i class="bi bi-plus-lg me-1"></i>Tambah FAQ
                </button>
                @endcan
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach ($categories as $category)
                @php
                    $active = $kategori === $category;
                @endphp
                <a href="{{ route('faq.index', ['kategori' => $category]) }}"
                    class="badge rounded-pill px-3 py-2 text-decoration-none {{ $active ? 'bg-primary' : 'bg-light-secondary text-body' }}">
                    {{ $category }} <span class="opacity-75">({{ $counts->get($category, 0) }})</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="fw-normal mb-0 text-body">Pertanyaan & Jawaban</h4>
            <span class="text-muted small">Menampilkan {{ $items->count() }} dari {{ $totalFaq }} entri</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="faqTable">
                    <thead>
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Pertanyaan</th>
                            <th style="width:110px">Status</th>
                            <th class="text-end" style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $previousKategori = null;
                        @endphp
                        @forelse ($items as $item)
                        @if ($item->kategori !== $previousKategori)
                        <tr class="table-light">
                            <td></td>
                            <td class="py-2 px-3">
                                <div class="d-flex align-items-center gap-2 small">
                                    <i class="bi bi-tag text-primary"></i>
                                    <span class="fw-semibold text-body">{{ $item->kategori }}</span>
                                    <span class="badge bg-light-primary text-primary">{{ $counts->get($item->kategori, 0) }}</span>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $previousKategori = $item->kategori;
                        @endphp
                        @endif
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-wrap">{{ $item->pertanyaan }}</td>
                            <td>
                                <span class="badge {{ $item->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('faq.update')
                                    <button type="button" title="Detail" class="btn btn-sm btn-info text-white"
                                        data-bs-toggle="modal" data-bs-target="#modal-form-view-faq-{{ $item->uuid }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @include('pages.faq.modal-view')
                                    <button type="button" title="Ubah" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal" data-bs-target="#modal-form-edit-faq-{{ $item->uuid }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @include('pages.faq.modal-edit')
                                    @endcan

                                    @can('faq.destroy')
                                    <button type="button" title="Hapus" class="btn btn-sm btn-danger"
                                        onclick="confirmDeleteFaq('{{ $item->uuid }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-faq-{{ $item->uuid }}" action="{{ route('faq.destroy', $item->uuid) }}" method="POST" class="d-none">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox d-block fs-1 mb-2"></i>
                                @if ($kategori !== '' || $search !== '')
                                Tidak ada FAQ yang cocok dengan pencarian.<br>
                                <span class="small">Coba kata kunci atau kategori lain.</span>
                                @else
                                Belum ada FAQ. Tambahkan pertanyaan pertama untuk mengisi halaman Kontak.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>

@can('faq.store')
@include('pages.faq.modal-create')
@endcan

<script>
    function confirmDeleteFaq(uuid) {
        Swal.fire({
            title: 'Hapus FAQ?',
            text: 'Entri ini akan dihapus permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-faq-' + uuid).submit();
            }
        });
    }
</script>

@push('after-script')
<script>
    $(function() {
        $('#faqTable').DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
        });
    });
</script>
@endpush
@endsection