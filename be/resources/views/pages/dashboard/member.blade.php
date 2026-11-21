{{-- Member dashboard (dilibatkan oleh pages.dashboard.index saat role user) --}}
<style>
    .hero-panel {
        background: var(--brand-gradient);
        border-radius: var(--bs-border-radius-xl);
        color: #fff;
        padding: 1.8rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-panel::after {
        content: "";
        position: absolute;
        right: -40px;
        top: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .hero-panel::before {
        content: "";
        position: absolute;
        right: 60px;
        bottom: -70px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
    }

    .membership-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
        background: linear-gradient(135deg,
                #ffffff 0%,
                #f8f7ff 100%);
        border: 1px solid #eceaff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.06);
        transition: all 0.3s ease;
    }

    .membership-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.12);
    }


    /* Header */

    .membership-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .membership-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #fff;
        font-size: 19px;
        background: linear-gradient(135deg,
                #4f46e5,
                #7c3aed);
        box-shadow: 0 5px 12px rgba(79, 70, 229, 0.25);
    }

    .membership-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 50px;
        color: #15803d;
        background: #dcfce7;
        font-size: 12px;
        font-weight: 600;
    }


    /* Plan */

    .membership-plan {
        position: relative;
        overflow: hidden;
        padding: 16px;
        margin-bottom: 18px;
        border-radius: 15px;
        background: linear-gradient(135deg,
                #eef2ff,
                #f5f3ff);
    }

    .membership-plan-name {
        margin-top: 3px;
        color: #5b4ce1;
        font-size: 20px;
        font-weight: 700;
    }

    .membership-stars {
        position: absolute;
        top: 12px;
        right: 16px;
        color: #7c3aed;
        font-size: 42px;
        opacity: 0.12;
    }


    /* Credit */

    .membership-credit {
        margin-top: 5px;
    }

    .membership-progress {
        width: 100%;
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #e9e7ff;
    }

    .membership-progress-bar {
        width: 67%;
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg,
                #4f46e5,
                #8b5cf6);
    }


    /* Button */
    .membership-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 18px;
        padding: 11px 16px;
        border-radius: 12px;
        color: #fff;
        background: linear-gradient(135deg,
                #4f46e5,
                #7c3aed);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .membership-icon i,
    .membership-button i {
        width: auto;
        height: auto;
    }

    .course-date-badge {
        width: 58px;
        height: 58px;
        border-radius: 14px;
        background: #eef6f1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .course-date-day {
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        color: var(--bs-success);
    }

    .course-date-month {
        font-size: 9px;
        text-transform: uppercase;
        color: var(--bs-secondary-color);
        margin-top: 4px;
    }

    .membership-button:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }
</style>

<div class="container-fluid pb-5">

    <!-- Hero -->
    <div class="hero-panel mb-4">
        <div class="row align-items-center position-relative">
            <div class="col-lg-9">
                <div class="badge bg-white bg-opacity-25 mb-2 px-3 py-2 rounded-pill">
                    {{ now()->translatedFormat('l, j F Y') }}
                </div>
                <h3 class="fw-bold text-white mb-1">Halo, {{ auth()->user()->name }}</h3>
                <p class="text-white-50 mb-0">
                    Kelola pesanan, pantau status pembayaran, dan temukan paket wedding terbaik untuk acara Anda.
                </p>
            </div>
            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0" style="position: relative;z-index: 1;">
                <a href="{{ route('packages.member') }}" class="btn btn-light fw-bold px-4"><i
                        class="bi bi-box-seam me-1"></i> Lihat Paket</a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.3rem"><i class="bi bi-bag-check"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $totalPackages ?? 0 }}</div><div class="small text-muted">Paket Tersedia</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.3rem"><i class="bi bi-calendar-event"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $totalEvents }}</div><div class="small text-muted">Event</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.3rem"><i class="bi bi-shop"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $jumlahVendor }}</div><div class="small text-muted">Vendor</div></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3"><div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.3rem"><i class="bi bi-people"></i></div><div><div class="fw-bold fs-4 lh-1">{{ $jumlahMembers }}</div><div class="small text-muted">Member</div></div></div></div></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Event Mendatang</h6>
                        <a href="{{ route('events.index') }}" class="small fw-semibold text-decoration-none">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Event</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                            <tbody>
                                @forelse (($events ?? collect()) as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event->judul ?? '-' }}</td>
                                    <td>{{ optional($event->tanggal)->format('d M Y') ?? '-' }}@if($event->waktu_mulai) <span class="text-muted small">• {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}</span>@endif</td>
                                    <td>{{ $event->lokasi ?? '-' }}</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ ucfirst($event->status ?? 'draft') }}</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->uuid }}">Detail</button></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada event mendatang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach (($events ?? collect()) as $event)
    <div class="modal fade" id="eventModal{{ $event->uuid }}" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
        @if ($event->gambar)<img src="{{ asset('storage/' . $event->gambar) }}" class="img-fluid" alt="{{ $event->judul }}" style="max-height:300px;object-fit:cover;">@endif
        <div class="modal-header"><h5 class="modal-title">{{ $event->judul }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p class="mb-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</p>
            <p class="mb-2"><strong>Waktu:</strong> @if ($event->waktu_mulai){{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}@if ($event->waktu_selesai) - {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') }}@endif @else - @endif</p>
            <p class="mb-2"><strong>Lokasi:</strong> {{ $event->lokasi ?? '-' }}</p>
            @if ($event->kapasitas)<p class="mb-2"><strong>Kapasitas:</strong> {{ $event->kapasitas }} orang</p>@endif
            @if ($event->deskripsi)<div class="mt-3">{!! $event->deskripsi !!}</div>@elseif ($event->excerpt)<p class="mt-3 mb-0">{{ $event->excerpt }}</p>@endif
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div></div>
    @endforeach
    <div class="row g-4 mb-4">

        <!-- Kelas berikutnya -->
        <!-- Kursus Art of Livings -->
        <div class="col-lg-12">
            <div class="card border-0 h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-1" style="color:var(--bs-heading-color);">
                                Kursus Mendatang
                            </h6>

                            <small class="text-secondary">
                                Program Art of Living
                            </small>
                        </div>

                        <span class="badge bg-primary-subtle text-primary">
                            {{ count($courses) }} Kursus
                        </span>
                    </div>


                    @forelse ($courses as $course)

                    @php
                    $title = $course['title'] ?? 'Kursus Art of Living';

                    $startDate = !empty($course['start_date'])
                    ? \Carbon\Carbon::parse($course['start_date'])
                    : null;

                    $endDate = !empty($course['end_date'])
                    ? \Carbon\Carbon::parse($course['end_date'])
                    : null;

                    $city = $course['city'] ?? '-';

                    $teachers = $course['teachers'] ?? [];

                    $teacher = is_array($teachers) && count($teachers)
                    ? implode(', ', $teachers)
                    : null;

                    $fee = (int) ($course['course_fee'] ?? 0);

                    $registerUrl = $course['register_url'] ?? null;

                    $isFull = ($course['is_event_capacity_full'] ?? '0') == '1';

                    $isClosed = !empty($course['is_registration_closed']);

                    $timing = $course['course_complex_timing']
                    ?? $course['complex_timings']
                    ?? $course['weekday_timings']
                    ?? null;

                    if ($timing) {
                    $timing = str_ireplace(
                    ['<br>', '<br />', '<br />'],
                    ' â€¢ ',
                    $timing
                    );

                    $timing = strip_tags($timing);
                    }
                    @endphp


                    <div class="py-3 border-bottom">

                        <div class="d-flex align-items-center gap-3">

                            {{-- DATE --}}
                            <div class="course-date-badge flex-shrink-0">
                                @if ($startDate)
                                <span class="course-date-day">{{ $startDate->format('d') }}</span>
                                <span class="course-date-month">{{ $startDate->format('M') }}</span>
                                @else
                                <span class="fw-bold text-secondary">-</span>
                                @endif
                            </div>


                            {{-- COURSE INFO --}}
                            <div class="flex-grow-1 min-w-0">

                                <div
                                    class="fw-semibold text-truncate"
                                    style="color:var(--bs-heading-color);">
                                    {{ $title }}
                                </div>


                                {{-- LOCATION --}}
                                <div
                                    class="small text-secondary text-truncate mt-1">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $city }}

                                    @if ($teacher)
                                    &nbsp;â€¢&nbsp;

                                    <i class="bi bi-person me-1"></i>
                                    {{ $teacher }}
                                    @endif
                                </div>


                                {{-- DATE + TIME --}}
                                <div
                                    class="small text-secondary mt-1">
                                    @if ($startDate)

                                    <i class="bi bi-calendar3 me-1"></i>

                                    {{ $startDate->format('d M Y') }}

                                    @if (
                                    $endDate &&
                                    $startDate->format('Y-m-d') !== $endDate->format('Y-m-d')
                                    )
                                    - {{ $endDate->format('d M Y') }}
                                    @endif

                                    @endif

                                    @if ($timing)
                                    &nbsp;â€¢&nbsp;
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $timing }}
                                    @endif
                                </div>

                            </div>


                            {{-- PRICE + BUTTON --}}
                            <div
                                class="text-end flex-shrink-0"
                                style="min-width:110px;">

                                {{-- PRICE / STATUS --}}
                                <div class="mb-2">

                                    @if ($isFull)

                                    <span class="badge bg-danger-subtle text-danger-emphasis">
                                        Full
                                    </span>

                                    @elseif ($isClosed)

                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                        Closed
                                    </span>

                                    @elseif ($fee > 0)

                                    <div
                                        class="fw-semibold"
                                        style="font-size:12px;">
                                        Rp {{ number_format($fee, 0, ',', '.') }}
                                    </div>

                                    @else

                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        Free
                                    </span>

                                    @endif

                                </div>


                                {{-- REGISTER --}}
                                @if ($registerUrl && !$isFull && !$isClosed)

                                <a
                                    href="{{ $registerUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-primary rounded-3 px-3"
                                    style="font-size:11px;">
                                    Register
                                    <i class="bi bi-arrow-up-right ms-1"></i>
                                </a>

                                @endif

                            </div>

                        </div>

                    </div>


                    @empty

                    <div class="text-center py-5">

                        <div class="mb-3">
                            <i
                                class="bi bi-calendar2-week"
                                style="
                                font-size:2rem;
                                color:var(--bs-secondary-color);
                            "></i>
                        </div>

                        <h6
                            class="fw-semibold mb-1"
                            style="color:var(--bs-heading-color);">
                            No Kursus Mendatang
                        </h6>

                        <p class="small text-secondary mb-0">
                            Belum ada kursus Art of Living mendatang.
                        </p>

                    </div>

                    @endforelse

                </div>
            </div>
        </div>



    </div>

