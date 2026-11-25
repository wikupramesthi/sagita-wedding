<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationReceivedMail;
use App\Models\Consultation;
use App\Services\TelegramAdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    private const CAPTCHA_TTL_MINUTES = 10;

    public function captcha(Request $request)
    {
        $answer = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $english = $request->query('lang') === 'en';

        $captchaId = Str::uuid()->toString();
        Cache::put(
            'contact_captcha_'.$captchaId,
            ['answer' => $answer],
            now()->addMinutes(self::CAPTCHA_TTL_MINUTES)
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'captcha_id' => $captchaId,
                'question' => $english
                    ? 'Enter the 4-digit code shown.'
                    : 'Masukkan 4 angka berikut.',
                'image' => $this->captchaImage($answer),
            ],
        ]);
    }

    private function captchaImage(string $answer): string
    {
        $segments = [
            '0' => ['a', 'b', 'c', 'd', 'e', 'f'],
            '1' => ['b', 'c'],
            '2' => ['a', 'b', 'g', 'e', 'd'],
            '3' => ['a', 'b', 'g', 'c', 'd'],
            '4' => ['f', 'g', 'b', 'c'],
            '5' => ['a', 'f', 'g', 'c', 'd'],
            '6' => ['a', 'f', 'g', 'e', 'c', 'd'],
            '7' => ['a', 'b', 'c'],
            '8' => ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
            '9' => ['a', 'b', 'c', 'd', 'f', 'g'],
        ];
        $lines = [
            'a' => [4, 2, 20, 2],
            'b' => [21, 3, 21, 17],
            'c' => [21, 21, 21, 35],
            'd' => [4, 36, 20, 36],
            'e' => [3, 21, 3, 35],
            'f' => [3, 3, 3, 17],
            'g' => [4, 19, 20, 19],
        ];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="48" viewBox="0 0 160 48">';
        $svg .= '<rect width="160" height="48" rx="8" fill="#fffefd"/>';

        for ($i = 0; $i < 4; $i++) {
            $svg .= '<g transform="translate('.(7 + $i * 38).' 5) rotate('.random_int(-9, 9).' 12 19)" stroke="#403a34" stroke-width="3.5" stroke-linecap="round">';
            foreach ($segments[$answer[$i]] as $segment) {
                $svg .= '<line x1="'.$lines[$segment][0].'" y1="'.$lines[$segment][1].'" x2="'.$lines[$segment][2].'" y2="'.$lines[$segment][3].'"/>';
            }
            $svg .= '</g>';
        }

        for ($i = 0; $i < 4; $i++) {
            $svg .= '<path d="M'.random_int(0, 160).' '.random_int(0, 48).' l'.random_int(-18, 18).' '.random_int(-8, 8).'" stroke="#b79f80" stroke-width="1" opacity=".55"/>';
        }

        $svg .= '</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255',
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'isi' => 'required|string|max:5000',
            'captcha_id' => 'required|uuid',
            'captcha_answer' => ['required', 'string', 'regex:/^\d{4}$/'],
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
        $cacheKey = 'contact_captcha_'.$data['captcha_id'];
        $challengeLock = Cache::lock('contact_captcha_lock_'.$data['captcha_id'], 60);

        if (! $challengeLock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi sedang diproses. Silakan coba kembali.',
            ], 422);
        }

        try {
            $captcha = Cache::pull($cacheKey);
        } finally {
            $challengeLock->release();
        }

        if (! is_array($captcha) || ! array_key_exists('answer', $captcha)) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi sudah kedaluwarsa atau telah digunakan. Silakan muat ulang.',
            ], 422);
        }

        if (! hash_equals((string) $captcha['answer'], (string) $data['captcha_answer'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jawaban verifikasi salah. Silakan muat ulang.',
            ], 422);
        }

        $fullName = strip_tags(trim($data['nama']));
        $email = strtolower(trim($data['email']));
        $whatsapp = trim($data['no_telp']);
        $concept = strip_tags(trim($data['isi']));

        $consultation = DB::transaction(function () use ($fullName, $email, $whatsapp, $concept) {
            if (Consultation::findDuplicates(null, [
                'email' => $email,
                'whatsapp' => $whatsapp,
            ], true)->isNotEmpty()) {
                return null;
            }

            return Consultation::create([
                'full_name' => $fullName,
                'email' => $email,
                'whatsapp' => $whatsapp,
                'wedding_concept' => $concept,
                'consultation_status' => 'Pending',
                'lead_status' => 'New',
                'source' => 'website',
            ]);
        });
        $isDuplicate = $consultation === null;

        if ($consultation) {
            try {
                app(TelegramAdminNotifier::class)->newConsultation($consultation);
            } catch (Throwable $exception) {
                Log::error('Telegram consultation notification failed: '.$exception->getMessage());
            }

            try {
                Mail::to($email)->send(new ConsultationReceivedMail($fullName));
            } catch (Throwable $exception) {
                Log::error('Consultation confirmation email failed: '.$exception->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => $isDuplicate
                ? 'Permintaan Anda sebelumnya sudah kami terima. Tim kami akan segera menghubungi Anda.'
                : 'Pesan berhasil dikirim. Kami akan segera menghubungi Anda.',
            'duplicate' => $isDuplicate,
        ], 201);
    }
}
