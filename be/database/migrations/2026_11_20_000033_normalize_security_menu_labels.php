<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        DB::table('menu_items')->where('route', 'security.audit-log.index')->update(['name' => 'Log Audit']);
        DB::table('menu_items')->where('route', 'security.failed-login.index')->update(['name' => 'Login Gagal']);
        DB::table('menu_items')->where('route', 'security.login-lockout.index')->update(['name' => 'Blokir Login']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        DB::table('menu_items')->where('route', 'security.audit-log.index')->update(['name' => 'Log Aktivitas']);
        DB::table('menu_items')->where('route', 'security.failed-login.index')->update(['name' => 'Percobaan Login']);
        DB::table('menu_items')->where('route', 'security.login-lockout.index')->update(['name' => 'Blokir Akses']);
    }
};
