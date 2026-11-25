@extends('layouts.app')

@section('title', 'Kalender Acara')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Kalender acara</h1>
            <p class="text-muted mb-0">Pesanan aktif, penugasan vendor, dan checklist persiapan berdasarkan tanggal acara.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Daftar pesanan</a>
            <a href="{{ route('orders.cancellations.index') }}" class="btn btn-outline-primary">Pembatalan</a>
        </div>
    </div>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <a href="{{ route('orders.calendar', ['month' => $previousMonth]) }}" class="btn btn-sm btn-outline-secondary" aria-label="Bulan sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
            <h2 class="h5 mb-0">{{ $month->translatedFormat('F Y') }}</h2>
            <a href="{{ route('orders.calendar', ['month' => $nextMonth]) }}" class="btn btn-sm btn-outline-secondary" aria-label="Bulan berikutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="order-calendar-scroll">
                <div class="order-calendar-grid" role="grid" aria-label="Kalender acara {{ $month->translatedFormat('F Y') }}">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                        <div class="order-calendar-weekday" role="columnheader">{{ $weekday }}</div>
                    @endforeach
                    @foreach ($days as $day)
                        @php
                            $dayOrders = $ordersByDate->get($day->toDateString(), collect());
                        @endphp
                        <section class="order-calendar-day {{ $day->month !== $month->month ? 'is-outside-month' : '' }} {{ $day->isToday() ? 'is-today' : '' }}" role="gridcell">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="order-calendar-date">{{ $day->day }}</span>
                                @if ($dayOrders->isNotEmpty())
                                    <span class="badge text-bg-light">{{ $dayOrders->count() }}</span>
                                @endif
                            </div>
                            <div class="d-grid gap-1">
                                @foreach ($dayOrders as $order)
                                    @php
                                        $statusTone = match ($order->status) {
                                            'paid' => 'success',
                                            'partially_paid' => 'primary',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <a href="{{ route('orders.show', $order->uuid) }}" class="order-calendar-event text-decoration-none">
                                        <span class="badge text-bg-{{ $statusTone }}">{{ $order->status === 'paid' ? 'Lunas' : ($order->status === 'partially_paid' ? 'DP' : 'Menunggu') }}</span>
                                        <strong class="d-block text-truncate">{{ $order->user?->name ?? 'Pelanggan' }}</strong>
                                        <span class="d-block text-truncate">{{ $order->customPackageOffer?->title ?? $order->package?->name ?? $order->order_number }}</span>
                                        <small class="d-block text-muted">{{ $order->vendor_assignments_count }} vendor · {{ $order->checklist_items_count }} checklist</small>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
            @if ($ordersByDate->isEmpty())
                <p class="text-center text-muted small mb-0 mt-3">Belum ada acara aktif pada bulan ini.</p>
            @endif
        </div>
    </section>
</div>
@endsection
