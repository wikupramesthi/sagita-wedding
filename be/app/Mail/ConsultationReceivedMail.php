<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsultationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $customerName,
    ) {}

    public function build()
    {
        return $this
            ->subject('Permintaan konsultasi Anda sudah kami terima | Sagita Wedding')
            ->view('emails.notifications.consultation-received', [
                'customerName' => $this->customerName,
            ]);
    }
}
