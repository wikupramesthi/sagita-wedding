<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        // Ingat tujuan setelah login (mis. FE booking page) agar callback bisa
        // mengembalikannya. Hanya simpan URL yang aman (relative atau FRONTEND_URL).
        $redirect = $request->query('redirect');
        if ($redirect && $this->isSafeRedirect($redirect)) {
            $request->session()->put('google_redirect', $redirect);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (! $googleUser->getEmail()) {
                return redirect()->route('login')
                    ->with('error', 'Login Google gagal. Google tidak memberikan alamat email. Silakan coba lagi.');
            }

            if (! filter_var($googleUser->getRaw()['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                return redirect()->route('login')
                    ->with('error', 'Login Google gagal. Gunakan akun dengan alamat email yang sudah terverifikasi.');
            }

            // Cek user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                // Buat user baru
                $user = User::create([
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(uniqid()),
                    'email_verified_at' => now(),
                ]);

                // Jangan fatal jika role 'user' belum di-seed.
                if (Role::where('name', 'user')->exists()) {
                    $user->assignRole('user');
                } else {
                    Log::warning('Google login: role [user] missing, skipping assignRole.', [
                        'email' => $user->email,
                    ]);
                }
            } else {
                // Tautkan google_id / avatar yang belum tersimpan (kolom google_id
                // sebelumnya tidak fillable sehingga tidak pernah tersimpan).
                $dirty = false;
                if (empty($user->google_id) && $googleUser->getId()) {
                    $user->google_id = $googleUser->getId();
                    $dirty = true;
                }
                if (empty($user->avatar) && $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                    $dirty = true;
                }
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $dirty = true;
                }
                if ($dirty) {
                    $user->save();
                }
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Kembali ke FE jika login dimulai dari sana (?redirect=...).
            $redirect = $request->session()->pull('google_redirect');
            if ($redirect && $this->isSafeRedirect($redirect)) {
                return redirect($redirect);
            }

            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            // Catat pesan asli (mis. invalid_state, redirect_uri_mismatch di sisi
            // token exchange) agar bisa didiagnosis via storage/logs/laravel.log.
            Log::warning('Google login failed: '.$e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return redirect()->route('login')
                ->with('error', 'Login Google gagal. Pastikan alamat pengalihan sudah terdaftar di Google Console, lalu coba lagi.');
        }
    }

    /**
     * Hanya izinkan redirect relative ("/...") atau di bawah FRONTEND_URL
     * untuk mencegah open-redirect.
     */
    private function isSafeRedirect(string $url): bool
    {
        if ($url === '' || preg_match('/[\x00-\x1F\x7F\\\\]/', $url)) {
            return false;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return true;
        }

        $base = parse_url((string) config('frontend.url', ''));
        $target = parse_url($url);

        if (! is_array($base) || empty($base['host']) || ! is_array($target)) {
            return false;
        }

        $baseScheme = strtolower((string) ($base['scheme'] ?? ''));
        $scheme = strtolower((string) ($target['scheme'] ?? ''));

        if (! in_array($scheme, ['http', 'https'], true) || $scheme !== $baseScheme) {
            return false;
        }

        $baseHost = strtolower(rtrim((string) $base['host'], '.'));
        $targetHost = strtolower(rtrim((string) ($target['host'] ?? ''), '.'));

        if ($targetHost === '' || $targetHost !== $baseHost) {
            return false;
        }

        $basePort = (int) ($base['port'] ?? ($baseScheme === 'https' ? 443 : 80));
        $targetPort = (int) ($target['port'] ?? ($scheme === 'https' ? 443 : 80));

        return $basePort === $targetPort;
    }
}
