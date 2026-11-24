@extends('layouts.app')
@section('title', 'Sinonim Sita')
@section('breadcrumb')
<x-breadcrumb title="Sinonim Sita" page="Sita" active="Sinonim" route="{{ route('sita.synonyms.index') }}" />
@endsection

@section('content')
@php
    $rows = $synonyms->items();
    $hasFilters = $filters['search'] !== '' || $filters['locale'] !== '' || $filters['topic'] !== '' || $filters['active'] !== '';
    $editAction = route('sita.synonyms.update', '__UUID__');
@endphp

<div class="card shadow-sm border-0 mb-4 sita-filter-card">
  <div class="card-body">
    <div class="sita-hero">
      <div class="sita-hero-icon"><i class="bi bi-diagram-3"></i></div>
      <div class="flex-grow-1">
        <h5 class="mb-1">Sinonim Sita</h5>
        <p class="text-muted small mb-0">Istilah dan alias yang dipakai untuk menormalkan pertanyaan Sita.</p>
      </div>
      <div class="sita-hero-meta"><span class="sita-meta-value">{{ $synonyms->total() }}</span><span class="sita-meta-label">Total sinonim</span></div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('sita.synonyms.export', request()->query()) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Export CSV</a>
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#synonymImportModal"><i class="bi bi-upload me-1"></i>Import CSV</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#synonymCreateModal"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label small fw-semibold" for="sitaSynonymSearch">Cari</label><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="sitaSynonymSearch" type="search" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Istilah atau bentuk baku"></div></div>
      <div class="col-md-2"><label class="form-label small fw-semibold" for="sitaSynonymLocale">Bahasa</label><select id="sitaSynonymLocale" name="locale" class="form-select"><option value="">Semua</option><option value="id" @selected($filters['locale']==='id')>Indonesia</option><option value="en" @selected($filters['locale']==='en')>English</option></select></div>
      <div class="col-md-2"><label class="form-label small fw-semibold" for="sitaSynonymTopic">Topik</label><select id="sitaSynonymTopic" name="topic" class="form-select"><option value="">Semua</option>@foreach(['packages','venues','vendors'] as $topic)<option value="{{ $topic }}" @selected($filters['topic']===$topic)>{{ $topic }}</option>@endforeach</select></div>
      <div class="col-md-2"><label class="form-label small fw-semibold" for="sitaSynonymActive">Status</label><select id="sitaSynonymActive" name="is_active" class="form-select"><option value="">Semua</option><option value="1" @selected($filters['active']==='1')>Aktif</option><option value="0" @selected($filters['active']==='0')>Nonaktif</option></select></div>
      <div class="col-md-2 d-flex gap-2"><button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>@if($hasFilters)<a href="{{ route('sita.synonyms.index') }}" class="btn btn-light w-100">Reset</a>@endif</div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-0 d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
    <div><h6 class="mb-0">Daftar Sinonim</h6><div class="text-muted small">Menampilkan {{ $synonyms->firstItem() ?? 0 }}–{{ $synonyms->lastItem() ?? 0 }} dari {{ $synonyms->total() }}</div></div>
    <form method="POST" action="{{ route('sita.synonyms.bulk') }}" id="synonymBulkForm" class="d-flex gap-2 align-items-center">
      @csrf
      <select name="action" class="form-select form-select-sm" style="width:auto"><option value="activate">Aktifkan</option><option value="deactivate">Nonaktifkan</option><option value="delete">Hapus</option></select>
      <button type="submit" class="btn btn-sm btn-outline-primary" id="synonymBulkBtn" disabled>Terapkan</button>
    </form>
  </div>
  <div class="table-responsive sita-table-responsive">
    <table class="table align-middle mb-0 sita-table">
      <thead class="table-light"><tr><th style="width:36px"><input type="checkbox" class="form-check-input" id="synonymCheckAll"></th><th>Bahasa</th><th>Istilah</th><th>Bentuk Baku</th><th>Topik</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
      <tbody>
        @forelse($rows as $synonym)
        <tr>
          <td><input type="checkbox" class="form-check-input synonym-row-check" name="uuids[]" value="{{ $synonym->uuid }}" form="synonymBulkForm"></td>
          <td><span class="badge bg-light text-dark">{{ strtoupper($synonym->locale) }}</span></td>
          <td class="fw-semibold">{{ $synonym->term }}</td>
          <td>{{ $synonym->canonical }}</td>
          <td>{{ $synonym->topic ?: '-' }}</td>
          <td>{{ $synonym->sort_order }}</td>
          <td><span class="badge {{ $synonym->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $synonym->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
          <td class="text-end"><div class="d-flex justify-content-end gap-1">
            <button class="btn btn-sm btn-outline-primary sita-action-btn sita-edit-synonym" type="button" data-bs-toggle="modal" data-bs-target="#synonymEditModal" data-uuid="{{ $synonym->uuid }}" data-locale="{{ $synonym->locale }}" data-term="{{ $synonym->term }}" data-canonical="{{ $synonym->canonical }}" data-topic="{{ $synonym->topic }}" data-sort-order="{{ $synonym->sort_order }}" data-is-active="{{ $synonym->is_active ? '1':'0' }}" aria-label="Edit {{ $synonym->term }}"><i class="bi bi-pencil-square"></i></button>
            <form method="POST" action="{{ route('sita.synonyms.destroy', $synonym->uuid) }}" class="d-inline sita-delete-form" data-name="{{ $synonym->term }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger sita-action-btn" type="submit"><i class="bi bi-trash3"></i></button></form>
          </div></td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="sita-empty"><i class="bi bi-list-check"></i><div class="fw-semibold">Belum ada sinonim.</div><div class="text-muted small">Tambahkan alias agar Sita memahami istilah lokal.</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($synonyms->hasPages())
  <div class="card-footer sita-pagination-footer">
    <div class="sita-pagination-summary">
      Menampilkan <strong>{{ $synonyms->firstItem() }}</strong>–<strong>{{ $synonyms->lastItem() }}</strong>
      dari <strong>{{ $synonyms->total() }}</strong> sinonim
    </div>
    <nav class="sita-pagination-nav" aria-label="Navigasi halaman sinonim">
      {{ $synonyms->onEachSide(1)->links('pagination::bootstrap-5') }}
    </nav>
  </div>
  @else
  @if($synonyms->total() > 0)
  <div class="card-footer sita-pagination-footer">
    <div class="sita-pagination-summary">
      Menampilkan <strong>{{ $synonyms->total() }}</strong> sinonim
    </div>
  </div>
  @endif
  @endif
