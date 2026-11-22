<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_is_disabled_without_blocking_unverified_accounts(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/verify-email')
            ->assertNotFound();

        $this->assertFalse(Route::has('verification.notice'));
        $this->assertFalse(Route::has('verification.verify'));
        $this->assertFalse(Route::has('verification.send'));
        $this->assertNotContains(MustVerifyEmail::class, class_implements(User::class));

        $dashboard = Route::getRoutes()->getByName('dashboard.index');
        $this->assertNotContains('verified', $dashboard->gatherMiddleware());
    }
}
