<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterControllerTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.frontend_api.key' => self::API_KEY,
        ]);
    }

    public function test_newsletter_signup_stores_a_normalized_subscriber(): void
    {
        $response = $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/newsletter', ['email' => 'Budi@Example.com']);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('duplicate', false);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'budi@example.com',
            'source' => 'website',
        ]);
    }

    public function test_duplicate_signup_is_not_stored_twice(): void
    {
        $payload = ['email' => 'sinta@example.com'];

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/newsletter', $payload)
            ->assertCreated()
            ->assertJsonPath('duplicate', false);

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/newsletter', $payload)
            ->assertCreated()
            ->assertJsonPath('duplicate', true);

        $this->assertSame(1, NewsletterSubscriber::count());
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/newsletter', ['email' => 'bukan-email'])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors('email');

        $this->assertSame(0, NewsletterSubscriber::count());
    }

    public function test_honeypot_is_rejected(): void
    {
        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/newsletter', [
                'email' => 'bot@example.com',
                'website' => 'https://spam.test',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('website');

        $this->assertSame(0, NewsletterSubscriber::count());
    }
}
