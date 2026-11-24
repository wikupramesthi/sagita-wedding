<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConsultationFormTest extends TestCase
{
    public function test_public_frontend_routes_redirect_to_astro(): void
    {
        config(['frontend.url' => 'https://frontend.test']);

        $this->get('/')
            ->assertRedirect('https://frontend.test');

        $this->get(route('consultation.form'))
            ->assertRedirect('https://frontend.test/formulir-konsultasi');

        $this->post(route('consultation.form.submit'))
            ->assertRedirect('https://frontend.test/formulir-konsultasi');
    }
}