</div>

@if (blank(Auth::user()->no_hp))
<div class="modal fade"
    id="completeProfileModal"
    tabindex="-1"
    aria-labelledby="completeProfileModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('dashboard.submitSumber') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="completeProfileModalLabel">
                        Lengkapi Informasi Anda
                    </h5>
                </div>

                <div class="modal-body">

                    <p class="text-secondary">
                        Mohon lengkapi informasi berikut
                        sebelum melanjutkan.
                    </p>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">
                            Nomor WhatsApp
                        </label>

                        <input type="text"
                            name="no_hp"
                            id="no_hp"
                            class="form-control @error('no_hp') is-invalid @enderror"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('no_hp') }}"
                            required>

                        @error('no_hp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sumber_informasi" class="form-label">
                            Dari mana Anda mengetahui kami?
                        </label>

                        <select name="sumber_informasi"
                            id="sumber_informasi"
                            class="form-select @error('sumber_informasi') is-invalid @enderror"
                            required>
                            <option value="">Pilih salah satu</option>
                            <option value="google">Pencarian Google</option>
                            <option value="sosmed">Media Sosial</option>
                            <option value="friend">Teman / Keluarga</option>
                            <option value="community">Komunitas</option>
                            <option value="event">Event / Workshop</option>
                            <option value="website">Website</option>
                            <option value="other">Lainnya</option>

                        </select>

                        @error('sumber_informasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Informasi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif

@if (empty($user->no_hp))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('completeProfileModal');

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });

            modal.show();
        }
    });
</script>
@endif
