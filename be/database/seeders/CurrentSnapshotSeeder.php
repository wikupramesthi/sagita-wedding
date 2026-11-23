<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CurrentSnapshotSeeder extends Seeder
{
    private const EXCLUDED_TABLES = [
        'cache',
        'cache_locks',
        'failed_jobs',
        'job_batches',
        'jobs',
        'migrations',
        'password_reset_tokens',
        'password_resets',
        'personal_access_tokens',
        'sessions',
    ];

    public function run(): void
    {
        $path = database_path('seeders/data/current-snapshot.sql');

        if (! is_file($path)) {
            throw new RuntimeException("Snapshot seeder tidak ditemukan: {$path}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::transaction(function () use ($path) {
                foreach ($this->tables() as $table) {
                    DB::table($table)->delete();
                }

                foreach ($this->statements((string) file_get_contents($path)) as $statement) {
                    if ($this->shouldSkip($statement)) {
                        continue;
                    }

                    DB::unprepared($statement);
                }
            });
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function tables(): array
    {
        $tables = [];

        foreach (DB::select('SHOW FULL TABLES') as $row) {
            $values = array_values((array) $row);
            $name = (string) ($values[0] ?? '');
            $type = strtoupper((string) ($values[1] ?? ''));

            if ($name === '' || $type !== 'BASE TABLE') {
                continue;
            }

            if (in_array($name, self::EXCLUDED_TABLES, true)) {
                continue;
            }

            $tables[] = $name;
        }

        sort($tables);

        return $tables;
    }

    private function statements(string $sql): array
    {
        $sql = (string) preg_replace('/^\/\*M!.*\*\/\s*$/m', '', $sql);
        $statements = [];
        $buffer = '';
        $quote = null;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($quote !== null) {
                $buffer .= $char;

                if ($char === '\\') {
                    if ($i + 1 < $length) {
                        $buffer .= $sql[$i + 1];
                        $i++;
                    }

                    continue;
                }

                if ($char === $quote) {
                    $quote = null;
                }

                continue;
            }

            if ($char === "'" || $char === '"') {
                $quote = $char;
                $buffer .= $char;
                continue;
            }

            if ($char === ';') {
                $statement = trim($buffer);

                if ($statement !== '') {
                    $statements[] = $statement;
                }

                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $statement = trim($buffer);

        if ($statement !== '') {
            $statements[] = $statement;
        }

        return $statements;
    }

    private function shouldSkip(string $statement): bool
    {
        if (str_starts_with($statement, '/*M!')) {
            return true;
        }

        return (bool) preg_match(
            '/^\/\*!40000 ALTER TABLE .* (?:DISABLE|ENABLE) KEYS \*\/$/s',
            $statement,
        );
    }
}
