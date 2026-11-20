@extends('layouts.app')
@section('title', 'Impor Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Impor Konsultasi" page="Data Prospek" active="Konsultasi" route="{{ route('konsultasi.index') }}" />
@endsection

<section class="section">
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3 mb-3">
        <i class="bx bx-import fs-4 mt-1"></i>
        <div>
            <strong>Impor Calon Pelanggan</strong>
            <div class="small">Unggah file CSV/XLSX maksimal 5 MB dan 500 baris. Periksa hasil preview sebelum menyimpan.</div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning border-0 shadow-sm">{{ session('error') }}</div>
    @endif

    @if (session('import_errors'))
        <div class="alert alert-danger border-0 shadow-sm">
            <strong class="d-block mb-1">Baris yang dilewati</strong>
            <ul class="mb-0 small">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h4 class="card-title h6 mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i> Unggah Data</h4>
            <a href="{{ route('konsultasi.import.template') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i> Unduh Template</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('konsultasi.import.preview') }}" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf
                <div class="col-12 col-md-8">
                    <label for="import-file" class="form-label small text-muted">File CSV / XLSX</label>
                    <input type="file" id="import-file" name="file" accept=".csv,.xlsx,.xls" class="form-control @error('file') is-invalid @enderror" required>
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye me-1"></i> Preview</button>
                </div>
            </form>
        </div>
    </div>

    @if (! empty($rows))
        @php
            $validRows = collect($rows)->where('valid', true);
        @endphp
        <div class="card">
            <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="card-title h6 mb-0"><i class="bi bi-table me-2"></i> Preview Import</h4>
                <span class="small text-muted">{{ $validRows->count() }} dari {{ count($rows) }} baris valid</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Baris</th>
                                <th>Nama</th>
                                <th>WhatsApp</th>
                                <th>Email</th>
                                <th>Sumber</th>
                                <th>Status Lead</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="{{ $row['valid'] ? '' : 'table-danger-subtle' }}">
                                    <td>{{ $row['row_number'] }}</td>
                                    <td class="fw-semibold">{{ $row['full_name'] ?: '—' }}</td>
                                    <td>{{ $row['whatsapp'] ?: '—' }}</td>
                                    <td>{{ $row['email'] ?: '—' }}</td>
                                    <td>{{ \App\Models\Consultation::SOURCE_LABELS[$row['source']] ?? $row['source'] }}</td>
                                    <td>{{ \App\Models\Consultation::LEAD_STATUS_LABELS[$row['lead_status']] ?? $row['lead_status'] }}</td>
                                    <td>
                                        @if ($row['valid'])
                                            <span class="badge bg-success">Siap</span>
                                        @else
                                            <span class="badge bg-danger" title="{{ $row['error'] }}">Error</span>
                                            <div class="small text-danger">{{ $row['error'] }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex flex-wrap justify-content-end gap-2">
                <a href="{{ route('konsultasi.import') }}" class="btn btn-light"><i class="bi bi-x-circle me-1"></i> Batalkan</a>
                <form method="POST" action="{{ route('konsultasi.import.commit') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ session('konsultasi_import_token') }}">
                    <button type="submit" class="btn btn-success" @disabled($validRows->isEmpty())><i class="bi bi-check2-circle me-1"></i> Simpan {{ $validRows->count() }} Data</button>
                </form>
            </div>
        </div>
    @endif
</section>
@endsection
