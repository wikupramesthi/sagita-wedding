<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiteAnalyticsController extends Controller
{
    public function storeVisit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'visitor_hash' => ['required', 'string', 'size:64', 'regex:/\A[a-f0-9]{64}\z/'],
            'path' => [
                'required',
                'string',
                'max:255',
                'regex:/\A\/[A-Za-z0-9._~!$&()*+,;=:@%\/-]*\z/',
                'not_regex:/\.\./',
            ],
            'device' => ['required', 'string', Rule::in(['mobile', 'tablet', 'desktop'])],
        ]);

        $dedupeKey = 'site_visit_dedup_'.hash('sha256', implode('|', [
            $validated['visitor_hash'],
            $validated['path'],
            $validated['device'],
        ]));
        $lock = Cache::lock($dedupeKey, 60);

        if (! $lock->get()) {
            return response()->json([
                'status' => 'success',
                'deduplicated' => true,
            ], 202);
        }

        try {
            $recentVisit = DB::table('site_visits')
                ->where('visitor_hash', $validated['visitor_hash'])
                ->where('path', $validated['path'])
                ->where('device', $validated['device'])
                ->where('visited_at', '>=', now()->subMinute())
                ->exists();

            if ($recentVisit) {
                return response()->json([
                    'status' => 'success',
                    'deduplicated' => true,
                ], 202);
            }

            DB::table('site_visits')->insert([
                ...$validated,
                'visited_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } finally {
            $lock->release();
        }

        return response()->json([
            'status' => 'success',
            'deduplicated' => false,
        ], 202);
    }
}
