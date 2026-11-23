@extends('layouts.app')
@section('title', 'Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Prospek Sita" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
@endsection

@section('content')
@php
  $labels = [
    'new' => 'Baru',
    'contacted' => 'Dihubungi',
    'qualified' => 'Terkualifikasi',
    'converted' => 'Terkonversi',
    'closed' => 'Ditutup',
  ];
  $canUpdate = auth()->user()?->can('sita.leads.update');
  $canDestroy = auth()->user()?->can('sita.leads.update');
@endphp
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <h5 class="mb-1">Prospek Sita</h5>
    <p class="text-muted small mb-0">Calon klien dari percakapan Sita. Data kontak hanya untuk tindak lanjut tim.</p>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Cari</label>
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nama atau telepon">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          @foreach($labels as $value => $label)
          <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
        <a href="{{ route('sita.leads.index') }}" class="btn btn-light w-100">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr><th>Nama</th><th>Telepon</th><th>Topik</th><th>Kriteria</th><th>Skor</th><th>Status</th><th class="text-end">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td><a href="{{ route('sita.leads.show', $lead->uuid) }}">{{ $lead->name }}</a></td>
          <td>{{ $lead->phone }}</td>
          <td>{{ $lead->topic ?? '-' }}</td>
          <td><code class="small">{{ json_encode($lead->criteria) }}</code></td>
          <td>{{ $lead->score }}</td>
          <td>
            <span class="badge bg-secondary-subtle text-secondary">{{ $labels[$lead->status] ?? $lead->status }}</span>
            @if($canUpdate)
            <form method="POST" action="{{ route('sita.leads.update', $lead->uuid) }}" class="d-flex gap-1 mt-1">
              @csrf @method('PUT')
              <select name="status" class="form-select form-select-sm">
                @foreach($labels as $value => $label)
                <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <button class="btn btn-sm btn-outline-primary">Simpan</button>
            </form>
            @endif
          </td>
          <td class="text-end">
            @if($canDestroy)
            <form method="POST" action="{{ route('sita.leads.destroy', $lead->uuid) }}" class="d-inline" onsubmit="return confirm('Hapus prospek {{ $lead->name }}?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada prospek.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($leads->hasPages())
  <div class="card-footer bg-white">{{ $leads->links() }}</div>
  @endif
</div>
@endsection
