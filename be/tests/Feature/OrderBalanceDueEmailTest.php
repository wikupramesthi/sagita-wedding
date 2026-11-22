<?php

namespace Tests\Feature;

use App\Notifications\OrderBalanceDueNotification;
use Tests\TestCase;

class OrderBalanceDueEmailTest extends TestCase
{
    public function test_installment_reminder_email_renders_order_amount_and_schedule(): void
    {
        $notification = new OrderBalanceDueNotification(
            'order-uuid',
            'ORD-TEST-123',
            3599000,
            '2026-12-01',
            1200000,
            'Termin 2',
            7,
        );

        $message = $notification->toMail((object) ['name' => 'Ayu']);
        $html = view($message->view, $message->viewData)->render();

        $this->assertStringContainsString('Ayu', $html);
        $this->assertStringContainsString('ORD-TEST-123', $html);
        $this->assertStringContainsString('Rp 3.599.000', $html);
        $this->assertStringContainsString('Rp 1.200.000', $html);
        $this->assertStringContainsString('Termin 2', $html);
        $this->assertStringContainsString('H-7', $html);
        $this->assertStringContainsString('Lihat detail pesanan', $html);
    }
}
