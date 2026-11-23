<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WeddingPackageCleanupSeeder extends Seeder
{
    private const UPDATED_AT = '2026-11-23 12:00:00';

    public function run(): void
    {
        $packages = [
            '01a095f2-d286-7241-b566-8027529b8196' => [
                'slug' => 'paket-rencana-pernikahan',
                'description' => 'Pendampingan awal wedding organizer untuk pasangan yang mulai merencanakan pernikahan. Kami bantu menyusun target acara, anggaran awal, dan checklist persiapan yang realistis.',
                'guest_capacity' => 50,
                'event_duration_hours' => 6,
                'venue_note' => 'Jakarta dan sekitarnya',
            ],
            '01a09615-73d8-7345-a20f-2d5eeb6b1d1a' => [
                'slug' => 'paket-pernikahan-lengkap',
                'description' => 'Layanan wedding organizer menyeluruh dari perencanaan konsep, koordinasi vendor, pengelolaan anggaran, hingga pendampingan teknis pada hari acara.',
                'guest_capacity' => 200,
                'event_duration_hours' => 10,
                'venue_note' => 'Venue sesuai kesepakatan, Jabodetabek',
            ],
            '01a098c6-e509-7154-869f-8d336d44ebc4' => [
                'slug' => 'paket-pernikahan-berdua',
                'description' => 'Paket wedding organizer untuk pasangan yang ingin tetap terlibat aktif. Kami mendampingi penyusunan jadwal, koordinasi layanan, dan evaluasi detail acara.',
                'guest_capacity' => 100,
                'event_duration_hours' => 8,
                'venue_note' => 'Jakarta dan sekitarnya',
            ],
            '01a098d2-a02c-72ee-a8eb-b9dc6786761d' => [
                'slug' => 'konsultasi-pernikahan-personal',
                'description' => 'Pertemuan konsultasi pernikahan personal bersama perencana acara. Fokus pada kebutuhan Anda, penyusunan rencana, serta rekomendasi vendor yang sesuai anggaran.',
                'guest_capacity' => 2,
                'event_duration_hours' => 2,
                'venue_note' => 'Konsultasi di kantor atau daring',
            ],
            '01a098e0-878e-7296-b1c0-a550bf0327b0' => [
                'slug' => 'konsultasi-pernikahan-pasangan',
                'description' => 'Konsultasi pernikahan untuk pasangan dalam satu ruang diskusi. Kami bantu menyelaraskan konsep, prioritas anggaran, dan daftar persiapan menjelang acara.',
                'guest_capacity' => 2,
                'event_duration_hours' => 2,
                'venue_note' => 'Konsultasi di kantor atau daring',
            ],
        ];

        $sharedTerms = [];

        if (Schema::hasColumn('packages', 'dp_percentage')) {
            $sharedTerms['dp_percentage'] = 30;
        }

        if (Schema::hasColumn('packages', 'payment_terms_note')) {
            $sharedTerms['payment_terms_note'] = 'DP 30% saat kontrak, pelunasan sesuai termin yang disepakati.';
        }

        foreach ($packages as $uuid => $attributes) {
            DB::table('packages')
                ->where('uuid', $uuid)
                ->update(array_merge(
                    $attributes,
                    $sharedTerms,
                    ['updated_at' => self::UPDATED_AT]
                ));
        }

        $features = [
            '01a09615-73fb-702f-8c0a-b5012141236e' => ['Koordinasi Vendor', 'Koordinasi menyeluruh seluruh vendor acara'],
            '01a09615-7401-7073-add9-f090d187e2f8' => ['Perencanaan', 'Perencanaan detail konsep dan dekorasi'],
            '01a09615-7402-71bd-a8b8-9e5f3acd8ad7' => ['Anggaran', 'Penyusunan anggaran dan jadwal pembayaran vendor'],
            '01a09615-7402-71bd-a8b8-9e5f3ae8a3fb' => ['Hari Acara', 'Pendampingan teknis venue dan rundown acara'],
            '01a09615-7409-70e7-9271-8596c85a0a7d' => ['Checklist', 'Checklist persiapan pernikahan lengkap'],
            '01a098de-ec82-701f-bb24-c2dbbe0c59b9' => ['Konsultasi', 'Konsultasi pernikahan personal bersama perencana'],
            '01a098de-ec83-7166-a29d-85d9cd8131b5' => ['Perencanaan', 'Penyusunan rencana dan target pernikahan'],
            '01a098de-ec83-7166-a29d-85d9cdfbb9df' => ['Pendampingan', 'Pendampingan konsultan pernikahan pribadi'],
            '01a098de-ec84-71fb-82be-6092568d7513' => ['Perencanaan', 'Evaluasi detail konsep dan kebutuhan acara'],
            '01a098de-ec86-70ea-a38d-1c994c52404a' => ['Koordinasi Vendor', 'Rekomendasi vendor sesuai anggaran'],
            '01a098de-ec87-70ea-ad3c-c41d16e6f542' => ['Konsultasi', 'Fokus diskusi sesuai kebutuhan pasangan'],
            '01a098e0-8799-703c-a314-911481118e4e' => ['Konsultasi', 'Konsultasi pernikahan untuk pasangan'],
            '01a098e0-879f-715a-8e83-0ae3ed067c6f' => ['Perencanaan', 'Penyusunan konsep acara bersama pasangan'],
            '01a098e0-87a0-705c-94e4-64e7348fd306' => ['Pendampingan', 'Pendampingan perencana pernikahan berpasangan'],
            '01a098e0-87a1-7275-b6b4-5e5b4873a277' => ['Anggaran', 'Penyesuaian anggaran dan prioritas acara'],
            '01a098e0-87a2-72dc-8631-635b1abff726' => ['Koordinasi Vendor', 'Diskusi dan pemilihan vendor bersama'],
            '01a098e0-87a3-72d4-b233-8ba8254b8bb6' => ['Checklist', 'Checklist persiapan pernikahan berpasangan'],
            '01a09965-fbf3-7255-bb66-859b3a4e1bff' => ['Perencanaan', 'Pembagian tugas persiapan pernikahan'],
            '01a09965-fbf9-7032-b9db-53e383f6b6c9' => ['Perencanaan', 'Penjadwalan persiapan yang fleksibel'],
            '01a09965-fbfa-72c8-9335-c298673b2bec' => ['Koordinasi Vendor', 'Koordinasi layanan yang sudah termasuk'],
            '01a09965-fbfa-72c8-9335-c298680c415e' => ['Hari Acara', 'Dukungan teknis pada hari acara'],
            '01a09965-fbfb-72aa-8e43-d33cdbde0650' => ['Konsultasi', 'Akses konsultasi selama masa penawaran'],
            '01a09966-2967-7153-8bac-e05d58fabab9' => ['Konsultasi', 'Konsultasi awal perencanaan pernikahan'],
            '01a09966-296d-7188-bf8c-90a9e73adf27' => ['Checklist', 'Checklist dasar persiapan pernikahan'],
            '01a09966-296d-7188-bf8c-90a9e8268917' => ['Anggaran', 'Panduan penyusunan anggaran awal'],
            '01a09966-296e-71e6-a0f5-c78ca826aad2' => ['Koordinasi Vendor', 'Referensi vendor sesuai kebutuhan'],
            '01a09966-296e-71e6-a0f5-c78ca89e5199' => ['Pendampingan', 'Pendampingan perencana saat diskusi awal'],
        ];

        foreach ($features as $uuid => [$category, $feature]) {
            DB::table('package_features')
                ->where('uuid', $uuid)
                ->update([
                    'category' => $category,
                    'feature' => $feature,
                    'updated_at' => self::UPDATED_AT,
                ]);
        }

        $options = [
            '01a09615-73f9-72ee-85e5-f2be4ef4c502' => 'Layanan Lengkap 1 Bulan',
            '01a098de-ec50-73bf-b928-c37f31d37568' => 'Pendampingan Personal 1 Bulan',
            '01a098de-ec7d-7352-9076-67a2defe3fde' => 'Pendampingan Personal 5 Bulan',
            '01a098de-ec7f-7275-a7f8-be27d7caa4ff' => 'Pendampingan Personal 10 Bulan',
            '01a098e0-8795-71b4-84a4-de9c8a0b517e' => 'Pendampingan Pasangan 1 Bulan',
            '01a098e0-8797-7309-954d-6caac6a8f4ee' => 'Pendampingan Pasangan 5 Bulan',
            '01a098e0-8798-707b-b260-c1379231361e' => 'Pendampingan Pasangan 10 Bulan',
            '01a09965-fbf0-7307-ba83-dfa1382cf270' => 'Paket Berdua 25 Layanan',
            '01a09966-2965-7201-ab57-9f085e23bbc7' => 'Paket Rencana Pernikahan Dasar',
        ];

        foreach ($options as $uuid => $name) {
            DB::table('package_options')
                ->where('uuid', $uuid)
                ->update([
                    'name' => $name,
                    'duration_unit' => 'month',
                    'updated_at' => self::UPDATED_AT,
                ]);
        }
    }
}
