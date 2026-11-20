@extends('layouts.app')
@section('title', 'Daftar Role')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Daftar Role" page="Role dan Akses" active="Daftar Role"
  route="{{ route('role.index') }}" />
@endsection
<!-- Content -->
<section class="section mt-2">
  <div class="card">
    <div class="card-header">
      <div class="col-12 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3">
        <form action="{{ route('role.index') }}" method="GET" class="row g-2 align-items-center flex-grow-1">
          <div class="col-auto">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Cari role atau pengguna" aria-label="Cari role atau pengguna">
            </div>
          </div>
          <div class="col-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Cari</button>
            <a href="{{ route('role.index') }}" class="btn btn-sm btn-secondary">Atur Ulang</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <p class="mb-6 mt-6">Role memberikan akses ke menu dan fitur yang telah ditentukan, sehingga administrator dapat
    mengakses sesuai dengan kebutuhan pengguna.</p>

  <div class="row g-6">
    @foreach ($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6 my-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-normal mb-0 text-body">{{ $role->users->count() }} pengguna</h6>
            @can('role.destroy')
            <button type="button" class="btn btn-icon btn-danger text-white" data-delete-form="{{ $role->id }}"
              data-delete-name="Role {{ $role->name }}" aria-label="Hapus role {{ $role->name }}"
              title="Hapus role {{ $role->name }}">
              <i class="bi bi-x"></i>
            </button>
            @endcan
          </div>
          <form id="deleteForm_{{ $role->id }}" action="{{ route('role.destroy', $role->id) }}" method="POST">
            @method('DELETE')
            @csrf
          </form>

          <div class="d-flex justify-content-between align-items-end gap-2 flex-wrap">
            <div class="d-flex flex-column gap-1">
              <h5 class="mb-0">{{ $role->name }}</h5>
              @can('role.update')
              <button type="button" class="btn btn-link p-0 text-primary" data-bs-toggle="modal"
                data-bs-target="#editRoleModal-{{ $role->id }}" aria-label="Ubah role {{ $role->name }}"
                title="Ubah role {{ $role->name }}">Ubah Role</button>
              @include('management-access.role.modal-edit')
              @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    @can('role.store')
    <div class="col-xl-4 col-lg-6 col-md-6 my-3">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4 ps-6">
              <img src="{{ asset('img/man-with-laptop-light.png') }}" class="img-fluid" alt="Ilustrasi tambah role"
                width="120">
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button type="button" class="btn btn-sm btn-primary mb-4 text-nowrap" data-bs-toggle="modal"
                data-bs-target="#addRoleModal" aria-label="Tambah role baru" title="Tambah role baru">
                <i class="bi bi-plus-lg"></i> Tambah Role
              </button>
              <p class="mb-0">Tambah role baru jika belum tersedia.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endcan
  </div>
  <div class="d-flex justify-content-center mt-4">{{ $roles->links('pagination::minimal') }}</div>
</section>

<section class="section mt-2">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-normal mb-0 text-body card-title">Daftar Pengguna</h4>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap mx-2">
        <table class="table table-border-bottom-0" id="table1">
          <thead>
            <tr>
              <th>No.</th>
              <th>Pengguna</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                @foreach ($user->roles as $role)
                <span class="badge bg-light-info me-1">{{ $role->name }}</span>
                @endforeach
              </td>
              <td>
                @if (!blank($user->email_verified_at))
                <span class="badge bg-light-primary me-1">Aktif</span>
                @else
                <span class="badge bg-light-danger me-1">Nonaktif</span>
                @endif
              </td>
              <td>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  @can('user.update')
                  <button type="button" class="btn btn-icon btn-success text-white" data-bs-toggle="modal"
                    data-bs-target="#modal-form-edit-user-{{ $user->uuid }}" aria-label="Ubah pengguna {{ $user->name }}"
                    title="Ubah pengguna {{ $user->name }}">
                    <i class="bi bi-pencil-square"></i> Ubah
                  </button>
                  @include('management-access.user.modal-edit')
                  @endcan

                  @can('user.destroy')
                  <button type="button" class="btn btn-icon btn-danger text-white" data-delete-form="{{ $user->uuid }}"
                    data-delete-name="Pengguna {{ $user->name }}" aria-label="Hapus pengguna {{ $user->name }}"
                    title="Hapus pengguna {{ $user->name }}">
                    <i class="bi bi-x-square"></i> Hapus
                  </button>

                  <form id="deleteForm_{{ $user->uuid }}" action="{{ route('user.destroy', $user->uuid) }}"
                    method="POST">
                    @method('DELETE')
                    @csrf
                  </form>
                  @endcan
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center mt-4">{{ $users->links('pagination::minimal') }}</div>
    </div>
  </div>
</section>
<!-- / Content -->

@include('management-access.role.modal-create')

<script>
  document.addEventListener('click', function (event) {
    if (!event.target || typeof event.target.closest !== 'function') {
      return;
    }

    const trigger = event.target.closest('[data-delete-form]');
    if (!trigger || typeof sagitaConfirm !== 'function') {
      return;
    }

    const id = trigger.dataset.deleteForm;
    const name = trigger.dataset.deleteName || 'Data ini';

    sagitaConfirm({
      title: 'Konfirmasi Penghapusan',
      message: name + ' akan dihapus permanen dan tidak dapat dikembalikan.',
      name: name,
      confirmText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed && document.getElementById('deleteForm_' + id)) {
        document.getElementById('deleteForm_' + id).submit();
      }
    });
  });

  document.addEventListener('shown.bs.modal', function (event) {
    if (!event.target || typeof event.target.closest !== 'function') {
      return;
    }

    const modal = event.target.closest('.edit-role-modal');
    if (!modal) {
      return;
    }

    const selectAll = modal.querySelector('[data-role-select-all]');
    const checkboxes = modal.querySelectorAll('.role-permission-checkbox');
    if (!selectAll || !checkboxes.length) {
      return;
    }

    selectAll.checked = Array.from(checkboxes).every((checkbox) => checkbox.checked);
  });

  document.addEventListener('change', function (event) {
    if (!event.target || typeof event.target.closest !== 'function') {
      return;
    }

    const modal = event.target.closest('.add-role-modal, .edit-role-modal');
    if (!modal) {
      return;
    }

    if (event.target.matches('[data-role-select-all]')) {
      modal.querySelectorAll('.role-permission-checkbox').forEach((checkbox) => {
        checkbox.checked = event.target.checked;
      });
      return;
    }

    if (event.target.matches('.role-permission-checkbox')) {
      const selectAll = modal.querySelector('[data-role-select-all]');
      const checkboxes = modal.querySelectorAll('.role-permission-checkbox');
      if (!selectAll || !checkboxes.length) {
        return;
      }

      selectAll.checked = Array.from(checkboxes).every((checkbox) => checkbox.checked);
    }
  });
</script>
@endsection
