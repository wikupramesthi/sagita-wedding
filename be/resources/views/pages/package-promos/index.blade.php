@extends('layouts.app')
@section('title', 'Promo Paket')
@section('breadcrumb')
<x-breadcrumb title="Promo Paket" page="Paket & Layanan" active="Promo" route="{{ route('package-promos.index') }}" />
@endsection

@section('content')
@php
  $canStore = auth()->user()?->can('package.promo.store');
  $canUpdate = auth()->user()?->can('package.promo.update');
  $canDestroy = auth()->user()?->can('package.promo.destroy');
  $fmtDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('Y-m-d\TH:i') : '';
@endphp
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <h5 class="mb-1">Promo Paket</h5>
      <p class="text-muted mb-0 small">Kelola kode promo untuk checkout paket. Promo bisa global atau spesifik paket.</p>
    </div>
    @if($canStore)
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#promoCreateModal"><i class="bi bi-plus-lg me-1"></i>Tambah Promo</button>
    @endif
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label small fw-semibold">Cari</label>
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Kode promo atau nama paket">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <option value="active" @selected($status==='active')>Aktif</option>
          <option value="inactive" @selected($status==='inactive')>Nonaktif</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Cakupan</label>
        <select name="scope" class="form-select">
          <option value="">Semua</option>
          <option value="global" @selected($scope==='global')>Global</option>
          <option value="package" @selected($scope==='package')>Paket tertentu</option>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
        <a href="{{ route('package-promos.index') }}" class="btn btn-light w-100">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Kode</th><th>Paket</th><th>Diskon</th><th>Min. Belanja</th><th>Pemakaian</th><th>Periode</th><th>Status</th><th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($promos as $promo)
        @php
          $now = now();
          $usable = $promo->is_active
            && (!$promo->starts_at || $promo->starts_at <= $now)
            && (!$promo->ends_at || $promo->ends_at >= $now)
            && (!$promo->max_uses || $promo->used_count < $promo->max_uses);
        @endphp
        <tr>
          <td><div class="fw-semibold">{{ $promo->code }}</div><div class="small text-muted">{{ $usable ? 'Bisa dipakai' : 'Tidak aktif/syarat belum terpenuhi' }}</div></td>
          <td>{{ $promo->package?->name ?? 'Semua paket' }}</td>
          <td>
            @if($promo->type==='percent')
              {{ (int)$promo->value }}%
              @if($promo->max_discount)<div class="small text-muted">Maks Rp {{ number_format((int)$promo->max_discount,0,',','.') }}</div>@endif
            @else
              Rp {{ number_format((int)$promo->value,0,',','.') }}
            @endif
          </td>
          <td>Rp {{ number_format((int)$promo->min_purchase,0,',','.') }}</td>
          <td>{{ (int)$promo->used_count }} / {{ $promo->max_uses ? (int)$promo->max_uses : '8' }}</td>
          <td class="small">
            <div>Mulai: {{ $promo->starts_at?->format('d/m/Y H:i') ?? '-' }}</div>
            <div>Sampai: {{ $promo->ends_at?->format('d/m/Y H:i') ?? '-' }}</div>
          </td>
          <td>
            <span class="badge {{ $promo->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}</span>
          </td>
          <td class="text-end">
            @if($canUpdate)
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#promoEditModal-{{ $promo->uuid }}">Edit</button>
            @endif
            @if($canDestroy)
            <form action="{{ route('package-promos.destroy', $promo->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo {{ $promo->code }}?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
            @endif
          </td>
        </tr>

        @if($canUpdate)
        <div class="modal fade" id="promoEditModal-{{ $promo->uuid }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <form method="POST" action="{{ route('package-promos.update', $promo->uuid) }}">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Promo {{ $promo->code }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                  @include('pages.package-promos._form', ['promo'=>$promo,'packages'=>$packages,'prefix'=>'edit-'.$promo->uuid,'fmtDate'=>$fmtDate])
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
              </form>
            </div>
          </div>
        </div>
        @endif
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada promo.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($promos->hasPages())
  <div class="card-footer bg-white">{{ $promos->links() }}</div>
  @endif
</div>

@if($canStore)
<div class="modal fade" id="promoCreateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="POST" action="{{ route('package-promos.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Tambah Promo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          @include('pages.package-promos._form', ['promo'=>null,'packages'=>$packages,'prefix'=>'create','fmtDate'=>$fmtDate])
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection