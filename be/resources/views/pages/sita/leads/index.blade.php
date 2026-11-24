@extends('layouts.app')
@section('title', 'Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Prospek Sita" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
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

  $canUpdate = auth()->user()?->can('sita.leads.update');
  $canDestroy = auth()->user()?->can('sita.leads.update');
@endphp

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="sita-hero">
      <div class="sita-hero-icon"><i class="bi bi-person-lines-fill"></i></div>
      <div>
        <h5 class="mb-1">Prospek Sita</h5>
        <p class="text-muted small mb-0">Calon klien dari percakapan Sita. Data kontak hanya untuk tindak lanjut tim.</p>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small fw-semibold" for="sitaLeadSearch">Cari</label>
        <input id="sitaLeadSearch" type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nama atau telepon">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold" for="sitaLeadStatus">Status</label>
        <select id="sitaLeadStatus" name="status" class="form-select">
          <option value="">Semua status</option>
          @foreach($statuses as $value => [$label])
          <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="{{ route('sita.leads.index') }}" class="btn btn-light w-100">Reset</a>
      </div>
    </form>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-3 border-top">
      <span class="text-muted small">Menampilkan {{ $leads->count() }} dari {{ $leads->total() }} prospek</span>
      @if($search !== '' || $status !== '')
      <a href="{{ route('sita.leads.index') }}" class="btn btn-sm btn-link">Hapus filter</a>
      @endif
    </div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive sita-table-responsive">
    <table class="table align-middle mb-0 sita-table sita-table--wide">
      <thead class="table-light">
        <tr><th>Nama</th><th>Telepon</th><th>Topik</th><th>Kriteria</th><th>Skor</th><th>Status</th><th class="text-end">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        @php [$statusLabel, $statusClass] = $statuses[$lead->status] ?? [$lead->status, 'bg-secondary-subtle text-secondary-emphasis']; @endphp
        <tr>
          <td>
            <a href="{{ route('sita.leads.show', $lead->uuid) }}" class="fw-semibold text-decoration-none">{{ $lead->name }}</a>
            @if($lead->message)
            <div class="text-muted small text-truncate" style="max-width: 180px;" title="{{ $lead->message }}">{{ $lead->message }}</div>
            @endif
          </td>
          <td>{{ $lead->phone }}</td>
          <td>{{ $lead->topic ?? '-' }}</td>
          <td class="sita-cell-wrap">
            @php $chips = $criteriaChips($lead->criteria); @endphp
            @if($chips)
            <div class="sita-criteria">
              @foreach($chips as $chip)
              <span class="sita-chip">{{ $chip['label'] }}: {{ $chip['value'] }}</span>
              @endforeach
            </div>
            @else
            <span class="text-muted">-</span>
            @endif
          </td>
          <td><span class="badge bg-light text-dark">{{ $lead->score }}</span></td>
          <td>
            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
            @if($canUpdate)
            <form method="POST" action="{{ route('sita.leads.update', $lead->uuid) }}" class="d-flex gap-1 mt-2">
              @csrf @method('PUT')
              <select name="status" class="form-select form-select-sm" aria-label="Ubah status {{ $lead->name }}">
                @foreach($statuses as $value => [$label])
                <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <button class="btn btn-sm btn-outline-primary sita-action-btn" type="submit" aria-label="Simpan status {{ $lead->name }}"><i class="bi bi-check2"></i></button>
            </form>
            @endif
          </td>
          <td class="text-end">
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('sita.leads.show', $lead->uuid) }}" class="btn btn-sm btn-outline-secondary sita-action-btn" aria-label="Lihat {{ $lead->name }}" title="Lihat detail"><i class="bi bi-eye"></i></a>
              @if($canDestroy)
              <form method="POST" action="{{ route('sita.leads.destroy', $lead->uuid) }}" class="d-inline sita-delete-form" data-name="{{ $lead->name }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger sita-action-btn" type="submit" aria-label="Hapus {{ $lead->name }}" title="Hapus"><i class="bi bi-trash3"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="sita-empty">
              <i class="bi bi-person-x"></i>
              <div class="fw-semibold">Belum ada prospek.</div>
              <div class="text-muted small">Prospek baru akan muncul setelah pengunjung mengisi formulir dari Sita.</div>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($leads->hasPages())
  <div class="card-footer">{{ $leads->links() }}</div>
  @endif
</div>
@endsection

@push('after-script')
<script>
document.querySelectorAll('.sita-delete-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        window.sagitaConfirm({ name: form.dataset.name }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
