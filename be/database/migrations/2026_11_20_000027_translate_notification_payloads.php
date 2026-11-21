<?php

use App\Notifications\BookingMissedNotification;
use App\Notifications\LoginAnehNotification;
use App\Notifications\MemberActiveNotification;
use App\Notifications\MemberCheckedInNotification;
use App\Notifications\OrderBaruNotification;
use App\Notifications\OrderExpiredNotification;
use App\Notifications\OrderRejectedNotification;
use App\Notifications\ProofUploadedNotification;
use App\Notifications\WaitingPromotedNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        $titles = [
            BookingMissedNotification::class => 'Kelas terlewat',
            LoginAnehNotification::class => 'Login tidak biasa terdeteksi',
            MemberActiveNotification::class => 'Membership diaktifkan',
            MemberCheckedInNotification::class => 'Check-in berhasil',
            OrderBaruNotification::class => 'Pesanan baru diterima',
            OrderExpiredNotification::class => 'Pesanan kedaluwarsa',
            OrderRejectedNotification::class => 'Pesanan ditolak',
            ProofUploadedNotification::class => 'Bukti transfer diunggah',
            WaitingPromotedNotification::class => 'Kursi tersedia — pesanan dikonfirmasi',
            'You missed a class' => 'Kelas terlewat',
            'Class reminder' => 'Pengingat kelas',
            'Unusual login detected' => 'Login tidak biasa terdeteksi',
            'Membership activated' => 'Membership diaktifkan',
            'Checked in successfully' => 'Check-in berhasil',
            'New order received' => 'Pesanan baru diterima',
            'Order expired' => 'Pesanan kedaluwarsa',
            'Order rejected' => 'Pesanan ditolak',
            'Transfer proof uploaded' => 'Bukti transfer diunggah',
            'Seat available — you are booked in' => 'Kursi tersedia — pesanan dikonfirmasi',
        ];

        $search = [
            'was marked as no-show because there was no check-in. Book again anytime!',
            'starts in about 2 hours',
            'is tomorrow',
            '. See you on the mat!',
            'signed in from',
            'unusual hour',
            'risky IP',
            'login from multiple IPs',
            'Admin checked you in to ',
            'You checked in to ',
            ' Remaining quota: ',
            ' ordered ',
            ' Active until ',
            'Payment for ',
            ' was verified. Your membership is now active.',
            ' was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.',
            ' was rejected by admin.',
            ' Reason: ',
            ' Please contact admin or place a new order.',
            ' uploaded transfer proof (Rp ',
            '). Please verify.',
            'A seat opened up: you moved from the waiting list to confirmed for ',
        ];

        $replace = [
            'ditandai tidak hadir karena tidak ada check-in. Kamu bisa memesan lagi kapan saja.',
            'akan dimulai sekitar 2 jam lagi',
            'akan berlangsung besok',
            '. Sampai jumpa di kelas!',
            'masuk dari',
            'jam tidak biasa',
            'IP berisiko',
            'login dari beberapa alamat IP',
            'Admin telah melakukan check-in untukmu di ',
            'Kamu telah check-in di ',
            ' Sisa kuota: ',
            ' memesan ',
            ' Aktif hingga ',
            'Pembayaran untuk ',
            ' telah diverifikasi. Membership kamu sekarang aktif.',
            ' otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.',
            ' ditolak oleh admin.',
            ' Alasan: ',
            ' Silakan hubungi admin atau buat pesanan baru.',
            ' mengunggah bukti transfer (Rp ',
            '). Silakan verifikasi.',
            'Ada kursi kosong: statusmu berubah dari daftar tunggu menjadi terkonfirmasi untuk ',
        ];

        DB::table('notifications')
            ->select(['id', 'type', 'data'])
            ->orderBy('created_at')
            ->chunk(100, function ($rows) use ($titles, $search, $replace) {
                foreach ($rows as $row) {
                    $data = json_decode($row->data, true);
                    if (! is_array($data)) {
                        continue;
                    }

                    $oldTitle = $data['judul_kegiatan'] ?? null;
                    $oldMessage = $data['message'] ?? null;
                    $title = $titles[$row->type] ?? $titles[$oldTitle] ?? $oldTitle;
                    $message = is_string($oldMessage) ? str_replace($search, $replace, $oldMessage) : $oldMessage;

                    if ($title === $oldTitle && $message === $oldMessage) {
                        continue;
                    }

                    $data['judul_kegiatan'] = $title;
                    $data['message'] = $message;

                    DB::table('notifications')
                        ->where('id', $row->id)
                        ->update([
                            'data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        ]);
                }
            });
    }

    public function down(): void {}
};
