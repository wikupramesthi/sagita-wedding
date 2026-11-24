@extends('layouts.app')
@section('title', 'Analitik Sita')
@section('breadcrumb')
<x-breadcrumb title="Analitik Sita" page="Sita" active="Analitik" route="{{ route('sita.analytics.index') }}" />
@endsection

@section('content')
@php
  $formatTime = static function ($value): string {
      return filled($value)
          ? \Carbon\Carbon::parse($value)->timezone(config('app.timezone'))->format('d M Y, H:i')
          : '-';
  };

  $cards = [
      ['Jawaban', $summary['totals']['answers'], 'bi bi-chat-square-text', 'purple', true],
      ['Feedback', $summary['totals']['feedback'], 'bi bi-hand-thumbs-up', 'blue', true],
      ['Membantu', $summary['totals']['helpful'], 'bi bi-check2-circle', 'green', true],
      ['Tidak membantu', $summary['totals']['unhelpful'], 'bi bi-x-circle', 'red', false],
      ['Tanpa jawaban', $summary['totals']['noMatch'], 'bi bi-question-circle', 'yellow', true],
      ['Fallback', $summary['totals']['fallback'], 'bi bi-arrow-return-left', 'cyan', false],
      ['Klarifikasi', $summary['totals']['clarify'], 'bi bi-question-diamond', 'pink', false],
      ['Keyakinan rendah', $summary['totals']['lowConfidence'], 'bi bi-exclamation-triangle', 'turquoise', false],
  ];

  $rates = [
      ['Membantu', 'helpful'],
      ['Fallback', 'fallback'],
      ['Tanpa jawaban', 'noMatch'],
      ['Klarifikasi', 'clarify'],
  ];

  $eventLabels = [
      'topic' => 'Topik',
      'confidence' => 'Yakin',
      'fallback' => 'Fallback',
      'clarify' => 'Klarifikasi',
      'helpful' => 'Membantu',
      'responseMs' => 'Respons',
      'queryHash' => 'Hash',
      'sample' => 'Contoh',
  ];

  $eventDetails = static function (array $event) use ($eventLabels): array {
      return collect($event)
          ->except(['at', 'name'])
          ->map(function ($value, $key) use ($eventLabels): array {
              if (is_bool($value)) {
                  $value = $value ? 'Ya' : 'Tidak';
              } elseif ($key === 'confidence') {
                  $value = round((float) $value * 100).'%';
              } elseif ($key === 'responseMs') {
                  $value = $value.' ms';
              }

              return ['label' => $eventLabels[$key] ?? $key, 'value' => $value];
          })
          ->values()
          ->all();
  };
