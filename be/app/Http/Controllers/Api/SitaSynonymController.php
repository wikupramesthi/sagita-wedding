<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaSynonym;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SitaSynonymController extends Controller
{
    public function index(): JsonResponse
    {
        $synonyms = Cache::remember(SitaSynonym::CACHE_KEY, now()->addMinutes(10), fn (): array => SitaSynonym::query()
            ->where('is_active', true)
            ->orderBy('locale')
            ->orderBy('term')
            ->orderBy('sort_order')
            ->get(['locale', 'term', 'canonical', 'topic', 'sort_order'])
            ->all());

        return response()->json([
            'success' => true,
            'status' => 'success',
            'data' => $synonyms,
        ])->header('Cache-Control', 'no-store');
    }
}
