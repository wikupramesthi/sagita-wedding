@extends('layouts.app')
@section('title', 'Detail Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Detail Prospek" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
@endsection

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="mb-3">{{ $lead->name }}</h5>
    <p class="small mb-1"><strong>Telepon:</strong> {{ $lead->phone }}</p>
    <p class="small mb-1"><strong>Topik:</strong> {{ $lead->topic ?? '-' }}</p>
    <p class="small mb-1"><strong>Skor:</strong> {{ $lead->score }}</p>
    <p class="small mb-1"><strong>Status:</strong> {{ $lead->status }}</p>
    <p class="small mb-1"><strong>Kriteria:</strong> <code>{{ json_encode($lead->criteria) }}</code></p>
    <p class="small mb-3"><strong>Pesan:</strong> {{ $lead->message ?? '-' }}</p>
    <a href="{{ route('sita.leads.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>
</div>
@endsection
