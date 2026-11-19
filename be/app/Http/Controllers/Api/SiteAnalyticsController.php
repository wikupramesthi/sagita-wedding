<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        DB::table('site_visits')->insert([
            ...$validated,
            'visited_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success'], 202);
    }
}
