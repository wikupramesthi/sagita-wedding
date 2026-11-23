<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('model_has_permissions')) {
            $type = Schema::getColumnType('model_has_permissions', 'model_id');

            if (! in_array($type, ['uuid', 'char'], true)) {
                Schema::table('model_has_permissions', function (Blueprint $table) {
                    $table->dropForeign('model_has_permissions_permission_id_foreign');
                    $table->dropPrimary('model_has_permissions_permission_model_type_primary');
                    $table->dropIndex('model_has_permissions_model_id_model_type_index');
                    $table->uuid('model_id')->change();
                    $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
                    $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
                    $table->foreign('permission_id', 'model_has_permissions_permission_id_foreign')
                        ->references('id')
                        ->on('permissions')
                        ->cascadeOnDelete();
                });
            }
        }

        $addIndex = function (string $table, array $columns, string $name): void {
            if (! Schema::hasTable($table)) {
                return;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    return;
                }
            }

            if (Schema::hasIndex($table, $name)) {
                return;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
                $blueprint->index($columns, $name);
            });
        };

        $addIndex('articles', ['status', 'deleted_at'], 'articles_status_deleted_at_index');
        $addIndex('events', ['status', 'tanggal'], 'events_status_tanggal_index');
        $addIndex('venues', ['status'], 'venues_status_index');
        $addIndex('packages', ['is_published', 'is_active', 'published_at'], 'packages_publish_active_published_index');
        $addIndex('payments', ['verification_status', 'status'], 'payments_verification_status_index');
        $addIndex('users', ['prospect_status'], 'users_prospect_status_index');
        $addIndex('users', ['sumber_informasi'], 'users_sumber_informasi_index');
        $addIndex('faqs', ['status'], 'faqs_status_index');
        $addIndex('testimonials', ['is_active'], 'testimonials_is_active_index');
        $addIndex('banners', ['status', 'tipe'], 'banners_status_tipe_index');
        $addIndex('pages', ['is_published', 'published_at'], 'pages_published_index');
    }

    public function down(): void
    {
        $dropIndex = function (string $table, string $name): void {
            if (Schema::hasTable($table) && Schema::hasIndex($table, $name)) {
                Schema::table($table, function (Blueprint $blueprint) use ($name) {
                    $blueprint->dropIndex($name);
                });
            }
        };

        $dropIndex('pages', 'pages_published_index');
        $dropIndex('banners', 'banners_status_tipe_index');
        $dropIndex('testimonials', 'testimonials_is_active_index');
        $dropIndex('faqs', 'faqs_status_index');
        $dropIndex('users', 'users_sumber_informasi_index');
        $dropIndex('users', 'users_prospect_status_index');
        $dropIndex('payments', 'payments_verification_status_index');
        $dropIndex('packages', 'packages_publish_active_published_index');
        $dropIndex('venues', 'venues_status_index');
        $dropIndex('events', 'events_status_tanggal_index');
        $dropIndex('articles', 'articles_status_deleted_at_index');
    }
};
