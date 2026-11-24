<div class="member-dashboard container-fluid pb-5">
    @php
        $memberNow = now('Asia/Jakarta');
        $memberHour = $memberNow->hour;
        $memberGreeting = $memberHour < 11 ? 'Selamat pagi' : ($memberHour < 15 ? 'Selamat siang' : ($memberHour < 18 ? 'Selamat sore' : 'Selamat malam'));
    @endphp
    <div class="member-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="member-hero-eyebrow">{{ $memberGreeting }}, <strong>{{ auth()->user()->name }}</strong></p>
                <h1>Selamat datang di dashboard {{ $brandName ?? 'Sagita Wedding' }}</h1>
                <p>Pantau operasional, verifikasi pembayaran, dan kelola konten dari satu tempat.</p>
                <span class="member-hero-date"><i class="bi bi-calendar3"></i>{{ $memberNow->translatedFormat('l, j F Y') }}</span>
            </div>
        </div>
        <div class="member-hero-decoration" aria-hidden="true">
            <span class="member-hero-orbit member-hero-orbit--one"></span>
            <span class="member-hero-orbit member-hero-orbit--two"></span>
            <i class="bi bi-stars"></i>
        </div>
    </div>

    <section class="card member-card mb-4" aria-labelledby="member-custom-offers-title">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <span class="member-eyebrow">Khusus untuk Anda</span>
                    <h2 id="member-custom-offers-title" class="h5 fw-semibold mb-0">Permintaan dan penawaran paket privat</h2>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('package-offer-requests.mine') }}" class="btn btn-sm btn-outline-success">Permintaan saya</a>
                    <a href="{{ route('package-offer-requests.create') }}" class="btn btn-sm btn-success">Ajukan kebutuhan</a>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-lg-6">
                    <h3 class="h6 fw-semibold">Penawaran terbaru</h3>
                    @forelse ($myPackageOffers as $offer)
                    <a href="{{ route('package-offers.show', $offer->uuid) }}" class="d-flex justify-content-between align-items-center gap-2 border rounded-3 p-3 mb-2 text-decoration-none">
                        <span class="min-w-0">
                            <span class="fw-semibold d-block text-truncate">{{ $offer->title }}</span>
                            <small class="text-muted">{{ $offer->options->count() }} pilihan · Versi {{ $offer->version }}</small>
                        </span>
                        <span class="badge text-bg-{{ $offer->status === 'sent' ? 'warning' : ($offer->status === 'accepted' ? 'success' : 'secondary') }}">
                            {{ ['sent' => 'Menunggu tanggapan', 'accepted' => 'Disetujui', 'rejected' => 'Ditolak', 'expired' => 'Kedaluwarsa', 'revision_requested' => 'Menunggu revisi', 'superseded' => 'Diperbarui'][$offer->display_status] ?? 'Dalam proses' }}
                        </span>
                    </a>
                    @empty
                    <div class="small text-muted border rounded-3 p-3">Belum ada penawaran privat. Ajukan kebutuhan acara untuk memulai.</div>
                    @endforelse
                </div>
                <div class="col-lg-6">
                    <h3 class="h6 fw-semibold">Status permintaan</h3>
                    @forelse ($myPackageRequests as $packageRequest)
                    <a href="{{ route('package-offer-requests.show', $packageRequest->uuid) }}" class="d-flex justify-content-between align-items-center gap-2 border rounded-3 p-3 mb-2 text-decoration-none">
                        <span class="min-w-0">
                            <span class="fw-semibold d-block text-truncate">{{ \Illuminate\Support\Str::limit($packageRequest->request_details, 70) }}</span>
                            <small class="text-muted">{{ $packageRequest->created_at?->translatedFormat('d M Y') }}</small>
                        </span>
                        <span class="badge text-bg-{{ $packageRequest->status === 'quoted' ? 'success' : ($packageRequest->status === 'submitted' ? 'warning' : 'info') }}">
                            {{ ['submitted' => 'Menunggu', 'in_review' => 'Ditinjau', 'quoted' => 'Ada penawaran', 'closed' => 'Ditutup'][$packageRequest->status] ?? 'Dalam proses' }}
                        </span>
                    </a>
                    @empty
                    <div class="small text-muted border rounded-3 p-3">Belum ada permintaan paket khusus.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4 member-stats">
        @foreach ($memberStats as $stat)
            <div class="col-6 col-xl-3">
                <div class="card member-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="member-stat-icon bg-{{ $stat['tone'] }}-subtle text-{{ $stat['tone'] }}">
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="member-stat-value">{{ $stat['value'] }}</div>
                            <div class="member-stat-label">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <section class="card member-card member-section-card">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Ringkasan akun dan pesanan</span>
                            <h2>{{ __('dashboard.planning') }}</h2>
                        </div>
                        <i class="bi bi-calendar-heart member-heading-icon" aria-hidden="true"></i>
                    </div>

                    <div class="member-planning-grid mt-3">
                        <div class="member-summary">
                            <div class="small text-muted">Tanggal pernikahan</div>
                            <div class="member-summary-date">
                                {{ $planningEventDate ? $planningEventDate->translatedFormat('d F Y') : 'Belum ada tanggal pesanan' }}
                            </div>
                            @if ($planningDaysRemaining !== null)
                                <div class="member-days-left">
                                    @if ($planningDaysRemaining > 0)
                                        {{ number_format($planningDaysRemaining, 0, ',', '.') }} hari lagi
                                    @elseif ($planningDaysRemaining === 0)
                                        Hari ini
                                    @else
                                        Acara telah berlangsung
                                    @endif
                                </div>
                            @else
                                <div class="small text-muted mt-2">Tanggal tampil otomatis dari pesanan paket aktif.</div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Langkah persiapan</span>
                                <span class="fw-semibold">{{ $completedPlanningSteps }}/{{ count($planningSteps) }} selesai</span>
                            </div>
                            <div class="progress member-progress" role="progressbar" aria-label="Progress checklist" aria-valuenow="{{ $planningProgress }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-success" style="width: {{ $planningProgress }}%"></div>
                            </div>
                        </div>

                        <div>
                            <h3 class="member-subtitle">Langkah persiapan</h3>
                            @foreach ($planningSteps as $step)
                                <div class="member-task">
                                    <i class="bi {{ $step['done'] ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} mt-1" aria-hidden="true"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $step['label'] }}</div>
                                        <div class="small text-muted">{{ $step['description'] }}</div>
                                    </div>
                                    @if ($step['done'])
                                        <span class="badge bg-success-subtle text-success-emphasis">Selesai</span>
                                    @else
                                        <a href="{{ $step['url'] }}" class="btn btn-sm btn-outline-success text-nowrap">{{ $step['action'] }}</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="card member-card member-section-card member-recommendation-card mt-4">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Sesuai anggaran</span>
                            <h2>{{ __('dashboard.recommendations') }}</h2>
                        </div>
                        <i class="bi bi-stars member-heading-icon" aria-hidden="true"></i>
                    </div>
                    @if ($planningOrderTotal > 0)
                        <div class="member-summary mt-2">
                            <div class="fw-semibold">{{ $planningOrders->count() }} pesanan paket aktif tersinkron</div>
                            <div class="small text-muted mt-1">Total disetujui dan kekurangan dapat dilihat pada rekap pembayaran.</div>
                            <a href="#payment-recap" class="small fw-semibold text-decoration-none d-inline-block mt-2">Lihat rekap pembayaran</a>
                        </div>
                    @else
                        <div class="member-summary mt-2">
                            <div class="fw-semibold">Belum ada pesanan paket aktif</div>
                            <div class="small text-muted mt-1">Setelah memilih paket, total biaya dan pembayaran akan muncul di sini.</div>
                        </div>
                    @endif
                    <div class="mt-2">
                        @include('pages.dashboard.partials.package-recommendations')
                    </div>
                </div>
            </section>
        </div>

        <div class="col-xl-4">
            <section class="card member-card member-section-card mb-4">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Jadwal otomatis</span>
                            <h2>Timeline persiapan</h2>
                        </div>
                        <i class="bi bi-clock-history member-heading-icon" aria-hidden="true"></i>
                    </div>
                    <p class="small text-muted mt-2 mb-3">Tanggal tahap dihitung dari tanggal acara pada pesanan.</p>
                    <div class="member-timeline">
                        @foreach ($planningTimeline as $milestone)
                            <div class="member-milestone">
                                <div class="fw-semibold">{{ $milestone['label'] }}</div>
                                <div class="small text-muted">
                                    {{ $milestone['date'] ? $milestone['date']->translatedFormat('d F Y') : $milestone['offset'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @unless ($planningEventDate)
                        <a href="#package-recommendation" class="btn btn-sm btn-outline-success mt-3">Pilih paket untuk mulai</a>
                    @endunless
                </div>
            </section>

            <section class="card member-card member-section-card member-actions-card mb-4">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Pintasan</span>
                            <h2>{{ __('dashboard.quick_actions') }}</h2>
                        </div>
                        <i class="bi bi-lightning-charge member-heading-icon" aria-hidden="true"></i>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <a href="#package-recommendation" class="btn btn-success"><i class="bi bi-search me-1"></i> {{ __('dashboard.find_packages') }}</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-success"><i class="bi bi-receipt me-1"></i> {{ __('dashboard.check_payment') }}</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary"><i class="bi bi-person me-1"></i> Profil Saya</a>
                    </div>
                    @if($activePackage)
                        <hr>
                        <div class="small text-muted mb-1">Paket Aktif</div>
                        <div class="fw-semibold">{{ $activePackage->package?->name ?? 'Paket Wedding' }}</div>
                        <div class="small text-muted">Berlaku sampai {{ $activePackage->expired_at?->translatedFormat('d M Y') ?? '-' }}</div>
                    @endif
                </div>
            </section>

        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <section class="card member-card member-section-card member-checklist-card">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Tugas Anda</span>
                            <h2>{{ __('dashboard.checklist') }}</h2>
                        </div>
                        <i class="bi bi-check2-square member-heading-icon" aria-hidden="true"></i>
                    </div>
                    @if ($dashboardChecklistOrder)
                        <div class="small text-muted mt-2 mb-3">
                            {{ $dashboardChecklistOrder->order_number }} ·
                            {{ $dashboardChecklistItems->where('status', 'completed')->count() }}/{{ $dashboardChecklistItems->count() }} selesai
                        </div>
                        @if ($dashboardChecklistItems->isNotEmpty())
                            @foreach ($dashboardChecklistItems->take(5) as $item)
                                <div class="member-task">
                                    <form method="POST" action="{{ route('orders.checklist.update', [$dashboardChecklistOrder->uuid, $item->uuid]) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $item->status === 'completed' ? 'pending' : 'completed' }}">
                                        <button type="submit" class="btn btn-link p-0 border-0" aria-label="{{ $item->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                            <i class="bi {{ $item->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold {{ $item->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $item->title }}</div>
                                        <div class="small text-muted">{{ $item->description }}@if($item->due_at) · Target {{ $item->due_at->translatedFormat('d M Y') }}@endif</div>
                                    </div>
                                    @if ($item->status === 'completed')
                                        <span class="badge bg-success-subtle text-success-emphasis">Selesai</span>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="small text-muted mt-3">Belum ada tugas checklist untuk ditampilkan.</div>
                        @endif
                        <a href="{{ route('orders.show', $dashboardChecklistOrder->uuid) }}" class="small fw-semibold text-decoration-none d-inline-block mt-3">Buka detail pesanan</a>
                    @else
                        <div class="small text-muted mt-3">Checklist akan muncul setelah Anda memiliki pesanan paket dengan tanggal acara.</div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <section id="payment-recap" class="card member-card member-section-card h-100">
                <div class="card-body">
                    <div class="member-section-heading">
                        <div>
                            <span class="member-eyebrow">Keuangan pesanan</span>
                            <h2>{{ __('dashboard.payment') }}</h2>
                        </div>
                        <a href="{{ route('orders.index') }}" class="small fw-semibold text-decoration-none">Riwayat lengkap</a>
                    </div>
                    <p class="small text-muted mt-2 mb-3">Rekap 5 pesanan paket terbaru. Pembayaran yang disetujui mengurangi sisa tagihan.</p>

                    <div class="member-payment-summary mb-3">
                        <div>
                            <div class="small text-muted">Total pesanan aktif</div>
                            <div class="fw-bold">Rp {{ number_format($paymentOrderTotal, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="small text-muted">Pembayaran disetujui</div>
                            <div class="fw-bold text-success">Rp {{ number_format($paymentPaidTotal, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="small text-muted">Kekurangan</div>
                            <div class="fw-bold text-danger">Rp {{ number_format($paymentRemainingTotal, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="table-responsive member-table-wrap">
                        <table class="table table-hover align-middle mb-0 member-orders-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Pesanan &amp; paket</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Disetujui</th>
                                    <th class="text-end">Kekurangan</th>
                                    <th>Bukti pembayaran</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($myOrders as $o)
                                    @php
                                        $total = (float) $o->full_amount;
                                        $dibayar = (float) $o->amount_paid_value;
                                        $sisa = (float) $o->remaining_amount_value;
                                        $proofPayments = $o->payments->filter(fn ($payment) => $payment->proof_image_path)->take(3);
                                        $proofStatusLabels = ['accepted' => 'Diterima', 'pending' => 'Pending', 'rejected' => 'Ditolak'];
                                        $proofStatusClasses = ['accepted' => 'bg-success-subtle text-success-emphasis', 'pending' => 'bg-warning-subtle text-warning-emphasis', 'rejected' => 'bg-danger-subtle text-danger-emphasis'];
                                        $legacyProofStatus = $o->status === 'paid' ? 'accepted' : ($o->status === 'failed' ? 'rejected' : 'pending');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold member-order-number">{{ $o->order_number }}</div>
                                            <div class="small text-muted">{{ $o->package?->name ?? 'Paket Wedding' }} {{ $o->packageOption?->name ? '· '.$o->packageOption->name : '' }}</div>
                                        </td>
                                        <td class="text-end text-nowrap">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap fw-semibold text-success">Rp {{ number_format($dibayar, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap fw-semibold {{ $sisa > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                        <td>
                                            @forelse ($proofPayments as $payment)
                                                @php
                                                    $proofStatus = $payment->proof_verification_status;
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-between gap-2 {{ $loop->first ? '' : 'mt-2' }}">
                                                    <span class="badge {{ $proofStatusClasses[$proofStatus] }}">{{ $proofStatusLabels[$proofStatus] }}</span>
                                                    <a href="{{ route('orders.payments.proof', [$o->uuid, $payment->uuid]) }}" class="small text-decoration-none">Lihat bukti</a>
                                                </div>
                                                <div class="small text-muted">Rp {{ number_format((float) ($payment->amount ?? $payment->gross_amount), 0, ',', '.') }}</div>
                                            @empty
                                                @if ($o->proof_image_path)
                                                    <span class="badge {{ $proofStatusClasses[$legacyProofStatus] }}">{{ $proofStatusLabels[$legacyProofStatus] }}</span>
                                                    <a href="{{ route('orders.proof.show', $o->uuid) }}" class="small text-decoration-none ms-1">Lihat bukti</a>
                                                @else
                                                    <span class="small text-muted">Belum ada bukti</span>
                                                @endif
                                            @endforelse
                                        </td>
                                        <td class="text-end"><a href="{{ route('orders.show', $o->uuid) }}" class="btn btn-sm btn-outline-success">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pembayaran. Cari paket sesuai anggaran untuk memulai.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
