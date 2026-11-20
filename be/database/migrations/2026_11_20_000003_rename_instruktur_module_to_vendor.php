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
        $this->renameClassReference('instructor_uuid', 'vendor_uuid');
        $this->renameRole('instruktur', 'vendor');
        $this->renameDatabaseReferences('instruktur', 'vendor');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $this->renameDatabaseReferences('vendor', 'instruktur');
        $this->renameRole('vendor', 'instruktur');
        $this->renameClassReference('vendor_uuid', 'instructor_uuid');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function renameClassReference(string $from, string $to): void
    {
        if (! Schema::hasTable('classes')) {
            return;
        }

        $hasOld = Schema::hasColumn('classes', $from);
        $hasNew = Schema::hasColumn('classes', $to);

        if ($hasOld && $hasNew) {
            throw new RuntimeException("Both {$from} and {$to} columns exist in classes.");
        }

        $oldIndex = 'classes_'.$from.'_index';
        $newIndex = 'classes_'.$to.'_index';
        $oldForeign = 'classes_'.$from.'_foreign';
        $newForeign = 'classes_'.$to.'_foreign';

        if ($hasOld) {
            $this->dropForeignKey('classes', $oldForeign);

            Schema::table('classes', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });

            $this->renameIndex('classes', $oldIndex, $newIndex);
            $this->addForeignKey('classes', $newForeign, $to);

            return;
        }

        if (! $hasNew) {
            throw new RuntimeException("Neither {$from} nor {$to} column exists in classes.");
        }

        $this->renameIndex('classes', $oldIndex, $newIndex);
        $this->dropForeignKey('classes', $oldForeign);
        $this->addForeignKey('classes', $newForeign, $to);
    }

    private function dropForeignKey(string $table, string $name): void
    {
        if (DB::getDriverName() !== 'mysql' || ! $this->hasForeignKey($table, $name)) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$name}`");
    }

    private function addForeignKey(string $table, string $name, string $column): void
    {
        if (DB::getDriverName() !== 'mysql' || $this->hasForeignKey($table, $name)) {
            return;
        }

        DB::statement(
            "ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}` ".
            "FOREIGN KEY (`{$column}`) REFERENCES `users` (`uuid`) ON DELETE SET NULL"
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

    private function renameIndex(string $table, string $old, string $new): void
    {
        if ($old === $new || ! Schema::hasTable($table) || ! Schema::hasIndex($table, $old)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($old, $new) {
            $blueprint->renameIndex($old, $new);
        });
    }

    private function renameRole(string $from, string $to): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $old = DB::table('roles')->where('name', $from)->first();
        $current = DB::table('roles')->where('name', $to)->first();

        if ($old && $current) {
            throw new RuntimeException("Both {$from} and {$to} roles exist.");
        }

        if ($old) {
            DB::table('roles')->where('id', $old->id)->update(['name' => $to]);
        }
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
            'vendor' => [
                'Instructors' => 'Vendor',
                'All Instructors' => 'Daftar Vendor',
                'Instructor' => 'Vendor',
                'Vendor Category' => 'Kategori Vendor',
                'Vendor Categories' => 'Kategori Vendor',
            ],
            'instruktur' => [
                'Vendor' => 'Instructors',
                'Daftar Vendor' => 'All Instructors',
                'Kategori Vendor' => 'Vendor Category',
            ],
        ];

        unset($table);

        if (in_array($column, ['name', 'route', 'permission_name'], true) && isset($labels[$to][$value])) {
            return $labels[$to][$value];
        }

        $prefix = $from.'.';

        return str_starts_with($value, $prefix)
            ? $to.substr($value, strlen($from))
            : $value;
    }
};
