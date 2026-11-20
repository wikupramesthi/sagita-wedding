<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setLabels('Events', 'Acara', 'All Events', 'Semua Acara');
    }

    public function down(): void
    {
        $this->setLabels('Acara', 'Events', 'Semua Acara', 'All Events');
    }

    private function setLabels(string $groupFrom, string $groupTo, string $itemFrom, string $itemTo): void
    {
        if (Schema::hasTable('menu_groups')) {
            DB::table('menu_groups')
                ->where('permission_name', 'events.index')
                ->where('name', $groupFrom)
                ->update(['name' => $groupTo]);
        }

        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')
                ->where('route', 'events.index')
                ->where('permission_name', 'events.index')
                ->where('name', $itemFrom)
                ->update(['name' => $itemTo]);
        }
    }
};
