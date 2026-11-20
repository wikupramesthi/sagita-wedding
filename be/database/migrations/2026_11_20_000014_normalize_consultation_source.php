<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->legacyMap() as $legacy => $key) {
            DB::table('consultations')->where('source', $legacy)->update(['source' => $key]);
        }

        DB::statement("ALTER TABLE `consultations` MODIFY `source` VARCHAR(50) NOT NULL DEFAULT 'website'");
    }

    public function down(): void
    {
        foreach ($this->legacyMap() as $legacy => $key) {
            DB::table('consultations')->where('source', $key)->update(['source' => $legacy]);
        }

        DB::statement("ALTER TABLE `consultations` MODIFY `source` VARCHAR(50) NOT NULL DEFAULT 'website'");
    }

    private function legacyMap(): array
    {
        return [
            'Website' => 'website',
            'Google' => 'google',
            'Media Sosial' => 'sosmed',
            'Rekomendasi Teman' => 'friend',
            'Komunitas' => 'community',
            'Acara / Pameran' => 'event',
            'Lainnya' => 'other',
            'Tanpa keterangan' => 'unknown',
        ];
    }
};
