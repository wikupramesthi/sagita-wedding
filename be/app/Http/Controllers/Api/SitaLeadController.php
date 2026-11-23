<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SitaLeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/\A[0-9+\-\s()]{6,30}\z/'],
            'topic' => ['nullable', 'string', Rule::in(['packages', 'venues', 'vendors', 'services', 'other'])],
            'criteria' => ['nullable', 'array', 'max:5'],
            'criteria.budgetLimit' => ['nullable', 'integer', 'min:0', 'max:1000000000000'],
            'criteria.guestCount' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'criteria.city' => ['nullable', 'string', 'max:100'],
            'criteria.eventDate' => ['nullable', 'date'],
            'criteria.vendorCategory' => ['nullable', 'string', 'max:100'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'string', 'max:0'],
        ])->validate();

        $criteria = $validated['criteria'] ?? [];
        $unknown = array_diff(array_keys($request->input('criteria', [])), SitaLead::CRITERIA_KEYS);

        if ($unknown !== []) {
            throw ValidationException::withMessages([
                'criteria' => 'Kriteria tidak dikenal.',
            ]);
        }

        $lead = SitaLead::create([
            'name' => strip_tags(trim($validated['name'])),
            'phone' => trim($validated['phone']),
            'topic' => $validated['topic'] ?? null,
            'criteria' => array_filter($criteria, fn ($value): bool => $value !== null && $value !== ''),
            'score' => (int) $validated['score'],
            'message' => isset($validated['message']) ? strip_tags(trim($validated['message'])) : null,
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'status' => 'success',
            'data' => ['uuid' => $lead->uuid],
        ], 201);
    }
}
