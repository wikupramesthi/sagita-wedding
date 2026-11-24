<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Sita\SitaAnalyticsService;
use Illuminate\Http\Request;

class SitaAnalyticsController extends Controller
{
    public function index(Request $request, SitaAnalyticsService $analytics)
    {
        abort_unless(auth()->user()?->can('sita.analytics.view'), 404);

        $filters = $analytics->normalizeFilters($request->only(['from', 'to', 'topic', 'locale']));
        $summary = $analytics->summary($filters);

        return view('pages.sita.analytics.index', compact('summary', 'filters'));
    }
}
