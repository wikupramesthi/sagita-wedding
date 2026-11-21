<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * HP (User-Agent mobile) mendapat versi mobile phone-frame agar tampilannya
     * sama dengan halaman member mobile; desktop/admin tetap versi penuh.
     */
    public function create(): View
    {
        $faqs = Faq::where('status', 'active')
            ->orderByKategori()
            ->get();

        // Tahan 500 bila role belum di-seed (mis. instalasi baru / testing).
        $hasRoles = Role::whereIn('name', ['vendor', 'user'])->exists();
        $jumlahVendor = $hasRoles ? User::role('vendor')->count() : 0;
        $jumlahMembers = $hasRoles ? User::role('user')->count() : 0;

        if ($this->isMobile()) {
            return view('auth.login-mobile');
        }

        return view('auth.login', compact('faqs', 'jumlahVendor', 'jumlahMembers'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $welcome = $request->user()->hasRole('user')
            ? 'Selamat datang kembali! Selamat berlatih.'
            : 'Selamat datang di halaman admin!';

        return redirect()
            ->intended(route('dashboard.index', absolute: false))
            ->with('success', $welcome);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user(); // ambil dulu sebelum logout

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && in_array($user->role, ['admin', 'super-admin'])) {
            return redirect('auth/login');
        }

        return redirect('auth/login');
    }

    /**
     * Deteksi HP dengan pola yang sama seperti controller member mobile
     * (Dashboard, Profile, Package, Vendor, Checkout).
     */
    private function isMobile(): bool
    {
        return (bool) preg_match(
            '/Mobile|Android|iPhone|iPad|iPod/i',
            (string) request()->header('User-Agent')
        );
    }
}
