<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Package\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $method = (string) $request->query('method', '');

        if (! array_key_exists($status, Consultation::STATUS_LABELS)) {
            $status = '';
        }

        if (! array_key_exists($method, Consultation::METHOD_LABELS)) {
            $method = '';
        }

        $consultations = Consultation::query()
            ->with(['package:id,name', 'handler:id,name'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('consultation_status', $status))
            ->when($method !== '', fn ($query) => $query->where('consultation_method', $method))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Consultation::count(),
            'pending' => Consultation::where('consultation_status', 'Pending')->count(),
            'confirmed' => Consultation::where('consultation_status', 'Confirmed')->count(),
            'completed' => Consultation::where('consultation_status', 'Completed')->count(),
        ];

        return view('pages.konsultasi.index', compact(
            'consultations',
            'stats',
            'search',
            'status',
            'method'
        ) + $this->formOptions());
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

        return view('pages.konsultasi.show', compact('consultation'));
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
            'admins' => User::role(['super-admin', 'admin'])
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }

    private function validateConsultation(Request $request): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,20}$/'],
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
            'source' => ['required', 'string', 'max:50'],
            'converted_to_booking_at' => ['nullable', 'date'],
        ], $this->validationMessages(), $this->validationAttributes());

        foreach (['full_name', 'whatsapp', 'venue', 'wedding_concept', 'admin_notes', 'source'] as $field) {
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
