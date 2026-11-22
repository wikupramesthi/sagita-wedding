<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function test_keep_alive_refreshes_session_without_logging_out(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        session(['last_activity' => time() - 3600]);
        $stale = session('last_activity');

        $this->post(route('keep-alive'))
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertAuthenticatedAs($user);
        $this->assertGreaterThan($stale, session('last_activity'));
    }

    public function test_read_all_marks_all_unread_notifications(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        foreach ([$user, $user, $other] as $notifiable) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'TestNotification',
                'notifiable_type' => User::class,
                'notifiable_id' => $notifiable->uuid,
                'data' => '{}',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->actingAs($user)
            ->post(route('notifications.readAll'))
            ->assertRedirect();

        $this->assertSame(0, DB::table('notifications')->where('notifiable_id', $user->uuid)->whereNull('read_at')->count());
        $this->assertSame(1, DB::table('notifications')->where('notifiable_id', $other->uuid)->whereNull('read_at')->count());
    }
}
