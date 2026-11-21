@php $p = $promo; @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control" required maxlength="50" value="{{ old('code', $p?->code) }}" placeholder="Mis. WEDDING10">
  </div>
  <div class="col-md-6">
    <label class="form-label">Cakupan Paket</label>
    <select name="package_uuid" class="form-select">
      <option value="" @selected(old('package_uuid',$p?->package_uuid)==='')>Semua paket (global)</option>
      @foreach($packages as $pkg)
        <option value="{{ $pkg->uuid }}" @selected(old('package_uuid',$p?->package_uuid)===$pkg->uuid)>{{ $pkg->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
    <select name="type" class="form-select" required>
      <option value="percent" @selected(old('type',$p?->type ?? 'percent')==='percent')>Persen (%)</option>
      <option value="fixed" @selected(old('type',$p?->type)==='fixed')>Nominal (Rp)</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
    <input type="number" name="value" class="form-control" required min="1" value="{{ old('value',$p?->value) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Minimal Pembelian</label>
    <input type="number" name="min_purchase" class="form-control" min="0" value="{{ old('min_purchase',$p?->min_purchase ?? 0) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Maksimal Diskon</label>
    <input type="number" name="max_discount" class="form-control" min="1" value="{{ old('max_discount',$p?->max_discount) }}" placeholder="Kosongkan jika tidak ada">
    <div class="form-text">Berguna untuk tipe persen.</div>
  </div>
  <div class="col-md-4">
    <label class="form-label">Maksimal Pemakaian</label>
    <input type="number" name="max_uses" class="form-control" min="1" value="{{ old('max_uses',$p?->max_uses) }}" placeholder="Kosongkan = tak terbatas">
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check form-switch mb-2">
      <input class="form-check-input" type="checkbox" value="1" id="is_active_{{ $prefix }}" name="is_active" @checked(old('is_active', $p?->is_active ?? true))>
      <label class="form-check-label" for="is_active_{{ $prefix }}">Aktif</label>
    </div>
  </div>
  <div class="col-md-6">
    <label class="form-label">Mulai Berlaku</label>
    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $fmtDate($p?->starts_at)) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Berakhir</label>
    <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', $fmtDate($p?->ends_at)) }}">
  </div>
</div>