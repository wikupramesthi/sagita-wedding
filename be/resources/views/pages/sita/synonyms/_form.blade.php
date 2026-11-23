@php $s = $synonym; @endphp
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">Bahasa <span class="text-danger">*</span></label>
    <select name="locale" class="form-select" required>
      <option value="id" @selected(old('locale', $s?->locale ?? 'id') === 'id')>Indonesia</option>
      <option value="en" @selected(old('locale', $s?->locale) === 'en')>English</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Istilah <span class="text-danger">*</span></label>
    <input type="text" name="term" class="form-control" required maxlength="80" value="{{ old('term', $s?->term) }}" placeholder="Mis. photographer">
  </div>
  <div class="col-md-5">
    <label class="form-label">Bentuk Baku <span class="text-danger">*</span></label>
    <input type="text" name="canonical" class="form-control" required maxlength="80" value="{{ old('canonical', $s?->canonical) }}" placeholder="Mis. fotografi">
  </div>
  <div class="col-md-4">
    <label class="form-label">Topik</label>
    <select name="topic" class="form-select">
      <option value="">Semua</option>
      @foreach(['packages', 'venues', 'vendors'] as $topic)
      <option value="{{ $topic }}" @selected(old('topic', $s?->topic) === $topic)>{{ $topic }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Urutan</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $s?->sort_order ?? 0) }}">
  </div>
  <div class="col-md-5 d-flex align-items-end">
    <div class="form-check form-switch mb-2">
      <input class="form-check-input" type="checkbox" value="1" id="is_active_{{ $prefix }}" name="is_active" @checked(old('is_active', $s?->is_active ?? true))>
      <label class="form-check-label" for="is_active_{{ $prefix }}">Aktif</label>
    </div>
  </div>
</div>
