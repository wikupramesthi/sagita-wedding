<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ConsultationExport;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Consultation;
use App\Models\Package\Package;
use App\Models\User;
use App\Models\WebsiteIdentity;
use App\Notifications\ConsultationFollowUpNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $filters = Consultation::normalizeFilters($request->query());

        $query = Consultation::query()->with(['package:id,name', 'handler:id,name']);
        Consultation::applyFilters($query, $filters);

        $consultations = $query->latest()->paginate(10)->withQueryString();
        $stats = Consultation::stats($filters);
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

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['string', 'exists:consultations,uuid'],
            'action' => ['required', Rule::in(['lead', 'pic', 'follow-up-done', 'delete'])],
            'lead_status' => ['nullable', Rule::in(array_keys(Consultation::LEAD_STATUS_LABELS))],
            'handled_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
        ], $this->validationMessages(), $this->validationAttributes());

        if ($data['action'] === 'lead' && empty($data['lead_status'])) {
            return back()->with('error', 'Pilih status lead terlebih dahulu.');
        }

        if ($data['action'] === 'pic' && ! $request->exists('handled_by')) {
            return back()->with('error', 'Pilih penanggung jawab terlebih dahulu.');
        }

        $ids = array_values(array_unique(array_filter($data['ids'])));
        $records = Consultation::query()->whereIn('uuid', $ids)->get();

        if ($records->isEmpty()) {
            return back()->with('error', 'Tidak ada data konsultasi yang dipilih.');
        }

        if ($data['action'] === 'delete') {
            $protected = Consultation::withoutGlobalScope('not_merged')
                ->whereIn('merged_into_uuid', $records->pluck('uuid'))
                ->pluck('merged_into_uuid')
                ->unique();

            if ($protected->isNotEmpty()) {
                return back()->with('error', 'Sebagian data tidak bisa dihapus karena masih menjadi target penggabungan.');
            }
        }

        DB::transaction(function () use ($records, $data) {
            foreach ($records as $record) {
                match ($data['action']) {
                    'lead' => $this->applyBulkLead($record, (string) $data['lead_status']),
                    'pic' => $record->update(['handled_by' => $data['handled_by'] ?: null]),
                    'follow-up-done' => $record->update([
                        'lead_status' => $record->lead_status === 'New' ? 'Contacted' : $record->lead_status,
                        'follow_up_at' => null,
                    ]),
                    'delete' => $record->delete(),
                };
            }
        });

        $messages = [
            'lead' => 'status lead diperbarui',
            'pic' => 'penanggung jawab diperbarui',
            'follow-up-done' => 'tindak lanjut ditandai selesai',
            'delete' => 'data dihapus',
        ];

        return back()->with('success', $records->count().' data konsultasi berhasil '.$messages[$data['action']].'.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ConsultationExport($request),
            'konsultasi-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function kanban(Request $request)
    {
        $filters = Consultation::normalizeFilters($request->query());

        $query = Consultation::query()->with(['package:id,name', 'handler:id,name']);
        Consultation::applyFilters($query, $filters);
        $items = $query->latest()->limit(300)->get();

        $columns = collect(Consultation::LEAD_STATUS_LABELS)
            ->mapWithKeys(fn ($label, $key) => [$key => [
                'label' => $label,
                'badge' => (new Consultation(['lead_status' => $key]))->lead_status_badge_class,
                'items' => $items->where('lead_status', $key)->values(),
            ]]);

        return view('pages.konsultasi.kanban', [
            'columns' => $columns,
            'filters' => $filters,
            'stats' => Consultation::stats($filters),
            'sources' => $this->sourceOptions(),
            'admins' => $this->adminOptions(),
        ]);
    }

    public function calendar(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);
        $year = $year >= 2000 && $year <= 2100 ? $year : now()->year;
        $month = $month >= 1 && $month <= 12 ? $month : now()->month;

        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $items = Consultation::query()
            ->with(['package:id,name', 'handler:id,name'])
            ->whereNotNull('consultation_date')
            ->whereBetween('consultation_date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->orderBy('consultation_date')
            ->orderBy('consultation_time')
            ->get();

        $byDate = $items->groupBy(fn ($item) => $item->consultation_date?->toDateString());

        $weeks = [];
        $cursor = $gridStart->copy();

        while ($cursor <= $gridEnd) {
            $week = [];

            for ($day = 1; $day <= 7; $day++) {
                $date = $cursor->copy();
                $key = $date->toDateString();
                $week[] = [
                    'date' => $date,
                    'key' => $key,
                    'in_month' => $date->month === $month && $date->year === $year,
                    'is_today' => $date->isToday(),
                    'items' => $byDate->get($key, collect()),
                ];
                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        $selectedDate = (string) $request->query('date', '');
        $selectedDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : today()->toDateString();
        $selectedItems = $byDate->get($selectedDate, collect());

        $prevMonth = $monthStart->copy()->subMonth();
        $nextMonth = $monthStart->copy()->addMonth();

        return view('pages.konsultasi.calendar', compact(
            'weeks',
            'monthStart',
            'year',
            'month',
            'selectedDate',
            'selectedItems',
            'prevMonth',
            'nextMonth'
        ));
    }

    public function duplicates()
    {
        return view('pages.konsultasi.duplicates', [
            'duplicates' => Consultation::duplicateGroups(),
        ]);
    }

    public function merge(Request $request)
    {
        $data = $request->validate([
            'primary_uuid' => ['required', 'string', 'exists:consultations,uuid'],
            'duplicate_uuid' => ['required', 'string', 'different:primary_uuid', 'exists:consultations,uuid'],
        ], $this->validationMessages(), $this->validationAttributes());

        $records = Consultation::withoutGlobalScope('not_merged')
            ->whereIn('uuid', [$data['primary_uuid'], $data['duplicate_uuid']])
            ->get()
            ->keyBy('uuid');

        $primary = $records->get($data['primary_uuid']);
        $duplicate = $records->get($data['duplicate_uuid']);

        if (! $primary || ! $duplicate) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        if ($primary->merged_into_uuid || $duplicate->merged_into_uuid) {
            return back()->with('error', 'Salah satu data sudah pernah digabungkan. Pilih data aktif.');
        }

        $duplicate->mergeInto($primary);

        return redirect()->route('konsultasi.duplicates')->with('success', 'Data duplikat berhasil digabungkan.');
    }

    public function remind(Consultation $consultation)
    {
        $recipients = $consultation->handler
            ? collect([$consultation->handler])
            : $this->adminOptions();

        if ($recipients->isEmpty()) {
            return back()->with('error', 'Tidak ada penerima pengingat yang tersedia.');
        }

        foreach ($recipients as $recipient) {
            Notification::send($recipient, new ConsultationFollowUpNotification($consultation));
        }

        $consultation->update(['reminder_sent_at' => now()]);

        return back()->with('success', $recipients->count().' pengingat tindak lanjut berhasil dikirim.');
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

        try {
            $result = DB::transaction(function () use ($data) {
                $duplicate = Consultation::findDuplicates(null, $data, true)->first();

                if ($duplicate) {
                    return ['duplicate' => $duplicate];
                }

                return ['consultation' => Consultation::create($data)];
            });
        } catch (QueryException $exception) {
            $duplicate = Consultation::findDuplicates(null, $data)->first();

            if (! $duplicate) {
                throw $exception;
            }

            return $this->duplicateResponse($duplicate);
        }

        if (isset($result['duplicate'])) {
            return $this->duplicateResponse($result['duplicate']);
        }

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil ditambahkan.');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['package:id,name', 'handler:id,name']);

        return view('pages.konsultasi.show', [
            'consultation' => $consultation,
            'admins' => $this->adminOptions(),
            'history' => $this->consultationHistory($consultation),
        ]);
    }

    public function edit(Consultation $consultation)
    {
        return view('pages.konsultasi.edit', compact('consultation') + $this->formOptions());
    }

    public function update(Request $request, Consultation $consultation)
    {
        $data = $this->validateConsultation($request);

        if ($data['lead_status'] === 'Converted' && empty($data['converted_to_booking_at'])) {
            $data['converted_to_booking_at'] = $consultation->converted_to_booking_at ?? now();
        }

        if ($data['lead_status'] !== 'Converted') {
            $data['converted_to_booking_at'] = null;
        }

        try {
            $result = DB::transaction(function () use ($consultation, $data) {
                $duplicate = Consultation::findDuplicates($consultation, $data, true)->first();

                if ($duplicate) {
                    return ['duplicate' => $duplicate];
                }

                $consultation->update($data);

                return ['consultation' => $consultation];
            });
        } catch (QueryException $exception) {
            $duplicate = Consultation::findDuplicates($consultation, $data)->first();

            if (! $duplicate) {
                throw $exception;
            }

            return $this->duplicateResponse($duplicate);
        }

        if (isset($result['duplicate'])) {
            return $this->duplicateResponse($result['duplicate']);
        }

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil diperbarui.');
    }

    public function updateLead(Request $request, Consultation $consultation)
    {
        $data = $request->validate([
            'lead_status' => ['sometimes', 'required', Rule::in(array_keys(Consultation::LEAD_STATUS_LABELS))],
            'handled_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'follow_up_at' => ['nullable', 'date'],
        ], $this->validationMessages(), $this->validationAttributes());

        $update = [];

        if ($request->exists('lead_status')) {
            $update['lead_status'] = $data['lead_status'];
            $update['converted_to_booking_at'] = $data['lead_status'] === 'Converted'
                ? ($consultation->converted_to_booking_at ?? now())
                : null;
        }

        if ($request->exists('handled_by')) {
            $update['handled_by'] = $data['handled_by'] ?: null;
        }

        if ($request->exists('follow_up_at')) {
            $update['follow_up_at'] = $data['follow_up_at'] ?: null;
        }

        DB::transaction(fn () => $consultation->update($update));

        return back()->with('success', 'Pipeline calon pelanggan berhasil diperbarui.');
    }

    public function destroy(Consultation $consultation)
    {
        DB::transaction(fn () => $consultation->delete());

        return redirect()->route('konsultasi.index')->with('success', 'Data konsultasi berhasil dihapus.');
    }

    private function applyBulkLead(Consultation $consultation, string $leadStatus): void
    {
        $consultation->update([
            'lead_status' => $leadStatus,
            'converted_to_booking_at' => $leadStatus === 'Converted'
                ? ($consultation->converted_to_booking_at ?? now())
                : null,
        ]);
    }

    private function sourceOptions()
    {
        return Consultation::query()
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source')
            ->mapWithKeys(fn ($value) => [
                $value => Consultation::SOURCE_LABELS[$value] ?? $value,
            ]);
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
        $roles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('name')->all();

        if ($roles === []) {
            return collect();
        }

        return User::role($roles)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function consultationHistory(Consultation $consultation): array
    {
        $packages = Package::query()->pluck('name', 'id');
        $admins = User::query()->pluck('name', 'id');
        $labels = [
            'full_name' => 'Nama Calon Pelanggan',
            'whatsapp' => 'Nomor WhatsApp',
            'email' => 'Email',
            'source' => 'Informasi dari mana',
            'wedding_date' => 'Tanggal Pernikahan',
            'guest_estimate' => 'Estimasi Tamu',
            'package_id' => 'Paket Diminati',
            'venue' => 'Lokasi / Venue',
            'wedding_concept' => 'Konsep Pernikahan',
            'consultation_date' => 'Tanggal Konsultasi',
            'consultation_time' => 'Jam Konsultasi',
            'consultation_method' => 'Metode Konsultasi',
            'consultation_status' => 'Status Konsultasi',
            'lead_status' => 'Status Calon Pelanggan',
            'follow_up_at' => 'Jadwal Tindak Lanjut',
            'handled_by' => 'Penanggung Jawab',
            'admin_notes' => 'Catatan Tim',
            'converted_to_booking_at' => 'Waktu Konversi Booking',
            'reminder_sent_at' => 'Pengingat Terakhir',
            'merged_at' => 'Waktu Penggabungan',
            'merged_by' => 'Digabungkan Oleh',
        ];
        $options = [
            'consultation_status' => Consultation::STATUS_LABELS,
            'lead_status' => Consultation::LEAD_STATUS_LABELS,
            'consultation_method' => Consultation::METHOD_LABELS,
            'source' => Consultation::SOURCE_LABELS,
        ];
        $hidden = [
            'email_normalized',
            'whatsapp_e164',
            'merged_into_uuid',
            'created_at',
            'updated_at',
        ];

        return AuditLog::query()
            ->where('auditable_type', Consultation::class)
            ->where('auditable_id', (string) $consultation->uuid)
            ->latest()
            ->limit(50)
            ->get()
            ->map(function (AuditLog $log) use ($labels, $options, $hidden, $packages, $admins) {
                $event = (string) $log->event;

                return [
                    'event_label' => match ($event) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        default => Str::headline($event),
                    },
                    'badge' => match ($event) {
                        'created' => 'bg-success-subtle text-success-emphasis',
                        'updated' => 'bg-warning-subtle text-warning-emphasis',
                        'deleted' => 'bg-danger-subtle text-danger-emphasis',
                        default => 'bg-secondary-subtle text-secondary-emphasis',
                    },
                    'actor' => $log->user_name ?: 'Sistem',
                    'time' => $log->created_at?->translatedFormat('d M Y H:i') ?: '—',
                    'changes' => collect($log->changes())
                        ->reject(fn ($change, $field) => in_array($field, $hidden, true))
                        ->map(fn ($change, $field) => [
                            'label' => $labels[$field] ?? Str::headline($field),
                            'old' => $this->formatHistoryValue($field, $change['old'], $packages, $admins, $options),
                            'new' => $this->formatHistoryValue($field, $change['new'], $packages, $admins, $options),
                        ])
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn ($entry) => $entry['changes'] !== [])
            ->values()
            ->all();
    }

    private function formatHistoryValue(string $field, mixed $value, $packages, $admins, array $options): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        if (isset($options[$field][$value])) {
            return (string) $options[$field][$value];
        }

        if (in_array($field, ['handled_by', 'merged_by'], true)) {
            return $admins[(string) $value] ?? '#'.$value;
        }

        if ($field === 'package_id') {
            return $packages[(string) $value] ?? '#'.$value;
        }

        if (in_array($field, ['wedding_date', 'consultation_date', 'follow_up_at', 'converted_to_booking_at', 'reminder_sent_at', 'merged_at'], true)) {
            try {
                $format = str_contains($field, '_at') ? 'd M Y H:i' : 'd M Y';

                return Carbon::parse((string) $value)->translatedFormat($format);
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        if (is_array($value)) {
            return json_encode($value) ?: '—';
        }

        return is_bool($value) ? ($value ? 'Ya' : 'Tidak') : (string) $value;
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
            'stats' => Consultation::stats($filters),
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

    private function duplicateResponse(Consultation $duplicate)
    {
        return back()->withInput()->with([
            'error' => 'Calon pelanggan dengan email atau WhatsApp ini sudah ada: '.$duplicate->full_name.'.',
            'duplicate_uuid' => $duplicate->uuid,
        ]);
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
            'ids' => 'data yang dipilih',
            'action' => 'aksi massal',
            'primary_uuid' => 'data utama',
            'duplicate_uuid' => 'data duplikat',
        ];
    }
}
