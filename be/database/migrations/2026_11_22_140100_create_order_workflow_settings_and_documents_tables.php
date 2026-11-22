<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_workflow_settings', function (Blueprint $table): void {
            $table->id();
            $table->longText('contract_template');
            $table->json('checklist_template');
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('updated_by')->references('uuid')->on('users')->nullOnDelete();
        });

        DB::table('order_workflow_settings')->insert([
            'id' => 1,
            'contract_template' => implode("\n", [
                'PERJANJIAN LAYANAN WEDDING ORGANIZER',
                'Nomor dokumen: {{document_number}}',
                'Tanggal diterbitkan: {{issued_at}}',
                '',
                'Penyedia layanan: Sagita Wedding Organizer',
                'Pelanggan: {{customer_name}}',
                'Nomor pesanan: {{order_number}}',
                '',
                'Layanan yang disepakati: {{package_name}} - {{option_name}}',
                'Tanggal acara: {{event_date}}',
                'Nilai pesanan: {{total_amount}}',
                'Pembayaran terverifikasi: {{amount_paid}}',
                'Sisa pembayaran: {{remaining_amount}}',
                '',
                'Rincian layanan mengikuti paket dan layanan tambahan yang tercatat pada pesanan.',
                'Perubahan tanggal atau layanan harus diajukan melalui pesanan dan disetujui admin.',
                'Ketentuan pembayaran dan pembatalan mengikuti kebijakan yang ditampilkan saat pemesanan.',
                '',
                'Dokumen ini mencatat rincian pesanan dan belum menggantikan persetujuan/tanda tangan para pihak.',
            ]),
            'checklist_template' => json_encode([
                ['title' => 'Konfirmasi detail acara', 'description' => 'Pastikan lokasi, waktu, dan kontak utama acara sudah benar.', 'weeks_before_event' => 16],
                ['title' => 'Finalisasi jumlah tamu', 'description' => 'Perbarui perkiraan jumlah tamu dan kebutuhan konsumsi.', 'weeks_before_event' => 12],
                ['title' => 'Konfirmasi susunan acara', 'description' => 'Tinjau susunan acara bersama tim wedding organizer.', 'weeks_before_event' => 8],
                ['title' => 'Finalisasi kebutuhan vendor', 'description' => 'Pastikan kebutuhan dekorasi, dokumentasi, dan vendor tambahan.', 'weeks_before_event' => 4],
                ['title' => 'Konfirmasi akhir acara', 'description' => 'Lakukan konfirmasi akhir detail dan jadwal acara.', 'weeks_before_event' => 1],
            ], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('order_documents', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid');
            $table->uuid('payment_uuid')->nullable();
            $table->string('document_key')->unique();
            $table->string('document_number')->unique();
            $table->string('type', 20);
            $table->json('snapshot');
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders')->cascadeOnDelete();
            $table->foreign('payment_uuid')->references('uuid')->on('payments')->nullOnDelete();
            $table->index(['order_uuid', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_documents');
        Schema::dropIfExists('order_workflow_settings');
    }
};
