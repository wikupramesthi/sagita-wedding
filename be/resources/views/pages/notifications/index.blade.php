@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Notifikasi" page="Akun" active="Notifikasi"
        route="{{ route('notifications.index') }}" />
@endsection

<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="stats-icon blue mb-0"><i class="bi bi-bell"></i></span>
                <div>
                    <h5 class="card-title mb-0">Semua notifikasi</h5>
                    <small class="text-muted">Pembaruan pesanan, pembayaran, dan aktivitas akun kamu.</small>
                </div>
                @if ($unreadCount > 0)
                    <span class="badge bg-danger-subtle text-danger">{{ $unreadCount }} belum dibaca</span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group" role="group" aria-label="Filter notifikasi">
                    <a href="{{ route('notifications.index') }}"
                        class="btn btn-sm {{ $filter === 'all' ? 'btn-success' : 'btn-light' }}">
                        <i class="bi bi-list me-1"></i>Semua
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                        class="btn btn-sm {{ $filter === 'unread' ? 'btn-success' : 'btn-light' }}">
                        <i class="bi bi-envelope me-1"></i>Belum dibaca
                    </a>
                </div>
                @if ($unreadCount > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check2-all me-1"></i>Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse ($notifications as $notification)
                    <div
                        class="list-group-item d-flex align-items-start gap-3 px-4 py-3 {{ $notification->read_at ? '' : 'bg-success bg-opacity-10 border-start border-3 border-success' }}">
                        <span class="d-flex align-items-center justify-content-center rounded-circle bg-light text-primary flex-shrink-0"
                            style="width:38px;height:38px;">
                            <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} fs-6"></i>
                        </span>
                        <div class="flex-fill min-w-0">
                            <div class="d-flex align-items-center gap-2">
                                <p class="fw-bold mb-0 text-dark">
                                    {{ $notification->data['judul_kegiatan'] ?? 'Notifikasi' }}
                                </p>
                                @if (!$notification->read_at)
                                    <span class="badge bg-success">Baru</span>
                                @endif
                            </div>
                            <p class="mb-1">{{ $notification->data['message'] ?? 'Detail belum tersedia.' }}</p>
                            <small class="text-muted d-inline-flex align-items-center gap-1">
                                <i class="bi bi-clock"></i>
                                {{ $notification->created_at->diffForHumans() }}
                                <span class="mx-1">•</span>
                                {{ $notification->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="d-flex flex-column gap-2 flex-shrink-0">
                            @if (!empty($notification->data['path']))
                                <a href="{{ $notification->data['path'] }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            @endif
                            @if (!$notification->read_at)
                                <a href="javascript:void(0)" class="btn btn-sm btn-light mark-as-read"
                                    data-id="{{ $notification->id }}">
                                    <i class="bi bi-check-lg me-1"></i>Tandai dibaca
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bell-slash fs-1 d-block mb-3"></i>
                        <p class="fw-semibold mb-1">
                            {{ $filter === 'unread' ? 'Tidak ada notifikasi belum dibaca.' : 'Belum ada notifikasi.' }}
                        </p>
                        <p class="small mb-0">Pembaruan pesanan, pembayaran, dan check-in akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 px-4 py-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $notifications->firstItem() }}</strong> –
                        <strong>{{ $notifications->lastItem() }}</strong> dari
                        <strong>{{ $notifications->total() }}</strong> notifikasi
                    </div>
                    <div>{{ $notifications->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
