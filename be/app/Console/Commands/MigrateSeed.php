<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateSeed extends Command
{
    protected $signature = 'migrate:seed';

    protected $description = 'Jalankan migrasi lalu isi database dari snapshot tanpa migrate:fresh';

    public function handle(): int
    {
        $this->call('migrate', ['--force' => true]);

        $status = $this->call('db:seed', ['--force' => true]);

        return $status === self::SUCCESS ? self::SUCCESS : self::FAILURE;
    }
}
