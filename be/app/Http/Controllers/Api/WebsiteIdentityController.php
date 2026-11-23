<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebsiteIdentity;
use App\Services\BatchVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Identitas website publik untuk frontend (nama, kontak, sosmed, SEO, branding).
 * Query selalu fresh; ETag/no-cache membuat perubahan admin langsung terlihat.
 */
class WebsiteIdentityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $identity = WebsiteIdentity::singleton();

            $etag = md5(
                'website-identity|'
                .BatchVersionService::current()
                .'|'.(string) $identity->updated_at?->timestamp
            );

            $preflight = response()->json([], 200);
            $preflight->setEtag($etag);
            $preflight->headers->set('Cache-Control', 'no-cache, must-revalidate');

            if ($preflight->isNotModified($request)) {
                return $preflight;
            }

            $data = [
                'site_name' => $identity->site_name,
                'site_title' => $identity->site_title,
                'tagline' => $identity->tagline,
                'short_description' => $identity->short_description,
                'email' => $identity->email,
                'phone' => $identity->phone,
                'address' => $identity->address,
                'facebook_url' => $identity->facebook_url,
                'instagram_url' => $identity->instagram_url,
                'youtube_url' => $identity->youtube_url,
                'tiktok_url' => $identity->tiktok_url,
                'meta_title' => $identity->meta_title,
                'meta_description' => $identity->meta_description,
                'meta_keywords' => $identity->meta_keywords,
                'google_analytics_id' => $identity->google_analytics_id,
                'google_site_verification' => $identity->google_site_verification,
                'logo_url' => $identity->logoUrl(),
                'logo_white_url' => $identity->logoUrl(),
                'favicon_url' => $identity->faviconUrl(),
                'og_image_url' => $identity->ogImageUrl(),
                'year' => now()->year,
            ];

            $response = response()->json([
                'status' => 'success',
                'message' => 'Identitas website berhasil diambil',
                'data' => $data,
            ], 200);

            $response->setEtag($etag);
            $response->headers->set('Cache-Control', 'no-cache, must-revalidate');

            return $response;
        } catch (\Exception $e) {
            Log::error('Gagal mengambil identitas website: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil identitas website',
                'data' => null,
            ], 500);
        }
    }
}
