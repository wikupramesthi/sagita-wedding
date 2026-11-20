@extends('layouts.app')
@section('title', 'Deteksi Duplikat Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Deteksi Duplikat Konsultasi" page="Konsultasi" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

@push('after-style')
<style>
    .duplicate-group .duplicate-value {
        overflow-wrap: anywhere;
    }

    .duplicate-group .table > :not(caption) > * > * {
        vertical-align: middle;
    }

    .duplicate-group .duplicate-merge-form select {
        width: 100%;
        max-width: 100%;
    }

    .duplicate-group .duplicate-actions {
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 767.98px) {
        .duplicate-group .duplicate-header {
            align-items: flex-start !important;
        }

        .duplicate-group .duplicate-actions {
            justify-content: stretch;
        }

        .duplicate-group .duplicate-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('after-script')
<script>
    (function () {
        document.querySelectorAll('.duplicate-merge-form').forEach(function (form) {
            var primary = form.querySelector('select[name="primary_uuid"]');
            var duplicate = form.querySelector('select[name="duplicate_uuid"]');
            if (!primary || !duplicate) return;

            function syncOptions() {
                Array.prototype.forEach.call(duplicate.options, function (option) {
                    option.disabled = option.value !== '' && option.value === primary.value;
                });

                if (duplicate.value && duplicate.value === primary.value) {
                    var alternative = Array.prototype.find.call(duplicate.options, function (option) {
                        return option.value !== '' && !option.disabled;
                    });
                    duplicate.value = alternative ? alternative.value : '';
                }
            }

            primary.addEventListener('change', syncOptions);
            syncOptions();

            form.addEventListener('submit', function (event) {
                if (!primary.value || !duplicate.value || primary.value === duplicate.value) {
                    event.preventDefault();
                    window.alert('Pilih dua data yang berbeda untuk digabungkan.');
                }
            });
        });
    })();
</script>
@endpush

<section class="section">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
            <div class="min-w-0">
                <h4 class="fw-normal mb-1 text-body">Deteksi Duplikat Konsultasi</h4>
                <div class="small text-muted">Data dengan email atau WhatsApp yang sama dikelompokkan agar bisa ditinjau dan digabungkan menjadi satu calon pelanggan.</div>
            </div>
            <a href="{{ route('konsultasi.index', request()->query()) }}" class="btn btn-light flex-shrink-0"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar</a>
        </div>
        <div class="card-body">
            @forelse ($duplicates as $groupIndex => $group)
                <div class="card border duplicate-group mb-3">
                    <div class="card-header duplicate-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-wrap align-items-center gap-2 min-w-0">
                            <span class="badge bg-soft-primary text-primary"><i class="bi bi-diagram-3 me-1"></i>{{ $group['field'] }}</span>
                            <span class="fw-semibold duplicate-value">{{ $group['value'] }}</span>
                        </div>
                        <span class="badge bg-light text-dark">{{ $group['records']->count() }} data</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>WhatsApp</th>
                                        <th>Email</th>
                                        <th>Sumber</th>
                                        <th>Status Lead</th>
                                        <th>Dibuat</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['records'] as $record)
                                        <tr>
                                            <td class="fw-semibold">{{ $record->full_name }}</td>
                                            <td>
                                                <a href="{{ $record->whatsapp_link }}" target="_blank" rel="noopener" class="small text-decoration-none"><i class="bx bxl-whatsapp text-success me-1"></i>{{ $record->whatsapp }}</a>
                                            </td>
                                            <td class="small text-muted">{{ $record->email ?: '—' }}</td>
                                            <td class="small text-muted"><i class="bx {{ $record->source_icon }} me-1"></i>{{ $record->source_label }}</td>
                                            <td><span class="badge {{ $record->lead_status_badge_class }}">{{ $record->lead_status_label }}</span></td>
                                            <td class="small text-muted">{{ $record->created_at?->translatedFormat('d M Y H:i') ?? '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('konsultasi.show', $record->uuid) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('konsultasi.merge') }}" method="POST" class="duplicate-merge-form row g-2 align-items-end">
                            @csrf
                            <div class="col-12 col-md-5">
                                <label for="primary_{{ $groupIndex }}" class="form-label mb-1 small text-muted">Data utama yang dipertahankan</label>
                                <select id="primary_{{ $groupIndex }}" name="primary_uuid" class="form-select form-select-sm" required>
                                    @foreach ($group['records'] as $recordIndex => $record)
                                        <option value="{{ $record->uuid }}" @selected($recordIndex === 0)>{{ $record->full_name }} — {{ $record->created_at?->translatedFormat('d M Y H:i') ?? $record->uuid }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="duplicate_{{ $groupIndex }}" class="form-label mb-1 small text-muted">Data duplikat yang digabungkan</label>
                                <select id="duplicate_{{ $groupIndex }}" name="duplicate_uuid" class="form-select form-select-sm" required>
                                    @foreach ($group['records'] as $recordIndex => $record)
                                        <option value="{{ $record->uuid }}" @selected($recordIndex === 1)>{{ $record->full_name }} — {{ $record->created_at?->translatedFormat('d M Y H:i') ?? $record->uuid }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2 duplicate-actions">
                                <button type="submit" class="btn btn-sm btn-warning text-dark"><i class="bi bi-rocket-takeoff me-1"></i> Gabungkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check2-circle d-block fs-1 mb-2"></i>
                    Tidak ada duplikat ditemukan.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
