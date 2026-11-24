<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="Panel admin Sagita Wedding Organizer untuk mengelola pesanan, pelanggan, dan layanan pernikahan." />
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet" />
    <meta name="googlebot" content="noindex, nofollow, noarchive" />
    <title>@yield('title') | Admin Sagita Wedding Organizer</title>

    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}" type="image/x-icon">

    <!-- Style -->
    @stack('before-style')
    {{-- @include('components.includes.style') --}}

    <link rel="stylesheet" href="{{ asset('dist/assets/bundles/admin.css') }}?v={{ \Illuminate\Support\Facades\File::exists(public_path('dist/assets/bundles/admin.css')) ? filemtime(public_path('dist/assets/bundles/admin.css')) : '1' }}">


    @stack('after-style')
    <style>
        .global-toast-stack {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: .55rem;
            width: min(370px, calc(100vw - 2rem));
            pointer-events: none;
        }

        .global-toast {
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .8rem .9rem;
            border: 1px solid rgba(255, 255, 255, .16);
            border-left: 4px solid #0d6efd;
            border-radius: .75rem;
            background: #1f2937;
            color: #fff;
            box-shadow: 0 14px 32px rgba(15, 23, 42, .28);
            opacity: 1;
            transform: translateY(0);
            transition: opacity .2s ease, transform .2s ease;
        }

        .global-toast.is-success { border-left-color: #198754; }
        .global-toast.is-error { border-left-color: #dc3545; }
        .global-toast.is-undo { border-left-color: #0dcaf0; }
        .global-toast.is-leaving { opacity: 0; transform: translateY(-8px); }
        .global-toast-icon { font-size: 1rem; line-height: 1.3; }
        .global-toast-body { flex: 1; min-width: 0; font-size: .875rem; line-height: 1.45; }
        .global-toast-close {
            border: 0;
            background: transparent;
            color: rgba(255, 255, 255, .72);
            font-size: 1.1rem;
            line-height: 1;
            padding: 0 .1rem;
        }
        .global-toast-close:hover { color: #fff; }
        .global-toast-undo {
            margin-top: .5rem;
            border: 0;
            border-radius: .45rem;
            background: rgba(255, 255, 255, .14);
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            padding: .35rem .6rem;
        }
        .global-toast-undo:hover { background: rgba(255, 255, 255, .24); }
        .global-toast-undo:disabled { opacity: .6; cursor: wait; }

        @media (max-width: 575.98px) {
            .global-toast-stack { top: .6rem; right: .6rem; width: calc(100vw - 1.2rem); }
        }
    </style>
    <!-- /Style -->

</head>

<body>
    <div class="app-loader" id="app-loader">
        <div class="app-loader__spinner" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
        <span class="app-loader__text">Memuat halaman&hellip;</span>
    </div>
    <div id="app">
        <x-menu />

        <div id="main" class='layout-navbar navbar-fixed'>
            <x-web.header />

            <div id="main-content">
                <x-validation-errors />
                <div class="page-heading">
                    <div class="page-title">
                        @yield('breadcrumb')
                    </div>
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <footer>
                <div class="footer clearfix mb-0">
                    <div class="argon-footer d-flex flex-wrap justify-content-center align-items-center gap-2 text-center">
                        <span>&copy; <script>
                                document.write(new Date().getFullYear())
                            </script> Sagita Wedding Organizer.</span>
                        <span class="argon-footer-dot" aria-hidden="true"></span>
                        <span>Seluruh hak dilindungi</span>
                    </div>
                </div>
            </footer>
            <!--/Footer -->

        </div>
    </div>

    {{-- Global command palette (Ctrl+K): at <body> level so it sits above all stacking contexts --}}
    <div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog gs-modal-dialog">
            <div class="modal-content gs-palette" role="dialog" aria-modal="true" aria-label="Pencarian global">
                <div class="gs-input-row">
                    <i class="bi bi-search gs-input-icon"></i>
                    <input type="text" id="global-search-input" class="gs-input"
                        placeholder="Cari di semua menu (min. 2 karakter)"
                        aria-label="Cari di semua menu"
                        autocomplete="off">
                    <button type="button" id="global-search-close" class="gs-esc" aria-label="Tutup">ESC</button>
                </div>
                <div id="global-search-results" class="gs-results">
                    <p class="gs-hint">Ketik untuk mencari di semua menu.</p>
                </div>
            </div>
        </div>
    </div>

    @auth
    <div id="global-toast-stack" class="global-toast-stack" aria-live="polite" aria-atomic="false">
        @if (session('success'))
        <div class="global-toast is-success" data-global-toast data-toast-life="6000">
            <i class="bi bi-check-circle-fill global-toast-icon"></i>
            <div class="global-toast-body">{{ session('success') }}</div>
            <button type="button" class="global-toast-close" data-toast-close aria-label="Tutup">&times;</button>
        </div>
        @endif
        @if (session('error'))
        <div class="global-toast is-error" data-global-toast data-toast-life="7000">
            <i class="bi bi-exclamation-triangle-fill global-toast-icon"></i>
            <div class="global-toast-body">{{ session('error') }}</div>
            <button type="button" class="global-toast-close" data-toast-close aria-label="Tutup">&times;</button>
        </div>
        @endif
        @if (session('undo_token'))
        <div class="global-toast is-undo" data-global-toast data-toast-life="0">
            <i class="bi bi-arrow-counterclockwise global-toast-icon"></i>
            <div class="global-toast-body">
                <div>{{ session('undo_message') ?: 'Tindakan massal dapat dibatalkan.' }}</div>
                <button type="button" class="global-toast-undo" data-undo-token="{{ session('undo_token') }}">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan sekarang
                </button>
            </div>
            <button type="button" class="global-toast-close" data-toast-close aria-label="Tutup">&times;</button>
        </div>
        @endif
    </div>
    @endauth

    <!-- Script -->
    @stack('before-script')
    {{-- @include('components.includes.script') --}}
    <script src="{{ asset('dist/assets/bundles/admin.js') }}?v={{ \Illuminate\Support\Facades\File::exists(public_path('dist/assets/bundles/admin.js')) ? filemtime(public_path('dist/assets/bundles/admin.js')) : '1' }}"></script>

    @stack('after-script')

    <script>
        (function () {
            function syncSubmenu(item) {
                var submenu = item.querySelector(':scope > .submenu');
                if (!submenu) return;
                submenu.style.setProperty('--submenu-height', Math.ceil(submenu.scrollHeight) + 'px');
                var link = item.querySelector(':scope > .sidebar-link');
                if (link) link.setAttribute('aria-expanded', submenu.classList.contains('submenu-open') ? 'true' : 'false');
            }
            function syncAll() {
                document.querySelectorAll('#sidebar .sidebar-item.has-sub').forEach(syncSubmenu);
            }
            document.querySelectorAll('#sidebar .sidebar-item.has-sub > .sidebar-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    window.setTimeout(function () { syncSubmenu(link.closest('.sidebar-item.has-sub')); }, 0);
                });
            });
            syncAll();
            window.addEventListener('load', syncAll);
            window.addEventListener('resize', syncAll);
        })();
    </script>

    <script>
        (function () {
            var stack = document.getElementById('global-toast-stack');
            if (!stack) return;

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
            var undoUrl = "{{ session('undo_url') ?: route('orders.bulk-undo') }}";

            function dismissToast(toast) {
                if (!toast) return;
                toast.classList.add('is-leaving');
                window.setTimeout(function () {
                    if (toast.parentNode) toast.parentNode.removeChild(toast);
                }, 220);
            }

            function makeToast(message, type, life) {
                var toast = document.createElement('div');
                toast.className = 'global-toast ' + (type === 'error' ? 'is-error' : 'is-success');
                toast.setAttribute('data-global-toast', '');
                toast.setAttribute('data-toast-life', String(life || 5000));

                var icon = document.createElement('i');
                icon.className = 'bi ' + (type === 'error' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill') + ' global-toast-icon';

                var body = document.createElement('div');
                body.className = 'global-toast-body';
                body.textContent = message || 'Tindakan berhasil.';

                var close = document.createElement('button');
                close.type = 'button';
                close.className = 'global-toast-close';
                close.setAttribute('data-toast-close', '');
                close.setAttribute('aria-label', 'Tutup');
                close.innerHTML = '&times;';

                toast.appendChild(icon);
                toast.appendChild(body);
                toast.appendChild(close);
                stack.appendChild(toast);

                close.addEventListener('click', function () {
                    dismissToast(toast);
                });
                window.setTimeout(function () {
                    dismissToast(toast);
                }, life || 5000);
            }

            Array.prototype.forEach.call(stack.querySelectorAll('[data-global-toast]'), function (toast) {
                var life = parseInt(toast.getAttribute('data-toast-life') || '6000', 10);

                var close = toast.querySelector('[data-toast-close]');
                if (close) {
                    close.addEventListener('click', function () {
                        dismissToast(toast);
                    });
                }

                if (life > 0) {
                    window.setTimeout(function () {
                        dismissToast(toast);
                    }, life);
                }
            });

            stack.addEventListener('click', function (event) {
                var target = event.target;
                var button = target && target.closest ? target.closest('[data-undo-token]') : null;
                if (!button) return;

                event.preventDefault();

                var toast = button.closest('[data-global-toast]');
                button.disabled = true;

                fetch(undoUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        undo_token: button.getAttribute('data-undo-token'),
                    }),
                })
                    .then(function (response) {
                        return response.json().catch(function () {
                            return {};
                        }).then(function (data) {
                            return { ok: response.ok, data: data };
                        });
                    })
                    .then(function (result) {
                        if (!result.ok || result.data.success === false) {
                            throw new Error(result.data.message || 'Pembatalan gagal.');
                        }

                        dismissToast(toast);
                        makeToast(result.data.message || 'Tindakan berhasil dibatalkan.', 'success', 4500);
                        window.dispatchEvent(new CustomEvent('orders:changed'));
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 900);
                    })
                    .catch(function (error) {
                        button.disabled = false;
                        makeToast(error && error.message ? error.message : 'Pembatalan gagal.', 'error', 6000);
                    });
            });
        })();
    </script>
    <!-- /Script -->

    <!-- include summernote css/js -->
    <script>
        $(document).ready(function() {
            // ID lama (halaman sebelumnya) — jangan dihapus agar editor lama tetap berfungsi
            if ($('#deskripsi').length && !$('#deskripsi').hasClass('summernote-initialized')) {
                $('#deskripsi').addClass('summernote-initialized').summernote();
            }
            if ($('#latar_belakang').length && !$('#latar_belakang').hasClass('summernote-initialized')) {
                $('#latar_belakang').addClass('summernote-initialized').summernote();
            }
            if ($('#hasil').length && !$('#hasil').hasClass('summernote-initialized')) {
                $('#hasil').addClass('summernote-initialized').summernote();
            }
            if ($('#event').length && !$('#event').hasClass('summernote-initialized')) {
                $('#event').addClass('summernote-initialized').summernote({
                    height: 200
                });
            }

            // Generic Sagita Wedding-style setup for .summernote textareas (including inside modals)
            function initSummernote($container) {
                $container.find('.summernote').each(function() {
                    if (!$(this).hasClass('summernote-initialized')) {
                        $(this).addClass('summernote-initialized').summernote({
                            height: 200,
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video']],
                                ['view', ['fullscreen', 'codeview', 'help']]
                            ]
                        });
                    }
                });
            }

            initSummernote($(document));

            $(document).on('shown.bs.modal', '.modal', function() {
                initSummernote($(this));
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#vendors_categories').select2({
                placeholder: 'Pilih kategori vendor',
                width: '100%',
                closeOnSelect: false
            });
        });
    </script>

    <script>
        (function() {
            var loader = document.getElementById('app-loader');

            function hideLoader() {
                if (!loader || loader.classList.contains('is-hidden')) return;
                loader.classList.add('is-hidden');

                window.setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 400);
            }

            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                window.addEventListener('load', hideLoader);
            }

            /* Safety net: don't let the loader block the page forever. */
            window.setTimeout(hideLoader, 8000);
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @auth
    <script>
        (function() {
            // Auto-logout after 30 minutes without activity (Sagita Wedding-style).
            // 2 menit sebelum habis tampil pilihan: Tetap login / Keluar.
            const INACTIVE_TIMEOUT_MIN = {{ (int) config('session.inactive_timeout', 30) }};
            const WARNING_BEFORE_MIN = 2;
            const INACTIVE_MS = INACTIVE_TIMEOUT_MIN * 60 * 1000;
            const WARNING_MS = WARNING_BEFORE_MIN * 60 * 1000;
            const TIMEOUT_MS = INACTIVE_MS - WARNING_MS;
            const idleLimit = TIMEOUT_MS > 5000 ? TIMEOUT_MS : 5000;

            let idleTimer = null;
            let warningShown = false;
            let countdownInterval = null;
            let alreadyLoggingOut = false;

            function getCsrf() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            function doLogout() {
                if (alreadyLoggingOut) return;
                alreadyLoggingOut = true;
                if (countdownInterval) clearInterval(countdownInterval);
                const token = getCsrf();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}";
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = token;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }

            function keepAliveOnce() {
                const token = getCsrf();
                return fetch("{{ route('keep-alive') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }).then((res) => {
                    // 419 (CSRF) / 401 (session habis) dianggap gagal -> logout tegas.
                    if (res.status === 401 || res.status === 419 || res.status === 404) return false;
                    if (!res.ok) throw new Error('keep-alive gagal');
                    return res.json().then((data) => data && data.ok === true).catch(() => false);
                });
            }

            function keepAlive() {
                return keepAliveOnce()
                    .then((ok) => ok ? true : keepAliveOnce())
                    .catch(() => false);
            }

            function showWarning() {
                if (warningShown) return;
                warningShown = true;
                let remaining = WARNING_BEFORE_MIN * 60;

                const updateText = () => {
                    const m = Math.floor(remaining / 60);
                    const s = String(remaining % 60).padStart(2, '0');
                    const html = `Sesi Anda akan berakhir dalam <b>${m}:${s}</b> karena tidak ada aktivitas.<br>Klik <b>Tetap login</b> untuk melanjutkan.`;
                    const container = document.querySelector('.swal2-html-container');
                    if (container) container.innerHTML = html;
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Sesi segera berakhir',
                        html: `Sesi Anda akan berakhir dalam <b>${WARNING_BEFORE_MIN}:00</b> karena tidak ada aktivitas.<br>Klik <b>Tetap login</b> untuk melanjutkan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Tetap login',
                        cancelButtonText: 'Keluar',
                        confirmButtonColor: '#2f7d4f',
                        cancelButtonColor: '#d33',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            countdownInterval = setInterval(() => {
                                remaining--;
                                if (remaining <= 0) {
                                    clearInterval(countdownInterval);
                                    countdownInterval = null;
                                    if (typeof Swal !== 'undefined') Swal.close();
                                    doLogout();
                                } else {
                                    updateText();
                                }
                            }, 1000);
                        },
                        willClose: () => {
                            if (countdownInterval) clearInterval(countdownInterval);
                        }
                    }).then((result) => {
                        // Klik "Tetap login": refresh server dulu, reset timer
                        // HANYA jika server jawab ok. Kalau gagal (401/419) -> logout.
                        if (result.isConfirmed) {
                            if (countdownInterval) {
                                clearInterval(countdownInterval);
                                countdownInterval = null;
                            }
                            keepAlive().then((alive) => {
                                if (alive) {
                                    warningShown = false;
                                    alreadyLoggingOut = false;
                                    resetTimer();
                                } else {
                                    doLogout();
                                }
                            });
                        } else {
                            // "Logout" / countdown habis / dismiss lain -> logout tegas.
                            // Jangan resetTimer tanpa keepAlive agar client & server tidak selisih.
                            doLogout();
                        }
                    });
                } else {
                    const ok = confirm(`Sesi Anda akan berakhir dalam ${WARNING_BEFORE_MIN} menit karena tidak ada aktivitas. Klik OK untuk tetap login.`);
                    if (ok) {
                        keepAlive().then((alive) => {
                            if (alive) {
                                warningShown = false;
                                resetTimer();
                            } else {
                                doLogout();
                            }
                        });
                    } else {
                        doLogout();
                    }
                }
            }

            function resetTimer() {
                if (idleTimer) clearTimeout(idleTimer);
                if (countdownInterval) clearInterval(countdownInterval);
                warningShown = false;
                idleTimer = setTimeout(showWarning, idleLimit);
            }

            let throttle = false;

            function onActivity() {
                if (warningShown) return;
                if (throttle) return;
                throttle = true;
                setTimeout(() => throttle = false, 1000);
                resetTimer();
            }

            ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, onActivity, {
                    passive: true
                });
            });

            resetTimer();
        })();
    </script>
    @endauth

    <script>
        $(document).on("click", ".mark-as-read", function(e) {
            e.preventDefault();

            let id = $(this).data("id");
            let $item = $(this).closest(".notification-item");
            let target = $(this).attr("href");

            $.ajax({
                url: "{{ url('/notifications') }}/" + id + "/read",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        // remove Sagita Wedding-style unread highlight
                        $item.removeClass("unread");
                        $item.find(".notif-dot").addClass("read");

                        // update badge count
                        let count = parseInt($("#notif-count").text()) - 1;
                        if (count > 0) {
                            $("#notif-count").text(count);
                        } else {
                            $("#notif-count").remove();
                        }

                        if (target && target !== "javascript:void(0)" && target !== "#") {
                            window.location.assign(target);
                        }
                    }
                }
            });
        });
    </script>


</body>

</html>