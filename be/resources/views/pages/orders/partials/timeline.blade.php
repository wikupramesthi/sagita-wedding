@php
$milestones = [];

if ($order->event_date) {
    $milestones = [
        [
            'label' => 'H-30 — Pelunasan',
            'date' => $order->event_date->copy()->subDays(30),
            'detail' => $order->balance_due_at ? 'Batas bayar: '.$order->balance_due_at->format('d M Y') : 'Pelunasan sebelum tanggal acara.',
            'done' => in_array($order->status, ['paid'], true) || (float) $order->remaining_amount_value <= 0.01,
        ],
        [
            'label' => 'H-14 — Konfirmasi Vendor',
            'date' => $order->event_date->copy()->subDays(14),
            'detail' => $order->vendorAssignments->isEmpty()
                ? 'Belum ada vendor yang ditugaskan.'
                : $order->vendorAssignments->map(fn ($assignment) => ($assignment->vendor?->name ?? 'Vendor').' ('.($assignment->status ?? '-').')')->implode(', '),
            'done' => $order->vendorAssignments->isNotEmpty() && $order->vendorAssignments->every(fn ($assignment) => in_array($assignment->status, ['confirmed', 'completed'], true)),
        ],
        [
            'label' => 'H-7 — Konfirmasi Venue',
            'date' => $order->event_date->copy()->subDays(7),
            'detail' => $order->venue
                ? $order->venue->name.' — '.($order->venue_booking_status ?: 'none')
                : 'Venue belum dipilih.',
            'done' => $order->venue_booking_status === 'confirmed',
        ],
        [
            'label' => 'H-1 — Finalisasi Checklist',
            'date' => $order->event_date->copy()->subDay(),
            'detail' => $order->checklistItems->isEmpty()
                ? 'Belum ada checklist persiapan.'
                : $order->checklistItems->where('status', 'completed')->count().'/'.$order->checklistItems->count().' checklist selesai.',
            'done' => $order->checklistItems->isNotEmpty() && $order->checklistItems->every(fn ($item) => $item->status === 'completed'),
        ],
        [
            'label' => 'Hari-H — Acara',
            'date' => $order->event_date->copy(),
            'detail' => $order->venue ? 'Venue: '.$order->venue->name : 'Lokasi belum ditentukan.',
            'done' => false,
        ],
    ];
}
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent border-0 pb-0">
        <h5 class="mb-1">Timeline Hari-H</h5>
        <p class="text-muted small mb-0">Ringkasan milestone persiapan hingga tanggal acara.</p>
    </div>
    <div class="card-body p-4">
        @if (! $order->event_date)
        <div class="text-center text-muted py-4">
            <i class="bx bx-calendar-event fs-1 d-block mb-2"></i>
            <p class="small mb-0">Tanggal acara belum diatur. Timeline akan muncul setelah tanggal acara diisi.</p>
        </div>
        @else
        <div class="timeline-milestones">
            @foreach ($milestones as $milestone)
            <div class="d-flex gap-3 pb-3 {{ $loop->last ? 'pb-0' : '' }}">
                <div class="flex-shrink-0">
                    <span class="badge rounded-pill {{ $milestone['done'] ? 'bg-success' : 'bg-light text-dark border' }}">
                        <i class="bi {{ $milestone['done'] ? 'bi-check2' : 'bi-clock' }}"></i>
                    </span>
                </div>
                <div class="min-w-0 flex-grow-1 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <strong class="small">{{ $milestone['label'] }}</strong>
                        <span class="text-muted small">{{ $milestone['date']->format('d M Y') }}</span>
                    </div>
                    <div class="text-muted small mt-1">{{ $milestone['detail'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($order->checklistItems->whereNotNull('due_at')->isNotEmpty())
        <div class="mt-4 pt-3 border-top">
            <h6 class="small fw-bold text-uppercase text-muted mb-3">Jatuh Tempo Checklist</h6>
            @foreach ($order->checklistItems->whereNotNull('due_at')->sortBy('due_at') as $item)
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <span class="small {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</span>
                <span class="small text-muted">{{ $item->due_at->format('d M Y') }}</span>
            </div>
            @endforeach
        </div>
        @endif

        @if ($order->venue_booking_at)
        <div class="mt-3 pt-3 border-top">
            <span class="small text-muted">Booking venue:</span>
            <strong class="small ms-1">{{ $order->venue_booking_at->format('d M Y H:i') }}</strong>
        </div>
        @endif
        @endif
    </div>
</div>
