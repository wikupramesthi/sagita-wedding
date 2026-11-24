<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $configured = array_values(array_unique(array_filter(array_map(
            'trim',
            [
                (string) config('services.frontend_api.key'),
                ...explode(',', (string) config('services.frontend_api.keys')),
            ],
        ))));

        $provided = trim((string) $request->header('X-Api-Key', ''));
        if ($provided === '') {
            $authorization = (string) $request->header('Authorization', '');
            if (preg_match('/^Bearer\s+(.+)$/i', $authorization, $matches) === 1) {
                $provided = trim($matches[1]);
            }
        }

        $valid = false;
        foreach ($configured as $key) {
            if (hash_equals($key, $provided)) {
                $valid = true;
                break;
            }
        }

        if ($configured === [] || $provided === '' || ! $valid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}
