<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_items')
            ->where('route', 'categories.index')
            ->where('name', 'Category')
            ->update(['name' => 'Kategori']);
    }

    public function down(): void
    {
        DB::table('menu_items')
            ->where('route', 'categories.index')
            ->where('name', 'Kategori')
            ->update(['name' => 'Category']);
    }
};
