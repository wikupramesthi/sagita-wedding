<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $dedupeColumns = [
        'email_normalized' => 'email_dedupe_key',
        'whatsapp_e164' => 'whatsapp_dedupe_key',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('consultations')) {
            return;
        }

        DB::table('consultations')
            ->whereNotNull('deleted_at')
            ->update([
                'email_dedupe_key' => null,
                'whatsapp_dedupe_key' => null,
            ]);

        foreach ($this->dedupeColumns as $source => $key) {
            DB::table('consultations')
                ->select('uuid', $source)
                ->whereNull('deleted_at')
                ->whereNull($key)
                ->whereNotNull($source)
                ->where($source, '!=', '')
                ->orderBy('created_at')
                ->chunkById(500, function ($rows) use ($source, $key) {
                    foreach ($rows as $row) {
                        $value = (string) $row->{$source};

                        if ($this->keyIsTaken($key, $value, $row->uuid)) {
                            continue;
                        }

                        DB::table('consultations')
                            ->where('uuid', $row->uuid)
                            ->update([$key => $value]);
                    }
                }, 'uuid');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('consultations')) {
            return;
        }

        foreach ($this->dedupeColumns as $source => $key) {
            DB::table('consultations')
                ->select('uuid', $source)
                ->whereNull('deleted_at')
                ->whereNull($key)
                ->whereNotNull($source)
                ->where($source, '!=', '')
                ->orderBy('created_at')
                ->chunkById(500, function ($rows) use ($source, $key) {
                    foreach ($rows as $row) {
                        $value = (string) $row->{$source};

                        if ($this->keyIsTaken($key, $value, $row->uuid)) {
                            continue;
                        }

                        DB::table('consultations')
                            ->where('uuid', $row->uuid)
                            ->update([$key => $value]);
                    }
                }, 'uuid');
        }
    }

    private function keyIsTaken(string $key, string $value, string $uuid): bool
    {
        return DB::table('consultations')
            ->where($key, $value)
            ->whereNull('deleted_at')
            ->where('uuid', '!=', $uuid)
            ->exists();
    }
};
