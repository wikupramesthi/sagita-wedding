<?php

use App\Http\Middleware\ApiSecurityHeaders;
use App\Http\Middleware\AutoLogoutInactive;
use App\Http\Middleware\RouteMiddleware;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'route.permission' => RouteMiddleware::class,
            'inactive' => AutoLogoutInactive::class,
        ]);
        // Auto-logout bila 30 menit tanpa aktivitas (ala DBMSDA).
        // Server-side layer: proteksi walau JS dimatikan. JS warning
        // "Tetap Login / Logout" ada di layouts/app.blade.php (@auth).
        $middleware->appendToGroup('web', 'route.permission');
        $middleware->appendToGroup('web', AutoLogoutInactive::class);
        $middleware->throttleApi('api');
        $middleware->prependToGroup('api', ApiSecurityHeaders::class);
        $middleware->appendToGroup('api', VerifyApiKey::class);
        // Logout yang dipanggil frontend publik (Express) tidak mengirim
        // token CSRF; endpoint ini tetap butuh cookie session yang valid.
        $middleware->validateCsrfTokens(except: [
            'auth/me/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi berakhir. Silakan login kembali.'], 419);
            }

            return redirect()->route('login')->withErrors(['session_expired' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $status = $e->getStatusCode();
            $messages = [
                401 => 'Unauthorized.',
                403 => 'Akses ditolak.',
                404 => 'Endpoint tidak ditemukan.',
                405 => 'Metode tidak diizinkan.',
                419 => 'Sesi berakhir. Silakan login kembali.',
                429 => 'Terlalu banyak permintaan. Silakan coba lagi.',
            ];

            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $messages[$status] ?? ($status >= 500
                    ? 'Terjadi kesalahan pada server.'
                    : ($e->getMessage() ?: 'Permintaan tidak dapat diproses.')),
            ], $status);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            Log::error('API exception', [
                'method' => $request->method(),
                'path' => $request->path(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => app()->environment('local', 'testing')
                    ? $e->getMessage()
                    : 'Terjadi kesalahan pada server.',
            ], 500);
        });
    })->create();
