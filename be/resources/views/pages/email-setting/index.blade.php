@extends('layouts.app')
@section('title', 'Pengaturan Email')

@section('breadcrumb')
<x-breadcrumb title="Pengaturan Email" page="Pengaturan Sistem" active="Pengaturan Email" route="{{ route('email-setting.index') }}" />
@endsection

@section('content')
<section class="section">
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

    <div class="row g-4">
        <div class="col-lg-8">
            <form id="email-setting-form" action="{{ route('email-setting.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-hdd-network me-2"></i> Koneksi SMTP</h6>
                        <small class="text-body-secondary">Digunakan oleh seluruh notifikasi email sistem.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="mail_driver" class="form-label fw-semibold">Metode Pengiriman</label>
                                <select id="mail_driver" name="mail_driver" class="form-select @error('mail_driver') is-invalid @enderror">
                                    @foreach (['smtp' => 'SMTP', 'log' => 'Log (simulasi)', 'array' => 'Array (testing)'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('mail_driver', $setting->mail_driver) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('mail_driver')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="mail_timeout" class="form-label fw-semibold">Batas Waktu (detik)</label>
                                <input type="number" id="mail_timeout" name="mail_timeout"
                                    class="form-control @error('mail_timeout') is-invalid @enderror"
                                    value="{{ old('mail_timeout', $setting->mail_timeout) }}" min="5" max="300">
                                @error('mail_timeout')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-8" data-smtp-field>
                                <label for="mail_host" class="form-label fw-semibold">Host SMTP</label>
                                <input type="text" id="mail_host" name="mail_host"
                                    class="form-control @error('mail_host') is-invalid @enderror"
                                    value="{{ old('mail_host', $setting->mail_host) }}" placeholder="smtp.example.com"
                                    autocomplete="off">
                                @error('mail_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4" data-smtp-field>
                                <label for="mail_port" class="form-label fw-semibold">Port</label>
                                <input type="number" id="mail_port" name="mail_port"
                                    class="form-control @error('mail_port') is-invalid @enderror"
                                    value="{{ old('mail_port', $setting->mail_port) }}" placeholder="587" min="1" max="65535">
                                @error('mail_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6" data-smtp-field>
                                <label for="mail_encryption" class="form-label fw-semibold">Enkripsi</label>
                                <select id="mail_encryption" name="mail_encryption" class="form-select @error('mail_encryption') is-invalid @enderror">
                                    @foreach (['' => 'Tidak ada', 'tls' => 'TLS', 'ssl' => 'SSL'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('mail_encryption', $setting->mail_encryption) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('mail_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6" data-smtp-field>
                                <label for="mail_username" class="form-label fw-semibold">Username SMTP</label>
                                <input type="text" id="mail_username" name="mail_username"
                                    class="form-control @error('mail_username') is-invalid @enderror"
                                    value="{{ old('mail_username', $setting->mail_username) }}" placeholder="noreply@example.com"
                                    autocomplete="off">
                                @error('mail_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12" data-smtp-field>
                                <label for="mail_password" class="form-label fw-semibold">Password SMTP</label>
                                <input type="password" id="mail_password" name="mail_password"
                                    class="form-control @error('mail_password') is-invalid @enderror"
                                    placeholder="Kosongkan untuk mempertahankan password lama" autocomplete="new-password">
                                @error('mail_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    @if ($setting->hasPassword())
                                    Password tersimpan: <strong>&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</strong>. Isi hanya jika ingin mengganti.
                                    @else
                                    Belum ada password yang disimpan.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-badge me-2"></i> Identitas Pengirim</h6>
                        <small class="text-body-secondary">Tampil sebagai pengirim pada email notifikasi.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="from_address" class="form-label fw-semibold">Alamat Pengirim</label>
                                <input type="email" id="from_address" name="from_address"
                                    class="form-control @error('from_address') is-invalid @enderror"
                                    value="{{ old('from_address', $setting->from_address) }}" placeholder="noreply@example.com">
                                @error('from_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="from_name" class="form-label fw-semibold">Nama Pengirim</label>
                                <input type="text" id="from_name" name="from_name"
                                    class="form-control @error('from_name') is-invalid @enderror"
                                    value="{{ old('from_name', $setting->from_name) }}" placeholder="Sagita Wedding">
                                @error('from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info mb-0 small">
                                    Nilai awal diambil dari <a href="{{ route('website-identity.index') }}">Identitas Website</a>
                                    ({{ $identity->email ?: 'belum diisi' }} &middot; {{ $identity->site_name ?: 'belum diisi' }}).
                                    Perubahan identitas akan ikut menyinkronkan nilai ini selama belum Anda ubah manual.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-sliders2 me-2"></i> Status &amp; Simpan</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-switch-md mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active"
                            value="1" form="email-setting-form" @checked(old('is_active', $setting->is_active))>
                        <label class="form-check-label fw-semibold" for="is_active">Gunakan pengaturan ini</label>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-body-secondary small">Metode aktif</span>
                        <span class="badge bg-light-primary">{{ strtoupper($setting->mail_driver) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-body-secondary small">Diperbarui</span>
                        <span class="small">{{ $setting->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="d-grid gap-2">
                        @can('email-setting.update')
                        <button type="submit" form="email-setting-form" class="btn btn-primary fw-bold py-2 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Pengaturan
                        </button>
                        @endcan
                        <a href="{{ route('website-identity.index') }}" class="btn btn-light border">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-send me-2"></i> Kirim Email Uji</h6>
                    <small class="text-body-secondary">Pastikan konfigurasi bekerja sebelum dipakai sistem.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('email-setting.test') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient" class="form-label fw-semibold">Alamat Tujuan</label>
                            <input type="email" id="recipient" name="recipient"
                                class="form-control @error('recipient') is-invalid @enderror"
                                value="{{ old('recipient') }}" placeholder="nama@example.com">
                            @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @can('email-setting.test')
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-send me-1"></i> Kirim Uji
                        </button>
                        @else
                        <p class="text-body-secondary small mb-0">Anda tidak memiliki izin untuk mengirim email uji.</p>
                        @endcan
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 identity-tip">
                <div class="identity-tip__glow"></div>
                <div class="identity-tip__body">
                    <div class="identity-tip__head">
                        <span class="identity-tip__icon"><i class="bi bi-stars"></i></span>
                        <div>
                            <h6 class="mb-0">Informasi Penting</h6>
                            <small>Koneksi email seluruh sistem</small>
                        </div>
                    </div>
                    <ul class="identity-tip__list">
                        <li>
                            <span class="tip-bullet"><i class="bi bi-bell"></i></span>
                            <div>
                                <strong>Notifikasi</strong>
                                <span>Tindak lanjut konsultasi, reset password, dan verifikasi email memakai setting ini.</span>
                            </div>
                        </li>
                        <li>
                            <span class="tip-bullet"><i class="bi bi-link-45deg"></i></span>
                            <div>
                                <strong>Identitas Website</strong>
                                <span>Nama dan alamat pengirim mengikuti identitas website sampai dioverride manual.</span>
                            </div>
                        </li>
                        <li>
                            <span class="tip-bullet"><i class="bi bi-shield-check"></i></span>
                            <div>
                                <strong>Password SMTP</strong>
                                <span>Kolom kata sandi sengaja dikosongkan. Isi hanya jika ingin mengganti kata sandi tersimpan.</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('after-script')
<script>
    const emailDriver = document.getElementById('mail_driver');
    const smtpFields = document.querySelectorAll('[data-smtp-field] input, [data-smtp-field] select');

    const syncSmtpFields = () => {
        const disabled = emailDriver.value !== 'smtp';
        smtpFields.forEach((field) => {
            field.disabled = disabled;
            field.closest('[data-smtp-field]')?.classList.toggle('d-none', disabled);
        });
    };

    emailDriver.addEventListener('change', syncSmtpFields);
    syncSmtpFields();
</script>
@endpush
