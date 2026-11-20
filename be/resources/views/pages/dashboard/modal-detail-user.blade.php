<div class="modal fade" id="cekProfilModal-{{ $user->uuid }}" tabindex="-1"
    aria-labelledby="cekProfilModalLabel-{{ $user->uuid }}" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 px-4 pt-4 pb-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="rounded-circle bg-primary-subtle overflow-hidden d-flex align-items-center justify-content-center"
                        style="width:52px;height:52px;">
                        @if ($user->avatar)
                        <img src="{{ \Illuminate\Support\Str::startsWith($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                            alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <span class="fw-bold text-primary fs-5">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        @endif
                    </div>

                    <div>
                        <h5 class="modal-title fw-semibold mb-1" id="cekProfilModalLabel-{{ $user->uuid }}">
                            Ringkasan Calon Pelanggan
                        </h5>

                        <small class="text-muted">
                            {{ $user->name }}
                        </small>
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>

            </div>

            <div class="modal-body px-4 pb-4">

                <div class="mb-4">

                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">
                            Informasi Pribadi
                        </h6>

                        <small class="text-muted">
                            Informasi dasar calon pelanggan
                        </small>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="bg-primary-subtle rounded-3 p-3 h-100">
                                <small class="text-primary d-block mb-1">Nama Lengkap</small>
                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-info-subtle rounded-3 p-3 h-100">
                                <small class="text-info-emphasis d-block mb-1">Alamat Email</small>
                                <div class="fw-semibold text-dark text-break">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-success-subtle rounded-3 p-3 h-100">
                                <small class="text-success d-block mb-1">Nomor WhatsApp</small>
                                <div class="fw-semibold text-dark">{{ $user->no_hp ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-warning-subtle rounded-3 p-3 h-100">
                                <small class="text-warning-emphasis d-block mb-2">Status Pemesanan</small>

                                @if ($user->userPackages->isNotEmpty())
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Sudah Memesan {{ $user->userPackages->count() }} Paket
                                </span>
                                @else
                                <span class="badge rounded-pill bg-secondary px-3 py-2">
                                    <i class="bi bi-dash-circle-fill me-1"></i>
                                    Belum Melakukan Pemesanan
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                <div class="bg-light border rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Status Calon Pelanggan</h6>
                            <small class="text-muted">{{ $user->prospect_status_description }}</small>
                        </div>
                        <span class="badge rounded-pill px-3 py-2 {{ $user->prospect_status_badge_class }}">
                            {{ $user->prospect_status_label }}
                        </span>
                    </div>

                    <form action="{{ route('pengguna.update-status', $user) }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        @method('PUT')

                        <div class="col-md-9">
                            <label class="form-label small text-muted mb-1">Perbarui status</label>
                            <select name="prospect_status" class="form-select form-select-sm">
                                @foreach (App\Models\User::PROSPECT_STATUS_LABELS as $key => $label)
                                <option value="{{ $key }}" title="{{ App\Models\User::PROSPECT_STATUS_DESCRIPTIONS[$key] }}"
                                    @selected($user->prospect_status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-save"></i> Simpan Status
                            </button>
                        </div>
                    </form>

                    @if ($user->prospect_status_updated_at)
                    <small class="text-muted d-block mt-2">
                        Diperbarui pada {{ $user->prospect_status_updated_at->translatedFormat('d F Y H:i') }}
                    </small>
                    @endif
                </div>

                <hr class="my-4">

                <div>

                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div>
                            <h6 class="fw-semibold mb-1">Status Pemesanan</h6>
                            <small class="text-muted">Paket & layanan yang dibeli</small>
                        </div>

                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                            {{ $user->userPackages->count() }} Paket
                        </span>

                    </div>

                    @forelse ($user->userPackages as $userPackage)
                    <div class="bg-light border rounded-3 p-3 mb-3">

                        <div class="d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center"
                                    style="width: 42px; height: 42px;">
                                    <i class="bi bi-box-seam text-primary"></i>
                                </div>

                                <div>
                                    <div class="fw-semibold">
                                        {{ $userPackage->package->name ?? 'Paket tidak diketahui' }}
                                    </div>
                                    <small class="text-muted">Paket Membership</small>
                                </div>

                            </div>

                            @if ($userPackage->status === 'active')
                            <span class="badge rounded-pill bg-success px-3 py-2">Aktif</span>
                            @elseif ($userPackage->status === 'expired')
                            <span class="badge rounded-pill bg-danger px-3 py-2">Kedaluwarsa</span>
                            @elseif ($userPackage->status === 'cancelled')
                            <span class="badge rounded-pill bg-secondary px-3 py-2">Dibatalkan</span>
                            @else
                            <span class="badge rounded-pill bg-secondary px-3 py-2">{{ ucfirst($userPackage->status) }}</span>
                            @endif

                        </div>

                        <div class="row g-3 mt-3">

                            <div class="col-md-4">
                                <div class="bg-white rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Sisa Kuota</small>
                                    <div class="fw-semibold">
                                        @if (is_null($userPackage->quota))
                                        <span class="text-primary">
                                            <i class="bi bi-infinity me-1"></i>
                                            Tanpa Batas
                                        </span>
                                        @else
                                        {{ $userPackage->quota }} Kelas
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="bg-white rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Tanggal Mulai</small>
                                    <div class="fw-semibold">
                                        {{ $userPackage->started_at ? $userPackage->started_at->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="bg-white rounded-3 p-3 h-100">
                                    <small class="text-muted d-block mb-1">Tanggal Kedaluwarsa</small>
                                    <div class="fw-semibold">
                                        @if ($userPackage->expired_at)
                                        {{ $userPackage->expired_at->translatedFormat('d M Y') }}
                                        @else
                                        <span class="text-primary">Tanpa Batas</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    @empty
                    <div class="rounded-3 text-center py-4" style="border: 1px dashed var(--bs-border-color);">
                        <div class="mb-2">
                            <i class="bi bi-box-seam fs-3 text-secondary"></i>
                        </div>
                        <div class="fw-semibold mb-1">Belum Melakukan Pemesanan</div>
                        <small class="text-muted">Calon pelanggan ini belum melakukan pemesanan layanan Wedding Organizer Sagita.</small>
                    </div>
                    @endforelse

                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-danger border px-4" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>

    </div>

</div>