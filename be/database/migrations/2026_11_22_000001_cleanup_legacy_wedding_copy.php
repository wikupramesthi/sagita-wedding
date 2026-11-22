<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $packageUpdates = [
            'First Timer Package' => ['name' => 'Paket Rencana Pernikahan', 'slug' => 'paket-rencana-pernikahan'],
            'Unlimited Package' => ['name' => 'Paket Pernikahan Lengkap', 'slug' => 'paket-pernikahan-lengkap'],
            'Sharing Package' => ['name' => 'Paket Pernikahan Berdua', 'slug' => 'paket-pernikahan-berdua'],
            '1 On 1 Private Class' => ['name' => 'Konsultasi Pernikahan Personal', 'slug' => 'konsultasi-pernikahan-personal'],
            "Couple's Private Class" => ['name' => 'Konsultasi Pernikahan Pasangan', 'slug' => 'konsultasi-pernikahan-pasangan'],
        ];

        foreach ($packageUpdates as $oldName => $newValues) {
            foreach (DB::table('packages')->where('name', $oldName)->pluck('uuid') as $uuid) {
                $slug = $newValues['slug'];

                if (DB::table('packages')->where('slug', $slug)->where('uuid', '!=', $uuid)->exists()) {
                    $slug .= '-'.substr((string) $uuid, 0, 8);
                }

                DB::table('packages')->where('uuid', $uuid)->update([
                    'name' => $newValues['name'],
                    'slug' => $slug,
                    'updated_at' => now(),
                ]);
            }
        }

        $optionNames = [
            'All Classes' => 'Seluruh Layanan',
            'Single drop in' => 'Satu Kali Konsultasi',
            '5 Private Classes' => '5 Kali Konsultasi',
            '10 Private Classes' => '10 Kali Konsultasi',
            '25 Classes' => '25 Sesi Layanan',
            'First Class Experience' => 'Pengalaman Pertama',
        ];

        foreach ($optionNames as $oldName => $newName) {
            DB::table('package_options')->where('name', $oldName)->update([
                'name' => $newName,
                'updated_at' => now(),
            ]);
        }

        if (DB::getSchemaBuilder()->hasTable('venues')) {
            foreach (DB::table('venues')->where('name', 'Yoga Roots Studio')->pluck('uuid') as $uuid) {
                $slug = 'sagita-wedding-venue';

                if (DB::table('venues')->where('slug', $slug)->where('uuid', '!=', $uuid)->exists()) {
                    $slug .= '-'.substr((string) $uuid, 0, 8);
                }

                DB::table('venues')->where('uuid', $uuid)->update([
                    'name' => 'Sagita Wedding Venue',
                    'slug' => $slug,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void {}
};
