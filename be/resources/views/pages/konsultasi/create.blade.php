@extends('layouts.app')
@section('title', 'Tambah Calon Pelanggan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Calon Pelanggan" page="Konsultasi" active="Tambah" route="{{ route('konsultasi.create') }}" />
@endsection

<section class="section">
    @include('pages.konsultasi._form', [
        'action' => route('konsultasi.store'),
        'submitLabel' => 'Simpan Calon Pelanggan',
    ])
</section>
@endsection
