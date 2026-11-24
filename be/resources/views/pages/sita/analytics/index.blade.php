@extends('layouts.app')
@section('title', 'Analitik Sita')
@section('breadcrumb')
<x-breadcrumb title="Analitik Sita" page="Sita" active="Analitik" route="{{ route('sita.analytics.index') }}" />
@endsection

@section('content')
@php
  $cards = [
    ['Jawaban', $summary['totals']['answers']],
    ['Feedback', $summary['totals']['feedback']],
    ['Membantu', $summary['totals']['helpful']],
    ['Tidak membantu', $summary['totals']['unhelpful']],
    ['Tanpa jawaban', $summary['totals']['noMatch']],
    ['Fallback', $summary['totals']['fallback']],
    ['Klarifikasi', $summary['totals']['clarify']],
    ['Keyakinan rendah', $summary['totals']['lowConfidence']],
  ];
@endphp
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h5 class="mb-1">Ringkasan Sita</h5>
    <p class="text-muted small mb-0">Jendela {{ $summary['window']['events'] }} dari maksimal {{ $summary['window']['maxEvents'] }} peristiwa. Dibuat {{ $summary['generatedAt'] }}.</p>
  </div>
</div>

<div class="row g-3 mb-4">
  @foreach($cards as [$label, $value])
  <div class="col-6 col-md-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">{{ $label }}</div>
        <div class="fs-4 fw-semibold">{{ $value }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-semibold">Tingkat</h6>
        <p class="small mb-1">Membantu: {{ $summary['rates']['helpful'] }}%</p>
        <p class="small mb-1">Fallback: {{ $summary['rates']['fallback'] }}%</p>
        <p class="small mb-1">Tanpa jawaban: {{ $summary['rates']['noMatch'] }}%</p>
        <p class="small mb-0">Klarifikasi: {{ $summary['rates']['clarify'] }}%</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-semibold">Kinerja</h6>
        <p class="small mb-0">Rata-rata waktu respons: {{ $summary['performance']['avgResponseMs'] }} ms</p>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-semibold mb-3">Topik</h6>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Topik</th><th>Jawaban</th><th>Membantu</th><th>Tidak membantu</th></tr></thead>
        <tbody>
          @foreach($summary['topics'] as $topic)
          <tr>
            <td>{{ $topic['topic'] }}</td>
            <td>{{ $topic['answers'] }}</td>
            <td>{{ $topic['helpful'] }}</td>
            <td>{{ $topic['unhelpful'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-semibold mb-3">Pola tanpa jawaban</h6>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Contoh pertanyaan</th><th>Jumlah</th><th>Topik</th><th>Jenis</th><th>Terakhir</th></tr></thead>
        <tbody>
          @forelse($summary['noMatchPatterns'] as $pattern)
          <tr>
            <td>
              <div>{{ $pattern['sample'] ?? '-' }}</div>
              <code class="small text-muted">{{ $pattern['queryHash'] }}</code>
            </td>
            <td>{{ $pattern['count'] }}</td>
            <td>{{ $pattern['topic'] ?? '-' }}</td>
            <td>{{ $pattern['fallback'] ? 'Tanpa jawaban' : 'Keyakinan rendah' }}</td>
            <td>{{ $pattern['lastAt'] }}</td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pola.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <h6 class="fw-semibold mb-3">Peristiwa terbaru</h6>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Waktu</th><th>Nama</th><th>Topik</th><th>Detail</th></tr></thead>
        <tbody>
          @forelse($summary['recent'] as $event)
          <tr>
            <td class="small">{{ $event['at'] }}</td>
            <td>{{ $event['name'] }}</td>
            <td>{{ $event['topic'] ?? '-' }}</td>
            <td><code>{{ json_encode(array_diff_key($event, array_flip(['at', 'name', 'topic']))) }}</code></td>
          </tr>
          @empty
          <tr><td colspan="4" class="text-center text-muted py-3">Belum ada peristiwa.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
