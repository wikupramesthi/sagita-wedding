@extends('layouts.app')
@section('title', 'Sinonim Sita')
@section('breadcrumb')
<x-breadcrumb title="Sinonim Sita" page="Sita" active="Sinonim" route="{{ route('sita.synonyms.index') }}" />
@endsection

@section('content')
@php $rows = $synonyms->items(); @endphp

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="sita-hero">
      <div class="sita-hero-icon"><i class="bi bi-diagram-3"></i></div>
      <div class="flex-grow-1">
        <h5 class="mb-1">Sinonim Sita</h5>
        <p class="text-muted small mb-0">Istilah dan alias yang dipakai untuk menormalkan pertanyaan Sita.</p>
      </div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#synonymCreateModal"><i class="bi bi-plus-lg me-1"></i>Tambah Sinonim</button>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label small fw-semibold" for="sitaSynonymSearch">Cari</label>
        <input id="sitaSynonymSearch" type="text" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Istilah atau bentuk baku">
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold" for="sitaSynonymLocale">Bahasa</label>
        <select id="sitaSynonymLocale" name="locale" class="form-select">
          <option value="">Semua</option>
          <option value="id" @selected($filters['locale'] === 'id')>Indonesia</option>
          <option value="en" @selected($filters['locale'] === 'en')>English</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold" for="sitaSynonymTopic">Topik</label>
        <select id="sitaSynonymTopic" name="topic" class="form-select">
          <option value="">Semua</option>
          @foreach(['packages', 'venues', 'vendors'] as $topic)
          <option value="{{ $topic }}" @selected($filters['topic'] === $topic)>{{ $topic }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold" for="sitaSynonymActive">Status</label>
        <select id="sitaSynonymActive" name="is_active" class="form-select">
          <option value="">Semua</option>
          <option value="1" @selected($filters['active'] === '1')>Aktif</option>
          <option value="0" @selected($filters['active'] === '0')>Nonaktif</option>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="{{ route('sita.synonyms.index') }}" class="btn btn-light w-100">Reset</a>
      </div>
    </form>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-3 border-top">
      <span class="text-muted small">Menampilkan {{ $synonyms->count() }} dari {{ $synonyms->total() }} sinonim</span>
      @if($filters['search'] !== '' || $filters['locale'] !== '' || $filters['topic'] !== '' || $filters['active'] !== '')
      <a href="{{ route('sita.synonyms.index') }}" class="btn btn-sm btn-link">Hapus filter</a>
      @endif
    </div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive sita-table-responsive">
    <table class="table align-middle mb-0 sita-table">
      <thead class="table-light">
        <tr><th>Bahasa</th><th>Istilah</th><th>Bentuk Baku</th><th>Topik</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $synonym)
        <tr>
          <td><span class="badge bg-light text-dark">{{ strtoupper($synonym->locale) }}</span></td>
          <td class="fw-semibold">{{ $synonym->term }}</td>
          <td>{{ $synonym->canonical }}</td>
          <td>{{ $synonym->topic ?: '-' }}</td>
          <td>{{ $synonym->sort_order }}</td>
          <td>
            <span class="badge {{ $synonym->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $synonym->is_active ? 'Aktif' : 'Nonaktif' }}</span>
          </td>
          <td class="text-end">
            <div class="d-flex justify-content-end gap-1">
              <button class="btn btn-sm btn-outline-primary sita-action-btn" type="button" data-bs-toggle="modal" data-bs-target="#synonymEditModal-{{ $synonym->uuid }}" aria-label="Edit {{ $synonym->term }}" title="Edit"><i class="bi bi-pencil-square"></i></button>
              <form method="POST" action="{{ route('sita.synonyms.destroy', $synonym->uuid) }}" class="d-inline sita-delete-form" data-name="{{ $synonym->term }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger sita-action-btn" type="submit" aria-label="Hapus {{ $synonym->term }}" title="Hapus"><i class="bi bi-trash3"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="sita-empty">
              <i class="bi bi-list-check"></i>
              <div class="fw-semibold">Belum ada sinonim.</div>
              <div class="text-muted small">Tambahkan alias agar Sita memahami istilah lokal yang dipakai pengunjung.</div>
              <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#synonymCreateModal"><i class="bi bi-plus-lg me-1"></i>Tambah Sinonim</button>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($synonyms->hasPages())
  <div class="card-footer">{{ $synonyms->links() }}</div>
  @endif
</div>

@foreach($rows as $synonym)
<div class="modal fade" id="synonymEditModal-{{ $synonym->uuid }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="POST" action="{{ route('sita.synonyms.update', $synonym->uuid) }}">
        @csrf @method('PUT')
        <div class="modal-header"><h5 class="modal-title">Edit {{ $synonym->term }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body">
          @include('pages.sita.synonyms._form', ['synonym' => $synonym, 'prefix' => $synonym->uuid])
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="synonymCreateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="POST" action="{{ route('sita.synonyms.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Tambah Sinonim</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body">
          @include('pages.sita.synonyms._form', ['synonym' => null, 'prefix' => 'create'])
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
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
