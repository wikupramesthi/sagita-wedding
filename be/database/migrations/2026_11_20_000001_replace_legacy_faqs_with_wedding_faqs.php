<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const FAQS = [
        [
            'uuid' => '4b9411a3-ccc5-44fd-bc24-e8dc7065773c',
            'pertanyaan' => 'When should we start planning our wedding?',
            'kategori' => 'Perencanaan Pernikahan',
            'jawaban' => 'Ideally, start 6–12 months before the big day. Setting the date, venue, and budget early gives you access to a wider range of options.',
            'urutan' => 1,
        ],
        [
            'uuid' => 'fa2b17f9-f0d4-4849-8373-8d787b80113e',
            'pertanyaan' => 'What is included in a wedding package?',
            'kategori' => 'Paket & Layanan',
            'jawaban' => 'Packages can include decoration, photography, videography, MC, music, and event coordination. Details can be tailored to your needs.',
            'urutan' => 2,
        ],
        [
            'uuid' => '9f25d34e-2a60-42cf-8379-ed069dc50ac0',
            'pertanyaan' => 'How do I book a package and make a payment?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Choose a package and submit the booking form. Our team will send an invoice, and payment can be made by bank transfer based on the agreed schedule.',
            'urutan' => 3,
        ],
        [
            'uuid' => '6bb623f8-916a-436b-85b3-a57035fe5ac5',
            'pertanyaan' => 'Can we choose our own vendors?',
            'kategori' => 'Vendor & Mitra',
            'jawaban' => 'Absolutely. You can select from our recommended vendors or propose your own. We will help make sure they fit your concept and budget.',
            'urutan' => 4,
        ],
        [
            'uuid' => 'fd90f57c-53aa-4b5d-8e11-faea4808686d',
            'pertanyaan' => 'What happens on the wedding day?',
            'kategori' => 'Pelaksanaan Acara',
            'jawaban' => 'Our team arrives early for setup, vendor coordination, and final checks, so you can enjoy the celebration with your guests.',
            'urutan' => 5,
        ],
        [
            'uuid' => '95b200f1-69c7-4f1b-a76b-172bcf5fcc55',
            'pertanyaan' => 'Can we change the date or package after booking?',
            'kategori' => 'Perubahan & Pembatalan',
            'jawaban' => 'Changes can be requested up to 30 days before the event, subject to availability. Cancellations follow the terms stated in your booking agreement.',
            'urutan' => 6,
        ],
    ];

    public function up(): void
    {
        DB::table('faqs')->whereNotIn('uuid', array_column(self::FAQS, 'uuid'))->delete();

        foreach (self::FAQS as $faq) {
            DB::table('faqs')->updateOrInsert(
                ['uuid' => $faq['uuid']],
                [
                    'pertanyaan' => $faq['pertanyaan'],
                    'kategori' => $faq['kategori'],
                    'jawaban' => $faq['jawaban'],
                    'urutan' => $faq['urutan'],
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('faqs')->whereIn('uuid', array_column(self::FAQS, 'uuid'))->delete();
    }
};
