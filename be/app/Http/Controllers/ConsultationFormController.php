<?php

namespace App\Http\Controllers;

use App\Mail\ConsultationReceivedMail;
use App\Models\Consultation;
use App\Services\TelegramAdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class ConsultationFormController extends Controller
{
    private const BUDGET_OPTIONS = [
        'Di bawah Rp100 juta',
        'Rp100–250 juta',
        'Rp250–500 juta',
        'Di atas Rp500 juta',
        'Masih mencari gambaran',
    ];

    private const SERVICE_OPTIONS = [
        'Perencanaan pernikahan menyeluruh',
        'Koordinasi di hari pernikahan',
        'Rekomendasi venue dan vendor',
        'Konsultasi anggaran dan paket',
    ];

    public function create()
    {
        $captchaId = Str::uuid()->toString();
        $captchaAnswer = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        Cache::put(
            'consultation_form_captcha_'.$captchaId,
            $captchaAnswer,
            now()->addMinutes(10)
        );

        return view('pages.public.consultation-form', [
            'captchaId' => $captchaId,
            'captchaImage' => $this->captchaImage($captchaAnswer),
            'guestEstimates' => Consultation::GUEST_ESTIMATES,
            'budgetOptions' => self::BUDGET_OPTIONS,
            'serviceOptions' => self::SERVICE_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'wedding_date' => ['nullable', 'date'],
            'wedding_city' => ['nullable', 'string', 'max:100'],
            'venue' => ['nullable', 'string', 'max:255'],
            'guest_estimate' => ['nullable', Rule::in(Consultation::GUEST_ESTIMATES)],
            'budget' => ['nullable', Rule::in(self::BUDGET_OPTIONS)],
            'services' => ['sometimes', 'array', 'max:4'],
            'services.*' => ['string', Rule::in(self::SERVICE_OPTIONS)],
            'message' => ['required', 'string', 'max:2000'],
            'captcha_id' => ['required', 'uuid'],
            'captcha_answer' => ['required', 'string', 'regex:/^\d{4}$/'],
            'website' => ['nullable', 'string', 'max:0'],
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'email' => 'Format email tidak valid.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'regex' => 'Format :attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'in' => 'Pilihan :attribute tidak valid.',
            'array' => ':attribute tidak valid.',
            'uuid' => 'Verifikasi sudah tidak valid. Muat ulang halaman, lalu coba kembali.',
            'captcha_answer.regex' => 'Masukkan 4 angka pada gambar verifikasi.',
            'website.max' => 'Permintaan tidak dapat diproses.',
        ], [
            'full_name' => 'Nama lengkap',
            'email' => 'Alamat email',
            'whatsapp' => 'Nomor WhatsApp',
            'wedding_date' => 'Perkiraan tanggal pernikahan',
            'wedding_city' => 'Kota pernikahan',
            'venue' => 'Lokasi atau venue',
            'guest_estimate' => 'Perkiraan jumlah tamu',
            'budget' => 'Kisaran anggaran',
            'services' => 'Layanan',
            'message' => 'Pesan',
            'captcha_id' => 'Verifikasi',
            'captcha_answer' => 'Kode verifikasi',
        ]);

        $cacheKey = 'consultation_form_captcha_'.$data['captcha_id'];
        $captchaLock = Cache::lock('consultation_form_captcha_lock_'.$data['captcha_id'], 60);

        if (! $captchaLock->get()) {
            return back()
                ->withInput($request->except(['captcha_answer', 'captcha_id']))
                ->withErrors(['captcha_answer' => 'Verifikasi sedang diproses. Silakan coba kembali.']);
        }

        try {
            $expectedAnswer = Cache::pull($cacheKey);
        } finally {
            $captchaLock->release();
        }

        if (! is_string($expectedAnswer) || ! hash_equals($expectedAnswer, $data['captcha_answer'])) {
            return back()
                ->withInput($request->except(['captcha_answer', 'captcha_id']))
                ->withErrors(['captcha_answer' => 'Verifikasi kedaluwarsa atau salah. Muat ulang halaman, lalu coba kembali.']);
        }

        $data['full_name'] = strip_tags(trim($data['full_name']));
        $data['email'] = strtolower(trim($data['email']));
        $data['whatsapp'] = trim($data['whatsapp']);
        $data['wedding_city'] = strip_tags(trim((string) ($data['wedding_city'] ?? '')));
        $data['venue'] = strip_tags(trim((string) ($data['venue'] ?? '')));
        $data['message'] = strip_tags(trim($data['message']));

        $duplicateCheck = [
            'email' => $data['email'],
            'whatsapp' => $data['whatsapp'],
        ];

        $consultation = DB::transaction(function () use ($data, $duplicateCheck) {
            if (Consultation::findDuplicates(null, $duplicateCheck, true)->isNotEmpty()) {
                return null;
            }

            return Consultation::create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'whatsapp' => $data['whatsapp'],
                'wedding_date' => $data['wedding_date'] ?? null,
                'guest_estimate' => $data['guest_estimate'] ?? null,
                'venue' => $data['venue'] ?: null,
                'wedding_concept' => $this->buildWeddingConcept($data),
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
                Mail::to($data['email'])->send(new ConsultationReceivedMail($data['full_name']));
            } catch (Throwable $exception) {
                Log::error('Consultation confirmation email failed: '.$exception->getMessage());
            }
        }

        $message = $isDuplicate
            ? 'Permintaan Anda sebelumnya sudah kami terima. Tim kami akan segera menghubungi Anda.'
            : 'Terima kasih. Permintaan konsultasi Anda telah kami terima dan akan segera ditindaklanjuti.';

        return redirect()->route('consultation.form')->with('success', $message);
    }

    private function buildWeddingConcept(array $data): string
    {
        $details = [
            'Kota pernikahan: '.($data['wedding_city'] ?: '-'),
            'Kisaran anggaran: '.($data['budget'] ?? '-'),
        ];

        if (! empty($data['services'])) {
            $details[] = 'Layanan yang diminati: '.implode(', ', array_unique($data['services']));
        }

        $details[] = '';
        $details[] = $data['message'];

        return implode("\n", array_filter($details, fn ($line) => $line !== null));
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
}
