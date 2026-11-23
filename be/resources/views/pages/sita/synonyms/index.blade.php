@extends('layouts.app')
@section('title', 'Sinonim Sita')
@section('breadcrumb')
<x-breadcrumb title="Sinonim Sita" page="Sita" active="Sinonim" route="{{ route('sita.synonyms.index') }}" />
@endsection

@section('content')
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <h5 class="mb-1">Sinonim Sita</h5>
      <p class="text-muted small mb-0">Istilah dan alias yang dipakai untuk menormalkan pertanyaan Sita.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#synonymCreateModal"><i class="bi bi-plus-lg me-1"></i>Tambah Sinonim</button>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr><th>Bahasa</th><th>Istilah</th><th>Bentuk Baku</th><th>Topik</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($synonyms as $synonym)
        <tr>
          <td>{{ strtoupper($synonym->locale) }}</td>
          <td>{{ $synonym->term }}</td>
          <td>{{ $synonym->canonical }}</td>
          <td>{{ $synonym->topic ?? '-' }}</td>
          <td>{{ $synonym->sort_order }}</td>
          <td>
            <span class="badge {{ $synonym->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $synonym->is_active ? 'Aktif' : 'Nonaktif' }}</span>
          </td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#synonymEditModal-{{ $synonym->uuid }}">Edit</button>
            <form method="POST" action="{{ route('sita.synonyms.destroy', $synonym->uuid) }}" class="d-inline" onsubmit="return confirm('Hapus sinonim {{ $synonym->term }}?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>

        <div class="modal fade" id="synonymEditModal-{{ $synonym->uuid }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <form method="POST" action="{{ route('sita.synonyms.update', $synonym->uuid) }}">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit {{ $synonym->term }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                  @include('pages.sita.synonyms._form', ['synonym' => $synonym, 'prefix' => $synonym->uuid])
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
              </form>
            </div>
          </div>
        </div>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada sinonim.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($synonyms->hasPages())
  <div class="card-footer bg-white">{{ $synonyms->links() }}</div>
  @endif
</div>

<div class="modal fade" id="synonymCreateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="POST" action="{{ route('sita.synonyms.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Tambah Sinonim</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          @include('pages.sita.synonyms._form', ['synonym' => null, 'prefix' => 'create'])
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
