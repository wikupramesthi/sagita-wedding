<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ConsultationExport;
use App\Http\Controllers\Controller;
use App\Imports\ConsultationImport;
use App\Models\AuditLog;
use App\Models\Consultation;
use App\Models\KonsultasiSavedFilter;
use App\Models\Package\Package;
use App\Models\User;
use App\Models\WebsiteIdentity;
use App\Notifications\ConsultationFollowUpNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $savedFilters = KonsultasiSavedFilter::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        if (! $request->query() && ! $request->has('all')) {
            $defaultFilter = $savedFilters->firstWhere('is_default', true);

            if ($defaultFilter) {
                $query = array_filter(
                    Consultation::normalizeFilters($defaultFilter->filters ?? []),
                    fn ($value) => $value !== '' && $value !== null
                );

                if ($query) {
                    return redirect()->route('konsultasi.index', $query);
                }
            }
        }

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

        $activeFilters = collect($filters)
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->map(function ($value, $key) use ($admins, $sources) {
                $labels = [
                    'q' => 'Pencarian',
                    'status' => 'Status',
                    'lead' => 'Lead',
                    'follow_up' => 'Tindak lanjut',
                    'sla' => 'SLA',
                    'source' => 'Sumber',
                    'start_date' => 'Dari',
                    'end_date' => 'Sampai',
                ];
                $values = [
                    'status' => Consultation::STATUS_LABELS,
                    'lead' => Consultation::LEAD_STATUS_LABELS,
                    'follow_up' => Consultation::FOLLOW_UP_LABELS,
                    'sla' => Consultation::SLA_LABELS,
                    'source' => $sources,
                    'handled_by' => $admins->pluck('name', 'id'),
                ];

                return [
                    'key' => $key,
                    'label' => $labels[$key] ?? Str::headline($key),
                    'value' => $values[$key][$value] ?? $value,
                ];
            })
            ->values();

        return view('pages.konsultasi.index', compact(
            'consultations',
            'stats',
            'filters',
            'sources',
            'admins',
            'savedFilters',
            'activeFilters'
        ));
    }

    public function savedFilters()
    {
        return response()->json(
            KonsultasiSavedFilter::query()
                ->where('user_id', auth()->id())
                ->orderBy('name')
                ->get(['id', 'name', 'filters', 'is_default'])
        );
    }

    public function storeSavedFilter(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'filters' => ['required', 'string'],
            'is_default' => ['nullable', 'boolean'],
        ], $this->validationMessages(), ['name' => 'nama filter']);

        $filters = json_decode($data['filters'], true);
        $isDefault = (bool) ($data['is_default'] ?? false);

        DB::transaction(function () use ($data, $filters, $isDefault) {
            if ($isDefault) {
                KonsultasiSavedFilter::query()
                    ->where('user_id', auth()->id())
                    ->update(['is_default' => false]);
            }

            KonsultasiSavedFilter::query()->updateOrCreate([
                'user_id' => auth()->id(),
                'name' => $data['name'],
            ], [
                'filters' => Consultation::normalizeFilters(is_array($filters) ? $filters : []),
                'is_default' => $isDefault,
            ]);
        });

        return back()->with('success', 'Filter "'.$data['name'].'" berhasil disimpan.');
    }

    public function setDefaultSavedFilter(Request $request, KonsultasiSavedFilter $savedFilter)
    {
        abort_unless($savedFilter->user_id === auth()->id(), 404);

        $data = $request->validate([
            'is_default' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($savedFilter, $data) {
            if ($data['is_default']) {
                KonsultasiSavedFilter::query()
                    ->where('user_id', auth()->id())
                    ->update(['is_default' => false]);
            }

            $savedFilter->update(['is_default' => (bool) $data['is_default']]);
        });

        return back()->with('success', $data['is_default']
            ? 'Filter "'.$savedFilter->name.'" dijadikan default.'
            : 'Filter default dinonaktifkan.');
    }

    public function applySavedFilter(KonsultasiSavedFilter $savedFilter)
    {
        abort_unless($savedFilter->user_id === auth()->id(), 404);

        $filters = Consultation::normalizeFilters($savedFilter->filters ?? []);

        return redirect()->route(
            'konsultasi.index',
            array_filter($filters, fn ($value) => $value !== '' && $value !== null)
        );
    }

    public function deleteSavedFilter(KonsultasiSavedFilter $savedFilter)
    {
        abort_unless($savedFilter->user_id === auth()->id(), 404);

        $savedFilter->delete();

        return back()->with('success', 'Filter tersimpan berhasil dihapus.');
    }

    public function bulk(Request $request)
    {
        $this->ensurePermission('konsultasi.bulk');

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

        $snapshot = [
            'action' => $data['action'],
            'records' => $records
                ->map(fn ($record) => [
                    'uuid' => $record->uuid,
                    'attributes' => $record->getAttributes(),
                ])
                ->all(),
        ];
        $undoToken = Str::random(24);

        $target = match ($data['action']) {
            'lead' => (string) $data['lead_status'],
            'follow-up-done' => 'Contacted',
            default => null,
        };
        $incoming = match ($data['action']) {
            'lead' => $records->where('lead_status', '!=', $target)->count(),
            'follow-up-done' => $records->where('lead_status', 'New')->count(),
            default => 0,
        };

        $apply = function () use ($records, $data, $target, $incoming, $snapshot, $undoToken) {
            if ($target !== null && $incoming > 0) {
                $this->assertWipCapacity($target, $records->pluck('uuid')->all(), $incoming);
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

            Cache::put(
                'konsultasi.bulk_undo.'.auth()->id().'.'.$undoToken,
                $snapshot,
                now()->addMinutes(10)
            );

            return back()->with([
                'success' => $records->count().' data konsultasi berhasil '.$messages[$data['action']].'.',
                'undo_token' => $undoToken,
                'undo_message' => $records->count().' data konsultasi berhasil '.$messages[$data['action']].'.',
            ]);
        };

        if ($target !== null && $incoming > 0) {
            return $this->withWipLock($target, $apply);
        }

        return $apply();
    }

    public function bulkUndo(Request $request)
    {
        $this->ensurePermission('konsultasi.bulk');

        $data = $request->validate([
            'undo_token' => ['required', 'string', 'max:100'],
        ]);

        $cacheKey = 'konsultasi.bulk_undo.'.auth()->id().'.'.$data['undo_token'];
        $snapshot = Cache::pull($cacheKey);

        if (! is_array($snapshot) || ! isset($snapshot['records'])) {
            return back()->with('error', 'Waktu pembatalan aksi massal sudah berakhir.');
        }

        $count = count($snapshot['records']);

        DB::transaction(function () use ($snapshot) {
            foreach ($snapshot['records'] as $record) {
                DB::table('consultations')
                    ->where('uuid', $record['uuid'])
                    ->update($record['attributes']);
            }
        });

        return back()->with('success', $count.' data konsultasi berhasil dikembalikan.');
    }

    public function export(Request $request)
    {
        $this->ensurePermission('konsultasi.export');

        Log::info('konsultasi.export.download', [
            'user_id' => auth()->id(),
            'filters' => Consultation::normalizeFilters($request->query()),
        ]);

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
        $items = $query->orderBy('sort_order')->orderByDesc('created_at')->limit(300)->get();

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
            'wipLimits' => Consultation::WIP_LIMITS,
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
        $this->ensurePermission('konsultasi.merge');

        return view('pages.konsultasi.duplicates', [
            'duplicates' => Consultation::duplicateGroups(),
        ]);
    }

    public function importTemplate()
    {
        $this->ensurePermission('konsultasi.import');

        $headers = [
            'Nama Calon Pelanggan', 'WhatsApp', 'Email', 'Tanggal Pernikahan', 'Estimasi Tamu',
            'Paket', 'Lokasi / Venue', 'Konsep Pernikahan', 'Tanggal Konsultasi', 'Jam Konsultasi',
            'Metode Konsultasi', 'Status Konsultasi', 'Penanggung Jawab', 'Catatan Tim',
            'Tanggal Tindak Lanjut', 'Status Calon Pelanggan', 'Informasi dari mana',
        ];

        $sample = [
            'Rani Putri', '081234567890', 'rani@example.test', '2026-12-20', '200–500 tamu',
            '', 'Grand Ballroom', 'Rustic', '2026-11-25', '10:00', 'Online', 'Menunggu',
            '', 'Follow up setelah konsultasi', '2026-11-26 10:00', 'Baru', 'Google',
        ];

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        fputcsv($handle, $sample);
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-konsultasi.csv"',
        ]);
    }

    public function import()
    {
        $this->ensurePermission('konsultasi.import');

        return view('pages.konsultasi.import', [
            'rows' => session('konsultasi_import_rows', []),
        ]);
    }

    public function importPreview(Request $request)
    {
        $this->ensurePermission('konsultasi.import');

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:5120'],
        ], $this->validationMessages(), ['file' => 'file CSV/XLSX']);

        $importer = new ConsultationImport;

        Excel::import($importer, $data['file']);

        if ($importer->rows->count() > 500) {
            return back()->with('error', 'Maksimal 500 baris per file import.');
        }

        if ($importer->rows->isEmpty()) {
            return back()->with('error', 'File tidak memiliki baris data.');
        }

        $rows = $importer->rows
            ->map(fn ($row, $index) => $this->normalizeImportRow($row, $index + 2))
            ->values()
            ->all();

        session([
            'konsultasi_import_rows' => $rows,
            'konsultasi_import_token' => (string) Str::uuid(),
        ]);

        $valid = collect($rows)->where('valid', true)->count();

        return redirect()->route('konsultasi.import')->with('success',
            $valid.' baris siap diimpor dari '.$importer->rows->count().' baris ditemukan.'
        );
    }

    public function importCommit(Request $request)
    {
        $this->ensurePermission('konsultasi.import');

        $rows = session('konsultasi_import_rows');
        $token = (string) $request->input('token', '');

        if (! $rows || $token === '' || $token !== session('konsultasi_import_token')) {
            return redirect()->route('konsultasi.import')->with('error', 'Preview import tidak ditemukan atau sudah kedaluwarsa. Unggah ulang file.');
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $row) {
            if (! $row['valid']) {
                $skipped++;
                $errors[] = 'Baris '.$row['row_number'].': '.$row['error'];

                continue;
            }

            $payload = Arr::except($row, ['row_number', 'valid', 'error']);

            $target = (string) ($payload['lead_status'] ?? 'New');

            try {
                $result = $this->withWipLock($target, function () use ($payload, $target) {
                    $this->assertWipCapacity($target);

                    return DB::transaction(function () use ($payload) {
                        if (Consultation::findDuplicates(null, $payload, true)->first()) {
                            return 'duplicate';
                        }

                        Consultation::create($payload);

                        return 'created';
                    });
                });

                if ($result === 'duplicate') {
                    $skipped++;
                    $errors[] = 'Baris '.$row['row_number'].': email atau WhatsApp sudah terdaftar.';
                } else {
                    $created++;
                }
            } catch (ValidationException $exception) {
                $skipped++;
                $errors[] = 'Baris '.$row['row_number'].': '.($exception->errors()['lead_status'][0] ?? 'kuota WIP penuh.');
            } catch (QueryException $exception) {
                $skipped++;
                $errors[] = 'Baris '.$row['row_number'].': gagal disimpan.';
                Log::warning('konsultasi.import.failed', [
                    'row' => $row['row_number'],
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        session()->forget(['konsultasi_import_rows', 'konsultasi_import_token']);

        $message = $created.' data konsultasi berhasil diimpor.';

        if ($skipped > 0) {
            $message .= ' '.$skipped.' baris dilewati.';
        }

        return redirect()->route('konsultasi.import')->with([
            'success' => $message,
            'import_errors' => $errors,
        ]);
    }

    public function merge(Request $request)
    {
        $this->ensurePermission('konsultasi.merge');

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
            Log::warning('konsultasi.merge.rejected', [
                'primary' => $primary->uuid,
                'duplicate' => $duplicate->uuid,
            ]);

            return back()->with('error', 'Salah satu data sudah pernah digabungkan. Pilih data aktif.');
        }

        Log::info('konsultasi.merge.attempt', [
            'primary' => $primary->uuid,
            'duplicate' => $duplicate->uuid,
        ]);

        $duplicate->mergeInto($primary);

        Log::info('konsultasi.merge.success', [
            'primary' => $primary->uuid,
            'duplicate' => $duplicate->uuid,
        ]);

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

        $sent = 0;

        foreach ($recipients as $recipient) {
            try {
                Notification::send($recipient, new ConsultationFollowUpNotification($consultation));
                $sent++;
                Log::info('konsultasi.reminder.manual_sent', [
                    'uuid' => $consultation->uuid,
                    'recipient_id' => $recipient->id,
                ]);
            } catch (\Throwable $exception) {
                Log::warning('konsultasi.reminder.manual_failed', [
                    'uuid' => $consultation->uuid,
                    'recipient_id' => $recipient->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if ($sent === 0) {
            return back()->with('error', 'Pengingat gagal dikirim. Coba lagi nanti.');
        }

        $consultation->update(['reminder_sent_at' => now()]);

        return back()->with('success', $sent.' pengingat tindak lanjut berhasil dikirim.');
    }

    public function whatsapp(Consultation $consultation)
    {
        $user = auth()->user();

        AuditLog::create([
            'user_uuid' => $user?->uuid,
            'user_name' => $user?->name ?? 'Sistem',
            'user_email' => $user?->email,
            'event' => 'whatsapp_opened',
            'auditable_type' => Consultation::class,
            'auditable_id' => (string) $consultation->uuid,
            'auditable_label' => $consultation->full_name,
            'new_values' => ['whatsapp' => $consultation->whatsapp],
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Log::info('konsultasi.whatsapp.opened', [
            'uuid' => $consultation->uuid,
            'user_id' => $user?->id,
        ]);

        return redirect()->away(
            $consultation->whatsapp_link.'?text='.rawurlencode($consultation->whatsapp_message)
        );
    }

    public function calendarIcs(Consultation $consultation)
    {
        if (! $consultation->consultation_date) {
            return back()->with('error', 'Konsultasi ini belum memiliki tanggal.');
        }

        $start = Carbon::parse(
            $consultation->consultation_date->toDateString().' '.($consultation->consultation_time ?: '10:00'),
            config('app.timezone')
        );
        $end = $start->copy()->addHour();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Sagita Wedding//Konsultasi//ID',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:'.$consultation->uuid.'@sagita-wedding',
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:'.$start->utc()->format('Ymd\THis\Z'),
            'DTEND:'.$end->utc()->format('Ymd\THis\Z'),
            'SUMMARY:'.$this->escapeIcs('Konsultasi Sagita Wedding - '.$consultation->full_name),
            'DESCRIPTION:'.$this->escapeIcs($consultation->whatsapp_message),
            'LOCATION:'.$this->escapeIcs((string) $consultation->venue),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return response(implode("\r\n", $lines)."\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="konsultasi-'.$consultation->uuid.'.ics"',
        ]);
    }

    public function pdfPreview(Request $request)
    {
        $this->ensurePermission('konsultasi.export');

        return view('pages.konsultasi.pdf-preview', $this->pdfData($request));
    }

    public function pdfStream(Request $request)
    {
        $this->ensurePermission('konsultasi.export');

        $data = $this->pdfData($request);

        return Pdf::loadView('pages.konsultasi.pdf-document', $data)
            ->setPaper('a4', $data['mode'] === 'detail' ? 'portrait' : 'landscape')
            ->stream($this->pdfFilename($data));
    }

    public function pdfDownload(Request $request)
    {
        $this->ensurePermission('konsultasi.export');

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

        if (empty($data['handled_by'])) {
            $data['handled_by'] = $this->autoAssignPic();
        }

        $target = (string) $data['lead_status'];

        return $this->withWipLock($target, function () use ($data, $target) {
            $this->assertWipCapacity($target);

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
        });
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

        $target = (string) $data['lead_status'];
        $changesLead = $target !== $consultation->lead_status;

        $save = function () use ($consultation, $data, $target, $changesLead) {
            if ($changesLead) {
                $this->assertWipCapacity($target, [$consultation->uuid]);
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
        };

        return $changesLead ? $this->withWipLock($target, $save) : $save();
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

        $target = isset($update['lead_status']) ? (string) $update['lead_status'] : null;
        $changesLead = $target !== null && $target !== $consultation->lead_status;

        $apply = function () use ($consultation, $update, $target, $changesLead) {
            if ($changesLead) {
                $this->assertWipCapacity($target, [$consultation->uuid]);
            }

            DB::transaction(fn () => $consultation->update($update));

            return back()->with('success', 'Pipeline calon pelanggan berhasil diperbarui.');
        };

        return $changesLead ? $this->withWipLock($target, $apply) : $apply();
    }

    public function reorder(Request $request, Consultation $consultation)
    {
        $data = $request->validate([
            'direction' => ['required', Rule::in(['up', 'down'])],
        ], $this->validationMessages());

        return $this->withNamedLock('konsultasi.reorder.'.$consultation->lead_status, function () use ($consultation, $data) {
            $siblings = Consultation::query()
                ->where('lead_status', $consultation->lead_status)
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->get(['uuid', 'sort_order']);

            $index = $siblings->search(fn ($item) => $item->uuid === $consultation->uuid);

            if ($index === false) {
                return back()->with('error', 'Data tidak ditemukan dalam kolom pipeline.');
            }

            $target = $data['direction'] === 'up' ? $index - 1 : $index + 1;

            if ($target < 0 || $target >= $siblings->count()) {
                return back();
            }

            $ordered = $siblings->all();
            [$ordered[$index], $ordered[$target]] = [$ordered[$target], $ordered[$index]];

            DB::transaction(function () use ($ordered) {
                foreach ($ordered as $position => $item) {
                    DB::table('consultations')
                        ->where('uuid', $item->uuid)
                        ->update(['sort_order' => $position, 'updated_at' => now()]);
                }
            });

            return back()->with('success', 'Urutan pipeline berhasil diperbarui.');
        });
    }

    public function destroy(Consultation $consultation)
    {
        $this->ensurePermission('konsultasi.delete');

        $isMergeTarget = Consultation::withoutGlobalScope('not_merged')
            ->where('merged_into_uuid', $consultation->uuid)
            ->exists();

        if ($isMergeTarget) {
            return back()->with('error', 'Data ini tidak bisa dihapus karena masih menjadi target penggabungan.');
        }

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

    private function withNamedLock(string $key, callable $callback): mixed
    {
        $lock = 'sagita:'.$key;
        $acquired = DB::selectOne('SELECT GET_LOCK(?, 5) AS acquired', [$lock])?->acquired;

        if ((int) $acquired !== 1) {
            throw ValidationException::withMessages([
                'lead_status' => 'Saat ini ada proses lain yang mengubah pipeline. Silakan coba beberapa saat lagi.',
            ]);
        }

        try {
            return $callback();
        } finally {
            DB::select('SELECT RELEASE_LOCK(?)', [$lock]);
        }
    }

    private function withWipLock(string $target, callable $callback): mixed
    {
        if (! array_key_exists($target, Consultation::WIP_LIMITS)) {
            return $callback();
        }

        return $this->withNamedLock('konsultasi.wip.'.$target, $callback);
    }

    private function assertWipCapacity(string $target, array $excludingUuids = [], int $incoming = 1): void
    {
        $limit = Consultation::WIP_LIMITS[$target] ?? null;

        if ($limit === null || $incoming <= 0) {
            return;
        }

        $occupied = Consultation::query()
            ->where('lead_status', $target)
            ->when($excludingUuids !== [], fn ($query) => $query->whereNotIn('uuid', $excludingUuids))
            ->count();

        if ($occupied + $incoming > $limit) {
            throw ValidationException::withMessages([
                'lead_status' => 'Kolom '.Consultation::LEAD_STATUS_LABELS[$target].' sudah mencapai batas WIP '.$limit.'. Pindahkan atau selesaikan lead lain terlebih dahulu.',
            ]);
        }
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

    private function escapeIcs(string $value): string
    {
        return str_replace(
            ['\\', ';', ',', "\r\n", "\n"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n'],
            $value
        );
    }

    private function normalizeImportRow(mixed $row, int $rowNumber): array
    {
        $row = ($row instanceof Collection ? $row : collect($row))
            ->mapWithKeys(fn ($value, $key) => [Str::slug((string) $key, '_') => $value]);

        $pick = function (array $aliases) use ($row) {
            foreach ($aliases as $alias) {
                $key = Str::slug($alias, '_');

                if ($row->has($key) && filled($row->get($key))) {
                    return trim((string) $row->get($key));
                }
            }

            return null;
        };

        $package = $pick(['package', 'nama_paket', 'paket']);
        $packageId = $pick(['package_id', 'paket_id', 'id_paket']);

        if (! $packageId && $package) {
            $packageId = Package::query()
                ->whereRaw('LOWER(name) = ?', [Str::lower($package)])
                ->value('id');
        }

        $data = [
            'full_name' => $pick(['full_name', 'nama', 'nama_calon_pelanggan', 'nama_lengkap']),
            'whatsapp' => $pick(['whatsapp', 'no_whatsapp', 'nomor_whatsapp', 'telepon', 'phone', 'no_hp']),
            'email' => $pick(['email', 'email_address', 'alamat_email']),
            'wedding_date' => $this->normalizeImportDate($pick(['wedding_date', 'tanggal_pernikahan', 'tgl_pernikahan'])),
            'guest_estimate' => $this->resolveImportOption(
                $pick(['guest_estimate', 'estimasi_tamu', 'jumlah_tamu']),
                collect(Consultation::GUEST_ESTIMATES)->mapWithKeys(fn ($value) => [$value => $value])->all()
            ),
            'venue' => $pick(['venue', 'lokasi', 'tempat', 'lokasi_acara']),
            'package_id' => $packageId,
            'wedding_concept' => $pick(['wedding_concept', 'konsep_pernikahan', 'konsep']),
            'consultation_date' => $this->normalizeImportDate($pick(['consultation_date', 'tanggal_konsultasi', 'tgl_konsultasi'])),
            'consultation_time' => $this->normalizeImportTime($pick(['consultation_time', 'jam_konsultasi', 'waktu_konsultasi'])),
            'consultation_method' => $this->resolveImportOption(
                $pick(['consultation_method', 'metode_konsultasi', 'metode']),
                Consultation::METHOD_LABELS
            ),
            'consultation_status' => $this->resolveImportOption(
                $pick(['consultation_status', 'status_konsultasi']),
                Consultation::STATUS_LABELS
            ) ?: 'Pending',
            'admin_notes' => $pick(['admin_notes', 'catatan', 'catatan_tim']),
            'handled_by' => null,
            'follow_up_at' => $this->normalizeImportDateTime($pick(['follow_up_at', 'tindak_lanjut', 'jadwal_tindak_lanjut'])),
            'lead_status' => $this->resolveImportOption(
                $pick(['lead_status', 'status_lead', 'status_calon_pelanggan']),
                Consultation::LEAD_STATUS_LABELS
            ) ?: 'New',
            'source' => $this->resolveImportOption(
                $pick(['source', 'sumber', 'informasi_dari_mana', 'dari_mana']),
                Consultation::SOURCE_LABELS
            ) ?: 'unknown',
            'converted_to_booking_at' => null,
        ];

        foreach (['full_name', 'venue', 'wedding_concept', 'admin_notes'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = strip_tags((string) $data[$field]);
            }
        }

        $validator = Validator::make($data, [
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
            'follow_up_at' => ['nullable', 'date'],
            'lead_status' => ['required', Rule::in(array_keys(Consultation::LEAD_STATUS_LABELS))],
            'source' => ['required', Rule::in(array_keys(Consultation::SOURCE_LABELS))],
        ], $this->validationMessages(), $this->validationAttributes());

        return [
            'row_number' => $rowNumber,
            'valid' => $validator->passes(),
            'error' => $validator->errors()->first() ?: null,
        ] + $data;
    }

    private function resolveImportOption(?string $value, array $labels): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $needle = Str::lower(trim($value));

        foreach ($labels as $key => $label) {
            if (Str::lower((string) $key) === $needle
                || Str::lower((string) $label) === $needle
                || Str::slug((string) $label, '_') === $needle) {
                return (string) $key;
            }
        }

        return $value;
    }

    private function normalizeImportDate(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function normalizeImportTime(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i');
        }

        try {
            return Carbon::parse((string) $value)->format('H:i');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function normalizeImportDateTime(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format('Y-m-d H:i:s');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function autoAssignPic(): ?int
    {
        $admins = $this->adminOptions();

        if ($admins->isEmpty()) {
            return null;
        }

        $workloads = Consultation::query()
            ->whereNotNull('handled_by')
            ->selectRaw('handled_by, COUNT(*) as total')
            ->groupBy('handled_by')
            ->pluck('total', 'handled_by');

        $chosen = $admins->sortBy(fn ($admin) => (int) ($workloads->get($admin->id) ?? 0))->first();

        return $chosen ? (int) $chosen->id : null;
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
                        'whatsapp_opened' => 'WhatsApp Dibuka',
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

    private function ensurePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 404);
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
