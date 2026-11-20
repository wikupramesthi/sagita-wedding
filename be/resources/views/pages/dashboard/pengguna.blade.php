@extends('layouts.app')
@section('title', 'Data Prospek')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Data Prospek" page="Data Prospek" active="Calon Pelanggan" route="{{ route('pengguna.index') }}" />
@endsection

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

<section class="section mt-2">
    <div class="row mb-4 g-3 ml-stats">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-group"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['total']) }}</div>
                            <div class="ml-stat-label">Total Calon Pelanggan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach (\App\Models\User::PROSPECT_STATUS_LABELS as $key => $label)
        @if ($key !== 'inactive')
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon {{ [
                                    'new_registered' => 'ic-info',
                                    'interested' => 'ic-danger',
                                    'consultation' => 'ic-violet',
                                    'negotiation' => 'ic-warning',
                                    'customer' => 'ic-success',
                                ][$key] }}">
                            <i class="bx {{ [
                                        'new_registered' => 'bx-user-plus',
                                        'interested' => 'bx-heart',
                                        'consultation' => 'bx-chat',
                                        'negotiation' => 'bx-comment-detail',
                                        'customer' => 'bx-check-circle',
                                    ][$key] }}"></i>
                        </div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats[$key]) }}</div>
                            <div class="ml-stat-label">{{ $label }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h6 class="fw-semibold mb-0">Sumber Informasi</h6>
                    <small class="text-muted">Jumlah calon pelanggan berdasarkan asal lead. Klik untuk memfilter.</small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if ($sumber !== '')
                    <a href="{{ route('pengguna.index', request()->except('page', 'sumber')) }}"
                        class="badge rounded-pill text-decoration-none bg-primary-subtle text-primary px-3 py-2">
                        <i class="bi bi-funnel-fill me-1"></i>{{ $sourceOptions[$sumber] }}<i class="bi bi-x ms-1"></i>
                    </a>
                    @endif
                    <span class="badge rounded-pill bg-light text-muted px-3 py-2">
                        Total {{ number_format(array_sum($stats['sources'])) }} data
                    </span>
                </div>
            </div>
            <div class="row g-2">
                @foreach ($sourceOptions as $key => $label)
                <div class="col-6 col-sm-4 col-lg-3">
                    <a href="{{ route('pengguna.index', ['sumber' => $key]) }}" class="text-decoration-none d-block h-100"
                        aria-label="Filter sumber {{ $label }}">
                        <div class="ml-source-chip {{ $sumber === $key ? 'active' : '' }}">
                            <span class="ml-source-icon"><i class="bx {{ \App\Models\User::SOURCE_ICONS[$key] }}"></i></span>
                            <span class="ml-source-label">{{ $label }}</span>
                            <span class="ml-source-count">{{ number_format($stats['sources'][$key]) }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="fw-normal mb-0 text-body">Calon Pelanggan</h4>
                    <small class="text-muted">Data calon konsumen dan status tindak lanjutnya</small>
                </div>
                <span class="badge rounded-pill bg-primary-subtle text-primary">
                    {{ $users->total() }} Data
                </span>
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('pengguna.index') }}" class="row g-2 mb-3">
                <div class="col-12 col-md-4">
                    <input type="search" name="q" class="form-control" placeholder="Cari nama, email, atau WhatsApp"
                        value="{{ $q }}">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\User::PROSPECT_STATUS_LABELS as $key => $label)
                        <option value="{{ $key }}" @selected($status===$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="sumber" class="form-select">
                        <option value="">Semua sumber</option>
                        @foreach ($sourceOptions as $key => $label)
                        <option value="{{ $key }}" @selected($sumber===$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"
                        aria-label="Dari tanggal">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"
                        aria-label="Sampai tanggal">
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('pengguna.index') }}" class="btn btn-light" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:45px;">No.</th>
                            <th>Nama Calon Pelanggan</th>
                            <th>Kontak</th>
                            <th>Tanggal Daftar</th>
                            <th>Status Calon Pelanggan</th>
                            <th>Lead dari Mana</th>
                            <th>Paket Pemesanan</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if ($user->email_verified_at)
                                <div class="small text-success"><i class="bi bi-patch-check-fill"></i> Email terverifikasi</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $user->email }}</div>
                                <div class="small text-muted">{{ $user->no_hp ?? '-' }}</div>
                            </td>
                            <td>{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $user->prospect_status_badge_class }} mb-1">
                                    {{ $user->prospect_status_label }}
                                </span>
                                <form action="{{ route('pengguna.update-status', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="prospect_status" class="form-select form-select-sm"
                                        onchange="this.form.submit()" aria-label="Ubah status calon pelanggan">
                                        @foreach (\App\Models\User::PROSPECT_STATUS_LABELS as $key => $label)
                                        <option value="{{ $key }}"
                                            title="{{ \App\Models\User::PROSPECT_STATUS_DESCRIPTIONS[$key] }}"
                                            @selected($user->prospect_status === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <div class="small text-muted mt-1" style="white-space: normal;">
                                    {{ $user->prospect_status_description }}
                                </div>
                            </td>
                            <td>{{ $user->sumber_informasi_label }}</td>
                            <td>
                                @if ($user->latestOrder?->package)
                                <div class="fw-semibold">{{ $user->latestOrder->package->name }}</div>
                                <small class="text-muted">{{ ucfirst($user->latestOrder->status ?? '-') }}</small>
                                @else
                                <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info text-white" title="Detail"
                                    data-bs-toggle="modal" data-bs-target="#cekProfilModal-{{ $user->uuid }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @include('pages.dashboard.modal-detail-user', ['user' => $user])
                            </td>
                        </tr>
                        @empty
                        <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada calon pelanggan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
</section>
@endsection