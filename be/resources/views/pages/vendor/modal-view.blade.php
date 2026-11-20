<div class="modal fade" id="modal-form-view-vendor-{{ $vendor->uuid }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Detail Vendor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center">
                        @if ($vendor->avatar)
                        <img src="{{ asset('storage/' . $vendor->avatar) }}" alt="Foto {{ $vendor->name }}"
                            class="img-fluid rounded-circle shadow" style="max-width: 150px;">
                        @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width:150px; height:150px; margin:auto;">
                            <i class="bi bi-person fs-1 text-muted"></i>
                        </div>
                        @endif
                        <h5 class="mt-3 mb-0">{{ $vendor->name }}</h5>
                    </div>

                    <div class="col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-muted" style="width: 35%;">Kategori Vendor</th>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse ($vendor->vendors_categories as $vendors_category)
                                        <span class="badge bg-primary">
                                            {{ $vendors_category->name }}
                                        </span>
                                        @empty
                                        <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Nomor HP</th>
                                <td>{{ $vendor->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Pengalaman</th>
                                <td>
                                    {{ $vendor->pengalaman ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Biografi</th>
                                <td class="text-wrap" style="white-space: normal; word-break: break-word;">
                                    {{ $vendor->biografi ?? '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>