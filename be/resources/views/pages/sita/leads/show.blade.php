@extends('layouts.app')
@section('title', 'Detail Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Detail Prospek Sita" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
@endsection

@section('content')
@php
  $statuses = ['new'=>['Baru','bg-primary-subtle text-primary-emphasis'],'contacted'=>['Dihubungi','bg-info-subtle text-info-emphasis'],'qualified'=>['Terkualifikasi','bg-warning-subtle text-warning-emphasis'],'converted'=>['Terkonversi','bg-success-subtle text-success-emphasis'],'closed'=>['Ditutup','bg-secondary-subtle text-secondary-emphasis']];
  $criteriaLabels = ['budgetLimit'=>'Budget','guestCount'=>'Tamu','city'=>'Kota','eventDate'=>'Tanggal','vendorCategory'=>'Vendor'];
  $criteriaChips = static function ($criteria) use ($criteriaLabels): array { $chips=[]; foreach ((is_array($criteria)?$criteria:[]) as $k=>$v){ if(!is_scalar($v)||$v==='') continue; if($k==='budgetLimit') $v='Rp '.number_format((float)$v,0,',','.'); $chips[]=['label'=>$criteriaLabels[$k]??$k,'value'=>(string)$v]; } return $chips; };
  [$statusLabel,$statusClass] = $statuses[$lead->status] ?? [$lead->status,'bg-secondary-subtle text-secondary-emphasis'];
  $canUpdate = auth()->user()?->can('sita.leads.update');
@endphp

<div class="card shadow-sm border-0 mb-4"><div class="card-body"><div class="sita-hero"><div class="sita-hero-icon"><i class="bi bi-person-badge"></i></div><div class="flex-grow-1"><h5 class="mb-1">{{ $lead->name }}</h5><p class="text-muted small mb-0">Detail prospek dari percakapan Sita.</p></div><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></div></div></div>

<div class="row g-3 mb-4">
  <div class="col-md-6"><div class="card shadow-sm border-0 h-100"><div class="card-body"><h6 class="fw-semibold mb-3">Kontak</h6>
    <div class="d-flex justify-content-between gap-3 border-bottom py-2"><span class="text-muted small">Telepon</span><a href="tel:{{ $lead->phone }}" class="fw-semibold text-decoration-none">{{ $lead->phone }}</a></div>
    <div class="d-flex justify-content-between gap-3 border-bottom py-2"><span class="text-muted small">Topik</span><span class="fw-semibold">{{ $lead->topic ?? '-' }}</span></div>
    <div class="d-flex justify-content-between gap-3 border-bottom py-2"><span class="text-muted small">Skor</span><span class="badge bg-light text-dark">{{ $lead->score }}</span></div>
    <div class="d-flex justify-content-between gap-3 py-2"><span class="text-muted small">Dibuat</span><span class="small">{{ $lead->created_at?->format('d M Y H:i') }}</span></div>
  </div></div></div>
  <div class="col-md-6"><div class="card shadow-sm border-0 h-100"><div class="card-body"><h6 class="fw-semibold mb-3">Kriteria</h6>@php $chips=$criteriaChips($lead->criteria); @endphp @if($chips)<div class="sita-criteria">@foreach($chips as $chip)<span class="sita-chip">{{ $chip['label'] }}: {{ $chip['value'] }}</span>@endforeach</div>@else<p class="text-muted small mb-0">Belum ada kriteria.</p>@endif</div></div></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6"><div class="card shadow-sm border-0 h-100"><div class="card-body"><h6 class="fw-semibold mb-3">Status & Catatan Internal</h6>
    @if($canUpdate)
    <form method="POST" action="{{ route('sita.leads.update', $lead->uuid) }}">@csrf @method('PUT')
      <div class="mb-2"><label class="form-label small">Status</label><select name="status" class="form-select">@foreach($statuses as $v=>[$l])<option value="{{ $v }}" @selected($lead->status===$v)>{{ $l }}</option>@endforeach</select></div>
      <div class="mb-2"><label class="form-label small">Catatan</label><textarea name="notes" class="form-control" rows="4" maxlength="2000">{{ old('notes', $lead->notes) }}</textarea></div>
      <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
    @else
    <p class="mb-0">{{ $lead->notes ?: '-' }}</p>
    @endif
  </div></div></div>
  <div class="col-md-6"><div class="card shadow-sm border-0 h-100"><div class="card-body"><h6 class="fw-semibold mb-3">Pesan Klien</h6><p class="text-muted small mb-0">{{ $lead->message ?: 'Tidak ada pesan tambahan.' }}</p></div></div></div>
</div>

<div class="card shadow-sm border-0 mb-4"><div class="card-body"><h6 class="fw-semibold mb-3">Timeline Aktivitas</h6>
  @if($canUpdate)
  <form method="POST" action="{{ route('sita.leads.notes.store', $lead->uuid) }}" class="mb-3">@csrf
    <div class="d-flex gap-2"><input type="text" name="note" class="form-control" maxlength="2000" placeholder="Tambah catatan tindak lanjut..." required><button class="btn btn-outline-primary">Tambah</button></div>
  </form>
  @endif
  <div class="sita-timeline">
    @forelse($lead->activities as $activity)
    <div class="border-start ps-3 pb-3 position-relative"><span class="position-absolute top-0 start-0 translate-middle-x bg-primary rounded-circle" style="width:10px;height:10px;margin-left:-1px"></span>
      <div class="small fw-semibold">{{ $activity->user_name ?: 'Sistem' }} — {{ $activity->action }} @if($activity->from_status || $activity->to_status)<span class="text-muted">({{ $activity->from_status }} → {{ $activity->to_status }})</span>@endif</div>
      <div class="small text-muted">{{ $activity->created_at?->format('d M Y H:i') }}</div>
      @if($activity->note)<div class="small mt-1">{{ $activity->note }}</div>@endif
    </div>
    @empty
    <div class="text-muted small">Belum ada aktivitas.</div>
    @endforelse
  </div>
</div></div>

<a href="{{ route('sita.leads.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
@endsection
