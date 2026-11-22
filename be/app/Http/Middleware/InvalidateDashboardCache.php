<?php

namespace App\Http\Middleware;

use App\Support\DashboardCache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidateDashboardCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            && $response->getStatusCode() >= 200
            && $response->getStatusCode() < 300) {
            DashboardCache::invalidate();
        }

        return $response;
    }
}
