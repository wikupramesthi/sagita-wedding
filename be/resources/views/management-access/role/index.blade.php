@extends('layouts.app')
@section('title', 'Daftar Role')
@section('breadcrumb')
<x-breadcrumb title="Daftar Role" page="Role dan Akses" active="Daftar Role"
  route="{{ route('role.index') }}" />
@endsection
@section('content')

<section class="section mt-2">
  <div class="card mb-0">
    <div class="card-body py-3">
      <form action="{{ route('role.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-8 col-xl-7">
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
              placeholder="Cari role atau pengguna" aria-label="Cari role atau pengguna">
          </div>
        </div>
        <div class="col-auto d-flex gap-2">
          <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i> Cari</button>
          <a href="{{ route('role.index') }}" class="btn btn-sm btn-secondary">Atur Ulang</a>
        </div>
      </form>
    </div>
  </div>

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
    <p class="text-body-secondary small mb-0">Role menentukan menu dan fitur yang dapat diakses pengguna.</p>
    <span class="text-body-secondary small">{{ $roles->total() }} role terdaftar</span>
  </div>

  <div class="row g-3 role-card-grid">
    @forelse ($roles as $role)
    <div class="col-xl-4 col-lg-6">
      <div class="card h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div class="min-w-0">
              <h5 class="fw-semibold mb-1 text-truncate" title="{{ $role->name }}">{{ $role->name }}</h5>
              <div class="text-body-secondary small">
                {{ $role->users->count() }} pengguna &middot; {{ $role->permissions->count() }} akses
              </div>
            </div>
            @can('role.destroy')
            <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0"
              data-delete-form="{{ $role->id }}" data-delete-name="Role {{ $role->name }}"
              aria-label="Hapus role {{ $role->name }}" title="Hapus role {{ $role->name }}">
              <i class="bi bi-trash3"></i>
            </button>
            @endcan
          </div>

          <div class="d-flex justify-content-between align-items-center gap-2 mt-auto">
            <span class="badge bg-light-secondary">Role</span>
            @can('role.update')
            <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal"
              data-bs-target="#editRoleModal-{{ $role->id }}" aria-label="Ubah role {{ $role->name }}"
              title="Ubah role {{ $role->name }}">Ubah Role</button>
            @include('management-access.role.modal-edit')
            @endcan
          </div>
        </div>

        <form id="deleteForm_{{ $role->id }}" action="{{ route('role.destroy', $role->id) }}" method="POST">
          @method('DELETE')
          @csrf
        </form>
      </div>
    </div>
    @empty
    <div class="col-12">
      <div class="alert alert-info mb-0">Belum ada role. Tambahkan role baru untuk mengatur akses pengguna.</div>
    </div>
    @endforelse

    @can('role.store')
    <div class="col-xl-4 col-lg-6">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center gap-3 py-4">
          <div class="d-flex align-items-center justify-content-center rounded-circle bg-light-secondary"
            style="width:52px;height:52px;">
            <i class="bi bi-plus-lg fs-5"></i>
          </div>
          <div>
            <h6 class="fw-semibold mb-1">Tambah Role</h6>
            <p class="text-body-secondary small mb-0">Buat role baru jika belum tersedia.</p>
          </div>
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal"
            aria-label="Tambah role baru" title="Tambah role baru">
            <i class="bi bi-plus-lg me-1"></i> Tambah Role
          </button>
        </div>
      </div>
    </div>
    @endcan
  </div>

  @if ($roles->hasPages())
  <div class="d-flex justify-content-center pt-1">{{ $roles->links('pagination::minimal') }}</div>
  @endif
</section>

<section class="section mt-4">
  <div class="card mb-0">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h4 class="fw-semibold mb-0 text-body card-title">Daftar Pengguna</h4>
      <span class="text-body-secondary small">{{ $users->total() }} pengguna terdaftar</span>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-border-bottom-0 align-middle mb-0" id="role-users-table">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Pengguna</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col">Status</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td class="fw-medium text-heading">{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                @foreach ($user->roles as $role)
                <span class="badge bg-light-info me-1 mb-1">{{ $role->name }}</span>
                @endforeach
              </td>
              <td>
                @if (!blank($user->email_verified_at))
                <span class="badge bg-light-primary">Aktif</span>
                @else
                <span class="badge bg-light-danger">Nonaktif</span>
                @endif
              </td>
              <td>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  @can('user.update')
                  <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-form-edit-user-{{ $user->uuid }}"
                    aria-label="Ubah pengguna {{ $user->name }}" title="Ubah pengguna {{ $user->name }}">
                    <i class="bi bi-pencil-square me-1"></i> Ubah
                  </button>
                  @include('management-access.user.modal-edit')
                  @endcan

                  @can('user.destroy')
                  <button type="button" class="btn btn-sm btn-outline-danger" data-delete-form="{{ $user->uuid }}"
                    data-delete-name="Pengguna {{ $user->name }}" aria-label="Hapus pengguna {{ $user->name }}"
                    title="Hapus pengguna {{ $user->name }}">
                    <i class="bi bi-x-square me-1"></i> Hapus
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
            @empty
            <tr>
              <td colspan="6" class="text-center text-body-secondary py-4">Belum ada pengguna.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($users->hasPages())
      <div class="d-flex justify-content-center pt-3 mt-1 border-top">{{ $users->links('pagination::minimal') }}</div>
      @endif
    </div>
  </div>
</section>
<!-- / Content -->

@include('management-access.role.modal-create')

@push('after-script')
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
@endpush
@endsection
