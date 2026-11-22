<?php

namespace App\Contracts;

interface WhatsAppGateway
{
    public function send(string $to, string $message, array $options = []): bool;

    public function link(string $to, string $message): ?string;
}
