<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class FaqController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $requested = $request->query('kategori');
        $kategori = is_string($requested) && in_array($requested, Faq::CATEGORIES, true)
            ? $requested
            : null;

        try {
            $faqs = Faq::query()
                ->where('status', 'active')
                ->when($kategori !== null, fn ($query) => $query->where('kategori', $kategori))
                ->orderByKategori()
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'FAQ list retrieved successfully.',
                'data' => FaqResource::collection($faqs),
                'meta' => [
                    'total' => $faqs->count(),
                    'kategori' => $kategori,
                    'categories' => Faq::CATEGORIES,
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('FAQ fetch error', [
                'message' => $e->getMessage(),
                'kategori' => $kategori,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch FAQs.',
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'kategori' => $kategori,
                    'categories' => Faq::CATEGORIES,
                ],
            ], 500);
        }
    }
}
