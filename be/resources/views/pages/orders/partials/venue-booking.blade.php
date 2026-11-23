@php
$isAdmin = $isAdmin ?? false;
$venues = $venues ?? collect();
$bookingStatusLabels = [
    'none' => 'Belum dipesan',
    'requested' => 'Diajukan',
    'confirmed' => 'Terkonfirmasi',
    'cancelled' => 'Dibatalkan',
];
@endphp

@if ($isAdmin)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pb-0">
        <div>
            <h5 class="mb-1">Booking Venue</h5>
            <p class="text-muted small mb-0">Kelola status pengikatan venue untuk tanggal acara.</p>
        </div>
        <span class="badge bg-light text-dark border">{{ $bookingStatusLabels[$order->venue_booking_status] ?? $order->venue_booking_status }}</span>
    </div>
    <div class="card-body p-4">
        @if ($order->venue)
        <div class="alert alert-light border small mb-4">
            <strong>Venue terpilih:</strong> {{ $order->venue->name }}
            @if ($order->venue_booking_at)
            <br><strong>Tanggal booking:</strong> {{ $order->venue_booking_at->format('d M Y H:i') }}
            @endif
            @if ($order->venue_booking_amount !== null)
            <br><strong>Nominal:</strong> Rp{{ number_format((float) $order->venue_booking_amount, 0, ',', '.') }}
            @endif
            @if ($order->venue_booking_note)
            <br><strong>Catatan:</strong> {{ $order->venue_booking_note }}
            @endif
        </div>
        @endif

        <form action="{{ route('orders.venue-booking.update', $order->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="venue_uuid" class="form-label small fw-semibold">Venue</label>
                    <select id="venue_uuid" name="venue_uuid" class="form-select form-select-sm">
                        <option value="">Belum ditentukan</option>
                        @foreach ($venues as $venue)
                        <option value="{{ $venue->uuid }}" @selected(old('venue_uuid', $order->venue_uuid) === $venue->uuid)>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="venue_booking_status" class="form-label small fw-semibold">Status Booking</label>
                    <select id="venue_booking_status" name="venue_booking_status" class="form-select form-select-sm" required>
                        @foreach ($bookingStatusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(old('venue_booking_status', $order->venue_booking_status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="venue_booking_at" class="form-label small fw-semibold">Tanggal Booking</label>
                    <input type="datetime-local" id="venue_booking_at" name="venue_booking_at" class="form-control form-control-sm" value="{{ old('venue_booking_at', optional($order->venue_booking_at)->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="venue_booking_amount" class="form-label small fw-semibold">Nominal Booking</label>
                    <input type="number" step="0.01" min="0" id="venue_booking_amount" name="venue_booking_amount" class="form-control form-control-sm" value="{{ old('venue_booking_amount', $order->venue_booking_amount) }}">
                </div>

                <div class="col-12">
                    <label for="venue_booking_note" class="form-label small fw-semibold">Catatan</label>
                    <textarea id="venue_booking_note" name="venue_booking_note" rows="2" class="form-control form-control-sm">{{ old('venue_booking_note', $order->venue_booking_note) }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Booking Venue
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
