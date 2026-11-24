@extends('layouts.app')
@section('title', 'Detail Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Detail Prospek Sita" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
@endsection

@section('content')
@php
  $statuses = [
      'new' => ['Baru', 'bg-primary-subtle text-primary-emphasis'],
      'contacted' => ['Dihubungi', 'bg-info-subtle text-info-emphasis'],
      'qualified' => ['Terkualifikasi', 'bg-warning-subtle text-warning-emphasis'],
      'converted' => ['Terkonversi', 'bg-success-subtle text-success-emphasis'],
      'closed' => ['Ditutup', 'bg-secondary-subtle text-secondary-emphasis'],
  ];

  $criteriaLabels = [
      'budgetLimit' => 'Budget',
      'guestCount' => 'Tamu',
      'city' => 'Kota',
      'eventDate' => 'Tanggal',
      'vendorCategory' => 'Vendor',
  ];

  $criteriaChips = static function ($criteria) use ($criteriaLabels): array {
      $criteria = is_array($criteria) ? $criteria : [];
      $chips = [];
      foreach ($criteria as $key => $value) {
          if (!is_scalar($value) || $value === '') continue;
          if ($key === 'budgetLimit') {
              $value = 'Rp '.number_format((float) $value, 0, ',', '.');
          }
          $chips[] = ['label' => $criteriaLabels[$key] ?? $key, 'value' => (string) $value];
      }
      return $chips;
  };

  [$statusLabel, $statusClass] = $statuses[$lead->status] ?? [$lead->status, 'bg-secondary-subtle text-secondary-emphasis'];
@endphp

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="sita-hero">
      <div class="sita-hero-icon"><i class="bi bi-person-badge"></i></div>
      <div class="flex-grow-1">
        <h5 class="mb-1">{{ $lead->name }}</h5>
        <p class="text-muted small mb-0">Detail prospek yang terkumpul dari percakapan Sita.</p>
      </div>
      <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Kontak</h6>
        <div class="d-flex justify-content-between gap-3 border-bottom py-2">
          <span class="text-muted small">Telepon</span>
          <a href="tel:{{ $lead->phone }}" class="fw-semibold text-decoration-none">{{ $lead->phone }}</a>
        </div>
        <div class="d-flex justify-content-between gap-3 border-bottom py-2">
          <span class="text-muted small">Topik</span>
          <span class="fw-semibold">{{ $lead->topic ?? '-' }}</span>
        </div>
        <div class="d-flex justify-content-between gap-3 py-2">
          <span class="text-muted small">Skor</span>
          <span class="badge bg-light text-dark">{{ $lead->score }}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Kriteria</h6>
        @php $chips = $criteriaChips($lead->criteria); @endphp
        @if($chips)
        <div class="sita-criteria">
          @foreach($chips as $chip)
          <span class="sita-chip">{{ $chip['label'] }}: {{ $chip['value'] }}</span>
          @endforeach
        </div>
        @else
        <p class="text-muted small mb-0">Belum ada kriteria yang tercatat.</p>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-semibold mb-2">Pesan</h6>
    <p class="text-muted small mb-0">{{ $lead->message ?: 'Tidak ada pesan tambahan.' }}</p>
  </div>
</div>

<a href="{{ route('sita.leads.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
@endsection
