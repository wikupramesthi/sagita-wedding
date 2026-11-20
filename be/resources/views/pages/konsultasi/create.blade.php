@extends('layouts.app')
@section('title', 'Tambah Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Konsultasi" page="Layanan" active="Tambah Konsultasi" route="{{ route('konsultasi.create') }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="fw-normal mb-0 text-body">Tambah Pertanyaan Konsultasi</h4>
        </div>
        <div class="card-body">
            @include('pages.konsultasi._form', [
                'action' => route('konsultasi.store'),
                'submitLabel' => 'Simpan Data',
            ])
        </div>
    </div>
</section>
@endsection
