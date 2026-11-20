<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('packages', 'id')) {
            DB::statement('ALTER TABLE `packages` ADD `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE FIRST');
        }

        if (! Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE `users` ADD `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE FIRST');
        }

        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('full_name', 150);
            $table->string('whatsapp', 20);
            $table->date('wedding_date')->nullable();
            $table->enum('guest_estimate', [
                'Di bawah 200 tamu',
                '200–500 tamu',
                '500–1.000 tamu',
                'Di atas 1.000 tamu',
            ])->nullable();
            $table->string('venue', 255)->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->text('wedding_concept')->nullable();
            $table->date('consultation_date')->nullable();
            $table->time('consultation_time')->nullable();
            $table->enum('consultation_method', ['Online', 'Offline'])->nullable();
            $table->enum('consultation_status', [
                'pending',
                'scheduled',
                'completed',
                'cancelled',
            ])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->timestamps();
        });

        $now = now();
        $routeNames = [
            'kontak.create',
            'kontak.store',
            'kontak.show',
            'kontak.edit',
            'kontak.update',
            'kontak.destroy',
        ];

        foreach ($routeNames as $name) {
            if (! DB::table('routes')->where('route', $name)->exists()) {
                DB::table('routes')->insert([
                    'id' => (DB::table('routes')->max('id') ?? 0) + 1,
                    'route' => $name,
                    'permission_name' => 'layanan.kontak',
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $groupId = DB::table('menu_groups')->where('permission_name', 'layanan.kontak')->value('id');

        if (! $groupId) {
            $groupId = (DB::table('menu_groups')->max('id') ?? 0) + 1;

            DB::table('menu_groups')->insert([
                'id' => $groupId,
                'name' => 'Kontak',
                'status' => 1,
                'permission_name' => 'layanan.kontak',
                'icon' => 'bx-message-detail',
                'position' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (! DB::table('menu_items')->where('route', 'layanan.kontak')->exists()) {
            DB::table('menu_items')->insert([
                'id' => (DB::table('menu_items')->max('id') ?? 0) + 1,
                'name' => 'Pertanyaan Masuk',
                'icon' => null,
                'route' => 'layanan.kontak',
                'status' => 1,
                'permission_name' => 'layanan.kontak',
                'menu_group_id' => $groupId,
                'position' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'layanan.kontak')->delete();
        DB::table('routes')->whereIn('route', [
            'kontak.create',
            'kontak.store',
            'kontak.show',
            'kontak.edit',
            'kontak.update',
            'kontak.destroy',
        ])->delete();

        $groupId = DB::table('menu_groups')->where('permission_name', 'layanan.kontak')->value('id');

        if ($groupId) {
            DB::table('menu_groups')->where('id', $groupId)->delete();
        }

        Schema::dropIfExists('contact_inquiries');

        if (Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE `users` DROP COLUMN `id`');
        }

        if (Schema::hasColumn('packages', 'id')) {
            DB::statement('ALTER TABLE `packages` DROP COLUMN `id`');
        }
    }
};
