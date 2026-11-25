<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, array<string, list<string>>>
     */
    private array $indexes = [
        'orders' => [
            'orders_event_date_status_index' => ['event_date', 'status'],
        ],
        'payments' => [
            'payments_order_created_at_index' => ['order_uuid', 'created_at'],
        ],
        'user_packages' => [
            'user_packages_started_at_index' => ['started_at'],
            'user_packages_user_started_at_index' => ['user_uuid', 'started_at'],
        ],
        'audit_logs' => [
            'audit_logs_event_created_at_index' => ['event', 'created_at'],
            'audit_logs_user_created_at_index' => ['user_uuid', 'created_at'],
        ],
        'file_downloads' => [
            'file_downloads_created_at_index' => ['created_at'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $tableName => $indexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($indexes as $indexName => $columns) {
                if (
                    Schema::hasIndex($tableName, $indexName)
                    || collect($columns)->contains(fn (string $column): bool => ! Schema::hasColumn($tableName, $column))
                ) {
                    continue;
                }

                Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName): void {
                    $table->index($columns, $indexName);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $tableName => $indexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            foreach (array_keys($indexes) as $indexName) {
                if (Schema::hasIndex($tableName, $indexName)) {
                    Schema::table($tableName, function (Blueprint $table) use ($indexName): void {
                        $table->dropIndex($indexName);
                    });
                }
            }
        }
    }
};
