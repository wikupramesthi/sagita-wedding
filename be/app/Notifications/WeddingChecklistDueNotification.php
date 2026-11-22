<?php

namespace App\Notifications;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WeddingChecklistDueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public string $itemTitle,
        public DateTimeInterface|string $dueAt,
        public bool $escalation = false,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $due = Carbon::parse($this->dueAt);
        $dueLabel = $due->translatedFormat('d F Y').' ('.$due->toDateString().')';

        return [
            'judul_kegiatan' => $this->escalation ? 'Eskalasi checklist persiapan' : 'Pengingat checklist persiapan',
            'message' => $this->escalation
                ? 'Checklist "'.$this->itemTitle.'" untuk pesanan '.$this->orderNumber
                    .' sudah terlewat jatuh tempo '.$dueLabel.'. Mohon admin segera menindaklanjuti.'
                : 'Checklist "'.$this->itemTitle.'" untuk pesanan '.$this->orderNumber
                    .' jatuh tempo '.$dueLabel.'. Mohon segera diselesaikan.',
            'path' => route('orders.show', $this->orderUuid, false),
            'icon' => $this->escalation ? 'bi-exclamation-triangle' : 'bi-check2-square',
        ];
    }
}
