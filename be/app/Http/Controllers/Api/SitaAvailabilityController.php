<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package\PackageBlackoutDate;
use App\Services\EventDateReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SitaAvailabilityController extends Controller
{
    public function show(
        Request $request,
        EventDateReservationService $reservations,
    ): JsonResponse {
        $validated = Validator::make($request->query(), [
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ])->validate();
        $date = $validated['date'];

        $blackouts = PackageBlackoutDate::query()
            ->whereDate('date', $date)
            ->whereHas('package', fn ($query) => $query->published()->wedding())
            ->with('package:uuid,name')
            ->orderBy('package_uuid')
            ->limit(20)
            ->get()
            ->map(fn (PackageBlackoutDate $blackout): array => [
                'packageName' => $blackout->package?->name ?? 'Paket Sagita',
                'reason' => $blackout->reason,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'reserved' => $reservations->isUnavailable($date),
                'blackouts' => $blackouts,
            ],
        ])->header('Cache-Control', 'no-store');
    }
}
