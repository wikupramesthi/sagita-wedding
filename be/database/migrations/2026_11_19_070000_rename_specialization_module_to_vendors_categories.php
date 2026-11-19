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
        $this->renameSchema(
            'specializations',
            'vendors_categories',
            'user_specialization',
            'user_vendors_category',
            'specialization_uuid',
            'vendors_category_uuid'
        );

        $this->renameDatabaseReferences('specializations', 'vendors_categories');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $this->renameDatabaseReferences('vendors_categories', 'specializations');

        $this->renameSchema(
            'vendors_categories',
            'specializations',
            'user_vendors_category',
            'user_specialization',
            'vendors_category_uuid',
            'specialization_uuid'
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function renameSchema(
        string $oldTable,
        string $newTable,
        string $oldPivot,
        string $newPivot,
        string $oldColumn,
        string $newColumn
    ): void {
        $this->renameTable($oldTable, $newTable, [
            $oldTable.'_name_unique' => $newTable.'_name_unique',
            $oldTable.'_slug_unique' => $newTable.'_slug_unique',
        ]);

        $this->renameTable($oldPivot, $newPivot, []);

        $this->renameColumn($newPivot, $oldColumn, $newColumn);

        $this->renameIndex(
            $newPivot,
            $oldPivot.'_user_uuid_'.$oldColumn.'_unique',
            $newPivot.'_user_uuid_'.$newColumn.'_unique'
        );

        $this->renameIndex(
            $newPivot,
            $oldPivot.'_'.$oldColumn.'_foreign',
            $newPivot.'_'.$newColumn.'_foreign'
        );

        $this->renameForeignKey(
            $newPivot,
            $oldPivot.'_'.$oldColumn.'_foreign',
            $newPivot.'_'.$newColumn.'_foreign',
            $newColumn,
            $newTable
        );

        $this->renameForeignKey(
            $newPivot,
            $oldPivot.'_user_uuid_foreign',
            $newPivot.'_user_uuid_foreign',
            'user_uuid',
            'users'
        );
    }

    private function renameTable(string $old, string $new, array $indexMap): void
    {
        $hasOld = Schema::hasTable($old);
        $hasNew = Schema::hasTable($new);

        if ($hasOld && $hasNew) {
            throw new RuntimeException("Both {$old} and {$new} tables exist.");
        }

        if ($hasOld) {
            Schema::rename($old, $new);
        } elseif (! $hasNew) {
            throw new RuntimeException("Neither {$old} nor {$new} table exists.");
        }

        foreach ($indexMap as $oldIndex => $newIndex) {
            $this->renameIndex($new, $oldIndex, $newIndex);
        }
    }

    private function renameColumn(string $table, string $old, string $new): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $hasOld = Schema::hasColumn($table, $old);
        $hasNew = Schema::hasColumn($table, $new);

        if ($hasOld && $hasNew) {
            throw new RuntimeException("Both {$old} and {$new} columns exist.");
        }

        if ($hasOld) {
            Schema::table($table, function (Blueprint $blueprint) use ($old, $new) {
                $blueprint->renameColumn($old, $new);
            });
        } elseif (! $hasNew) {
            throw new RuntimeException("Neither {$old} nor {$new} column exists.");
        }
    }

    private function renameIndex(string $table, string $oldIndex, string $newIndex): void
    {
        if ($oldIndex === $newIndex || ! Schema::hasTable($table) || ! Schema::hasIndex($table, $oldIndex)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($oldIndex, $newIndex) {
            $blueprint->renameIndex($oldIndex, $newIndex);
        });
    }

    private function renameForeignKey(
        string $table,
        string $oldName,
        string $newName,
        string $column,
        string $referenceTable,
        string $referenceColumn = 'uuid'
    ): void {
        if (
            DB::getDriverName() !== 'mysql' ||
            $oldName === $newName ||
            ! Schema::hasTable($table) ||
            ! $this->hasForeignKey($table, $oldName)
        ) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$oldName}`");
        DB::statement(
            "ALTER TABLE `{$table}` ADD CONSTRAINT `{$newName}` ".
            "FOREIGN KEY (`{$column}`) REFERENCES `{$referenceTable}` (`{$referenceColumn}`) ON DELETE CASCADE"
        );
    }

    private function hasForeignKey(string $table, string $name): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $name)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
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

    private function renameValue(string $table, string $column, string $value, string $from, string $to): string
    {
        $labels = [
            'vendors_categories' => [
                'Specialization' => 'Vendor Category',
                'Specializations' => 'Vendor Categories',
                'All Specializations' => 'All Vendor Categories',
                'Add Specialization' => 'Add Vendor Category',
            ],
            'specializations' => [
                'Vendor Category' => 'Specialization',
                'Vendor Categories' => 'Specializations',
                'All Vendor Categories' => 'All Specializations',
                'Add Vendor Category' => 'Add Specialization',
            ],
        ];

        if (in_array($column, ['name', 'route', 'permission_name'], true) && isset($labels[$to][$value])) {
            return $labels[$to][$value];
        }

        $prefix = $from.'.';

        return str_starts_with($value, $prefix)
            ? $to.substr($value, strlen($from))
            : $value;
    }
};
