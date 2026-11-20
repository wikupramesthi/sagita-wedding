@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')

@section('breadcrumb')
<x-breadcrumb title="Riwayat Pemesanan" page="Pemesanan Saya" active="Daftar Pesanan"
    route="{{ route('orders.index') }}" />
@endsection

@php
$statusLabels = [
'pending' => 'Menunggu',
'paid' => 'Lunas',
'failed' => 'Gagal',
'expired' => 'Kedaluwarsa',
'cancelled' => 'Dibatalkan',
];
$typeLabels = [
'package' => 'Paket',
'class' => 'Kelas',
];
@endphp

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="row g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-receipt"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->total ?? 0) }}</div>
                            <div class="ml-stat-label">Total Pesanan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-hourglass"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->pending ?? 0) }}</div>
                            <div class="ml-stat-label">Menunggu Verifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->paid ?? 0) }}</div>
                            <div class="ml-stat-label">Lunas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-x-circle"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats->others ?? 0) }}</div>
                            <div class="ml-stat-label">Gagal / Kedaluwarsa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-wallet"></i></div>
                        <div>
                            <div class="ml-stat-value">Rp {{ number_format($stats->revenue ?? 0, 0, ',', '.') }}</div>
                            <div class="ml-stat-label">Pendapatan (Lunas)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ml-toolbar security-toolbar mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="{{ route('orders.index') }}"
                    class="security-filter-form d-flex flex-wrap align-items-end gap-3">
                    @if ($isAdmin)
                    <div>
                        <label class="security-filter-label" for="filterSearch">Cari</label>
                        <input type="text" name="search" id="filterSearch" class="form-control"
                            placeholder="No. pesanan, nama, email..." value="{{ $filters['search'] ?? '' }}" style="min-width: 200px;">
                    </div>
                    @endif
                    <div>
                        <label class="security-filter-label" for="filterStatus">Status</label>
                        <select name="status" id="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '' )===$value)>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterType">Tipe</label>
                        <select name="type" id="filterType" class="form-select">
                            <option value="">Semua Tipe</option>
                            @foreach ($typeLabels as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['type'] ?? '' )===$value)>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterProof">Bukti Transfer</label>
                        <select name="proof" id="filterProof" class="form-select">
                            <option value="">Semua</option>
                            <option value="uploaded" @selected(($filters['proof'] ?? '' )==='uploaded' )>Sudah diunggah</option>
                            <option value="missing" @selected(($filters['proof'] ?? '' )==='missing' )>Belum diunggah</option>
                        </select>
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterStart">Dari Tanggal</label>
                        <input type="date" name="start_date" id="filterStart" class="form-control"
                            value="{{ $filters['start_date'] ?? '' }}">
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterEnd">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="filterEnd" class="form-control"
                            value="{{ $filters['end_date'] ?? '' }}">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-clockwise"></i> Atur Ulang
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Daftar Pesanan</h5>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($orders->total()) }}
                    pesanan</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap flex-shrink-0">
                <p class="text-muted small mb-0 d-none d-xl-block">
                    @if ($isAdmin)
                    Semua pesanan keanggotaan dan status pembayaran.
                    @else
                    Pesanan keanggotaan Anda dan status pembayaran.
                    @endif
                </p>
                <a href="{{ route('orders.report', request()->query()) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="bi bi-file-text me-1"></i>Pratinjau Laporan
                </a>
                <a href="{{ route('orders.exportCsv', request()->query()) }}" class="btn btn-sm btn-outline-success text-nowrap">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Ekspor CSV
                </a>
                <a href="{{ route('orders.exportPdf', request()->query()) }}" class="btn btn-sm btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Ekspor PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            @if ($isAdmin)
            <form id="bulkForm" method="POST" action="{{ route('orders.bulk') }}">
                @csrf
                <input type="hidden" name="action" id="bulkAction" value="">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="text-muted small" id="bulkCount">Pilih pesanan menunggu untuk tindakan massal.</span>
                    <button type="button" class="btn btn-sm btn-success" id="bulkApprove">
                        <i class="bi bi-check-lg me-1"></i>Setujui Terpilih
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="bulkReject">
                        <i class="bi bi-x-lg me-1"></i>Tolak Terpilih
                    </button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table security-table">
                        <thead>
                            <tr>
                                @if ($isAdmin)
                                <th style="width:36px;">
                                    <input class="form-check-input" type="checkbox" id="checkAll" aria-label="Pilih semua">
                                </th>
                                @endif
                                <th>Pesanan</th>
                                @if ($isAdmin)
                                <th>Pelanggan</th>
                                @endif
                                <th>Paket</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($orders as $order)
                            <tr>
                                @if ($isAdmin)
                                <td>
                                    @if ($order->status === 'pending')
                                    <input class="form-check-input row-check" type="checkbox" name="ids[]"
                                        value="{{ $order->uuid }}" aria-label="Pilih {{ $order->order_number }}">
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <span class="d-block fw-semibold">{{ $order->order_number }}</span>
                                    <small class="log-muted">{{ strtoupper($typeLabels[$order->type] ?? $order->type) }}</small>
                                </td>
                                @if ($isAdmin)
                                <td>
                                    <span class="d-block fw-semibold">{{ $order->user?->name ?? '-' }}</span>
                                    <small class="log-muted">{{ $order->user?->email ?? '-' }}</small>
                                </td>
                                @endif
                                <td>
                                    <span class="d-block fw-semibold">{{ $order->package?->name ?? 'Kelas Tunggal' }}</span>
                                    @if ($order->packageOption?->name)
                                    <small class="log-muted">{{ $order->packageOption->name }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($order->status === 'paid')
                                    <span class="badge bg-success-subtle text-success">Lunas</span>
                                    @elseif ($order->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning">Menunggu</span>
                                    @elseif ($order->status === 'failed')
                                    <span class="badge bg-danger-subtle text-danger">Gagal</span>
                                    @elseif ($order->status === 'expired')
                                    <span class="badge bg-secondary-subtle text-secondary">Kedaluwarsa</span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->proof_image_path)
                                    <a href="{{ route('orders.show', $order->uuid) }}"
                                        class="badge bg-info-subtle text-info text-decoration-none"
                                        title="Diunggah {{ $order->proof_uploaded_at?->format('d M Y H:i') ?? '' }} — klik untuk verifikasi">
                                        <i class="bi bi-file-earmark-check me-1"></i>Diunggah
                                    </a>
                                    @else
                                    <span class="log-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ $order->created_at?->translatedFormat('d F Y') ?? '-' }}</span>
                                    <small class="log-muted">{{ $order->created_at?->format('H:i') ?? '' }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('orders.show', $order->uuid) }}"
                                            class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if ($isAdmin && $order->status === 'pending')
                                        <form method="POST" action="{{ route('orders.approve', $order->uuid) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                title="Setujui pesanan" onclick="return confirm('Setujui pesanan {{ $order->order_number }}?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('orders.reject', $order->uuid) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Tolak pesanan" onclick="return confirm('Tolak pesanan {{ $order->order_number }}?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 9 : 7 }}" class="text-center text-muted py-4">
                                    <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                                    Tidak ada pesanan ditemukan. Coba ubah filter pencarian.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($isAdmin)
            </form>
            @endif

            @if ($orders->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $orders->firstItem() }}</strong> –
                    <strong>{{ $orders->lastItem() }}</strong> dari
                    <strong>{{ $orders->total() }}</strong> pesanan
                </div>
                <div>{{ $orders->links('pagination::bootstrap-5') }}</div>
            </div>
            @endif
        </div>
    </div>
