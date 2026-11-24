<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc', 'max:255'],
            'website' => ['nullable', 'string', 'max:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $email = strtolower(trim($data['email']));

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $email],
            ['source' => 'website'],
        );

        return response()->json([
            'success' => true,
            'message' => $subscriber->wasRecentlyCreated
                ? 'Terima kasih, email Anda telah terdaftar.'
                : 'Email Anda sudah terdaftar.',
            'duplicate' => ! $subscriber->wasRecentlyCreated,
        ], 201);
    }
}
