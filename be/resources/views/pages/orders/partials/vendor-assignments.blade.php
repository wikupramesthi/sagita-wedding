@php
    $canManage = auth()->user()?->can('manageVendors', \App\Models\Payment\Order::class);
    $statuses = \App\Models\Payment\OrderVendorAssignment::STATUS_LABELS;
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>Vendor & Penugasan</span>
        <span class="badge text-bg-light">{{ $order->vendorAssignments->count() }} vendor</span>
    </div>
    <div class="card-body">
        @if($canManage)
            <form action="{{ route('orders.vendors.assign', $order->uuid) }}" method="POST" class="row g-2 mb-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label small mb-1">Vendor</label>
                    <select name="vendor_uuid" class="form-select form-select-sm" required>
                        <option value="">Pilih vendor</option>
                        @foreach($vendors ?? [] as $vendor)
                            <option value="{{ $vendor->uuid }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Scope</label>
                    <input type="text" name="scope" class="form-control form-control-sm" maxlength="80" placeholder="Dekorasi, foto, dll" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Catatan</label>
                    <input type="text" name="notes" class="form-control form-control-sm" maxlength="500" placeholder="Opsional">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-sm btn-primary w-100">Tugaskan</button>
                </div>
            </form>
        @endif

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Scope</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Waktu</th>
                        @if($canManage)<th>Aksi</th>@endif
                    </tr>
                </thead>
                <tbody>
                @forelse($order->vendorAssignments as $assignment)
                    <tr>
                        <td>{{ $assignment->vendor?->name ?? '-' }}</td>
                        <td>{{ $assignment->scope }}</td>
                        <td><span class="badge {{ $assignment->status === 'completed' ? 'bg-success' : ($assignment->status === 'cancelled' ? 'bg-secondary' : 'bg-primary-subtle text-primary border') }}">{{ $statuses[$assignment->status] ?? $assignment->status }}</span></td>
                        <td class="small">{{ $assignment->notes ?: '-' }}</td>
                        <td class="small text-muted">
                            Ditugaskan: {{ $assignment->created_at?->format('d/m/Y H:i') ?? '-' }}
                            @if($assignment->accepted_at)<br>Diterima: {{ $assignment->accepted_at->format('d/m/Y H:i') }}@endif
                            @if($assignment->completed_at)<br>Selesai: {{ $assignment->completed_at->format('d/m/Y H:i') }}@endif
                        </td>
                        @if($canManage)
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <form action="{{ route('orders.vendors.status', [$order->uuid, $assignment->uuid]) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" style="min-width:130px">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected($assignment->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Update</button>
                                </form>
                                <form action="{{ route('orders.vendors.destroy', [$order->uuid, $assignment->uuid]) }}" method="POST" onsubmit="return confirm('Hapus penugasan vendor ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ $canManage ? 6 : 5 }}" class="text-center text-muted small py-3">Belum ada vendor ditugaskan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
