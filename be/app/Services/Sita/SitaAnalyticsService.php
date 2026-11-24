<?php

namespace App\Services\Sita;

use App\Models\Sita\SitaAnalyticsEvent;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SitaAnalyticsService
{
    public const MAX_EVENTS = 500;

    public const TOPICS = ['packages', 'venues', 'vendors'];

    public const CACHE_KEY = 'sita.analytics.summary';

    public const CACHE_TTL = 30;

    public static function invalidate(): void
    {
        Cache::forget(self::CACHE_KEY);
        $keys = Cache::get(self::CACHE_KEY.'.keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget(self::CACHE_KEY.'.keys');
    }

    public function summary(array $filters = []): array
    {
        $filters = $this->normalizeFilters($filters);
        $cacheKey = self::CACHE_KEY.'.'.md5(json_encode($filters));

        $keys = Cache::get(self::CACHE_KEY.'.keys', []);
        if (! in_array($cacheKey, $keys, true)) {
            $keys[] = $cacheKey;
            Cache::put(self::CACHE_KEY.'.keys', $keys, now()->addDay());
        }

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL), function () use ($filters): array {
            $events = $this->filteredQuery($filters)
                ->orderByDesc('occurred_at')
                ->orderByDesc('id')
                ->limit(self::MAX_EVENTS)
                ->get()
                ->reverse()
                ->values();

            $answers = $events->where('name', 'sita.answer')->values();
            $feedback = $events->where('name', 'sita.feedback')->values();
            $noMatch = $events->where('name', 'sita.no_match')->values();

            $helpful = $feedback->where('helpful', true)->count();
            $fallback = $answers->where('fallback', true)->count();
            $clarify = $answers->where('clarify', true)->count();
            $lowConfidence = $answers->filter(fn (SitaAnalyticsEvent $event): bool => $event->confidence !== null && $event->confidence < 0.6)->count();
            $responseTimes = $answers->filter(fn (SitaAnalyticsEvent $event): bool => $event->response_ms !== null)->pluck('response_ms');

            return [
                'success' => true,
                'generatedAt' => $this->iso(now()),
                'window' => [
                    'events' => $events->count(),
                    'maxEvents' => self::MAX_EVENTS,
                ],
                'filters' => $filters,
                'totals' => [
                    'answers' => $answers->count(),
                    'feedback' => $feedback->count(),
                    'helpful' => $helpful,
                    'unhelpful' => $feedback->count() - $helpful,
                    'noMatch' => $noMatch->count(),
                    'fallback' => $fallback,
                    'clarify' => $clarify,
                    'lowConfidence' => $lowConfidence,
                ],
                'rates' => [
                    'helpful' => $this->percent($helpful, $feedback->count()),
                    'fallback' => $this->percent($fallback, $answers->count()),
                    'noMatch' => $this->percent($noMatch->count(), $answers->count()),
                    'clarify' => $this->percent($clarify, $answers->count()),
                ],
                'performance' => [
                    'avgResponseMs' => $responseTimes->isEmpty()
                        ? 0
                        : (int) round($responseTimes->sum() / $responseTimes->count()),
                ],
                'topics' => collect(self::TOPICS)->map(fn (string $topic): array => [
                    'topic' => $topic,
                    'answers' => $answers->where('topic', $topic)->count(),
                    'helpful' => $feedback->where('topic', $topic)->where('helpful', true)->count(),
                    'unhelpful' => $feedback->where('topic', $topic)->count() - $feedback->where('topic', $topic)->where('helpful', true)->count(),
                ])->all(),
                'feedbackSegments' => $this->feedbackSegments($feedback),
                'noMatchPatterns' => $this->noMatchPatterns($noMatch),
                'dailyTrend' => $this->dailyTrend($filters),
                'recent' => $events->slice(-20)->reverse()->map(fn (SitaAnalyticsEvent $event): array => $this->sanitizeEvent($event))->values()->all(),
            ];
        });
    }

    public function normalizeFilters(array $filters): array
    {
        $from = $this->parseDate($filters['from'] ?? null)?->startOfDay();
        $to = $this->parseDate($filters['to'] ?? null)?->endOfDay();

        if ($from && $to && $from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $topic = (string) ($filters['topic'] ?? '');
        $locale = (string) ($filters['locale'] ?? '');

        return [
            'from' => $from?->toDateString(),
            'to' => $to?->toDateString(),
            'topic' => in_array($topic, self::TOPICS, true) ? $topic : '',
            'locale' => in_array($locale, ['id', 'en'], true) ? $locale : '',
        ];
    }

    private function filteredQuery(array $filters): Builder
    {
        $query = SitaAnalyticsEvent::query();

        if ($filters['from']) {
            $query->where('occurred_at', '>=', Carbon::parse($filters['from'])->startOfDay());
        }
        if ($filters['to']) {
            $query->where('occurred_at', '<=', Carbon::parse($filters['to'])->endOfDay());
        }
        if ($filters['topic']) {
            $query->where('topic', $filters['topic']);
        }
        if ($filters['locale']) {
            $query->where('locale', $filters['locale']);
        }

        return $query;
    }

    private function dailyTrend(array $filters): array
    {
        $from = $filters['from'] ? Carbon::parse($filters['from'])->startOfDay() : now()->subDays(13)->startOfDay();
        $to = $filters['to'] ? Carbon::parse($filters['to'])->endOfDay() : now()->endOfDay();

        $useAggregate = DB::getSchemaBuilder()->hasTable('sita_analytics_daily');

        if ($useAggregate) {
            $rows = DB::table('sita_analytics_daily')
                ->selectRaw('date, name, SUM(total) as total')
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
                ->when($filters['topic'], fn ($q) => $q->where('topic', $filters['topic']))
                ->when($filters['locale'], fn ($q) => $q->where('locale', $filters['locale']))
                ->groupBy('date', 'name')
                ->get();
        } else {
            $rows = $this->filteredQuery($filters)
                ->whereBetween('occurred_at', [$from, $to])
                ->selectRaw('DATE(occurred_at) as date, name, COUNT(*) as total')
                ->groupBy('date', 'name')
                ->get();
        }

        $days = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $days[$key] = ['date' => $key, 'answers' => 0, 'feedback' => 0, 'noMatch' => 0];
            $cursor->addDay();
        }

        foreach ($rows as $row) {
            $key = (string) $row->date;
            if (! isset($days[$key])) {
                continue;
            }
            $count = (int) $row->total;
            if ($row->name === 'sita.answer') {
                $days[$key]['answers'] += $count;
            } elseif ($row->name === 'sita.feedback') {
                $days[$key]['feedback'] += $count;
            } elseif ($row->name === 'sita.no_match') {
                $days[$key]['noMatch'] += $count;
            }
        }

        return array_values($days);
    }

    private function feedbackSegments(Collection $feedback): array
    {
        $segments = [];

        foreach (['id', 'en', null] as $locale) {
            foreach (self::TOPICS as $topic) {
                $group = $feedback->filter(
                    fn (SitaAnalyticsEvent $event): bool => $event->locale === $locale
                        && $event->topic === $topic,
                );
                if ($group->isEmpty()) {
                    continue;
                }

                $helpful = $group->where('helpful', true)->count();
                $segments[] = [
                    'locale' => $locale ?? 'unknown',
                    'topic' => $topic,
                    'feedback' => $group->count(),
                    'helpful' => $helpful,
                    'unhelpful' => $group->count() - $helpful,
                    'helpfulRate' => $this->percent($helpful, $group->count()),
                ];
            }
        }

        return $segments;
    }

    private function noMatchPatterns(Collection $events): array
    {
        return $events
            ->whereNotNull('query_hash')
            ->groupBy('query_hash')
            ->map(function (Collection $group, string $queryHash): array {
                $last = $group->sortByDesc('occurred_at')->first();
                $topic = $group->last(fn (SitaAnalyticsEvent $event): bool => $event->topic !== null);
                $sample = $group->last(fn (SitaAnalyticsEvent $event): bool => $event->query_sample !== null);

                return [
                    'queryHash' => $queryHash,
                    'count' => $group->count(),
                    'topic' => $topic?->topic,
                    'sample' => $sample?->query_sample,
                    'fallback' => (bool) $last->fallback,
                    'lastAt' => $this->iso($last->occurred_at),
                ];
            })
            ->sort(fn (array $left, array $right): int => [$right['count'], $right['lastAt']] <=> [$left['count'], $left['lastAt']])
            ->take(10)
            ->values()
            ->all();
    }

    private function sanitizeEvent(SitaAnalyticsEvent $event): array
    {
        $data = [
            'at' => $this->iso($event->occurred_at),
            'name' => $event->name,
        ];

        foreach ([
            'topic' => 'topic',
            'locale' => 'locale',
            'confidence' => 'confidence',
            'fallback' => 'fallback',
            'clarify' => 'clarify',
            'helpful' => 'helpful',
            'response_ms' => 'responseMs',
            'query_hash' => 'queryHash',
            'query_sample' => 'sample',
        ] as $column => $key) {
            if ($event->{$column} !== null) {
                $data[$key] = $event->{$column};
            }
        }

        return $data;
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function percent(int $part, int $total): float
    {
        return $total > 0 ? round(($part / $total) * 100, 1) : 0.0;
    }

    private function iso(CarbonInterface $timestamp): string
    {
        return $timestamp->utc()->format('Y-m-d\TH:i:s.v\Z');
    }
}
