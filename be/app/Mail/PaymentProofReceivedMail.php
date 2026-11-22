<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentProofReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $customerName,
        public string $orderUuid,
        public string $orderNumber,
        public float $amount,
    ) {}

    public function build()
    {
        return $this
            ->subject('Bukti pembayaran diterima | '.$this->orderNumber)
            ->view('emails.notifications.payment-proof-received', [
                'customerName' => $this->customerName,
                'orderNumber' => $this->orderNumber,
                'amount' => $this->amount,
                'orderUrl' => route('orders.show', $this->orderUuid),
            ]);
    }
}
