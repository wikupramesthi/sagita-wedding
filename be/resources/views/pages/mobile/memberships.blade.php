@extends('layouts.mobile')
@section('title', __('mobile.my_membership'))
@section('content')

@php
$rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<section class="screen active" id="membership">
    <div class="px-4 pt-4">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('dashboard.index') }}"
                class="text-dark text-decoration-none d-inline-flex align-items-center"
                onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <i class="bi bi-arrow-left fs-5"></i>
                <span class="ms-2 small fw-semibold">{{ __('mobile.back') }}</span>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="app-card border-0 rounded-circle position-relative d-flex align-items-center justify-content-center text-dark text-decoration-none flex-shrink-0"
                style="height: 44px; width: 44px; border-radius: 50% !important" aria-label="{{ __('mobile.notifications') }}">
                <i class="bi bi-bell"></i>
                @if (!empty($unreadCount))
                    <span class="position-absolute rounded-circle"
                        style="height: 8px; width: 8px; background: var(--terra); top: 10px; right: 10px;"></span>
                @endif
            </a>
        </div>

        <div class="mb-1">
            <p class="eyebrow mb-1">{{ __('mobile.membership') }}</p>
            <h1 class="fw-semibold mb-0" style="font-size: 28px;">{{ __('mobile.my_membership') }}</h1>
            <p class="small text-muted2 mb-0 mt-1">
                {{ $stats['active'] }} {{ strtolower(__('mobile.active')) }}
                @if ($stats['expiring'] > 0)
                    · <span class="text-warning fw-semibold">{{ __('mobile.expiring_count', ['count' => $stats['expiring']]) }}</span>
                @endif
            </p>
        </div>

        {{-- RINGKASAN PEMBAYARAN --}}
        <div class="app-card p-3 mt-3 ms-mobile-finance">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="ms-mobile-finance-icon"><i class="bi bi-wallet2"></i></span>
                <div class="flex-fill">
                    <p class="mb-0 small fw-semibold">{{ __('mobile.monitoring') }}</p>
                    <p class="mb-0 text-muted2 m-meta">{{ __('mobile.full_amount') }} {{ $rupiah($finance['total']) }}</p>
                </div>
                <span class="badge rounded-pill {{ $finance['progress'] >= 100 ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }}">
                    {{ $finance['progress'] }}%
                </span>
            </div>
            <div class="progress ms-mobile-progress" role="progressbar" aria-valuenow="{{ $finance['progress'] }}"
                aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: {{ $finance['progress'] }}%; background: var(--sage);"></div>
            </div>
            <div class="d-flex justify-content-between gap-2 mt-3">
                <div>
                    <p class="mb-0 text-muted2 m-meta">{{ __('mobile.paid') }}</p>
                    <p class="mb-0 small fw-semibold text-success">{{ $rupiah($finance['paid']) }}</p>
                </div>
                <div class="text-end">
                    <p class="mb-0 text-muted2 m-meta">{{ __('mobile.remaining') }}</p>
                    <p class="mb-0 small fw-semibold text-danger">{{ $rupiah($finance['remaining']) }}</p>
                </div>
            </div>
        </div>

        {{-- TAGIHAN --}}
        @if (!empty($followUps))
            @php $followUp = $followUps[0]; @endphp
            <a href="{{ route('memberships.show', $followUp['membership']->uuid) }}"
                class="app-card p-3 mt-3 text-decoration-none text-dark d-block">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <p class="mb-0 small fw-semibold"><i class="bi bi-cash-coin me-1 text-terra"></i>{{ __('mobile.follow_up') }}</p>
                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ count($followUps) }}</span>
                </div>
                <div class="d-flex justify-content-between gap-2 mt-2">
                    <div style="min-width:0">
                        <p class="mb-0 small fw-semibold text-truncate">{{ $followUp['package'] }}</p>
                        <p class="mb-0 text-muted2 m-meta">
                            @if ($followUp['membership']->event_date)
                                {{ $followUp['membership']->event_date->format('d M Y') }}
                            @else
                                {{ __('mobile.no_event') }}
                            @endif
                        </p>
                    </div>
                    <span class="small fw-semibold text-danger flex-shrink-0">{{ $rupiah($followUp['remaining']) }}</span>
                </div>
            </a>
        @endif

        {{-- PENGINGAT ACARA --}}
        @if (!empty($upcomingEvents))
            <div class="app-card p-3 mt-3 ms-mobile-reminder">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="ms-mobile-reminder-icon"><i class="bi bi-calendar2-week"></i></span>
                    <div class="flex-fill">
                        <p class="mb-0 small fw-semibold">{{ __('mobile.event_date') }}</p>
                        <p class="mb-0 text-muted2 m-meta">{{ $stats['events_soon'] }} {{ strtolower(__('mobile.event_date')) }} · 30 hari</p>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    @foreach ($upcomingEvents as $event)
                        <a href="{{ route('orders.show', $event['order']->uuid) }}"
                            class="d-flex align-items-center justify-content-between gap-2 text-decoration-none text-dark">
                            <div style="min-width:0">
                                <p class="mb-0 small fw-semibold text-truncate">{{ $event['package'] }}</p>
                                <p class="mb-0 text-muted2 m-meta">{{ $event['order']->event_date->format('d M Y') }} · {{ $event['customer'] }}</p>
                            </div>
                            <span class="badge rounded-pill flex-shrink-0 {{ $event['days'] <= 1 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-primary-subtle text-primary-emphasis' }}">
                                @if ($event['days'] === 0)
                                    {{ __('mobile.event_today') }}
                                @elseif ($event['days'] === 1)
                                    {{ __('mobile.event_tomorrow') }}
                                @else
                                    {{ __('mobile.days_left', ['count' => $event['days']]) }}
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- FILTER --}}
        <div class="d-flex gap-2 mt-4" style="overflow-x:auto;scrollbar-width:none;">
            @php $st = $filters['status'] ?? ''; @endphp
            <a href="{{ route('memberships.index') }}"
                class="filter d-inline-block text-decoration-none {{ $st === '' ? 'active' : '' }}">{{ __('mobile.all') }}</a>
            <a href="{{ route('memberships.index', ['status' => 'active']) }}"
                class="filter d-inline-block text-decoration-none {{ $st === 'active' ? 'active' : '' }}">{{ __('mobile.membership_status.active') }}</a>
            <a href="{{ route('memberships.index', ['status' => 'expired']) }}"
                class="filter d-inline-block text-decoration-none {{ $st === 'expired' ? 'active' : '' }}">{{ __('mobile.membership_status.expired') }}</a>
            @php $pay = $filters['payment'] ?? ''; @endphp
            <a href="{{ route('memberships.index', ['payment' => 'lunas']) }}"
                class="filter d-inline-block text-decoration-none {{ $pay === 'lunas' ? 'active' : '' }}">{{ __('mobile.payment_status_label.Lunas') }}</a>
            <a href="{{ route('memberships.index', ['payment' => 'cicilan']) }}"
                class="filter d-inline-block text-decoration-none {{ $pay === 'cicilan' ? 'active' : '' }}">{{ __('mobile.payment_status_label.Cicilan') }}</a>
            <a href="{{ route('memberships.index', ['payment' => 'menunggu']) }}"
                class="filter d-inline-block text-decoration-none {{ $pay === 'menunggu' ? 'active' : '' }}">{{ __('mobile.payment_status_label.Menunggu') }}</a>
        </div>

        {{-- LIST --}}
        <div class="d-grid gap-3 mt-3">
            @forelse ($memberships as $m)
                @php
                    $isActive = $m->status === 'active';
                    $badge = $isActive ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis';
                    $expiringSoon = $isActive && $m->expired_at && $m->expired_at->isFuture() && $m->expired_at->diffInDays(now()) <= 7;
                    $avatar = $m->user?->avatar_url;
                @endphp
                <a href="{{ route('memberships.show', $m->uuid) }}" class="app-card p-3 text-decoration-none text-dark d-block">
                    <div class="d-flex align-items-center gap-3">
                        <div class="grad-sage d-flex align-items-center justify-content-center flex-shrink-0"
                            style="height: 48px; width: 48px; border-radius: 18px; overflow: hidden">
                            @if ($avatar)
                                <img src="{{ $avatar }}" alt="{{ $m->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="text-white fw-semibold">{{ strtoupper(substr($m->user?->name ?? '?', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-fill" style="min-width:0">
                            <p class="mb-0 small fw-semibold text-truncate">{{ $m->package?->name ?? 'Membership' }}</p>
                            <p class="mb-0 text-muted2 m-meta">
                                @if (is_null($m->quota))
                                    {{ __('mobile.unlimited_classes') }}
                                @else
                                    {{ $m->quota == 1 ? __('mobile.class_left', ['count' => $m->quota]) : __('mobile.classes_left', ['count' => $m->quota]) }}
                                @endif
                                @if ($m->expired_at)
                                    · {{ __('mobile.until', ['date' => $m->expired_at->format('d M Y')]) }}
                                @endif
                            </p>
                        </div>
                        <span class="badge rounded-pill {{ $badge }}">{{ __('mobile.membership_status.' . $m->status) }}</span>
                    </div>

                    @if ($m->event_date)
                        <div class="d-flex align-items-center justify-content-between gap-2 mt-2 p-2 rounded-3 ms-mobile-event">
                            <div class="d-flex align-items-center gap-2" style="min-width:0">
                                <i class="bi bi-calendar-event"></i>
                                <span class="m-meta text-truncate">{{ $m->event_date->format('d M Y') }}</span>
                            </div>
                            <span class="badge rounded-pill flex-shrink-0 {{ $m->days_to_event <= 1 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-white text-terra' }}">
                                @if ($m->days_to_event === 0)
                                    {{ __('mobile.event_today') }}
                                @elseif ($m->days_to_event === 1)
                                    {{ __('mobile.event_tomorrow') }}
                                @elseif ($m->days_to_event > 0)
                                    {{ __('mobile.days_left', ['count' => $m->days_to_event]) }}
                                @else
                                    {{ __('mobile.event_passed') }}
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between gap-2 mt-2">
                        <span class="text-muted2 m-meta">{{ __('mobile.paid') }} <b class="text-success">{{ $rupiah($m->amount_paid_value) }}</b></span>
                        <span class="text-muted2 m-meta">{{ __('mobile.remaining') }} <b class="{{ $m->remaining_amount_value > 0 ? 'text-danger' : 'text-success' }}">{{ $rupiah($m->remaining_amount_value) }}</b></span>
                    </div>
                    <div class="progress ms-mobile-progress mt-1" role="progressbar" aria-valuenow="{{ $m->payment_progress }}"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $m->payment_progress }}%; background: var(--sage);"></div>
                    </div>

                    @if ($expiringSoon)
                        <div class="mt-2 p-2 rounded-3 bg-warning-subtle text-warning-emphasis m-meta fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ __('mobile.expiring_soon', ['date' => $m->expired_at->format('d M Y')]) }}
                        </div>
                    @endif
                </a>
            @empty
                <div class="app-card text-center p-4">
                    <i class="bi bi-flower1 text-muted2 fs-3"></i>
                    <p class="mb-1 small fw-semibold">{{ __('mobile.no_membership') }}</p>
                    <p class="mb-3 text-muted2 text-small">{{ __('mobile.no_membership_desc') }}</p>
                    <a href="{{ route('packages.member') }}" class="btn btn-warm px-4 py-2">{{ __('mobile.browse_packages') }}</a>
                </div>
            @endforelse
        </div>

        @if ($memberships instanceof \Illuminate\Pagination\LengthAwarePaginator && $memberships->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $memberships->links() }}
            </div>
        @endif

        <div style="height: 24px"></div>
    </div>
</section>

@push('after-style')
<style>
    .ms-mobile-finance { background: linear-gradient(130deg, #f6faf7, #fff 60%, #fdf8f4); }
    .ms-mobile-finance-icon, .ms-mobile-reminder-icon {
        width: 36px; height: 36px; flex-shrink: 0; border-radius: 12px; display: grid; place-items: center;
        font-size: 16px; color: #fff; background: linear-gradient(135deg, #4b6b52, #6b8f6f);
    }
    .ms-mobile-reminder-icon { background: linear-gradient(135deg, #c4703c, #dc9064); }
    .ms-mobile-progress { height: 6px; border-radius: 999px; background: #e6ece7; overflow: hidden; }
    .ms-mobile-progress .progress-bar { border-radius: 999px; }
    .ms-mobile-event { background: rgba(75,107,82,.08); color: #4b6b52; }
</style>
@endpush

@endsection
