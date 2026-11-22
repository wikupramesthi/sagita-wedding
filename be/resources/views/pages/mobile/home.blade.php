@extends('layouts.mobile')
@section('title', __('mobile.home'))
@section('content')

<section class="screen {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" id="home">
    <div class="px-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-1 small text-muted2">
                    {{ now()->translatedFormat('l, j F Y') }}
                </p>

                <h1 class="h3 fw-semibold mb-0">
                    {{ __('mobile.greeting_hi', ['name' => auth()->user()->name]) }}
                </h1>
            </div>
            <a href="{{ route('notifications.index') }}"
                class="app-card border-0 rounded-circle position-relative d-flex align-items-center justify-content-center text-dark text-decoration-none"
                style="height: 44px; width: 44px; border-radius: 50% !important"
                aria-label="{{ __('mobile.notifications') }}">
                <i class="bi bi-bell"></i>
                @if (!empty($unreadCount))
                <span
                    class="position-absolute rounded-circle"
                    style="
                    height: 8px;
                    width: 8px;
                    background: var(--terra);
                    top: 10px;
                    right: 10px;
                  "></span>
                @endif
            </a>
        </div>

        @if ($banner)
        <div class="hero mt-4">

            <img
                src="{{ asset('storage/' . $banner->gambar) }}"
                alt="{{ $banner->nama }}"
                fetchpriority="high"
                decoding="async">

            <div class="veil"></div>
            <div class="position-absolute bottom-0 start-0 end-0 p-4">
                <span class="chip bg-light text-dark">
                    <i class="bi bi-heart-fill text-terra"></i>
                    {{ __('mobile.wellness') }}
                </span>

                @if ($banner->deskripsi)
                <h2
                    class="fw-semibold text-white mt-3 m-h1"
                    style="line-height:1.1;max-width:90%;">
                    {{ $banner->deskripsi }}
                </h2>
                @endif

                <a href="#package-recommendation" class="btn btn-warm px-4 py-2 mt-3">
                    Cari paket pernikahan →
                </a>

            </div>
        </div>
        @endif

        <section class="mt-4" aria-labelledby="mobile-wedding-plan-title">
            <div class="app-card p-3">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <p class="eyebrow mb-1">Rencana pernikahan</p>
                        <h2 id="mobile-wedding-plan-title" class="h5 fw-semibold mb-0">Rencana pernikahan</h2>
                    </div>
                    @if ($planningOrders->isNotEmpty())
                        <span class="badge bg-sage-soft text-sage">{{ $planningOrders->count() }} pesanan aktif</span>
                    @endif
                </div>

                @if ($planningOrders->isNotEmpty())
                    @php
                        $mobilePlanningOrder = $planningOrders->sortByDesc('created_at')->first();
                    @endphp
                    <div class="border rounded-3 p-3 mt-3">
                        <div class="small text-muted2">Pesanan paket aktif</div>
                        <div class="fw-semibold">{{ $mobilePlanningOrder->package?->name ?? 'Paket Pernikahan' }}</div>
                        <div class="small text-muted2">{{ $mobilePlanningOrder->order_number }}</div>
                        <div class="small mt-2">
                            Tanggal acara:
                            <span class="fw-semibold">{{ $planningEventDate?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div class="progress mt-3" style="height:6px">
                            <div class="progress-bar bg-success" role="progressbar" style="width:{{ $planningBudgetProgress }}%" aria-valuenow="{{ $planningBudgetProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row g-2 mt-2 text-center">
                            <div class="col-4">
                                <div class="small text-muted2">Total</div>
                                <div class="small fw-semibold">Rp {{ number_format($planningOrderTotal, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted2">Dibayar</div>
                                <div class="small fw-semibold text-success">Rp {{ number_format($planningPaidTotal, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted2">Sisa</div>
                                <div class="small fw-semibold text-danger">Rp {{ number_format($planningRemainingTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-3">Lihat pesanan & pembayaran</a>
                    </div>
                @else
                    <div class="small text-muted2 mt-3">
                        Belum ada pesanan paket. Masukkan batas anggaran untuk melihat rekomendasi. Setelah memesan, tanggal acara dan pembayaran akan tampil di sini.
                    </div>
                @endif

            </div>

            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-1">Rekap Pembayaran dan Bukti Transfer</h3>
                <p class="small text-muted2 mb-3">Ringkasan ini mengikuti lima pesanan paket terbaru. Pembayaran yang disetujui mengurangi sisa tagihan.</p>
                <div class="progress mb-3" style="height:6px">
                    <div class="progress-bar bg-success" role="progressbar" style="width:{{ $paymentProgress }}%" aria-valuenow="{{ $paymentProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="small text-muted2">Total</div>
                        <div class="small fw-semibold">Rp {{ number_format($paymentOrderTotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted2">Disetujui</div>
                        <div class="small fw-semibold text-success">Rp {{ number_format($paymentPaidTotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted2">Sisa tagihan</div>
                        <div class="small fw-semibold text-danger">Rp {{ number_format($paymentRemainingTotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @if ($recentPaymentProofs->isNotEmpty())
                    @php
                        $mobileProofLabels = ['accepted' => 'Diterima', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'];
                        $mobileProofClasses = ['accepted' => 'bg-success-subtle text-success-emphasis', 'pending' => 'bg-warning-subtle text-warning-emphasis', 'rejected' => 'bg-danger-subtle text-danger-emphasis'];
                    @endphp
                    <div class="border-top mt-3 pt-3">
                        <div class="small fw-semibold mb-2">Status bukti pembayaran</div>
                        <div class="d-grid gap-2">
                            @foreach ($recentPaymentProofs as $proof)
                                @php
                                    $proofStatus = $proof['payment']->proof_verification_status;
                                @endphp
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="small">
                                        <div class="fw-semibold">{{ $proof['order']->order_number }}</div>
                                        <div class="text-muted2">Rp {{ number_format((float) ($proof['payment']->amount ?? $proof['payment']->gross_amount), 0, ',', '.') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $mobileProofClasses[$proofStatus] }}">{{ $mobileProofLabels[$proofStatus] }}</span>
                                        <a href="{{ route('orders.payments.proof', [$proof['order']->uuid, $proof['payment']->uuid]) }}" class="small d-block mt-1">Lihat bukti</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="small text-muted2 mt-3">Belum ada bukti pembayaran yang diunggah.</div>
                @endif
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-3">Lihat semua pesanan dan pembayaran</a>
            </div>

            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-3">Timeline persiapan</h3>
                <div class="d-grid gap-3">
                    @foreach ($planningTimeline as $milestone)
                        <div class="d-flex gap-3 align-items-start">
                            <span class="rounded-circle bg-sage-soft flex-shrink-0 mt-1" style="width:10px;height:10px"></span>
                            <div class="small">
                                <div class="fw-semibold">{{ $milestone['label'] }}</div>
                                <div class="text-muted2">{{ $milestone['date']?->translatedFormat('d M Y') ?? $milestone['offset'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($dashboardChecklistOrder && $dashboardChecklistItems->isNotEmpty())
                <div class="app-card p-3 mt-3">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <h3 class="h6 fw-semibold mb-1">Checklist pernikahan</h3>
                            <div class="small text-muted2">{{ $dashboardChecklistItems->where('status', 'completed')->count() }}/{{ $dashboardChecklistItems->count() }} selesai</div>
                        </div>
                        <a href="{{ route('orders.show', $dashboardChecklistOrder->uuid) }}" class="small text-nowrap">Detail</a>
                    </div>
                    @foreach ($dashboardChecklistItems->take(5) as $item)
                        <div class="d-flex align-items-start gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <form method="POST" action="{{ route('orders.checklist.update', [$dashboardChecklistOrder->uuid, $item->uuid]) }}">
                                @csrf
                                <input type="hidden" name="status" value="{{ $item->status === 'completed' ? 'pending' : 'completed' }}">
                                <button type="submit" class="btn btn-link p-0 border-0" aria-label="{{ $item->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                    <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                </button>
                            </form>
                            <div class="flex-grow-1">
                                <div class="small fw-semibold {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</div>
                                @if ($item->due_at)<div class="m-micro text-muted2">Target {{ $item->due_at->translatedFormat('d M Y') }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="app-card p-3 mt-3">
                <h3 class="h6 fw-semibold mb-1">Anggaran & rekomendasi paket</h3>
                <p class="small text-muted2 mb-3">Temukan paket sesuai anggaran, lalu pilih untuk melanjutkan pemesanan.</p>
                @include('pages.dashboard.partials.package-recommendations')
            </div>
        </section>
        <div class="d-flex justify-content-between align-items-end mt-4">
            <h3 class="h5 fw-semibold mb-0">{{ __('mobile.upcoming_events') }}</h3>
            <a href="{{ route('events.index') }}"
                class="btn btn-link p-0 text-terra fw-semibold text-decoration-none text-small">{{ __('mobile.view_all') }}</a>
        </div>

        <div class="d-flex gap-3 no-scrollbar mt-3 pb-1" id="eventList">

            @forelse($events as $event)

            <article class="app-card overflow-hidden flex-shrink-0"
                style="width:190px"
                data-bs-toggle="modal"
                data-bs-target="#eventModal{{ $event->uuid }}">

                <img src="{{ $event->gambar
                ? (Str::startsWith($event->gambar, 'http')
                    ? $event->gambar
                    : asset('storage/' . $event->gambar))
                : asset('dist/assets/images/event-placeholder.jpg') }}"
                    alt="{{ $event->judul }}"
                    loading="lazy"
                    style="height:120px;width:100%;object-fit:cover">

                <div class="p-3">

                    <span class="chip bg-terra-soft text-terra text-uppercase m-micro">{{ __('mobile.event') }}</span>

                    <p class="mb-1 mt-2 small fw-semibold lh-sm text-truncate">
                        {{ $event->judul }}
                    </p>

                    <p class="mb-0 text-muted2 text-truncate m-meta">
                        <i class="bi bi-calendar3"></i>
                        {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') }}
                    </p>

                </div>

            </article>

            {{-- Event Modal --}}
            <div class="modal fade"
                id="eventModal{{ $event->uuid }}"
                tabindex="-1"
                aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 overflow-hidden">

                        @if($event->gambar)
                        <img src="{{ Str::startsWith($event->gambar, 'http')
                            ? $event->gambar
                            : asset('storage/' . $event->gambar) }}"
                            alt="{{ $event->judul }}"
                            class="w-100"
                            loading="lazy"
                            decoding="async"
                            style="height:200px;object-fit:cover">
                        @endif

                        <div class="modal-body p-4">

                            <span class="chip bg-terra-soft text-terra text-uppercase m-micro">{{ __('mobile.event') }}</span>

                            <h5 class="fw-bold mt-2 mb-3">
                                {{ $event->judul }}
                            </h5>

                            @if($event->tanggal)
                            <p class="small text-muted2 mb-2">
                                <i class="bi bi-calendar3 me-2"></i>
                                {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('l, d F Y') }}
                            </p>
                            @endif

                            @if($event->waktu_mulai)
                            <p class="small text-muted2 mb-2">
                                <i class="bi bi-clock me-2"></i>
                                {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}

                                @if($event->waktu_selesai)
                                - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') }}
                                @endif
                            </p>
                            @endif

                            @if($event->lokasi)
                            <p class="small text-muted2 mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                {{ $event->lokasi }}
                            </p>
                            @endif

                            @if($event->kapasitas)
                            <p class="small text-muted2 mb-3">
                                <i class="bi bi-people me-2"></i>
                                {{ __('mobile.capacity', ['count' => $event->kapasitas]) }}
                            </p>
                            @endif

                            @if($event->excerpt)
                            <p class="small fw-semibold mb-2">
                                {{ $event->excerpt }}
                            </p>
                            @endif

                            @if($event->deskripsi)
                            <div class="small text-muted2 lh-lg event-description">
                                {!! $event->deskripsi !!}
                            </div>
                            @endif

                        </div>

                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button"
                                class="btn btn-sage w-100"
                                data-bs-dismiss="modal">{{ __('mobile.close') }}</button>
                        </div>

                    </div>
                </div>

            </div>

            @empty
            <div class="app-card text-center p-4 w-100 flex-shrink-0">
                <i class="bi bi-calendar2-week text-muted2 fs-3"></i>

                <p class="mb-1 mt-2 fw-semibold">
                    {{ __('mobile.no_upcoming_events') }}
                </p>
                <p class="mb-0 text-muted2 small">
                    {{ __('mobile.no_upcoming_events_desc') }}
                </p>
            </div>

            @endforelse

        </div>

        <div class="app-card p-4 mt-4 mb-2">

            <p class="eyebrow mb-1">Bantuan pernikahan</p>
            <h3 class="h5 fw-semibold mb-2">
                Perlu bantuan?
            </h3>

            <p class="small text-muted2 mb-3">
                Hubungi tim kami melalui WhatsApp jika Anda memiliki pertanyaan tentang paket atau pesanan.
            </p>

            @php
                $whatsappMessage = implode("\n\n", [
                    'Halo Sagita Wedding Planner 👋',
                    'Saya mendapatkan informasi mengenai Sagita Wedding Planner melalui website dan tertarik dengan layanan Wedding Organizer yang ditawarkan.',
                    'Saya ingin mengetahui lebih lanjut mengenai pilihan paket, layanan, serta estimasi biaya untuk pernikahan saya.',
                    'Terima kasih 😊',
                ]);
            @endphp
            <a href="https://api.whatsapp.com/send/?phone=6281380760095&text={{ rawurlencode($whatsappMessage) }}&app_absent=0"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-sage w-100 py-2">
                <i class="bi bi-whatsapp me-2"></i>
                Hubungi kami
            </a>

        </div>
    </div>
</section>

@if (blank(Auth::user()->no_hp))

<div
    class="modal fade"
    id="completeProfileModal"
    tabindex="-1"
    aria-labelledby="completeProfileModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered px-3">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <form
                action="{{ route('dashboard.submitSumber') }}"
                method="POST">
                @csrf

                {{-- Header --}}
                <div class="modal-header border-0 px-4 pt-4 pb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div
                                class="d-flex align-items-center justify-content-center rounded-circle bg-sage-soft"
                                style="width:40px;height:40px;">
                                <i class="bi bi-person-check text-sage"></i>
                            </div>

                            <span class="small fw-semibold text-sage">
                                Hampir selesai
                            </span>
                        </div>

                        <h5
                            class="modal-title fw-semibold mb-1"
                            id="completeProfileModalLabel">
                            Lengkapi profil
                        </h5>

                        <p class="small text-muted2 mb-0">
                            Lengkapi beberapa data sebelum melanjutkan.
                        </p>
                    </div>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 pt-3">

                    {{-- WhatsApp --}}
                    <div class="mb-3">
                        <label
                            for="mobile_no_hp"
                            class="form-label small fw-semibold">
                            Nomor WhatsApp
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-whatsapp text-sage"></i>
                            </span>

                            <input
                                type="text"
                                name="no_hp"
                                id="mobile_no_hp"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                placeholder="08xxxxxxxxxx"
                                value="{{ old('no_hp') }}"
                                inputmode="tel"
                                required>
                        </div>

                        @error('no_hp')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Source --}}
                    <div class="mb-2">
                        <label
                            for="mobile_sumber_informasi"
                            class="form-label small fw-semibold">
                            Dari mana Anda mengenal kami?
                        </label>

                        <select
                            name="sumber_informasi"
                            id="mobile_sumber_informasi"
                            class="form-select @error('sumber_informasi') is-invalid @enderror"
                            required>

                            <option value="">
                                Pilih salah satu opsi
                            </option>

                            <option
                                value="google"
                                @selected(old('sumber_informasi')==='google' )>
                                Pencarian Google
                            </option>

                            <option
                                value="sosmed"
                                @selected(old('sumber_informasi')==='sosmed' )>
                                Media sosial
                            </option>

                            <option
                                value="friend"
                                @selected(old('sumber_informasi')==='friend' )>
                                Teman atau keluarga
                            </option>

                            <option
                                value="community"
                                @selected(old('sumber_informasi')==='community' )>
                                Komunitas
                            </option>

                            <option
                                value="event"
                                @selected(old('sumber_informasi')==='event' )>
                                Acara
                            </option>

                            <option
                                value="website"
                                @selected(old('sumber_informasi')==='website' )>
                                Website
                            </option>

                            <option
                                value="other"
                                @selected(old('sumber_informasi')==='other' )>
                                Lainnya
                            </option>

                        </select>

                        @error('sumber_informasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-4 pt-2 pb-4">
                    <button
                        type="submit"
                        class="btn btn-warm w-100 py-2">
                        <i class="bi bi-check2 me-1"></i>
                        Simpan & Lanjutkan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('completeProfileModal');

        if (!modalElement || typeof bootstrap === 'undefined') {
            return;
        }

        const modal = bootstrap.Modal.getOrCreateInstance(
            modalElement, {
                backdrop: 'static',
                keyboard: false
            }
        );

        modal.show();
    });
</script>

@endif

@endsection