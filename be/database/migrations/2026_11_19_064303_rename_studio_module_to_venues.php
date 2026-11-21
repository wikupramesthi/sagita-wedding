<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameSchema('studios', 'venues', 'studio_uuid', 'venue_uuid');
        $this->renameDatabaseReferences('studios', 'venues');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $this->renameDatabaseReferences('venues', 'studios');
        $this->renameSchema('venues', 'studios', 'venue_uuid', 'studio_uuid');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function renameSchema(
        string $oldTable,
        string $newTable,
        string $oldColumn,
        string $newColumn
    ): void {
        $hasOldTable = Schema::hasTable($oldTable);
        $hasNewTable = Schema::hasTable($newTable);

        if ($hasOldTable && $hasNewTable) {
            throw new RuntimeException("Both {$oldTable} and {$newTable} tables exist.");
        }

        if ($hasOldTable) {
            Schema::rename($oldTable, $newTable);
            $this->renameVenueIndex($newTable, $oldTable.'_slug_unique', $newTable.'_slug_unique');
        } elseif (! $hasNewTable) {
            throw new RuntimeException("Neither {$oldTable} nor {$newTable} table exists.");
        } else {
            $this->renameVenueIndex($newTable, $oldTable.'_slug_unique', $newTable.'_slug_unique');
        }

        // class module removed; no class_schedules column rename needed

    }

    private function renameDatabaseReferences(string $from, string $to): void
    {
        $tables = [
            'menu_groups' => ['name', 'permission_name'],
            'menu_items' => ['name', 'route', 'permission_name'],
            'routes' => ['route', 'permission_name'],
            'permissions' => ['name'],
        ];

        foreach ($tables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $columns = array_values(array_filter(
                $columns,
                fn (string $column) => Schema::hasColumn($table, $column),
            ));

            if (! $columns) {
                continue;
            }

            foreach (DB::table($table)->select(array_merge(['id'], $columns))->get() as $row) {
                $updates = [];

                foreach ($columns as $column) {
                    $value = $row->{$column};
                    if (! is_string($value)) {
                        continue;
                    }

                    $renamed = $this->renameValue($table, $column, $value, $from, $to);
                    if ($renamed !== $value) {
                        $updates[$column] = $renamed;
                    }
                }

                if ($updates) {
                    DB::table($table)->where('id', $row->id)->update($updates);
                }
            }
        }
    }

    private function renameVenueIndex(string $table, string $oldIndex, string $newIndex): void
    {
        if (Schema::hasIndex($table, $oldIndex)) {
            Schema::table($table, function (Blueprint $blueprint) use ($oldIndex, $newIndex) {
                $blueprint->renameIndex($oldIndex, $newIndex);
            });
        }
    }

    private function renameValue(string $table, string $column, string $value, string $from, string $to): string
    {
        if ($table === 'menu_groups' && $column === 'name') {
            return $value === ucfirst($from) ? ucfirst($to) : $value;
        }

        if ($table === 'menu_items' && $column === 'name') {
            return match ($value) {
                'All '.ucfirst($from) => 'All '.ucfirst($to),
                'Add '.ucfirst(rtrim($from, 's')) => 'Add '.ucfirst(rtrim($to, 's')),
                default => $value,
            };
        }

        $prefix = $from.'.';

        return str_starts_with($value, $prefix)
            ? $to.substr($value, strlen($from))
            : $value;
    }
};
