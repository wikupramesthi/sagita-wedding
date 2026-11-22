<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\CheckoutSession;
use Illuminate\Support\Facades\Auth;

class OrderWorkflowAnalyticsController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->hasAnyRole(['super-admin', 'admin']), 403);

        $sessions = CheckoutSession::with('package:uuid,name')
            ->where('started_at', '>=', now()->subDays(30))
            ->get();
        $matureSessions = $sessions->filter(fn (CheckoutSession $session) => $session->started_at->lte(now()->subHours(24)));
        $started = $sessions->count();
        $completed = $sessions->where('status', 'completed')->count();
        $abandoned = $matureSessions->where('status', 'started')->count();
        $conversionRate = $started > 0 ? round($completed / $started * 100, 1) : 0;

        $packageStats = $sessions->groupBy('package_uuid')->map(function ($packageSessions) use ($matureSessions) {
            $packageUuid = $packageSessions->first()->package_uuid;
            $maturePackageSessions = $matureSessions->where('package_uuid', $packageUuid);

            return [
                'name' => $packageSessions->first()->package?->name ?? 'Paket dihapus',
                'started' => $packageSessions->count(),
                'completed' => $packageSessions->where('status', 'completed')->count(),
                'abandoned' => $maturePackageSessions->where('status', 'started')->count(),
            ];
        })->sortByDesc('started')->values();

        return view('pages.orders.analytics', compact(
            'started',
            'completed',
            'abandoned',
            'conversionRate',
            'packageStats'
        ));
    }
}