@endphp

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="sita-hero">
      <div class="sita-hero-icon"><i class="bi bi-robot"></i></div>
      <div>
        <h5 class="mb-1">Ringkasan Sita</h5>
        <p class="text-muted small mb-0">Jendela {{ $summary['window']['events'] }} dari maksimal {{ $summary['window']['maxEvents'] }} peristiwa. Dibuat {{ $formatTime($summary['generatedAt']) }}. Retensi {{ config('sita.analytics.retention_days') }} hari / maksimal {{ number_format(config('sita.analytics.max_rows')) }} baris.</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  @foreach($cards as [$label, $value, $icon, $color, $featured])
  <div class="col-6 col-md-3">
    <div class="card shadow-sm border-0 h-100 sita-stat-card {{ $featured ? 'sita-stat-card--featured' : '' }}">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stats-icon {{ $color }}"><i class="{{ $icon }}"></i></div>
        <div>
          <div class="stat-label">{{ $label }}</div>
          <div class="stat-value">{{ $value }}</div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row g-3 mb-4">
  <div class="col-md-8">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Tingkat</h6>
        @foreach($rates as [$label, $key])
        <div class="sita-rate-row">
          <div class="sita-rate-head">
            <span class="small fw-semibold">{{ $label }}</span>
            <span class="small fw-bold">{{ $summary['rates'][$key] }}%</span>
          </div>
          <div class="sita-rate-bar"><span style="width: {{ min(100, max(0, (float) $summary['rates'][$key])) }}%"></span></div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stats-icon blue"><i class="bi bi-stopwatch"></i></div>
        <div>
          <div class="stat-label">Kinerja</div>
          <div class="stat-value">{{ $summary['performance']['avgResponseMs'] }} <span class="fs-6 fw-semibold">ms</span></div>
          <div class="text-muted small mt-1">Rata-rata waktu respons</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-semibold mb-3">Topik</h6>
    <div class="table-responsive sita-table-responsive">
      <table class="table align-middle mb-0 sita-table">
        <thead class="table-light"><tr><th>Topik</th><th>Jawaban</th><th>Membantu</th><th>Tidak membantu</th></tr></thead>
        <tbody>
          @foreach($summary['topics'] as $topic)
          <tr>
            <td><span class="fw-semibold">{{ $topic['topic'] }}</span></td>
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
    <h6 class="fw-semibold mb-3">Feedback menurut bahasa dan topik</h6>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Bahasa</th><th>Topik</th><th>Feedback</th><th>Membantu</th><th>Tidak membantu</th><th>Rasio membantu</th></tr></thead>
        <tbody>
          @forelse($summary['feedbackSegments'] as $segment)
          <tr>
            <td>{{ $segment['locale'] === 'id' ? 'Bahasa Indonesia' : ($segment['locale'] === 'en' ? 'English' : 'Belum diketahui') }}</td>
            <td>{{ $segment['topic'] }}</td>
            <td>{{ $segment['feedback'] }}</td>
            <td>{{ $segment['helpful'] }}</td>
            <td>{{ $segment['unhelpful'] }}</td>
            <td>{{ $segment['helpfulRate'] }}%</td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-3">Belum ada feedback yang dapat dikelompokkan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <h6 class="fw-semibold mb-0">Pola tanpa jawaban</h6>
      <span class="badge bg-light text-muted">{{ count($summary['noMatchPatterns']) }} pola</span>
    </div>
    <p class="small text-muted">Gunakan contoh pertanyaan untuk meninjau FAQ atau sinonim. Perubahan jawaban tetap perlu ditinjau admin.</p>
    <div class="table-responsive sita-table-responsive">
      <table class="table align-middle mb-0 sita-table sita-table--wide">
        <thead class="table-light">
          <tr><th>Contoh pertanyaan</th><th>Jumlah</th><th>Topik</th><th>Klasifikasi</th><th>Terakhir</th><th>Tindakan</th></tr>
        </thead>
        <tbody>
          @forelse($summary['noMatchPatterns'] as $pattern)
          <tr>
            <td class="sita-cell-wrap">
              <div class="fw-semibold">{{ $pattern['sample'] ?? '-' }}</div>
              <code class="small text-muted">{{ $pattern['queryHash'] }}</code>
            </td>
            <td><span class="badge bg-primary-subtle text-primary-emphasis">{{ $pattern['count'] }}</span></td>
            <td>{{ $pattern['topic'] ?? '-' }}</td>
            <td>
              <span class="badge {{ $pattern['fallback'] ? 'bg-warning-subtle text-warning-emphasis' : 'bg-info-subtle text-info-emphasis' }}">
                {{ $pattern['fallback'] ? 'Tanpa jawaban' : 'Keyakinan rendah' }}
              </span>
            </td>
            <td class="small">{{ $formatTime($pattern['lastAt']) }}</td>
            <td>
              <div class="d-flex flex-wrap gap-1">
                @if(!empty($pattern['sample']))
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('faq.index', ['q' => $pattern['sample']]) }}"><i class="bi bi-search me-1"></i>Tinjau FAQ</a>
                  @can('faq.store')
                    <a class="btn btn-sm btn-outline-success" href="{{ route('faq.create', ['pertanyaan' => $pattern['sample']]) }}"><i class="bi bi-file-plus me-1"></i>Buat draft FAQ</a>
                  @endcan
                @endif
                @can('sita.synonyms.manage')
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('sita.synonyms.index') }}"><i class="bi bi-list-check me-1"></i>Kelola sinonim</a>
                @endcan
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">
              <div class="sita-empty">
                <i class="bi bi-inboxes"></i>
                <div class="fw-semibold">Belum ada pola.</div>
                <div class="text-muted small">Pola tanpa jawaban akan muncul di sini setelah Sita menerima pertanyaan baru.</div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <h6 class="fw-semibold mb-3">Peristiwa terbaru</h6>
    <div class="table-responsive sita-table-responsive">
      <table class="table align-middle mb-0 sita-table sita-table--wide">
        <thead class="table-light"><tr><th>Waktu</th><th>Nama</th><th>Topik</th><th>Detail</th></tr></thead>
        <tbody>
          @forelse($summary['recent'] as $event)
          <tr>
            <td class="small">{{ $formatTime($event['at']) }}</td>
            <td><span class="fw-semibold">{{ $event['name'] }}</span></td>
            <td>{{ $event['topic'] ?? '-' }}</td>
            <td class="sita-cell-wrap">
              @php $details = $eventDetails($event); @endphp
              @if($details)
              <div class="sita-criteria">
                @foreach($details as $detail)
                <span class="sita-chip">{{ $detail['label'] }}: {{ $detail['value'] }}</span>
                @endforeach
              </div>
              @else
              <span class="text-muted">-</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="sita-empty">
                <i class="bi bi-activity"></i>
                <div class="fw-semibold">Belum ada peristiwa.</div>
                <div class="text-muted small">Aktivitas percakapan Sita akan tampil di sini.</div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
