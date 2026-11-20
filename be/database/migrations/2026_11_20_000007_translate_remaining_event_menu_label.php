<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_items')
            ->where('route', 'events.index')
            ->where('name', 'Event')
            ->update(['name' => 'Acara', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('menu_items')
            ->where('route', 'events.index')
            ->where('name', 'Acara')
            ->update(['name' => 'Event', 'updated_at' => now()]);
    }
};
