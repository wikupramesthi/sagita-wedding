@extends('layouts.app')
@section('title', 'Ubah Calon Pelanggan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Ubah Calon Pelanggan" page="Konsultasi" active="Ubah" route="{{ route('konsultasi.edit', $consultation->uuid) }}" />
@endsection

<section class="section">
    @include('pages.konsultasi._form', [
        'consultation' => $consultation,
        'action' => route('konsultasi.update', $consultation->uuid),
        'method' => 'PUT',
        'submitLabel' => 'Perbarui Calon Pelanggan',
    ])
</section>
@endsection
