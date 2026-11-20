<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_inquiries') && ! Schema::hasTable('consultations')) {
            Schema::rename('contact_inquiries', 'consultations');
        }

        if (Schema::hasTable('consultations')) {
            DB::table('consultations')->where('consultation_status', 'pending')->update(['consultation_status' => 'Pending']);
            DB::table('consultations')->where('consultation_status', 'scheduled')->update(['consultation_status' => 'Confirmed']);
            DB::table('consultations')->where('consultation_status', 'completed')->update(['consultation_status' => 'Completed']);
            DB::table('consultations')->where('consultation_status', 'cancelled')->update(['consultation_status' => 'Cancelled']);

            DB::statement("ALTER TABLE `consultations` MODIFY `consultation_status` ENUM('Pending','Confirmed','Rescheduled','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Pending'");

            if (! Schema::hasColumn('consultations', 'follow_up_at')) {
                Schema::table('consultations', fn (Blueprint $table) => $table->dateTime('follow_up_at')->nullable()->after('handled_by'));
            }

            if (! Schema::hasColumn('consultations', 'lead_status')) {
                Schema::table('consultations', fn (Blueprint $table) => $table->enum('lead_status', [
                    'New',
                    'Contacted',
                    'Interested',
                    'Negotiation',
                    'Converted',
                    'Lost',
                ])->default('New')->after('follow_up_at'));
            }

            if (! Schema::hasColumn('consultations', 'source')) {
                Schema::table('consultations', fn (Blueprint $table) => $table->string('source', 50)->default('Website')->after('lead_status'));
            }

            if (! Schema::hasColumn('consultations', 'converted_to_booking_at')) {
                Schema::table('consultations', fn (Blueprint $table) => $table->dateTime('converted_to_booking_at')->nullable()->after('source'));
            }
        }

        $permission = Permission::firstOrCreate([
            'name' => 'konsultasi.index',
            'guard_name' => 'web',
        ]);

        $oldPermission = Permission::where('name', 'layanan.kontak')
            ->where('guard_name', 'web')
            ->first();

        if ($oldPermission) {
            $roleIds = DB::table('role_has_permissions')
                ->where('permission_id', $oldPermission->id)
                ->pluck('role_id');

            foreach ($roleIds as $roleId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permission->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::table('role_has_permissions')->where('permission_id', $oldPermission->id)->delete();
            $oldPermission->delete();
        }

        DB::table('routes')->whereIn('route', [
            'layanan.kontak',
            'kontak.create',
            'kontak.store',
            'kontak.show',
            'kontak.edit',
            'kontak.update',
            'kontak.destroy',
        ])->delete();

        $now = now();

        foreach ([
            'konsultasi.index',
            'konsultasi.create',
            'konsultasi.store',
            'konsultasi.show',
            'konsultasi.edit',
            'konsultasi.update',
            'konsultasi.destroy',
        ] as $name) {
            DB::table('routes')->insert([
                'id' => (DB::table('routes')->max('id') ?? 0) + 1,
                'route' => $name,
                'permission_name' => 'konsultasi.index',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $groupId = DB::table('menu_groups')->where('permission_name', 'layanan.kontak')->value('id');

        if ($groupId) {
            DB::table('menu_groups')->where('id', $groupId)->update([
                'name' => 'Konsultasi',
                'permission_name' => 'konsultasi.index',
                'updated_at' => $now,
            ]);
        } else {
            $groupId = DB::table('menu_groups')->where('permission_name', 'konsultasi.index')->value('id');
        }

        if (! $groupId) {
            $groupId = (DB::table('menu_groups')->max('id') ?? 0) + 1;

            DB::table('menu_groups')->insert([
                'id' => $groupId,
                'name' => 'Konsultasi',
                'status' => 1,
                'permission_name' => 'konsultasi.index',
                'icon' => 'bx-message-detail',
                'position' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('menu_items')->where('route', 'layanan.kontak')->delete();

        if (! DB::table('menu_items')->where('route', 'konsultasi.index')->exists()) {
            DB::table('menu_items')->insert([
                'id' => (DB::table('menu_items')->max('id') ?? 0) + 1,
                'name' => 'Pertanyaan Masuk',
                'icon' => null,
                'route' => 'konsultasi.index',
                'status' => 1,
                'permission_name' => 'konsultasi.index',
                'menu_group_id' => $groupId,
                'position' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'konsultasi.index')->delete();
        DB::table('routes')->whereIn('route', [
            'konsultasi.index',
            'konsultasi.create',
            'konsultasi.store',
            'konsultasi.show',
            'konsultasi.edit',
            'konsultasi.update',
            'konsultasi.destroy',
        ])->delete();

        $permission = Permission::where('name', 'konsultasi.index')
            ->where('guard_name', 'web')
            ->first();

        $oldPermission = Permission::firstOrCreate([
            'name' => 'layanan.kontak',
            'guard_name' => 'web',
        ]);

        if ($permission) {
            $roleIds = DB::table('role_has_permissions')
                ->where('permission_id', $permission->id)
                ->pluck('role_id');

            foreach ($roleIds as $roleId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $oldPermission->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
            $permission->delete();
        }

        $groupId = DB::table('menu_groups')->where('permission_name', 'konsultasi.index')->value('id');

        if ($groupId) {
            DB::table('menu_groups')->where('id', $groupId)->update([
                'name' => 'Kontak',
                'permission_name' => 'layanan.kontak',
            ]);
        }

        $now = now();

        foreach ([
            'layanan.kontak' => 'layanan.kontak',
            'kontak.create' => 'layanan.kontak',
            'kontak.store' => 'layanan.kontak',
            'kontak.show' => 'layanan.kontak',
            'kontak.edit' => 'layanan.kontak',
            'kontak.update' => 'layanan.kontak',
            'kontak.destroy' => 'layanan.kontak',
        ] as $name => $permissionName) {
            DB::table('routes')->insert([
                'id' => (DB::table('routes')->max('id') ?? 0) + 1,
                'route' => $name,
                'permission_name' => $permissionName,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (Schema::hasTable('consultations')) {
            DB::table('consultations')->where('consultation_status', 'Pending')->update(['consultation_status' => 'pending']);
            DB::table('consultations')->where('consultation_status', 'Confirmed')->update(['consultation_status' => 'scheduled']);
            DB::table('consultations')->where('consultation_status', 'Rescheduled')->update(['consultation_status' => 'scheduled']);
            DB::table('consultations')->where('consultation_status', 'Completed')->update(['consultation_status' => 'completed']);
            DB::table('consultations')->where('consultation_status', 'Cancelled')->update(['consultation_status' => 'cancelled']);
            DB::table('consultations')->where('consultation_status', 'No Show')->update(['consultation_status' => 'cancelled']);

            DB::statement("ALTER TABLE `consultations` MODIFY `consultation_status` ENUM('pending','scheduled','completed','cancelled') NOT NULL DEFAULT 'pending'");

            Schema::table('consultations', function (Blueprint $table) {
                foreach (['follow_up_at', 'lead_status', 'source', 'converted_to_booking_at'] as $column) {
                    if (Schema::hasColumn('consultations', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });

            if (! Schema::hasTable('contact_inquiries')) {
                Schema::rename('consultations', 'contact_inquiries');
            }
        }
    }
};
