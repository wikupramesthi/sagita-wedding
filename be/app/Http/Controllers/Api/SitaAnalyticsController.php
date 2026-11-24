<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaAnalyticsEvent;
use App\Services\Sita\SitaAnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SitaAnalyticsController extends Controller
{
    private const NAMES = ['sita.answer', 'sita.feedback', 'sita.no_match'];

    private const TOPICS = ['packages', 'venues', 'vendors'];

    private const ALIASES = [
        'turnId' => 'turn_id',
        'queryHash' => 'query_hash',
        'querySample' => 'query_sample',
        'responseMs' => 'response_ms',
    ];

    private const ALLOWED_FIELDS = [
        'kind',
        'name',
        'topic',
        'query_hash',
        'query_sample',
        'turn_id',
        'confidence',
        'fallback',
        'clarify',
        'helpful',
        'response_ms',
        'path',
        'occurred_at',
    ];

    public function storeEvent(Request $request): JsonResponse|Response
    {
        $payload = $request->all();

        foreach (self::ALIASES as $alias => $field) {
            if (array_key_exists($alias, $payload) && ! array_key_exists($field, $payload)) {
                $payload[$field] = $payload[$alias];
            }
        }

        $unknown = array_diff(array_keys($payload), array_merge(self::ALLOWED_FIELDS, array_keys(self::ALIASES)));

        if ($unknown !== []) {
            throw ValidationException::withMessages([
                'payload' => 'Payload telemetry tidak dikenal.',
            ]);
        }

        $validated = Validator::make($payload, [
            'kind' => ['nullable', Rule::in(['event'])],
            'name' => ['required', 'string', Rule::in(self::NAMES)],
            'topic' => ['nullable', 'string', Rule::in(self::TOPICS)],
            'query_hash' => ['nullable', 'string', 'max:32', 'regex:/\A[a-z0-9]+\z/'],
            'query_sample' => ['nullable', 'string', 'max:500'],
            'turn_id' => ['nullable', 'string', 'max:80', 'regex:/\A[A-Za-z0-9_-]+\z/'],
            'confidence' => ['nullable', 'numeric', 'between:0,1'],
            'fallback' => ['nullable', 'boolean'],
            'clarify' => ['nullable', 'boolean'],
            'helpful' => ['nullable', 'boolean'],
            'response_ms' => ['nullable', 'integer', 'min:0', 'max:600000'],
            'path' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/\A\/[A-Za-z0-9._~!$&()*+,;=:@%\/-]*\z/',
                'not_regex:/\.\./',
            ],
            'occurred_at' => ['nullable', 'date'],
            'turnId' => ['nullable', 'string', 'max:80'],
            'queryHash' => ['nullable', 'string', 'max:32'],
            'querySample' => ['nullable', 'string', 'max:500'],
            'responseMs' => ['nullable'],
        ])->validate();

        $validated['path'] = $validated['path'] ?? '/';
        $validated['occurred_at'] = isset($validated['occurred_at'])
            ? Carbon::parse($validated['occurred_at'])
            : now();

        foreach (['fallback', 'clarify', 'helpful'] as $flag) {
            if (array_key_exists($flag, $validated) && $validated[$flag] !== null) {
                $validated[$flag] = (bool) $validated[$flag];
            }
        }

        if (isset($validated['confidence'])) {
            $validated['confidence'] = (float) $validated['confidence'];
        }

        if (isset($validated['response_ms'])) {
            $validated['response_ms'] = (int) $validated['response_ms'];
        }

        $sample = $this->cleanSample($validated['query_sample'] ?? null);
        if ($sample !== null) {
            $validated['query_sample'] = $sample;
        } else {
            unset($validated['query_sample']);
        }

        $turnId = $validated['turn_id'] ?? null;

        if ($turnId === null) {
            SitaAnalyticsEvent::create($validated);

            return response()->json([
                'success' => true,
                'status' => 'success',
                'deduplicated' => false,
            ], 202);
        }

        $lock = Cache::lock('sita_event_'.hash('sha256', $validated['name'].'|'.$turnId), 30);

        if (! $lock->get()) {
            return response()->noContent();
        }

        try {
            $duplicate = SitaAnalyticsEvent::query()
                ->where('name', $validated['name'])
                ->where('turn_id', $turnId)
                ->exists();

            if ($duplicate) {
                return response()->noContent();
            }

            SitaAnalyticsEvent::create($validated);
        } finally {
            $lock->release();
        }

        return response()->json([
            'success' => true,
            'status' => 'success',
            'deduplicated' => false,
        ], 202);
    }

    private function cleanSample(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $sample = preg_replace('~https?://\S+~i', '[tautan]', $value) ?? '';
        $sample = preg_replace('~\S+@\S+\.\S+~', '[email]', $sample) ?? '';
        $sample = preg_replace('~\b\d(?:[\s().-]?\d){9,}\b~', '[nomor]', $sample) ?? '';
        $sample = preg_replace('~[\x00-\x1F\x7F]~', ' ', $sample) ?? '';
        $sample = trim(preg_replace('~\s+~u', ' ', $sample) ?? '');

        return $sample !== '' ? mb_substr($sample, 0, 160) : null;
    }

    public function index(): JsonResponse
    {
        $summary = app(SitaAnalyticsService::class)->summary();

        return response()->json($summary)->header('Cache-Control', 'no-store');
    }
}