</section>

@push('after-script')
<script>
    (function() {
        var checkAll = document.getElementById('checkAll');
        var rows = Array.prototype.slice.call(document.querySelectorAll('.row-check'));
        var count = document.getElementById('bulkCount');

        function updateCount() {
            var selected = rows.filter(function(box) {
                return box.checked;
            }).length;
            if (count) {
                count.textContent = selected ?
                    selected + ' pesanan dipilih untuk tindakan massal.' :
                    'Pilih pesanan menunggu untuk tindakan massal.';
            }
            if (checkAll) {
                checkAll.checked = rows.length > 0 && selected === rows.length;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                rows.forEach(function(box) {
                    box.checked = checkAll.checked;
                });
                updateCount();
            });
        }

        rows.forEach(function(box) {
            box.addEventListener('change', updateCount);
        });

        function submitBulk(action, message) {
            var selected = rows.filter(function(box) {
                return box.checked;
            }).length;
            if (!selected) {
                alert('Pilih minimal satu pesanan menunggu.');
                return;
            }
            if (!confirm(message.replace(':count', selected))) {
                return;
            }
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkForm').submit();
        }

        var approve = document.getElementById('bulkApprove');
        var reject = document.getElementById('bulkReject');
        if (approve) {
            approve.addEventListener('click', function() {
                submitBulk('approve', 'Setujui :count pesanan terpilih?');
            });
        }
        if (reject) {
            reject.addEventListener('click', function() {
                submitBulk('reject', 'Tolak :count pesanan terpilih?');
            });
        }
    })();
</script>
@endpush

@endsection