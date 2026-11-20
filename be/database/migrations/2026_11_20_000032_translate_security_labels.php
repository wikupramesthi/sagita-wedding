<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->translateLabels([
            'Security' => 'Keamanan',
        ], [
            'Login Activity' => 'Aktivitas Login',
            'Audit Log' => 'Log Audit',
            'Failed Login' => 'Login Gagal',
            'Login Blocks' => 'Blokir Login',
        ]);
    }

    public function down(): void
    {
        $this->translateLabels([
            'Keamanan' => 'Security',
        ], [
            'Aktivitas Login' => 'Login Activity',
            'Log Audit' => 'Audit Log',
            'Login Gagal' => 'Failed Login',
            'Blokir Login' => 'Login Blocks',
        ]);
    }

    private function translateLabels(array $groupLabels, array $itemLabels): void
    {
        if (Schema::hasTable('menu_groups')) {
            foreach ($groupLabels as $from => $to) {
                DB::table('menu_groups')
                    ->where('permission_name', 'menu.keamanan')
                    ->where('name', $from)
                    ->update(['name' => $to]);
            }
        }

        if (Schema::hasTable('menu_items')) {
            foreach ($itemLabels as $from => $to) {
                DB::table('menu_items')
                    ->whereIn('route', [
                        'security.login-activity.index',
                        'security.audit-log.index',
                        'security.failed-login.index',
                        'security.login-lockout.index',
                    ])
                    ->where('name', $from)
                    ->update(['name' => $to]);
            }
        }
    }
};
