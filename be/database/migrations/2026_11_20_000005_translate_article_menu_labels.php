<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->translateLabels([
            'Content' => 'Konten',
        ], [
            'Articles' => 'Artikel',
            'Add Articles' => 'Tambah Artikel',
        ]);
    }

    public function down(): void
    {
        $this->translateLabels([
            'Konten' => 'Content',
        ], [
            'Artikel' => 'Articles',
            'Tambah Artikel' => 'Add Articles',
        ]);
    }

    private function translateLabels(array $groupLabels, array $itemLabels): void
    {
        if (Schema::hasTable('menu_groups')) {
            foreach ($groupLabels as $from => $to) {
                DB::table('menu_groups')
                    ->where('permission_name', 'articles.index')
                    ->where('name', $from)
                    ->update(['name' => $to]);
            }
        }

        if (Schema::hasTable('menu_items')) {
            foreach ($itemLabels as $from => $to) {
                DB::table('menu_items')
                    ->whereIn('route', ['articles.index', 'articles.create'])
                    ->where('name', $from)
                    ->update(['name' => $to]);
            }
        }
    }
};
