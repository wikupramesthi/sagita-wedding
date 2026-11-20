@extends('layouts.app')
@section('title', $mode === 'detail' ? 'Preview PDF Detail Konsultasi' : 'Preview PDF Rekap Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Preview PDF" page="Data Prospek" active="Preview PDF" route="{{ route('konsultasi.index') }}" />
@endsection

@php
    $query = request()->query();
    $backUrl = $mode === 'detail'
        ? route('konsultasi.show', $consultation->uuid)
        : route('konsultasi.index', $query);
@endphp

<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Preview PDF {{ $mode === 'detail' ? 'Detail Calon Pelanggan' : 'Rekap Calon Pelanggan' }}</h4>
                <p class="text-muted small mb-0">Periksa isi dokumen terlebih dahulu, lalu unduh jika sudah sesuai.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ $backUrl }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak</button>
                <a href="{{ route('konsultasi.pdf-download', $query) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF</a>
            </div>
        </div>
        <div class="card-body p-0">
            <iframe src="{{ route('konsultasi.pdf-stream', $query) }}" class="pdf-preview-frame" title="Preview PDF Konsultasi"></iframe>
        </div>
    </div>
</section>
@endsection
