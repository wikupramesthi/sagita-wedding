<?php

namespace App\Services;

use App\Jobs\SendTelegramMessage;
use App\Models\Consultation;
use App\Models\Package\CustomPackageOfferRequest;
use App\Models\Payment\Order;
use App\Models\Payment\OrderChangeRequest;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use LogicException;

class TelegramAdminNotifier
{
    public function newUser(User $user): void
    {
        $this->send($this->message(
            'Pelanggan Baru Terdaftar',
            'Ada pelanggan baru yang baru saja membuat akun di Sagita Wedding.',
            [
                'Nama' => $user->name,
                'Email' => $user->email,
            ],
            route('user.index'),
            'Lihat data pelanggan'
        ));
    }

    public function unregisteredLoginAttempt(string $email, string $ip, int $attempts): void
    {
        $this->send(
            "🔐 <b>Percobaan Masuk Tidak Dikenal</b>\n\n".
            "Sistem mendeteksi beberapa percobaan masuk menggunakan email yang belum terdaftar. Jika aktivitas ini tidak dikenali, periksa keamanan akun admin.\n\n".
            '<b>Email:</b> '.$this->escape($this->maskEmail($email))."\n".
            '<b>Alamat IP:</b> <code>'.$this->escape($ip)."</code>\n".
            '<b>Jumlah percobaan:</b> '.$attempts.' kali dalam 15 menit'
        );
    }

    public function newConsultation(Consultation $consultation): void
    {
        $this->send($this->message(
            'Permintaan Konsultasi Baru',
            'Calon pelanggan mengirim formulir konsultasi. Silakan hubungi dan tindak lanjuti kebutuhannya.',
            [
                'Nama' => $consultation->full_name,
                'Email' => $consultation->email,
                'WhatsApp' => $consultation->whatsapp,
                'Tanggal pernikahan' => $consultation->wedding_date?->format('d M Y'),
                'Lokasi' => $consultation->venue,
            ],
            route('konsultasi.show', $consultation->uuid),
            'Tinjau permintaan konsultasi'
        ));
    }

