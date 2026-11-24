<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\CustomPackageOfferRequest;
use App\Models\Package\CustomPackageOfferRequestAttachment;
use App\Models\User;
use App\Notifications\CustomPackageRequestNotification;
use App\Services\CustomPackageWorkflowAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomPackageOfferRequestController extends Controller
{
    public function mine()
    {
        $user = auth()->user();

        if ($user?->hasAnyRole(['super-admin', 'admin']) && $user->can('package.index')) {
            return redirect()->route('package-offer-requests.index');
        }

        abort_unless($user?->hasRole('user') && $user->can('package.member'), 404);

        $requests = CustomPackageOfferRequest::with('offer')
            ->where('user_uuid', auth()->id())
            ->latest()
            ->paginate(15);

        return view('pages.package-offer-requests.mine', compact('requests'));
    }

    public function create()
    {
        abort_unless(auth()->user()?->hasRole('user') && auth()->user()->can('package.member'), 404);

        return view('pages.package-offer-requests.create');
    }

    public function store(Request $request, CustomPackageWorkflowAudit $audit)
    {
        abort_unless(auth()->user()?->hasRole('user') && auth()->user()->can('package.member'), 404);

        $data = $request->validate([
            'event_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.now()->toDateString()],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'venue_note' => ['nullable', 'string', 'max:1000'],
            'budget_min' => ['nullable', 'integer', 'min:0', 'max:1000000000000'],
            'budget_max' => ['nullable', 'integer', 'min:0', 'max:1000000000000'],
            'request_details' => ['required', 'string', 'min:20', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ], [], [
            'event_date' => 'tanggal acara',
            'guest_count' => 'jumlah tamu',
            'venue_note' => 'lokasi acara',
            'budget_min' => 'anggaran minimum',
            'budget_max' => 'anggaran maksimum',
            'request_details' => 'rincian kebutuhan',
            'attachments' => 'lampiran',
        ]);

        if (isset($data['budget_min'], $data['budget_max']) && $data['budget_min'] > $data['budget_max']) {
            return back()
                ->withErrors(['budget_max' => 'Anggaran maksimum harus sama dengan atau lebih besar dari anggaran minimum.'])
                ->withInput();
        }

        $user = auth()->user();
        $attachmentFiles = $request->file('attachments', []);
        unset($data['attachments']);
        $fingerprint = $this->requestFingerprint($data);

        $storeResult = DB::transaction(function () use ($data, $attachmentFiles, $user, $fingerprint, $audit) {
            User::where('uuid', $user->uuid)->lockForUpdate()->firstOrFail();
            $duplicate = CustomPackageOfferRequest::where('user_uuid', $user->uuid)
                ->where('fingerprint_hash', $fingerprint)
                ->whereIn('status', ['submitted', 'in_review', 'quoted'])
                ->latest()
                ->first();
            if ($duplicate) {
                return ['duplicate' => $duplicate];
            }
            $todayCount = CustomPackageOfferRequest::where('user_uuid', $user->uuid)
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->count();
            if ($todayCount >= 3) {
                return ['limit_reached' => true];
            }

            $customRequest = CustomPackageOfferRequest::create([
                ...$data,
                'user_uuid' => $user->uuid,
                'status' => 'submitted',
                'fingerprint_hash' => $fingerprint,
                'review_due_at' => now()->addHours(24),
            ]);

            foreach ($attachmentFiles as $file) {
                $attachmentUuid = (string) Str::uuid();
                $extension = $file->guessExtension() ?: 'bin';
                $path = "custom-package-requests/{$customRequest->uuid}/{$attachmentUuid}.{$extension}";
                $storedPath = $file->storeAs(dirname($path), basename($path), 'local');
                if ($storedPath === false) {
                    throw new \RuntimeException('Lampiran permintaan tidak dapat disimpan.');
                }

                $customRequest->attachments()->create([
                    'uuid' => $attachmentUuid,
                    'uploaded_by_uuid' => $user->uuid,
                    'storage_path' => $storedPath,
                    'original_name' => Str::limit(basename($file->getClientOriginalName()), 255, ''),
                    'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                    'size_bytes' => $file->getSize(),
                ]);
            }

            $audit->record(
                $user->uuid,
                'request_submitted',
                $customRequest->uuid,
                null,
                null,
                'submitted',
                ['attachment_count' => count($attachmentFiles)]
            );

            return ['request' => $customRequest];
        });

        if (isset($storeResult['duplicate'])) {
            return redirect()
                ->route('package-offer-requests.show', $storeResult['duplicate']->uuid)
                ->with('warning', 'Permintaan serupa masih aktif. Buka permintaan tersebut untuk melihat perkembangannya.');
        }
        if (! empty($storeResult['limit_reached'])) {
            return back()
                ->withErrors(['request_details' => 'Batas pengajuan tercapai. Maksimal 3 permintaan paket dapat dikirim dalam sehari.'])
                ->withInput();
        }

        $customRequest = $storeResult['request'];
        $admins = User::role(['super-admin', 'admin'])->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new CustomPackageRequestNotification(
                $customRequest->uuid,
                'Permintaan paket khusus baru',
                auth()->user()->name.' mengirim permintaan paket khusus.',
                'bi-inbox'
            ));
        }

        return redirect()
            ->route('package-offer-requests.show', $customRequest->uuid)
            ->with('success', 'Permintaan paket berhasil dikirim. Admin akan meninjau kebutuhan Anda.');
    }

    public function index(Request $request)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.index'), 404);

        $status = (string) $request->query('status', '');
        $requests = CustomPackageOfferRequest::with(['user', 'assignedAdmin', 'offer'])
            ->when(in_array($status, ['submitted', 'in_review', 'quoted', 'closed'], true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $admins = User::role(['super-admin', 'admin'])->orderBy('name')->get(['uuid', 'name']);

        return view('pages.package-offer-requests.index', compact('requests', 'status', 'admins'));
    }

    public function show(CustomPackageOfferRequest $packageOfferRequest)
    {
        $user = auth()->user();
        $isAdmin = $user?->hasAnyRole(['super-admin', 'admin']) ?? false;
        abort_unless(
            ($isAdmin && $user->can('package.index')) ||
            ($user?->hasRole('user') && $packageOfferRequest->user_uuid === $user->uuid),
            404
        );

        $packageOfferRequest->load(['user', 'assignedAdmin', 'offer.options.items', 'attachments']);
        if ($isAdmin) {
            $packageOfferRequest->load('workflowEvents.actor');
        }

        return view('pages.package-offer-requests.show', [
            'packageRequest' => $packageOfferRequest,
            'isAdmin' => $isAdmin,
            'admins' => $isAdmin ? User::role(['super-admin', 'admin'])->orderBy('name')->get(['uuid', 'name']) : collect(),
        ]);
    }

    public function updateStatus(
        Request $request,
        CustomPackageOfferRequest $packageOfferRequest,
        CustomPackageWorkflowAudit $audit
    )
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);
        abort_unless(in_array($packageOfferRequest->status, ['submitted', 'in_review'], true), 404);

        $data = $request->validate([
            'status' => ['required', Rule::in(['in_review', 'closed'])],
            'admin_note' => ['nullable', 'string', 'max:5000'],
            'assigned_admin_uuid' => ['nullable', 'uuid', Rule::exists('users', 'uuid')],
        ]);

        $previousStatus = $packageOfferRequest->status;
        $assignedAdminUuid = array_key_exists('assigned_admin_uuid', $data)
            ? $data['assigned_admin_uuid']
            : $packageOfferRequest->assigned_admin_uuid;
        if ($assignedAdminUuid && ! User::role(['super-admin', 'admin'])->where('uuid', $assignedAdminUuid)->exists()) {
            return back()->withErrors(['assigned_admin_uuid' => 'Admin yang dipilih tidak valid.'])->withInput();
        }

        $updates = [
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'assigned_admin_uuid' => $assignedAdminUuid,
            'handled_at' => now(),
        ];
        if ($assignedAdminUuid !== $packageOfferRequest->assigned_admin_uuid) {
            $updates['sla_notified_at'] = null;
        }
        $packageOfferRequest->update($updates);
        $audit->record(
            auth()->id(),
            'request_status_updated',
            $packageOfferRequest->uuid,
            null,
            $previousStatus,
            $data['status'],
            ['assigned_admin_uuid' => $assignedAdminUuid]
        );
        if ($previousStatus !== $data['status']) {
            $recipient = $packageOfferRequest->user;
            if ($recipient) {
                $recipient->notify(new CustomPackageRequestNotification(
                    $packageOfferRequest->uuid,
                    $data['status'] === 'closed' ? 'Permintaan paket ditutup' : 'Permintaan paket sedang ditinjau',
                    $data['status'] === 'closed'
                        ? 'Admin telah menutup permintaan paket Anda. Hubungi admin bila masih membutuhkan bantuan.'
                        : 'Admin sedang meninjau permintaan paket khusus Anda.',
                    $data['status'] === 'closed' ? 'bi-x-circle' : 'bi-hourglass-split'
                ));
            }
        }

        return redirect()
            ->route('package-offer-requests.show', $packageOfferRequest->uuid)
            ->with('success', 'Status permintaan berhasil diperbarui.');
    }

    public function convert(
        CustomPackageOfferRequest $packageOfferRequest,
        CustomPackageWorkflowAudit $audit
    )
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.store'), 404);
        $packageOfferRequest->load('offer');

        if ($packageOfferRequest->offer) {
            if ($packageOfferRequest->offer->status === 'draft') {
                return redirect()->route('package-offers.edit', $packageOfferRequest->offer->uuid);
            }

            return redirect()->route('package-offers.show', $packageOfferRequest->offer->uuid);
        }

        abort_unless(in_array($packageOfferRequest->status, ['submitted', 'in_review'], true), 404);

        $wasSubmitted = $packageOfferRequest->status === 'submitted';
        $packageOfferRequest->update([
            'status' => 'in_review',
            'assigned_admin_uuid' => auth()->id(),
            'handled_at' => now(),
        ]);
        $audit->record(
            auth()->id(),
            'request_opened_for_offer',
            $packageOfferRequest->uuid,
            null,
            $wasSubmitted ? 'submitted' : 'in_review',
            'in_review',
            ['assigned_admin_uuid' => auth()->id()]
        );
        if ($wasSubmitted) {
            $recipient = $packageOfferRequest->user;
            if ($recipient) {
                $recipient->notify(new CustomPackageRequestNotification(
                    $packageOfferRequest->uuid,
                    'Permintaan paket sedang ditinjau',
                    'Admin sedang meninjau permintaan paket khusus Anda.',
                    'bi-hourglass-split'
                ));
            }
        }

        return redirect()->route('package-offers.create', [
            'request_uuid' => $packageOfferRequest->uuid,
        ]);
    }

    public function downloadAttachment(
        CustomPackageOfferRequest $packageOfferRequest,
        CustomPackageOfferRequestAttachment $attachment
    ) {
        $user = auth()->user();
        $isAdmin = $user?->hasAnyRole(['super-admin', 'admin']) && $user->can('package.index');
        abort_unless(
            $isAdmin || ($user?->hasRole('user') && $packageOfferRequest->user_uuid === $user->uuid),
            404
        );
        abort_unless($attachment->request_uuid === $packageOfferRequest->uuid, 404);
        abort_unless(Storage::disk('local')->exists($attachment->storage_path), 404);

        return Storage::disk('local')->download($attachment->storage_path, $attachment->original_name);
    }

    private function requestFingerprint(array $data): string
    {
        $normalize = static fn ($value) => Str::lower(preg_replace('/\s+/u', ' ', trim((string) $value)) ?? '');
        $payload = [
            'event_date' => $data['event_date'] ?? null,
            'guest_count' => isset($data['guest_count']) ? (int) $data['guest_count'] : null,
            'venue_note' => $normalize($data['venue_note'] ?? ''),
            'budget_min' => isset($data['budget_min']) ? (int) $data['budget_min'] : null,
            'budget_max' => isset($data['budget_max']) ? (int) $data['budget_max'] : null,
            'request_details' => $normalize($data['request_details'] ?? ''),
        ];

        return hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