</div>

<div class="modal fade" id="synonymEditModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
  <form id="synonymEditForm" method="POST" action="{{ $editAction }}" data-base-action="{{ $editAction }}">@csrf @method('PUT')
    <div class="modal-header"><h5 class="modal-title" id="synonymEditTitle">Edit Sinonim</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">@include('pages.sita.synonyms._form', ['synonym' => null, 'prefix' => 'edit'])</div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div></div>

<div class="modal fade" id="synonymCreateModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
  <form method="POST" action="{{ route('sita.synonyms.store') }}">@csrf
    <div class="modal-header"><h5 class="modal-title">Tambah Sinonim</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">@include('pages.sita.synonyms._form', ['synonym' => null, 'prefix' => 'create'])</div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
  </form>
</div></div></div>

<div class="modal fade" id="synonymImportModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
  <form method="POST" action="{{ route('sita.synonyms.import') }}" enctype="multipart/form-data">@csrf
    <div class="modal-header"><h5 class="modal-title">Import CSV Sinonim</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p class="small text-muted">Kolom: <code>locale,term,canonical,topic,sort_order,is_active</code>. Baris dengan locale+term sama akan di-update.</p><input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required></div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Import</button></div>
  </form>
</div></div></div>
@endsection

@push('after-script')
<script>
(function () {
    document.querySelectorAll('.sita-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) { event.preventDefault(); window.sagitaConfirm({ name: form.dataset.name }).then(function (r) { if (r.isConfirmed) form.submit(); }); });
    });
    const editModal = document.getElementById('synonymEditModal');
    const editForm = document.getElementById('synonymEditForm');
    if (editForm && editModal) {
        const baseAction = editForm.dataset.baseAction;
        const title = document.getElementById('synonymEditTitle');
        function setField(name, value) { const f = editForm.querySelector('[name="' + name + '"]'); if (!f) return; if (f.type === 'checkbox') { f.checked = String(value) === '1'; return; } f.value = value ?? ''; }
        document.querySelectorAll('.sita-edit-synonym').forEach(function (btn) {
            btn.addEventListener('click', function () {
                editForm.action = baseAction.replace('__UUID__', encodeURIComponent(btn.dataset.uuid || ''));
                if (title) title.textContent = 'Edit ' + (btn.dataset.term || 'Sinonim');
                setField('locale', btn.dataset.locale || 'id'); setField('term', btn.dataset.term || ''); setField('canonical', btn.dataset.canonical || '');
                setField('topic', btn.dataset.topic || ''); setField('sort_order', btn.dataset.sortOrder || '0'); setField('is_active', btn.dataset.isActive || '0');
            });
        });
        editModal.addEventListener('hidden.bs.modal', function () { editForm.reset(); setField('is_active', '1'); });
    }
    const checkAll = document.getElementById('synonymCheckAll');
    const checks = document.querySelectorAll('.synonym-row-check');
    const bulkBtn = document.getElementById('synonymBulkBtn');
    function syncBulk() { const any = Array.from(checks).some(c => c.checked); if (bulkBtn) bulkBtn.disabled = !any; }
    if (checkAll) checkAll.addEventListener('change', function () { checks.forEach(c => c.checked = checkAll.checked); syncBulk(); });
    checks.forEach(c => c.addEventListener('change', syncBulk));
    const bulkForm = document.getElementById('synonymBulkForm');
    if (bulkForm) bulkForm.addEventListener('submit', function (e) { if (bulkForm.querySelector('[name=action]').value === 'delete') { e.preventDefault(); window.sagitaConfirm({ name: 'sinonim terpilih' }).then(r => { if (r.isConfirmed) bulkForm.submit(); }); } });
})();
</script>
@endpush
