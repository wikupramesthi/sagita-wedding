@extends('layouts.app')
@section('title', 'Pengaturan Alur Pesanan')
@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Pengaturan alur pesanan</h1>
            <p class="text-muted mb-0">Atur naskah kontrak dan checklist persiapan untuk pesanan baru.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali ke Orders</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('orders.workflow-settings.update') }}">
        @csrf
        @method('PUT')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Template kontrak digital</div>
            <div class="card-body">
                <p class="small text-muted">Gunakan placeholder: <code>&#123;&#123;document_number&#125;&#125;</code>, <code>&#123;&#123;issued_at&#125;&#125;</code>, <code>&#123;&#123;customer_name&#125;&#125;</code>, <code>&#123;&#123;order_number&#125;&#125;</code>, <code>&#123;&#123;package_name&#125;&#125;</code>, <code>&#123;&#123;option_name&#125;&#125;</code>, <code>&#123;&#123;event_date&#125;&#125;</code>, <code>&#123;&#123;total_amount&#125;&#125;</code>, <code>&#123;&#123;amount_paid&#125;&#125;</code>, dan <code>&#123;&#123;remaining_amount&#125;&#125;</code>.</p>
                <textarea class="form-control" name="contract_template" rows="18" maxlength="12000" required>{{ old('contract_template', $settings->contract_template) }}</textarea>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Checklist persiapan</div>
            <div class="card-body">
                <div id="checklist-template-items" class="d-grid gap-3">
                    @foreach (old('checklist', $settings->checklist_template ?? []) as $index => $item)
                        <div class="border rounded-3 p-3 checklist-template-row">
                            <div class="row g-2">
                                <div class="col-lg-4">
                                    <label class="form-label small">Nama tugas</label>
                                    <input class="form-control" name="checklist[{{ $index }}][title]" maxlength="120" value="{{ $item['title'] ?? '' }}" required>
                                </div>
                                <div class="col-lg-5">
                                    <label class="form-label small">Keterangan</label>
                                    <input class="form-control" name="checklist[{{ $index }}][description]" maxlength="500" value="{{ $item['description'] ?? '' }}">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Minggu sebelum acara</label>
                                    <input type="number" min="0" max="104" class="form-control" name="checklist[{{ $index }}][weeks_before_event]" value="{{ $item['weeks_before_event'] ?? 0 }}" required>
                                </div>
                                <div class="col-lg-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-checklist-row" aria-label="Hapus tugas"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-checklist-row" class="btn btn-sm btn-outline-primary mt-3">
                    <i class="bi bi-plus-lg me-1"></i>Tambah tugas
                </button>
            </div>
        </div>

        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan template</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('checklist-template-items');
        const addButton = document.getElementById('add-checklist-row');
        const syncButtons = () => {
            const rows = container.querySelectorAll('.checklist-template-row');
            addButton.disabled = rows.length >= 20;
            container.querySelectorAll('.remove-checklist-row').forEach(button => button.disabled = rows.length <= 1);
        };

        addButton.addEventListener('click', () => {
            if (container.querySelectorAll('.checklist-template-row').length >= 20) return;
            const index = container.querySelectorAll('.checklist-template-row').length;
            const row = document.createElement('div');
            row.className = 'border rounded-3 p-3 checklist-template-row';
            row.innerHTML = `
                <div class="row g-2">
                    <div class="col-lg-4"><label class="form-label small">Nama tugas</label><input class="form-control" name="checklist[${index}][title]" maxlength="120" required></div>
                    <div class="col-lg-5"><label class="form-label small">Keterangan</label><input class="form-control" name="checklist[${index}][description]" maxlength="500"></div>
                    <div class="col-lg-2"><label class="form-label small">Minggu sebelum acara</label><input type="number" min="0" max="104" class="form-control" name="checklist[${index}][weeks_before_event]" value="0" required></div>
                    <div class="col-lg-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger w-100 remove-checklist-row" aria-label="Hapus tugas"><i class="bi bi-trash"></i></button></div>
                </div>`;
            container.appendChild(row);
            syncButtons();
        });

        container.addEventListener('click', event => {
            const remove = event.target.closest('.remove-checklist-row');
            if (remove) {
                remove.closest('.checklist-template-row').remove();
                container.querySelectorAll('.checklist-template-row').forEach((row, index) => {
                    row.querySelectorAll('[name]').forEach(input => {
                        input.name = input.name.replace(/checklist\[\d+\]/, `checklist[${index}]`);
                    });
                });
                syncButtons();
            }
        });
        syncButtons();
    });
</script>
@endsection
