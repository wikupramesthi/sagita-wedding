@extends('layouts.app')
@section('title', 'Ubah Konsultasi')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Ubah Konsultasi" page="Layanan" active="Ubah Konsultasi" route="{{ route('konsultasi.edit', $consultation->uuid) }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="fw-normal mb-0 text-body">Ubah Pertanyaan Konsultasi</h4>
        </div>
        <div class="card-body">
            @include('pages.konsultasi._form', [
                'consultation' => $consultation,
                'action' => route('konsultasi.update', $consultation->uuid),
                'method' => 'PUT',
                'submitLabel' => 'Perbarui Data',
            ])
        </div>
    </div>
</section>
@endsection