    public function newOrder(Order $order): void
    {
        $order->loadMissing(['user', 'package', 'packageOption', 'customPackageOffer', 'customPackageOfferOption']);
        $packageName = $order->customPackageOffer?->title ?? $order->package?->name ?? '-';
        $optionName = $order->packageOption?->name ?? $order->customPackageOfferOption?->label;

        if ($optionName) {
            $packageName .= ' — '.$optionName;
        }

        $this->send($this->message(
            'Pesanan Baru Diterima',
            'Pesanan baru masuk dan menunggu tindak lanjut tim.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Paket' => $packageName,
                'Tanggal acara' => $order->event_date?->format('d M Y'),
                'Pembayaran awal' => $this->rupiah((float) $order->amount),
            ],
            route('orders.show', $order->uuid),
            'Tinjau pesanan'
        ));
    }

    public function paymentProofUploaded(Order $order, string $customerName, float $amount): void
    {
        $this->send($this->message(
            'Bukti Pembayaran Menunggu Verifikasi',
            'Pelanggan telah mengirim bukti pembayaran. Mohon periksa bukti dan verifikasi pembayarannya.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $customerName,
                'Jumlah' => $this->rupiah($amount),
            ],
            route('orders.show', $order->uuid),
            'Periksa bukti pembayaran'
        ));
    }

    public function paymentStatusChanged(Order $order, float $amount, bool $approved, ?string $reason = null): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            $approved ? 'Pembayaran Berhasil Diverifikasi' : 'Pembayaran Belum Dapat Diverifikasi',
            $approved
                ? 'Pembayaran sudah diverifikasi. Pesanan dapat dilanjutkan sesuai statusnya.'
                : 'Pembayaran ditolak. Pastikan catatan penolakan jelas agar pelanggan tahu langkah berikutnya.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Jumlah' => $this->rupiah($amount),
                'Catatan' => $approved ? null : $reason,
            ],
            route('orders.show', $order->uuid),
            'Lihat detail pesanan'
        ));
    }

    public function newCustomPackageRequest(CustomPackageOfferRequest $request): void
    {
        $request->loadMissing('user');

        $this->send($this->message(
            'Permintaan Paket Custom Baru',
            'Pelanggan meminta penawaran yang disesuaikan dengan kebutuhan acaranya.',
            [
                'Pelanggan' => $request->user?->name,
                'Tanggal acara' => $request->event_date?->format('d M Y'),
                'Jumlah tamu' => $request->guest_count,
                'Kisaran anggaran' => $request->budget_min !== null || $request->budget_max !== null
                    ? ($request->budget_min !== null ? $this->rupiah((float) $request->budget_min) : 'Belum ditentukan')
                        .' – '.($request->budget_max !== null ? $this->rupiah((float) $request->budget_max) : 'Belum ditentukan')
                    : null,
            ],
            route('package-offer-requests.show', $request->uuid),
            'Tinjau kebutuhan pelanggan'
        ));
    }

    public function cancellationRequested(Order $order): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            'Permintaan Pembatalan Pesanan',
            'Pelanggan mengajukan pembatalan. Mohon tinjau pesanan dan pengajuan refund jika ada.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
            ],
            route('orders.cancellations.index'),
            'Tinjau permintaan pembatalan'
        ));
    }

    public function changeRequested(OrderChangeRequest $change): void
    {
        $change->loadMissing(['order.user', 'addon']);
        $order = $change->order;

        $this->send($this->message(
            'Permintaan Perubahan Pesanan',
            'Pelanggan meminta perubahan pada pesanan. Mohon periksa dampaknya sebelum menyetujui.',
            [
                'Nomor pesanan' => $order?->order_number,
                'Pelanggan' => $order?->user?->name,
                'Perubahan' => $change->change_type === 'event_date'
                    ? 'Tanggal acara menjadi '.$change->requested_event_date?->format('d M Y')
                    : 'Add-on: '.($change->addon?->name ?? 'layanan tambahan'),
            ],
            route('orders.change-requests.index'),
            'Tinjau perubahan pesanan'
        ));
    }

    public function orderNeedsAction(Order $order, int $ageHours): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            'Pesanan Perlu Ditindaklanjuti',
            'Pesanan ini masih menunggu verifikasi. Mohon tindak lanjuti agar pelanggan segera mendapat kepastian.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Status' => 'Menunggu verifikasi selama '.$ageHours.' jam',
            ],
            route('orders.show', $order->uuid),
            'Tindak lanjuti pesanan'
        ));
    }

    public function orderExpiringSoon(Order $order): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            'Batas Pembayaran Hampir Tiba',
            'Batas pembayaran pesanan ini akan segera berakhir. Periksa statusnya agar tidak kedaluwarsa.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Batas pembayaran' => $order->expired_at?->format('d M Y, H.i'),
            ],
            route('orders.show', $order->uuid),
            'Periksa status pembayaran'
        ));
    }

    public function balanceDue(Order $order, float $remaining, int $daysUntilDue): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            'Pengingat Sisa Pembayaran',
            'Tenggat pelunasan sudah dekat. Silakan periksa status pembayaran dan tindak lanjuti bila diperlukan.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Sisa tagihan' => $this->rupiah($remaining),
                'Tenggat' => $daysUntilDue <= 0 ? 'Hari ini' : 'Besok',
            ],
            route('orders.show', $order->uuid),
            'Lihat tagihan pesanan'
        ));
    }

    public function checklistOverdue(Order $order, string $taskTitle, CarbonInterface $dueAt): void
    {
        $order->loadMissing('user');

        $this->send($this->message(
            'Checklist Persiapan Acara Terlewat',
            'Ada persiapan acara yang melewati batas waktu. Mohon tinjau dan koordinasikan tindak lanjutnya.',
            [
                'Nomor pesanan' => $order->order_number,
                'Pelanggan' => $order->user?->name,
                'Tugas' => $taskTitle,
                'Batas waktu' => $dueAt->format('d M Y'),
            ],
            route('orders.show', $order->uuid),
            'Buka checklist persiapan'
        ));
    }

    private function send(string $message): void
    {
        if (! config('services.telegram.enabled')) {
            return;
        }

        if (blank(config('services.telegram.bot_token')) || blank(config('services.telegram.chat_id'))) {
            throw new LogicException('Telegram is enabled but TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID is not configured.');
        }

        SendTelegramMessage::dispatch($message);
    }

    private function message(
        string $title,
        string $summary,
        array $fields,
        string $url,
        string $linkLabel
    ): string {
        $lines = [
            '💍 <b>'.$this->escape($title).'</b>',
            '',
            $this->escape($summary),
            '',
            '<b>Detail</b>',
        ];

        foreach ($fields as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $lines[] = '<b>'.$this->escape((string) $label).':</b> '.$this->escape((string) $value);
        }

        $lines[] = '';
        $lines[] = '👉 <a href="'.$this->escape($url).'">'.$this->escape($linkLabel).'</a>';

        return implode("\n", $lines);
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        if ($local === '' || $domain === '') {
            return mb_substr($email, 0, 1).'***';
        }

        $localMasked = mb_substr($local, 0, 1).'***';
        $domainParts = explode('.', $domain);
        $domainParts[0] = mb_substr($domainParts[0], 0, 1).'***';

        return $localMasked.'@'.implode('.', $domainParts);
    }

    private function escape(string $value): string
    {
        $value = Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($value)) ?? ''), 180);

        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function rupiah(float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }
}
