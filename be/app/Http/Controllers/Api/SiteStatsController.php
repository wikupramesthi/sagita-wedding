<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BatchVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Statistik publik untuk frontend (angka agregat saja, tanpa data personal).
 * Query selalu fresh; ETag/no-cache membuat perubahan data langsung terlihat.
 */
class SiteStatsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $etag = md5('site-stats|'.BatchVersionService::current());

            $preflight = response()->json([], 200);
            $preflight->setEtag($etag);
            $preflight->headers->set('Cache-Control', 'no-cache, must-revalidate');

            if ($preflight->isNotModified($request)) {
                return $preflight;
            }

            $data = [
                'members' => User::query()
                    ->whereHas('roles', fn ($query) => $query->where('name', 'user'))
                    ->count(),
                'vendors' => User::query()
                    ->whereHas('roles', fn ($query) => $query->where('name', 'vendor'))
                    ->count(),
            ];

            $response = response()->json([
                'status' => 'success',
                'message' => 'Site statistics retrieved successfully',
                'data' => $data,
            ], 200);

            $response->setEtag($etag);
            $response->headers->set('Cache-Control', 'no-cache, must-revalidate');

            return $response;
        } catch (\Exception $e) {
            Log::error('Site stats fetch error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch site statistics',
                'data' => null,
            ], 500);
        }
    }
}
