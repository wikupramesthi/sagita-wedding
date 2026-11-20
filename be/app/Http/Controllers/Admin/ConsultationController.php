<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Package\Package;
use App\Models\User;
use App\Models\WebsiteIdentity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $filters = Consultation::normalizeFilters($request->query());

        $query = Consultation::query()->with(['package:id,name', 'handler:id,name']);
        Consultation::applyFilters($query, $filters);

        $consultations = $query->latest()->paginate(10)->withQueryString();
        $stats = Consultation::stats();
        $sources = Consultation::query()
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source')
            ->mapWithKeys(fn ($value) => [
                $value => Consultation::SOURCE_LABELS[$value] ?? $value,
            ]);
        $admins = $this->adminOptions();

        return view('pages.konsultasi.index', compact(
            'consultations',
            'stats',
            'filters',
            'sources',
            'admins'
        ));
    }

    public function pdfPreview(Request $request)
    {
        return view('pages.konsultasi.pdf-preview', $this->pdfData($request));
    }

    public function pdfStream(Request $request)
    {
        $data = $this->pdfData($request);

        return Pdf::loadView('pages.konsultasi.pdf-document', $data)
            ->setPaper('a4', $data['mode'] === 'detail' ? 'portrait' : 'landscape')
            ->stream($this->pdfFilename($data));
    }

    public function pdfDownload(Request $request)
    {
        $data = $this->pdfData($request);

        return Pdf::loadView('pages.konsultasi.pdf-document', $data)
            ->setPaper('a4', $data['mode'] === 'detail' ? 'portrait' : 'landscape')
            ->download($this->pdfFilename($data));
    }

    public function create()
    {
        return view('pages.konsultasi.create', $this->formOptions());
    }

    public function store(Request $request)
    {
        $data = $this->validateConsultation($request);

        DB::transaction(fn () => Consultation::create($data));

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil ditambahkan.');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['package:id,name', 'handler:id,name']);

        return view('pages.konsultasi.show', [
            'consultation' => $consultation,
            'admins' => $this->adminOptions(),
        ]);
    }

    public function edit(Consultation $consultation)
    {
        return view('pages.konsultasi.edit', compact('consultation') + $this->formOptions());
    }

    public function update(Request $request, Consultation $consultation)
    {
        $data = $this->validateConsultation($request);

        DB::transaction(fn () => $consultation->update($data));

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil diperbarui.');
    }

    public function updateLead(Request $request, Consultation $consultation)
    {
        $data = $request->validate([
            'lead_status' => ['required', Rule::in(array_keys(Consultation::LEAD_STATUS_LABELS))],
            'handled_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'follow_up_at' => ['nullable', 'date'],
        ], $this->validationMessages(), $this->validationAttributes());

        $data['converted_to_booking_at'] = $data['lead_status'] === 'Converted'
            ? ($consultation->converted_to_booking_at ?? now())
            : null;

        DB::transaction(fn () => $consultation->update($data));

        return back()->with('success', 'Pipeline calon pelanggan berhasil diperbarui.');
    }

    public function destroy(Consultation $consultation)
    {
        DB::transaction(fn () => $consultation->delete());

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil dihapus.');
    }

    private function formOptions(): array
    {
        return [
            'packages' => Package::query()
                ->where('is_active', 'active')
                ->orderBy('name')
                ->get(['id', 'name']),
            'admins' => $this->adminOptions(),
        ];
    }

    private function adminOptions()
    {
        return User::role(['super-admin', 'admin'])
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function pdfData(Request $request): array
    {
        $filters = Consultation::normalizeFilters($request->query());
        $uuid = trim((string) $request->query('uuid', ''));
        $siteName = WebsiteIdentity::singleton()->site_name ?: 'Sagita Wedding';
        $generatedAt = now()->translatedFormat('d F Y H:i');
        $generatedBy = auth()->user()?->name ?: 'Admin';

        if ($uuid !== '') {
            return [
                'mode' => 'detail',
                'consultation' => Consultation::with(['package:id,name', 'handler:id,name'])
                    ->where('uuid', $uuid)
                    ->firstOrFail(),
                'siteName' => $siteName,
                'generatedAt' => $generatedAt,
                'generatedBy' => $generatedBy,
                'filters' => $filters,
            ];
        }

        $query = Consultation::query()->with(['package:id,name', 'handler:id,name']);
        Consultation::applyFilters($query, $filters);

        return [
            'mode' => 'rekap',
            'consultations' => $query->latest()->get(),
            'stats' => Consultation::stats(),
            'siteName' => $siteName,
            'generatedAt' => $generatedAt,
            'generatedBy' => $generatedBy,
            'filters' => $filters,
        ];
    }

    private function pdfFilename(array $data): string
    {
        $suffix = now()->format('Ymd-His');

        return $data['mode'] === 'detail'
            ? 'konsultasi-'.$data['consultation']->uuid.'-'.$suffix.'.pdf'
            : 'rekap-konsultasi-'.$suffix.'.pdf';
    }

    private function validateConsultation(Request $request): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'wedding_date' => ['nullable', 'date'],
            'guest_estimate' => ['nullable', Rule::in(Consultation::GUEST_ESTIMATES)],
            'venue' => ['nullable', 'string', 'max:255'],
            'package_id' => ['nullable', 'integer', Rule::exists('packages', 'id')],
            'wedding_concept' => ['nullable', 'string'],
            'consultation_date' => ['nullable', 'date'],
            'consultation_time' => ['nullable', 'date_format:H:i'],
            'consultation_method' => ['nullable', Rule::in(array_keys(Consultation::METHOD_LABELS))],
            'consultation_status' => ['required', Rule::in(array_keys(Consultation::STATUS_LABELS))],
            'admin_notes' => ['nullable', 'string'],
            'handled_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'follow_up_at' => ['nullable', 'date'],
            'lead_status' => ['required', Rule::in(array_keys(Consultation::LEAD_STATUS_LABELS))],
            'source' => ['required', Rule::in(array_keys(Consultation::SOURCE_LABELS))],
            'converted_to_booking_at' => ['nullable', 'date'],
        ], $this->validationMessages(), $this->validationAttributes());

        foreach (['full_name', 'whatsapp', 'venue', 'wedding_concept', 'admin_notes'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = strip_tags((string) $data[$field]);
            }
        }

        return $data;
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'regex' => 'Format :attribute tidak valid.',
            'email' => 'Format :attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'date_format' => ':attribute harus berupa jam yang valid.',
            'integer' => ':attribute harus berupa angka.',
            'in' => 'Pilihan :attribute tidak valid.',
            'exists' => ':attribute tidak ditemukan.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'full_name' => 'nama calon pengantin',
            'whatsapp' => 'nomor WhatsApp',
            'email' => 'email calon pelanggan',
            'wedding_date' => 'rencana tanggal pernikahan',
            'guest_estimate' => 'estimasi jumlah tamu',
            'venue' => 'lokasi pernikahan',
            'package_id' => 'paket',
            'wedding_concept' => 'konsep pernikahan',
            'consultation_date' => 'tanggal konsultasi',
            'consultation_time' => 'jam konsultasi',
            'consultation_method' => 'metode konsultasi',
            'consultation_status' => 'status konsultasi',
            'admin_notes' => 'catatan admin',
            'handled_by' => 'admin penanggung jawab',
            'follow_up_at' => 'jadwal tindak lanjut',
            'lead_status' => 'status calon pelanggan',
            'source' => 'sumber calon pelanggan',
            'converted_to_booking_at' => 'waktu konversi booking',
        ];
    }
}
