<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Sita\SitaAnalyticsService;

class SitaAnalyticsController extends Controller
{
    public function index(SitaAnalyticsService $analytics)
    {
        abort_unless(auth()->user()?->can('sita.analytics.view'), 404);

        $summary = $analytics->summary();

        return view('pages.sita.analytics.index', compact('summary'));
    }
}
