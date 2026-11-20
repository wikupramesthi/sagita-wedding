@extends('layouts.app')
@section('title', 'Vendor & Mitra')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Vendor & Mitra" page="Vendor & Mitra" active="Kategori Vendor" route="{{ route('vendors_categories.index') }}" />
@endsection
<!-- Content -->
<section class="section">

    <div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show position-relative" role="alert">
        <div class="d-flex">
            <i class="bi-bell-fill text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>
            <div class="text-white mt-0">
                Kelola Kategori Vendor yang tersedia di sistem.<br>
                Tambah, ubah, atau nonaktifkan kategori vendor untuk mendukung pengelolaan vendor dan kelas.
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center ">
                <h4 class="fw-normal mb-0 text-body">Kategori Vendor</h4>
                @can('vendors_categories.store')
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-vendors_categories">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Baru
                </button>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kategori Vendor</th>
                            <th>Deskripsi</th>
                            <th>Total Vendor</th>
                            <th>Ubah</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors_categories as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->vendors_categories_count }}</td>

                            <td>
                                @can('vendors_categories.update')
                                <a data-bs-toggle="modal"
                                    data-bs-target="#modal-form-edit-vendors_categories-{{ $item->uuid }}"
                                    class="btn btn-icon btn-success text-white">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </a>
                                @include('pages.vendors_categories.modal-edit')
                                @endcan
                            </td>

                            <td>
                                @can('vendors_categories.destroy')
                                <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Hapus"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Hapus
                                </a>
                                <form id="deleteForm_{{ $item->uuid }}"
                                    action="{{ route('vendors_categories.destroy', $item->uuid) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                @endcan
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
@include('pages.vendors_categories.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus permanen dan tidak dapat dikembalikan. Apakah Anda yakin ingin menghapusnya?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection