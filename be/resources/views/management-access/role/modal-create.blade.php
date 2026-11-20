<div class="modal fade add-role-modal" id="addRoleModal" tabindex="-1" aria-hidden="true" aria-labelledby="addRoleModal-label">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleModal-label">Tambah Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('role.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="text-center mb-6">
            <h4 class="mb-2">Tambah Role</h4>
            <p>Atur izin untuk role baru.</p>
          </div>
          <div class="row">
            <div class="col-12 mb-6">
              <label class="form-label" for="name-create">Nama Role</label>
              <input type="text" id="name-create" name="name"
                class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama role"
                value="{{ old('name') }}" required />
              @error('name')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 mb-6">
              <label class="form-label" for="guard_name-create">Nama Guard</label>
              <input type="text" id="guard_name-create" name="guard_name"
                class="form-control @error('guard_name') is-invalid @enderror" placeholder="Masukkan nama guard"
                value="{{ old('guard_name', 'web') }}" />
              @error('guard_name')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <div class="table-responsive">
                <table class="table mb-0">
                  <tbody>
                    <tr>
                      <td colspan="2" class="text-nowrap fw-medium text-heading">
                        <h5 class="mb-0">Izin Role</h5>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-nowrap fw-medium text-heading">Akses Administrator <i class="bx bx-info-circle"
                          data-bs-toggle="tooltip" data-bs-placement="top" title="Memberikan akses penuh ke sistem"></i>
                      </td>
                      <td>
                        <div class="d-flex justify-content-end flex-wrap gap-2">
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAll-create" data-role-select-all />
                            <label class="form-check-label" for="selectAll-create">Pilih Semua</label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @foreach ($permissions as $key => $group)
                    <tr>
                      <td class="text-nowrap fw-medium text-heading">{{ $groupLabels[$key] ?? Str::headline($key) }}</td>
                      <td>
                        <div class="d-flex justify-content-end flex-wrap gap-3">
                          @forelse ($group as $permission)
                          @php
                            $action = Str::after($permission->name, $key . '.');
                          @endphp
                          <div class="form-check mb-0">
                            <input class="form-check-input role-permission-checkbox" type="checkbox" name="permissions[]"
                              value="{{ $permission->name }}" id="{{ $permission->name }}-create" />
                            <label class="form-check-label" for="{{ $permission->name }}-create">
                              {{ $actionLabels[$action] ?? Str::headline($action) }}
                            </label>
                          </div>
                          @empty
                          <span class="text-muted">Belum ada izin pada grup ini.</span>
                          @endforelse
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
