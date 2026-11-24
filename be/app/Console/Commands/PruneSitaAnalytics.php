<?php

namespace App\Console\Commands;

use App\Models\Sita\SitaAnalyticsEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PruneSitaAnalytics extends Command
{
    protected $signature = 'sita:prune-analytics {--days=} {--keep=} {--chunk=} {--dry-run}';

    protected $description = 'Membersihkan event analytics SITA yang lama dan membatasi jumlah baris';

    public function handle(): int
    {
        $days = max(1, (int) ($this->option('days') ?? config('sita.analytics.retention_days')));
        $keep = max(1, (int) ($this->option('keep') ?? config('sita.analytics.max_rows')));
        $chunk = max(1, min(5000, (int) ($this->option('chunk') ?? config('sita.analytics.prune_chunk'))));
        $cutoff = now()->subDays($days);

        $keepBoundaryId = SitaAnalyticsEvent::query()
            ->orderByDesc('id')
            ->limit($keep)
            ->pluck('id')
            ->min();

        $query = SitaAnalyticsEvent::query()
            ->where(function ($builder) use ($cutoff, $keepBoundaryId) {
                $builder->where('occurred_at', '<', $cutoff);

                if ($keepBoundaryId !== null) {
                    $builder->orWhere('id', '<', $keepBoundaryId);
                }
            });

        if ($this->option('dry-run')) {
            $count = $query->count();
            $this->info("{$count} event analytics SITA akan dibersihkan (dry-run).");

            return self::SUCCESS;
        }

        $deleted = 0;

        while (true) {
            $ids = (clone $query)->orderBy('id')->limit($chunk)->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            $removed = SitaAnalyticsEvent::query()->whereIn('id', $ids)->delete();

            if ($removed === 0) {
                break;
            }

            $deleted += $removed;
        }

        Log::info('sita.analytics.pruned', [
            'deleted' => $deleted,
            'days' => $days,
            'keep' => $keep,
        ]);

        $this->info("{$deleted} event analytics SITA berhasil dibersihkan.");

        return self::SUCCESS;
    }
}
