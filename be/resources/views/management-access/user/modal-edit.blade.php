<div id="modal-form-edit-user-{{ $user->uuid }}" class="modal fade modal-form-user-edit" tabindex="-1"
  aria-labelledby="modal-form-edit-user-{{ $user->uuid }}-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('user.update', $user->uuid) }}" method="post">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="modal-form-edit-user-{{ $user->uuid }}-label">Ubah Pengguna ({{ $user->name }})</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name-{{ $user->uuid }}" class="form-label fw-semibold">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
              id="name-{{ $user->uuid }}" placeholder="Nama pengguna" name="name" value="{{ $user->name }}">
            @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="email-{{ $user->uuid }}" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
              id="email-{{ $user->uuid }}" placeholder="Email" name="email" value="{{ $user->email }}">
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="role-{{ $user->uuid }}" class="form-label fw-semibold">Nama Role</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role-{{ $user->uuid }}" name="role"
              data-choices data-choices-removeItem>
              <option value="" disabled selected>Pilih role</option>
              @foreach ($roles as $role)
              <option @selected($user->hasRole($role->name)) value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
            @error('role')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="form-check form-switch form-switch-md">
              <input class="form-check-input" type="checkbox" id="verified-{{ $user->uuid }}" name="verified"
                value="1" @checked(!blank($user->email_verified_at))>
              <label class="form-check-label fw-semibold" for="verified-{{ $user->uuid }}">Terverifikasi</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
      </form>
    </div>
  </div>
</div>
