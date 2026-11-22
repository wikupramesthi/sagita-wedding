<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 20);

        try {
            $vendors = User::query()
                ->whereHas('roles', fn ($query) => $query->where('name', 'vendor'))
                ->with('vendors_categories')
                ->orderBy('name')
                ->paginate($perPage);

            $vendorData = VendorResource::collection($vendors->items());

            return response()->json([
                'status' => 'success',
                'message' => $vendors->isEmpty()
                    ? 'No vendors available'
                    : 'Vendor data retrieved successfully',

                'data' => $vendorData,
                'vendors' => $vendorData,

                'meta' => [
                    'current_page' => $vendors->currentPage(),
                    'per_page' => $vendors->perPage(),
                    'total' => $vendors->total(),
                    'last_page' => $vendors->lastPage(),
                    'from' => $vendors->firstItem(),
                    'to' => $vendors->lastItem(),
                ],
            ], 200);
        } catch (Throwable $e) {
            \Log::error('Vendor API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Failed to fetch vendor data',
                'data' => [],
                'vendors' => [],
            ], 500);
        }
    }
}
