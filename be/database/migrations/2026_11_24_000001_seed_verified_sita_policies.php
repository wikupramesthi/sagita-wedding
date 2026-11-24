<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const RETIRED_FAQS = [
        '9f25d34e-2a60-42cf-8379-ed069dc50ac0',
        '95b200f1-69c7-4f1b-a76b-172bcf5fcc55',
    ];

    private const POLICY_FAQS = [
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000001',
            'locale' => 'id',
            'pertanyaan' => 'Bagaimana proses pemesanan pernikahan?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Proses pemesanan dimulai dengan konsultasi untuk membahas konsep, tanggal, lokasi, dan kebutuhan pernikahan. Setelah paket dan biaya disepakati, tim Sagita akan memeriksa ketersediaan tanggal serta vendor.',
            'urutan' => 8,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000002',
            'locale' => 'en',
            'pertanyaan' => 'How does the wedding booking process work?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Booking starts with a consultation about your concept, date, location, and wedding needs. Once the package and fees are agreed, the Sagita team checks date and vendor availability.',
            'urutan' => 7,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000003',
            'locale' => 'id',
            'pertanyaan' => 'Bagaimana tahapan pembayaran Sagita Wedding Planner?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Pembayaran dilakukan dalam tiga tahap: DP 30% saat penandatanganan kontrak, pembayaran kedua 40% paling lambat H-90, dan pelunasan 30% paling lambat H-30 sebelum acara. Pembayaran dilakukan melalui rekening resmi Sagita Wedding Planner. Kirim bukti pembayaran agar dapat diverifikasi tim. Untuk detail rekening resmi, silakan hubungi admin.',
            'urutan' => 9,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000004',
            'locale' => 'en',
            'pertanyaan' => 'What is the Sagita Wedding Planner payment schedule?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Payments are made in three stages: a 30% deposit when the contract is signed, a second payment of 40% no later than 90 days before the event, and the final 30% no later than 30 days before the event. Payment must be made to Sagita Wedding Planner’s official account. Please send the payment receipt for verification. Contact the team for official account details.',
            'urutan' => 10,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000005',
            'locale' => 'id',
            'pertanyaan' => 'Kapan jam operasional Sagita?',
            'kategori' => 'Perencanaan Pernikahan',
            'jawaban' => 'Sagita melayani konsultasi setiap hari. Jam operasional spesifik belum dicantumkan, jadi silakan hubungi admin untuk memastikan waktu respons.',
            'urutan' => 7,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000006',
            'locale' => 'en',
            'pertanyaan' => 'What are Sagita’s operating hours?',
            'kategori' => 'Perencanaan Pernikahan',
            'jawaban' => 'Sagita is available for consultations every day. Specific operating hours have not been provided, so please contact the team to confirm response times.',
            'urutan' => 8,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000007',
            'locale' => 'id',
            'pertanyaan' => 'Bagaimana kebijakan perubahan tanggal dan pembatalan?',
            'kategori' => 'Perubahan & Pembatalan',
            'jawaban' => 'Perubahan tanggal dapat diajukan satu kali tanpa biaya perubahan jadwal, dengan pemberitahuan paling lambat H-60 sebelum tanggal acara semula, dan tetap bergantung pada ketersediaan tim serta vendor. Jika ada biaya tambahan dari vendor atau kebutuhan operasional, biayanya akan diinformasikan terlebih dahulu. Untuk pembatalan, DP yang telah dibayarkan tidak dapat dikembalikan; penyelesaian pembayaran lainnya mengikuti kontrak kerja sama.',
            'urutan' => 7,
        ],
        [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000008',
            'locale' => 'en',
            'pertanyaan' => 'What is the rescheduling and cancellation policy?',
            'kategori' => 'Perubahan & Pembatalan',
            'jawaban' => 'One date change may be requested without a schedule-change fee, with notice no later than 60 days before the original event date, and remains subject to team and vendor availability. Any additional vendor or operational costs will be disclosed in advance. If you cancel, the deposit is non-refundable; other payments are settled according to the contract.',
            'urutan' => 8,
        ],
    ];

    public function up(): void
    {
        DB::table('faqs')
            ->whereIn('uuid', self::RETIRED_FAQS)
            ->update(['status' => 'inactive', 'updated_at' => now()]);

        foreach (self::POLICY_FAQS as $faq) {
            DB::table('faqs')->updateOrInsert(
                ['uuid' => $faq['uuid']],
                [
                    ...$faq,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('faqs')->whereIn('uuid', array_column(self::POLICY_FAQS, 'uuid'))->delete();
        DB::table('faqs')
            ->whereIn('uuid', self::RETIRED_FAQS)
            ->update(['status' => 'active', 'updated_at' => now()]);
    }
};
