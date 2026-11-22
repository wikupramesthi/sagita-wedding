<?php

namespace Tests\Feature;

use App\Mail\ConsultationReceivedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactControllerTest extends TestCase
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

    public function test_contact_captcha_returns_a_four_digit_local_challenge(): void
    {
        $response = $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/contact/captcha?lang=en');

        $response->assertOk()
            ->assertJsonPath('data.question', 'Enter the 4-digit code shown.');

        $captchaId = $response->json('data.captcha_id');
        $image = $response->json('data.image');
        $captcha = cache()->get('contact_captcha_'.$captchaId);
        $this->assertTrue(Str::isUuid($captchaId));
        $this->assertMatchesRegularExpression('/^\d{4}$/', $captcha['answer']);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $image);
        $this->assertArrayNotHasKey('code', $response->json('data'));
    }

    public function test_contact_captcha_is_localized_and_keeps_answer_server_side(): void
    {
        $response = $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/contact/captcha?lang=id')
            ->assertOk()
            ->assertJsonPath('data.question', 'Masukkan 4 angka berikut.');

        $captchaId = $response->json('data.captcha_id');
        $this->assertIsArray(cache()->get('contact_captcha_'.$captchaId));
        $this->assertArrayHasKey('answer', cache()->get('contact_captcha_'.$captchaId));
        $this->assertArrayNotHasKey('answer', $response->json('data'));
    }

    public function test_correct_local_captcha_is_one_time_and_cannot_be_replayed(): void
    {
        $captchaId = (string) Str::uuid();
        cache()->put('contact_captcha_'.$captchaId, ['answer' => '0042'], now()->addMinutes(10));
        $payload = [
            'nama' => 'Test User',
            'email' => 'test@example.test',
            'no_telp' => '081234567890',
            'isi' => 'Consultation request',
            'captcha_id' => $captchaId,
            'captcha_answer' => '0042',
        ];

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/contact', $payload)
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/contact', $payload)
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        $anotherCaptchaId = (string) Str::uuid();
        cache()->put('contact_captcha_'.$anotherCaptchaId, true, now()->addMinutes(10));

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/contact', [
                ...$payload,
                'captcha_id' => $anotherCaptchaId,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('consultations', 1);
    }

    public function test_wrong_local_captcha_is_rejected_and_consumed(): void
    {
        $captchaId = (string) Str::uuid();
        cache()->put('contact_captcha_'.$captchaId, ['answer' => '0042'], now()->addMinutes(10));

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/contact', [
                'nama' => 'Test User',
                'email' => 'test@example.test',
                'no_telp' => '081234567890',
                'isi' => 'Consultation request',
                'captcha_id' => $captchaId,
                'captcha_answer' => '0043',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('consultations', 0);
    }

    public function test_honeypot_field_rejects_bot_submission(): void
    {
        $captchaId = (string) Str::uuid();
        cache()->put('contact_captcha_'.$captchaId, ['answer' => '0042'], now()->addMinutes(10));

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->postJson('/api/contact', [
                'nama' => 'Test User',
                'email' => 'bot@example.test',
                'no_telp' => '081234567890',
                'isi' => 'Consultation request',
                'captcha_id' => $captchaId,
                'captcha_answer' => '0042',
                'website' => 'https://spam.test',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['website']]);

        $this->assertDatabaseCount('consultations', 0);
        $this->assertIsArray(cache()->get('contact_captcha_'.$captchaId));
    }

    public function test_duplicate_contact_is_not_stored_twice(): void
    {
        Mail::fake();
        $payload = [
            'nama' => 'Test User',
            'email' => 'duplicate@example.test',
            'no_telp' => '081234567890',
            'isi' => 'Consultation request',
            'captcha_answer' => '0042',
        ];

        foreach ([false, true] as $isDuplicate) {
            $captchaId = (string) Str::uuid();
            cache()->put('contact_captcha_'.$captchaId, ['answer' => '0042'], now()->addMinutes(10));

            $this->withHeader('X-Api-Key', self::API_KEY)
                ->postJson('/api/contact', [
                    ...$payload,
                    'captcha_id' => $captchaId,
                ])
                ->assertCreated()
                ->assertJsonPath('success', true)
                ->assertJsonPath('duplicate', $isDuplicate);
        }

        $this->assertDatabaseCount('consultations', 1);
        Mail::assertSentCount(1);
        Mail::assertSent(
            ConsultationReceivedMail::class,
            fn (ConsultationReceivedMail $mail) => $mail->hasTo('duplicate@example.test')
        );
    }
}
