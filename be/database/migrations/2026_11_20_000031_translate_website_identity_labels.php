<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEGACY_NAME = 'YogaRoots';

    private const NEW_NAME = 'Sagita Wedding';

    private const NEW_TAGLINE = 'Wujudkan pernikahan impian Anda';

    public function up(): void
    {
        $this->translateIdentity(self::LEGACY_NAME, self::NEW_NAME, self::NEW_TAGLINE);
        $this->translateLabels('Settings', 'Pengaturan', 'Website Identity', 'Identitas Website');
    }

    public function down(): void
    {
        $this->translateIdentity(self::NEW_NAME, self::LEGACY_NAME, null);
        $this->translateLabels('Pengaturan', 'Settings', 'Identitas Website', 'Website Identity');
    }

    private function translateIdentity(string $fromName, string $toName, ?string $tagline): void
    {
        if (! Schema::hasTable('website_identities')) {
            return;
        }

        $rows = DB::table('website_identities')
            ->where('site_name', $fromName)
            ->where('site_title', $fromName)
            ->get(['uuid', 'tagline']);

        foreach ($rows as $row) {
            $data = [
                'site_name' => $toName,
                'site_title' => $toName,
                'updated_at' => now(),
            ];

            if ($tagline !== null && ($row->tagline === null || $row->tagline === '' || stripos((string) $row->tagline, 'yoga') !== false)) {
                $data['tagline'] = $tagline;
            } elseif ($tagline === null && $row->tagline === self::NEW_TAGLINE) {
                $data['tagline'] = null;
            }

            DB::table('website_identities')->where('uuid', $row->uuid)->update($data);
        }
    }

    private function translateLabels(
        string $groupFrom,
        string $groupTo,
        string $itemFrom,
        string $itemTo
    ): void {
        $now = now();

        if (Schema::hasTable('menu_groups')) {
            DB::table('menu_groups')
                ->where('name', $groupFrom)
                ->update(['name' => $groupTo, 'updated_at' => $now]);
        }

        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')
                ->where('route', 'website-identity.index')
                ->where('name', $itemFrom)
                ->update(['name' => $itemTo, 'updated_at' => $now]);
        }
    }
};
