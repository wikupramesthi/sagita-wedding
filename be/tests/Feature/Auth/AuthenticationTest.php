<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/auth/login');

        $response->assertStatus(200);
    }

    public function test_login_screen_uses_mobile_view_on_phone(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) Mobile Safari/537.36')
            ->get('/auth/login')
            ->assertOk()
            ->assertViewIs('auth.login-mobile');
    }

    public function test_login_screen_uses_mobile_view_on_tablet(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Safari/604.1')
            ->get('/auth/login')
            ->assertOk()
            ->assertViewIs('auth.login-mobile');
    }

    public function test_login_screen_stays_desktop_on_laptop(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->withHeader('Sec-CH-Viewport-Width', '390')
            ->get('/auth/login')
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard.index', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('auth/login');
    }

    public function test_logout_confirmation_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/logout')
            ->assertOk()
            ->assertViewIs('auth.logout');
    }
}
