<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NormalizeApiResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response instanceof JsonResponse || $response->getStatusCode() === 304) {
            return $response;
        }

        $body = $response->getData(true);
        if (! is_array($body)) {
            return $response;
        }

        $statusCode = $response->getStatusCode();
        $hasStatus = array_key_exists('status', $body);
        $hasSuccess = array_key_exists('success', $body);
        $isError = $statusCode >= 400
            || ($hasStatus && $body['status'] === 'error')
            || ($hasSuccess && $body['success'] === false);

        $message = is_string($body['message'] ?? null)
            ? $body['message']
            : match (true) {
                $statusCode === 202 => 'Request accepted.',
                $statusCode === 204 => 'No content.',
                $isError => 'Request failed.',
                default => 'Request successful.',
            };

        $envelopeKeys = ['success', 'status', 'message', 'data', 'meta', 'errors'];
        $payload = $body['data'] ?? null;
        $payload = is_array($payload) ? $payload : [];

        foreach ($body as $key => $value) {
            if (in_array($key, $envelopeKeys, true)) {
                continue;
            }
            if (! array_key_exists($key, $payload)) {
                $payload[$key] = $value;
            }
        }

        $normalized = [
            'success' => ! $isError,
            'status' => $isError ? 'error' : 'success',
            'message' => $message,
            'data' => $payload,
        ];

        if (array_key_exists('meta', $body)) {
            $normalized['meta'] = $body['meta'];
        }

        if (array_key_exists('errors', $body)) {
            $normalized['errors'] = $body['errors'];
        }

        $response->setData($normalized);

        return $response;
    }
}
