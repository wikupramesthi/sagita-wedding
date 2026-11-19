<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontendSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_endpoint_omits_missing_avatar_and_keeps_valid_avatar_urls(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['avatar' => 'avatars/missing.jpg']);

        $this->actingAs($user)
            ->getJson('/auth/me')
            ->assertOk()
            ->assertJsonPath('user.avatar', null);

        Storage::disk('public')->put('avatars/available.jpg', 'image');
        $user->avatar = 'avatars/available.jpg';
        $user->save();

        $this->getJson('/auth/me')
            ->assertOk()
            ->assertJsonPath('user.avatar', asset('storage/avatars/available.jpg'));

        $user->avatar = 'https://example.test/avatars/profile.jpg';
        $user->save();

        $this->getJson('/auth/me')
            ->assertOk()
            ->assertJsonPath('user.avatar', 'https://example.test/avatars/profile.jpg');
    }
}
