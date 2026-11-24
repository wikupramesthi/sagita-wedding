@extends('layouts.app')
@section('title', 'Master Data')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Master Data" page="Master Data" active="Testimoni" route="{{ route('testimonial.index') }}" />
@endsection
<!-- Content -->
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
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center ">
                <h4 class="fw-normal mb-0 text-body">Testimoni</h4>
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-testimonial">
                    <i class="bi bi-plus-lg"></i> Tambah Baru
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Bergabung Sejak</th>
                            <th>Isi Testimoni</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th>Ubah</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($item->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->foto))
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->name }}"
                                    width="60" class="img-thumbnail">
                                @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted"
                                    style="width:60px;height:48px" aria-label="Foto tidak tersedia"><i class="bi bi-person"></i></span>
                                @endif
                            </td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->isi_testimoni }}</td>
                            <td>{{ $item->urutan }}</td>
                            <td>
                                <span class="badge {{ $item->is_active === 'active' ? 'bg-info' : 'bg-danger' }}">
                                    {{ $item->is_active === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                @can('testimonial.update')
                                <a data-bs-toggle="modal"
                                    data-bs-target="#modal-form-edit-testimonial-{{ $item->uuid }}"
                                    class="btn btn-icon btn-success text-white">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </a>
                                @include('pages.testimonial.modal-edit')
                                @endcan
                            </td>

                            <td>
                                @can('testimonial.destroy')
                                <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Hapus"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Hapus
                                </a>
                                <form id="deleteForm_{{ $item->uuid }}"
                                    action="{{ route('testimonial.destroy', $item->uuid) }}" method="POST">
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

@include('pages.testimonial.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Data ini akan dihapus permanen dan tidak dapat dikembalikan. Apakah Anda yakin ingin menghapusnya?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengklik "Ya, Hapus!", kirim formulir yang sesuai
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection