<?php

namespace App\Services\Sita;

use App\Models\Sita\SitaAnalyticsEvent;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class SitaAnalyticsService
{
    public const MAX_EVENTS = 500;

    public const TOPICS = ['packages', 'venues', 'vendors'];

    public function summary(): array
    {
        $events = SitaAnalyticsEvent::query()
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
            'noMatchPatterns' => $this->noMatchPatterns($noMatch),
            'recent' => $events->slice(-20)->reverse()->map(fn (SitaAnalyticsEvent $event): array => $this->sanitizeEvent($event))->values()->all(),
        ];
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

    private function percent(int $part, int $total): float
    {
        return $total > 0 ? round(($part / $total) * 100, 1) : 0.0;
    }

    private function iso(CarbonInterface $timestamp): string
    {
        return $timestamp->utc()->format('Y-m-d\TH:i:s.v\Z');
    }
}
