@extends('layouts.mobile')
@section('title', __('mobile.membership_detail'))
@section('content')

@php
$rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
$avatar = $membership->user?->avatar_url;
@endphp

<section class="screen active" id="membership-detail">
    <div class="px-4 pt-4">

        <a href="{{ route('memberships.index') }}"
            class="text-dark text-decoration-none d-inline-flex align-items-center mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <i class="bi bi-arrow-left fs-5"></i>
                <span class="ms-2 small fw-semibold">{{ __('mobile.back') }}</span>
            </a>

        <p class="eyebrow mb-1">{{ __('mobile.membership') }}</p>
        <h1 class="fw-semibold mb-0" style="font-size: 28px;">{{ $membership->package?->name ?? __('mobile.membership') }}</h1>
        <p class="small text-muted2 mb-0 mt-1">
            <span class="badge rounded-pill {{ $membership->status === 'active' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">
                {{ __('mobile.membership_status.' . $membership->status) }}
            </span>
        </p>

        {{-- QUOTA CARD --}}
        <div class="app-card d-flex align-items-center gap-3 p-3 mt-3">
            <div class="grad-sage d-flex align-items-center justify-content-center flex-shrink-0"
                style="height: 48px; width: 48px; border-radius: 18px; overflow: hidden">
                @if ($avatar)
                    <img src="{{ $avatar }}" alt="{{ $membership->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span class="text-white fw-semibold">{{ strtoupper(substr($membership->user?->name ?? '?', 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-fill">
                <p class="mb-0 small fw-semibold">
                    @if (is_null($membership->quota))
                        {{ __('mobile.unlimited_classes') }}
                    @else
                        {{ $membership->quota == 1 ? __('mobile.class_left', ['count' => $membership->quota]) : __('mobile.classes_left', ['count' => $membership->quota]) }}
                    @endif
                </p>
                <p class="mb-0 text-muted2 m-meta">
                    {{ $membership->started_at?->format('d M Y') ?? '-' }}
                    →
                    {{ $membership->expired_at?->format('d M Y') ?? '-' }}
                </p>
            </div>
        </div>

        @if ($membership->started_at && $membership->expired_at)
            @php
                $total = max(1, $membership->started_at->diffInSeconds($membership->expired_at));
                $remaining = max(0, now()->diffInSeconds($membership->expired_at, false));
                $pct = min(100, max(0, $remaining / $total * 100));
            @endphp
            <div class="progress mt-2" style="height: 6px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background: var(--sage);"></div>
            </div>
        @endif

        {{-- PEMBAYARAN --}}
        <div class="app-card p-3 mt-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <p class="mb-0 small fw-semibold"><i class="bi bi-wallet2 me-1 text-terra"></i>{{ __('mobile.monitoring') }}</p>
                <span class="badge rounded-pill {{ $membership->payment_progress >= 100 ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }}">
                    {{ __('mobile.payment_status_label.' . $membership->payment_status_label) ?? $membership->payment_status_label }}
                </span>
            </div>
            <p class="mb-1 text-muted2 m-meta">{{ __('mobile.full_amount') }} <b class="text-dark">{{ $rupiah($membership->total_amount_value) }}</b></p>
            <div class="d-flex justify-content-between gap-2">
                <span class="text-muted2 m-meta">{{ __('mobile.paid') }} <b class="text-success">{{ $rupiah($membership->amount_paid_value) }}</b></span>
                <span class="text-muted2 m-meta">{{ __('mobile.remaining') }} <b class="{{ $membership->remaining_amount_value > 0 ? 'text-danger' : 'text-success' }}">{{ $rupiah($membership->remaining_amount_value) }}</b></span>
            </div>
            <div class="progress mt-2" style="height: 6px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $membership->payment_progress }}%; background: var(--sage);"></div>
            </div>
            @if ($membership->order?->scheme_label)
                <p class="mb-0 mt-2 text-muted2 m-meta">{{ $membership->order->scheme_label }}</p>
            @endif
        </div>

        {{-- RIWAYAT PEMBAYARAN --}}
        @if ($membership->order)
            <div class="app-card p-3 mt-3">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                    <h3 class="h6 fw-semibold mb-0">{{ __('mobile.payment_history') }}</h3>
                    <span class="badge rounded-pill bg-sage-soft text-muted2">{{ $membership->order->payments->count() }}</span>
                </div>
                @forelse ($membership->order->payments->sortByDesc('created_at') as $payment)
                    @php
                        $pStatus = $payment->verification_status ?: $payment->status;
                        $pLabel = __('mobile.payment_verification.' . $pStatus);
                        $pLabel = $pLabel === 'mobile.payment_verification.' . $pStatus ? ucfirst($pStatus) : $pLabel;
                    @endphp
                    <div class="py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                <p class="mb-0 small fw-semibold">{{ ucfirst($payment->kind ?: __('mobile.payment_kind')) }} · {{ $rupiah($payment->amount ?: $payment->gross_amount) }}</p>
                                <p class="mb-0 text-muted2 m-meta">{{ ($payment->paid_at ?: $payment->created_at)?->format('d M Y H:i') }}</p>
                            </div>
                            <span class="badge rounded-pill align-self-start {{ in_array($pStatus, ['verified', 'approved', 'paid'], true) ? 'bg-success-subtle text-success-emphasis' : ($pStatus === 'pending' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger-emphasis') }}">{{ $pLabel }}</span>
                        </div>
                        @if ($payment->note)
                            <p class="mb-0 mt-1 text-muted2 m-meta">{{ $payment->note }}</p>
                        @endif
                        @if ($payment->proof_image_path)
                            <a href="{{ route('orders.payments.proof', [$membership->order->uuid, $payment->uuid]) }}" target="_blank" rel="noopener"
                                class="small text-terra text-decoration-none">{{ __('mobile.view_proof') }} →</a>
                        @endif
                    </div>
                @empty
                    <p class="mb-0 text-muted2 text-small mt-2">{{ __('mobile.no_payments') }}</p>
                @endforelse
                <a href="{{ route('orders.show', $membership->order->uuid) }}"
                    class="d-flex justify-content-between align-items-center mt-2 text-decoration-none text-terra small fw-semibold">
                    {{ __('mobile.view_all') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif

        {{-- DETAIL --}}
        <div class="app-card p-3 mt-3">
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.package') }}</span>
                <span class="small fw-semibold">{{ $membership->package?->name ?? '-' }}</span>
            </div>
            @if ($membership->order)
                <a href="{{ route('orders.show', $membership->order->uuid) }}" class="d-flex justify-content-between py-2 border-bottom text-decoration-none text-dark">
                    <span class="text-muted2 text-small">{{ __('mobile.order') }}</span>
                    <span class="small fw-semibold text-terra">#{{ $membership->order->order_number }} →</span>
                </a>
            @endif
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.event_date') }}</span>
                @if ($membership->event_date)
                    <span class="small fw-semibold text-end">
                        {{ $membership->event_date->format('d M Y') }}
                        @if ($membership->days_to_event > 0)
                            <small class="d-block text-terra">{{ __('mobile.days_left', ['count' => $membership->days_to_event]) }}</small>
                        @elseif ($membership->days_to_event === 0)
                            <small class="d-block text-terra">{{ __('mobile.event_today') }}</small>
                        @endif
                    </span>
                @else
                    <span class="small fw-semibold">{{ __('mobile.no_event') }}</span>
                @endif
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted2 text-small">{{ __('mobile.started') }}</span>
                <span class="small fw-semibold">{{ $membership->started_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted2 text-small">{{ __('mobile.expires') }}</span>
                <span class="small fw-semibold">{{ $membership->expired_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
        </div>

        {{-- BOOKING HISTORY --}}
        <div class="d-flex justify-content-between align-items-end mt-4">
            <h3 class="h5 fw-semibold mb-0">{{ __('mobile.recent_classes') }}</h3>
            <a href="{{ route('orders.index') }}" class="btn btn-link p-0 text-terra fw-semibold text-small text-decoration-none">{{ __('mobile.view_all') }}</a>
        </div>
        <div class="d-grid gap-3 mt-3">
            @forelse ($bookings as $b)
                <div class="app-card p-3">
                    <p class="mb-1 small fw-semibold">{{ $b->schedule?->class?->name ?? 'Class' }}</p>
                    <p class="mb-0 text-muted2 m-meta">
                        {{ $b->booking_date?->format('d M Y') ?? '-' }}
                        · {{ substr((string) $b->schedule?->start_time, 0, 5) }}–{{ substr((string) $b->schedule?->end_time, 0, 5) }}
                        · {{ ucfirst($b->status) }}
                    </p>
                </div>
            @empty
                <div class="app-card text-center p-4">
                    <p class="mb-0 text-muted2 text-small">{{ __('mobile.no_classes_membership') }}</p>
                </div>
            @endforelse
        </div>

        <a href="{{ route('packages.member') }}" class="btn btn-sage w-100 py-2 mt-4">
            <i class="bi bi-calendar-plus me-1"></i> Lihat Paket
        </a>

        <div style="height: 24px"></div>
    </div>
</section>

@endsection
