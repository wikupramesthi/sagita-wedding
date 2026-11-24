@extends('layouts.app')
@section('title', 'Prospek Sita')
@section('breadcrumb')
<x-breadcrumb title="Prospek Sita" page="Sita" active="Prospek" route="{{ route('sita.leads.index') }}" />
@endsection

@section('content')
@php
  $filters = $filters ?? ['search'=>'','status'=>'','topic'=>'','score_min'=>null,'score_max'=>null,'from'=>'','to'=>''];
  $statuses = ['new'=>['Baru','bg-primary-subtle text-primary-emphasis'],'contacted'=>['Dihubungi','bg-info-subtle text-info-emphasis'],'qualified'=>['Terkualifikasi','bg-warning-subtle text-warning-emphasis'],'converted'=>['Terkonversi','bg-success-subtle text-success-emphasis'],'closed'=>['Ditutup','bg-secondary-subtle text-secondary-emphasis']];
  $criteriaLabels = ['budgetLimit'=>'Budget','guestCount'=>'Tamu','city'=>'Kota','eventDate'=>'Tanggal','vendorCategory'=>'Vendor'];
  $criteriaChips = static function ($criteria) use ($criteriaLabels): array {
      $chips=[]; foreach ((is_array($criteria)?$criteria:[]) as $k=>$v){ if(!is_scalar($v)||$v==='') continue; if($k==='budgetLimit') $v='Rp '.number_format((float)$v,0,',','.'); $chips[]=['label'=>$criteriaLabels[$k]??$k,'value'=>(string)$v]; } return $chips;
  };
  $canUpdate = auth()->user()?->can('sita.leads.update');
@endphp

<div class="card shadow-sm border-0 mb-4"><div class="card-body"><div class="sita-hero"><div class="sita-hero-icon"><i class="bi bi-person-lines-fill"></i></div><div class="flex-grow-1"><h5 class="mb-1">Prospek Sita</h5><p class="text-muted small mb-0">Calon klien dari percakapan Sita. Data kontak hanya untuk tindak lanjut tim.</p></div><div class="sita-hero-meta"><span class="sita-meta-value">{{ $leads->total() }}</span><span class="sita-meta-label">Total prospek</span></div><a href="{{ route('sita.leads.export', request()->query()) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Export CSV</a></div></div></div>

<div class="card shadow-sm border-0 mb-4 sita-filter-card"><div class="card-body">
  <form method="GET" class="row g-3 align-items-end sita-filter-grid">
    <div class="col-md-3"><label class="form-label small fw-semibold">Cari</label><input type="search" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="Nama atau telepon"></div>
    <div class="col-md-2"><label class="form-label small fw-semibold">Status</label><select name="status" class="form-select"><option value="">Semua</option>@foreach($statuses as $v=>[$l])<option value="{{ $v }}" @selected($filters['status']===$v)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-2"><label class="form-label small fw-semibold">Topik</label><select name="topic" class="form-select"><option value="">Semua</option>@foreach(['packages','venues','vendors'] as $t)<option value="{{ $t }}" @selected($filters['topic']===$t)>{{ $t }}</option>@endforeach</select></div>
    <div class="col-md-1"><label class="form-label small fw-semibold">Skor min</label><input type="number" name="score_min" min="0" max="100" value="{{ $filters['score_min'] }}" class="form-control"></div>
    <div class="col-md-1"><label class="form-label small fw-semibold">Skor max</label><input type="number" name="score_max" min="0" max="100" value="{{ $filters['score_max'] }}" class="form-control"></div>
    <div class="col-md-1"><label class="form-label small fw-semibold">Dari</label><input type="date" name="from" value="{{ $filters['from'] }}" class="form-control"></div>
    <div class="col-md-1"><label class="form-label small fw-semibold">Sampai</label><input type="date" name="to" value="{{ $filters['to'] }}" class="form-control"></div>
    <div class="col-md-1 d-flex gap-2"><button class="btn btn-outline-primary w-100" type="submit">Filter</button></div>
  </form>
</div></div>

<div class="card shadow-sm border-0"><div class="table-responsive sita-table-responsive"><table class="table align-middle mb-0 sita-table sita-table--wide"><thead class="table-light"><tr><th>Nama</th><th>Telepon</th><th>Topik</th><th>Kriteria</th><th>Skor</th><th>Status</th><th>Diperbarui</th><th class="text-end">Aksi</th></tr></thead><tbody>
@forelse($leads as $lead)
@php [$statusLabel,$statusClass] = $statuses[$lead->status] ?? [$lead->status,'bg-secondary-subtle text-secondary-emphasis']; @endphp
<tr>
  <td><a href="{{ route('sita.leads.show', $lead->uuid) }}" class="fw-semibold text-decoration-none">{{ $lead->name }}</a>@if($lead->message)<div class="text-muted small text-truncate" style="max-width:180px" title="{{ $lead->message }}">{{ $lead->message }}</div>@endif</td>
  <td>{{ $lead->phone }}</td><td>{{ $lead->topic ?? '-' }}</td>
  <td class="sita-cell-wrap">@php $chips=$criteriaChips($lead->criteria); @endphp @if($chips)<div class="sita-criteria">@foreach($chips as $chip)<span class="sita-chip">{{ $chip['label'] }}: {{ $chip['value'] }}</span>@endforeach</div>@else<span class="text-muted">-</span>@endif</td>
  <td><span class="badge bg-light text-dark">{{ $lead->score }}</span></td>
  <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>@if($canUpdate)<form method="POST" action="{{ route('sita.leads.update', $lead->uuid) }}" class="d-flex gap-1 mt-2">@csrf @method('PUT')<select name="status" class="form-select form-select-sm">@foreach($statuses as $v=>[$l])<option value="{{ $v }}" @selected($lead->status===$v)>{{ $l }}</option>@endforeach</select><button class="btn btn-sm btn-outline-primary sita-action-btn" type="submit"><i class="bi bi-check2"></i></button></form>@endif</td>
  <td class="small text-muted">{{ $lead->updated_at?->format('d/m/Y H:i') }}</td>
  <td class="text-end"><div class="d-flex justify-content-end gap-1"><a href="{{ route('sita.leads.show', $lead->uuid) }}" class="btn btn-sm btn-outline-secondary sita-action-btn"><i class="bi bi-eye"></i></a>@if($canUpdate)<form method="POST" action="{{ route('sita.leads.destroy', $lead->uuid) }}" class="d-inline sita-delete-form" data-name="{{ $lead->name }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger sita-action-btn" type="submit"><i class="bi bi-trash3"></i></button></form>@endif</div></td>
</tr>
@empty<tr><td colspan="8"><div class="sita-empty"><i class="bi bi-person-x"></i><div class="fw-semibold">Belum ada prospek.</div></div></td></tr>@endforelse
</tbody></table></div>@if($leads->hasPages())<div class="card-footer">{{ $leads->links() }}</div>@endif</div>
@endsection
@push('after-script')
<script>document.querySelectorAll('.sita-delete-form').forEach(function(f){f.addEventListener('submit',function(e){e.preventDefault();window.sagitaConfirm({name:f.dataset.name}).then(function(r){if(r.isConfirmed)f.submit();});});});</script>
@endpush
