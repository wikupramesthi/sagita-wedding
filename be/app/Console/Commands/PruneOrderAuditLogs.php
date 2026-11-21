<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Payment\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PruneOrderAuditLogs extends Command
{
    protected $signature = 'orders:prune-audit {--days=180} {--dry-run}';

    protected $description = 'Membersihkan log audit pesanan yang sudah lama';

    public function handle(): int
    {
        $days = max(30, (int) $this->option('days'));
        $cutoff = now()->subDays($days);

        $query = AuditLog::query()
            ->where('auditable_type', Order::class)
            ->where('created_at', '<', $cutoff);

        $count = $query->count();

        if ($this->option('dry-run')) {
            $this->info("{$count} log audit pesanan akan dibersihkan (dry-run).");

            return self::SUCCESS;
        }

        $deleted = $query->delete();

        Log::info('orders.audit.pruned', ['deleted' => $deleted, 'days' => $days]);
        $this->info("{$deleted} log audit pesanan berhasil dibersihkan.");

        return self::SUCCESS;
    }
}
