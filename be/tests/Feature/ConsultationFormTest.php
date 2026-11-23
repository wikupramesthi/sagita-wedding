<?php

namespace Tests\Feature;

use App\Mail\ConsultationReceivedMail;
use App\Models\Consultation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ConsultationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_consultation_form_renders_its_fields_and_captcha(): void
    {
        $this->get(route('consultation.form'))
            ->assertOk()
            ->assertViewIs('pages.public.consultation-form')
            ->assertSee(route('dashboard.index'), false)
            ->assertSee('← Kembali')
            ->assertDontSee('Kembali ke website')
            ->assertSee('name="full_name"', false)
            ->assertSee('name="wedding_date"', false)
            ->assertSee('name="guest_estimate"', false)
            ->assertSee('name="captcha_id"', false)
            ->assertSee('data:image/svg+xml;base64,', false);
    }

    public function test_public_consultation_form_saves_a_new_lead_without_frontend_api_key(): void
    {
        Mail::fake();
        $captchaId = (string) Str::uuid();
        Cache::put('consultation_form_captcha_'.$captchaId, '0042', now()->addMinutes(10));

        $this->post(route('consultation.form.submit'), [
            'full_name' => 'Test User',
            'email' => 'test@example.test',
            'whatsapp' => '081234567890',
            'wedding_date' => '2027-05-20',
            'wedding_city' => 'Jakarta',
            'venue' => 'Gedung Contoh',
            'guest_estimate' => Consultation::GUEST_ESTIMATES[1],
            'budget' => 'Rp100–250 juta',
            'services' => ['Koordinasi di hari pernikahan'],
            'message' => 'Ingin konsultasi paket pernikahan.',
            'captcha_id' => $captchaId,
            'captcha_answer' => '0042',
        ])
            ->assertRedirect(route('consultation.form'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('consultations', [
            'full_name' => 'Test User',
            'email' => 'test@example.test',
            'whatsapp' => '081234567890',
            'wedding_date' => '2027-05-20',
            'guest_estimate' => Consultation::GUEST_ESTIMATES[1],
            'venue' => 'Gedung Contoh',
            'consultation_status' => 'Pending',
            'lead_status' => 'New',
            'source' => 'website',
        ]);

        $consultation = Consultation::query()->where('email', 'test@example.test')->firstOrFail();
        $this->assertStringContainsString('Jakarta', $consultation->wedding_concept);
        $this->assertStringContainsString('Rp100–250 juta', $consultation->wedding_concept);
        $this->assertStringContainsString('Ingin konsultasi paket pernikahan.', $consultation->wedding_concept);
        $this->assertNull(Cache::get('consultation_form_captcha_'.$captchaId));
        Mail::assertSent(ConsultationReceivedMail::class, function (ConsultationReceivedMail $mail): bool {
            $mail->assertSeeInHtml('Formulir konsultasi pernikahan Anda sudah kami terima.');

            return $mail->hasTo('test@example.test') && $mail->customerName === 'Test User';
        });
    }

    public function test_public_consultation_form_rejects_an_invalid_or_replayed_captcha(): void
    {
        $captchaId = (string) Str::uuid();
        Cache::put('consultation_form_captcha_'.$captchaId, '0042', now()->addMinutes(10));
        $payload = [
            'full_name' => 'Test User',
            'email' => 'test@example.test',
            'whatsapp' => '081234567890',
            'message' => 'Consultation request',
            'captcha_id' => $captchaId,
            'captcha_answer' => '0043',
        ];

        $this->from(route('consultation.form'))
            ->post(route('consultation.form.submit'), $payload)
            ->assertRedirect(route('consultation.form'))
            ->assertSessionHasErrors('captcha_answer');

        $payload['captcha_answer'] = '0042';

        $this->from(route('consultation.form'))
            ->post(route('consultation.form.submit'), $payload)
            ->assertRedirect(route('consultation.form'))
            ->assertSessionHasErrors('captcha_answer');

        $this->assertDatabaseCount('consultations', 0);
    }
}
