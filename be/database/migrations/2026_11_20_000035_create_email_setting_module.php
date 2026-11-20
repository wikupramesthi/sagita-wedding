<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        'email-setting.index',
        'email-setting.update',
        'email-setting.test',
    ];

    private array $routes = [
        'email-setting.index' => 'email-setting.index',
        'email-setting.update' => 'email-setting.update',
        'email-setting.test' => 'email-setting.test',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->seedDefaultSetting();
        $this->renameSettingsGroup('Pengaturan', 'Pengaturan Sistem');
        $this->seedPermissionsAndAccess();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('route', 'email-setting.index')->delete();
        }

        if (Schema::hasTable('routes')) {
            DB::table('routes')->whereIn('route', array_keys($this->routes))->delete();
        }

        if (Schema::hasTable('permissions')) {
            $ids = DB::table('permissions')->whereIn('name', $this->permissions)->pluck('id');

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
            }

            DB::table('permissions')->whereIn('id', $ids)->delete();
        }

        $this->renameSettingsGroup('Pengaturan Sistem', 'Pengaturan');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function seedDefaultSetting(): void
    {
        if (! Schema::hasTable('email_settings') || DB::table('email_settings')->exists()) {
            return;
        }

        $identity = Schema::hasTable('website_identities')
            ? DB::table('website_identities')->first()
            : null;

        DB::table('email_settings')->insert([
            'uuid' => (string) Str::uuid(),
            'mail_driver' => config('mail.default', 'log'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_timeout' => 60,
            'from_address' => $identity?->email ?: config('mail.from.address'),
            'from_name' => $identity?->site_name ?: config('app.name'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function renameSettingsGroup(string $from, string $to): void
    {
        if (! Schema::hasTable('menu_groups')) {
            return;
        }

        DB::table('menu_groups')
            ->where('name', $from)
            ->update(['name' => $to, 'icon' => 'bx-cog', 'position' => 12, 'updated_at' => now()]);
    }

    private function seedPermissionsAndAccess(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $groupId = Schema::hasTable('menu_groups')
            ? DB::table('menu_groups')->where('name', 'Pengaturan Sistem')->value('id')
            : null;

        if (! $groupId && Schema::hasTable('menu_groups')) {
            $groupId = DB::table('menu_groups')->insertGetId([
                'name' => 'Pengaturan Sistem',
                'icon' => 'bx-cog',
                'status' => true,
                'permission_name' => 'menu-item.index',
                'position' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($groupId && Schema::hasTable('menu_items')) {
            DB::table('menu_items')->updateOrInsert(
                ['route' => 'email-setting.index', 'menu_group_id' => $groupId],
                [
                    'name' => 'Pengaturan Email',
                    'icon' => null,
                    'status' => true,
                    'permission_name' => 'email-setting.index',
                    'position' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (Schema::hasTable('routes')) {
            foreach ($this->routes as $route => $permission) {
                DB::table('routes')->updateOrInsert(
                    ['route' => $route],
                    [
                        'permission_name' => $permission,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($this->permissions);
        }
    }
};
