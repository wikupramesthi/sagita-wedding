<div class="modal fade edit-role-modal" id="editRoleModal-{{ $role->id }}" tabindex="-1" aria-hidden="true"
  aria-labelledby="editRoleModal-{{ $role->id }}-label">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoleModal-{{ $role->id }}-label">Ubah Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('role.update', $role->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="text-center mb-6">
            <h4 class="mb-2">Ubah Role</h4>
            <p>Atur izin untuk role {{ $role->name }}.</p>
          </div>
          <div class="row">
            <div class="col-12 mb-6">
              <label class="form-label" for="name-{{ $role->id }}">Nama Role</label>
              <input type="text" id="name-{{ $role->id }}" name="name"
                class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama role"
                value="{{ old('name', $role->name) }}" required />
              @error('name')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 mb-6">
              <label class="form-label" for="guard_name-{{ $role->id }}">Nama Guard</label>
              <input type="text" id="guard_name-{{ $role->id }}" name="guard_name"
                class="form-control @error('guard_name') is-invalid @enderror" placeholder="Masukkan nama guard"
                value="{{ old('guard_name', $role->guard_name) }}" />
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
                            <input class="form-check-input" type="checkbox" id="selectAll-{{ $role->id }}" data-role-select-all />
                            <label class="form-check-label" for="selectAll-{{ $role->id }}">Pilih Semua</label>
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
                              value="{{ $permission->name }}" id="{{ $permission->name }}-{{ $role->id }}"
                              @checked($role->permissions->contains('name', $permission->name)) />
                            <label class="form-check-label" for="{{ $permission->name }}-{{ $role->id }}">
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
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
