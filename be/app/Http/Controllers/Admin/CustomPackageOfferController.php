<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\CustomPackageOffer;
use App\Models\Package\CustomPackageOfferRequest;
use App\Models\Package\Package;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\User;
use App\Models\WebsiteIdentity;
use App\Notifications\CustomPackageOfferNotification;
use App\Notifications\OrderBaruNotification;
use App\Services\EventDateReservationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomPackageOfferController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.index'), 404);

        $this->markExpiredOffers();

        $status = (string) $request->query('status', '');
        $offers = $this->latestOffersQuery()
            ->with(['user', 'items', 'basedPackage', 'sourceRequest'])
            ->when(in_array($status, ['draft', 'sent', 'revision_requested', 'accepted', 'rejected', 'expired', 'superseded'], true), fn ($query) => $query->where('status', $status))
            ->latest('sent_at')
            ->paginate(20)
            ->withQueryString();

        return view('pages.package-offers.index', compact('offers', 'status'));
    }

    public function create(Request $request)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.store'), 404);

        $this->markExpiredOffers();
        $previous = null;
        $sourceRequest = null;
        $selectedOption = null;

        if ($request->filled('revision_of')) {
            $previous = CustomPackageOffer::with('items')
                ->where('uuid', $request->query('revision_of'))
                ->firstOrFail();

            abort_unless(
                $previous->isLatestVersion() &&
                ! in_array($previous->status, ['accepted', 'rejected', 'superseded']),
                404
            );
            abort_if(
                CustomPackageOffer::where('thread_uuid', $previous->thread_uuid)->where('status', 'draft')->exists(),
                409,
                'Selesaikan atau hapus draf revisi yang sudah ada terlebih dahulu.'
            );
        } elseif ($request->filled('request_uuid')) {
            $sourceRequest = CustomPackageOfferRequest::with('user')
                ->where('uuid', $request->query('request_uuid'))
                ->firstOrFail();
            abort_unless(in_array($sourceRequest->status, ['submitted', 'in_review'], true), 404);
            $existingOffer = CustomPackageOffer::where('source_request_uuid', $sourceRequest->uuid)
                ->orderByDesc('version')
                ->first();
            if ($existingOffer) {
                return redirect()->route(
                    $existingOffer->status === 'draft' ? 'package-offers.edit' : 'package-offers.show',
                    $existingOffer->uuid
                );
            }
        } elseif ($request->filled('based_option_uuid')) {
            $selectedOption = $this->findActiveBaseOption((string) $request->query('based_option_uuid'));
        }

        $users = User::role('user')->orderBy('name')->get(['uuid', 'name', 'email']);
        $baseOptions = PackageOption::query()
            ->with('package:uuid,name')
            ->where('is_active', true)
            ->whereHas('package', fn ($query) => $query->where('is_active', 'active'))
            ->orderBy('sort_order')
            ->get();

        $defaultItems = $previous
            ? $previous->items->map(fn ($item) => [
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ])->all()
            : ($selectedOption ? [[
                'name' => $selectedOption->package->name.' — '.$selectedOption->name,
                'description' => $selectedOption->package->description,
                'quantity' => 1,
                'unit_price' => $this->finalOptionPrice($selectedOption),
            ]] : []);

        $draft = null;

        return view('pages.package-offers.create', compact(
            'users',
            'baseOptions',
            'previous',
            'draft',
            'sourceRequest',
            'selectedOption',
            'defaultItems'
        ));
    }

    public function edit(CustomPackageOffer $offer)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);
        abort_unless($offer->status === 'draft', 404);

        $offer->load(['items', 'user', 'previousOffer', 'sourceRequest.user', 'basedPackageOption.package']);
        $previous = $offer->previousOffer;
        $sourceRequest = $offer->sourceRequest;
        $selectedOption = $offer->basedPackageOption;
        $users = User::role('user')->orderBy('name')->get(['uuid', 'name', 'email']);
        $baseOptions = PackageOption::query()
            ->with('package:uuid,name')
            ->where('is_active', true)
            ->whereHas('package', fn ($query) => $query->where('is_active', 'active'))
            ->orderBy('sort_order')
            ->get();
        $defaultItems = $offer->items->map(fn ($item) => [
            'name' => $item->name,
            'description' => $item->description,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
        ])->all();

        return view('pages.package-offers.create', [
            'users' => $users,
            'baseOptions' => $baseOptions,
            'previous' => $previous,
            'draft' => $offer,
            'sourceRequest' => $sourceRequest,
            'selectedOption' => $selectedOption,
            'defaultItems' => $defaultItems,
        ]);
    }

    public function store(Request $request, EventDateReservationService $reservations)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.store'), 404);

        $data = $this->validatedOffer($request);
        $user = User::role('user')->where('uuid', $data['user_uuid'])->firstOrFail();
        $sourceRequest = filled($data['source_request_uuid'] ?? null)
            ? CustomPackageOfferRequest::where('uuid', $data['source_request_uuid'])->firstOrFail()
            : null;
        abort_unless(! $sourceRequest || (
            $sourceRequest->user_uuid === $user->uuid &&
            in_array($sourceRequest->status, ['submitted', 'in_review'], true)
        ), 422);
        $baseOption = filled($data['based_option_uuid'] ?? null)
            ? $this->findActiveBaseOption($data['based_option_uuid'])
            : null;
        [$items, $subtotal, $discount, $total] = $this->priceOffer($data);

        $this->validateEventDate(
            $data['event_date'],
            $baseOption?->package,
            $reservations
        );

        $offer = DB::transaction(function () use ($data, $user, $baseOption, $sourceRequest, $items, $subtotal, $discount, $total) {
            if ($sourceRequest) {
                $lockedRequest = CustomPackageOfferRequest::where('uuid', $sourceRequest->uuid)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $lockedRequest->user_uuid !== $user->uuid ||
                    ! in_array($lockedRequest->status, ['submitted', 'in_review'], true) ||
                    CustomPackageOffer::where('source_request_uuid', $lockedRequest->uuid)->exists()
                ) {
                    throw ValidationException::withMessages([
                        'source_request_uuid' => 'Permintaan ini sudah ditangani atau sudah memiliki penawaran.',
                    ]);
                }
            }

            $threadUuid = (string) Str::uuid();
            $offer = CustomPackageOffer::create([
                'thread_uuid' => $threadUuid,
                'user_uuid' => $user->uuid,
                'created_by_uuid' => auth()->id(),
                'based_package_uuid' => $baseOption?->package_uuid,
                'based_package_option_uuid' => $baseOption?->uuid,
                'source_request_uuid' => $sourceRequest?->uuid,
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'event_date' => $data['event_date'],
                'guest_count' => $data['guest_count'] ?? null,
                'venue_note' => $data['venue_note'] ?? null,
                'customer_note' => $data['customer_note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
                'version' => 1,
                'status' => 'draft',
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'deposit_percentage' => (int) $data['deposit_percentage'],
                'payment_terms_note' => $data['payment_terms_note'] ?? null,
            ]);

            $this->saveItems($offer, $items);

            return $offer;
        });

        return redirect()
            ->route('package-offers.show', $offer->uuid)
            ->with('success', 'Draf tersimpan. Periksa pratinjau sebelum mengirim penawaran kepada pengguna.');
    }

    public function update(Request $request, CustomPackageOffer $offer, EventDateReservationService $reservations)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);
        abort_unless($offer->status === 'draft', 404);

        $data = $this->validatedOffer($request);
        abort_unless($data['user_uuid'] === $offer->user_uuid, 422);
        $baseOption = filled($data['based_option_uuid'] ?? null)
            ? $this->findActiveBaseOption($data['based_option_uuid'])
            : null;
        [$items, $subtotal, $discount, $total] = $this->priceOffer($data);
        $this->validateEventDate($data['event_date'], $baseOption?->package, $reservations);

        DB::transaction(function () use ($offer, $data, $baseOption, $items, $subtotal, $discount, $total) {
            $locked = CustomPackageOffer::where('uuid', $offer->uuid)->lockForUpdate()->firstOrFail();
            abort_unless($locked->status === 'draft', 404);

            $locked->update([
                'based_package_uuid' => $baseOption?->package_uuid,
                'based_package_option_uuid' => $baseOption?->uuid,
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'event_date' => $data['event_date'],
                'guest_count' => $data['guest_count'] ?? null,
                'venue_note' => $data['venue_note'] ?? null,
                'customer_note' => $data['customer_note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'deposit_percentage' => (int) $data['deposit_percentage'],
                'payment_terms_note' => $data['payment_terms_note'] ?? null,
            ]);
            $locked->items()->delete();
            $this->saveItems($locked, $items);
        });

        return redirect()
            ->route('package-offers.show', $offer->uuid)
            ->with('success', 'Perubahan draf berhasil disimpan. Tinjau kembali sebelum mengirim.');
    }

    public function sendDraft(CustomPackageOffer $offer, EventDateReservationService $reservations)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);

        $sentOffer = DB::transaction(function () use ($offer, $reservations) {
            $locked = CustomPackageOffer::with(['user', 'sourceRequest', 'basedPackage'])
                ->where('uuid', $offer->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'draft' || ! $locked->isLatestVersion(true)) {
                throw ValidationException::withMessages([
                    'offer' => 'Draf sudah berubah atau bukan versi terbaru.',
                ]);
            }
            if (! $locked->user) {
                throw ValidationException::withMessages([
                    'offer' => 'Pengguna penawaran sudah tidak tersedia.',
                ]);
            }

            $this->validateEventDate(
                $locked->event_date->toDateString(),
                $locked->basedPackage,
                $reservations
            );

            $previous = $locked->previousOffer
                ? CustomPackageOffer::where('uuid', $locked->previous_offer_uuid)->lockForUpdate()->first()
                : null;

            if ($previous && (
                ! $previous->isLatestVersion() ||
                in_array($previous->status, ['accepted', 'rejected', 'superseded'], true)
            )) {
                throw ValidationException::withMessages([
                    'offer' => 'Versi sebelumnya sudah ditutup. Draf ini tidak dapat dikirim.',
                ]);
            }

            $locked->update([
                'status' => 'sent',
                'sent_at' => now(),
                'expires_at' => now()->addDays(7),
            ]);

            if ($previous) {
                $previous->update(['status' => 'superseded']);
            }

            if ($locked->sourceRequest) {
                $locked->sourceRequest->update([
                    'status' => 'quoted',
                    'assigned_admin_uuid' => auth()->id(),
                    'handled_at' => now(),
                ]);
            }

            $locked->user->notify(new CustomPackageOfferNotification(
                $locked->uuid,
                $previous ? 'Penawaran paket diperbarui' : 'Penawaran paket khusus',
                'Penawaran '.$locked->title.' tersedia hingga '.$locked->expires_at->translatedFormat('d F Y').'.'
            ));

            return $locked;
        });

        return redirect()
            ->route('package-offers.show', $sentOffer->uuid)
            ->with('success', 'Penawaran berhasil dikirim dan pelanggan telah diberi notifikasi.');
    }

    public function discardDraft(CustomPackageOffer $offer)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);

        DB::transaction(function () use ($offer) {
            $locked = CustomPackageOffer::where('uuid', $offer->uuid)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless($locked->status === 'draft', 404);

            $locked->items()->delete();
            $locked->delete();
        });

        return redirect()
            ->route('package-offers.index')
            ->with('success', 'Draf penawaran dihapus. Penawaran yang sebelumnya dikirim tetap tidak berubah.');
    }

    public function mine()
    {
        $user = auth()->user();

        if ($user?->hasAnyRole(['super-admin', 'admin']) && $user->can('package.index')) {
            return redirect()->route('package-offers.index');
        }

        abort_unless($user?->hasRole('user') && $user->can('package.member'), 404);

        $this->markExpiredOffers();
        $offers = $this->latestOffersQuery()
            ->where('status', '!=', 'draft')
            ->where('user_uuid', auth()->id())
            ->with(['items', 'basedPackage'])
            ->latest('sent_at')
            ->paginate(15);

        return view('pages.package-offers.mine', compact('offers'));
    }

    public function show(CustomPackageOffer $offer)
    {
        $user = auth()->user();
        $isAdmin = $user?->hasAnyRole(['super-admin', 'admin']) ?? false;

        abort_unless(
            ($isAdmin && $user->can('package.index')) ||
            ($user?->hasRole('user') && $offer->user_uuid === $user->uuid),
            404
        );
        abort_if(! $isAdmin && $offer->status === 'draft', 404);

        if ($offer->status === 'sent' && $offer->expires_at?->isPast()) {
            $offer->update(['status' => 'expired']);
        }

        $offer->load(['user', 'createdBy', 'items', 'basedPackage', 'basedPackageOption', 'orders' => fn ($query) => $query->latest()]);
        $versions = $offer->versions()
            ->when(! $isAdmin, fn ($query) => $query->where('status', '!=', 'draft'))
            ->with('createdBy')
            ->get();
        $isLatest = $offer->isLatestVersion($isAdmin);
        $activeOrder = $offer->orders->first(fn (Order $order) => in_array($order->status, ['partially_paid', 'paid'], true)
            || ($order->status === 'pending' && ! $order->expired_at?->isPast()));

        return view('pages.package-offers.show', compact('offer', 'versions', 'isAdmin', 'isLatest', 'activeOrder'));
    }

    public function downloadPdf(CustomPackageOffer $offer)
    {
        $user = auth()->user();
        $isAdmin = $user?->hasAnyRole(['super-admin', 'admin']) ?? false;
        abort_unless(
            ($isAdmin && $user->can('package.index')) ||
            ($user?->hasRole('user') && $offer->user_uuid === $user->uuid && $offer->status !== 'draft'),
            404
        );

        $offer->load(['user', 'items', 'basedPackage']);
        $identity = WebsiteIdentity::singleton();

        return Pdf::loadView('pages.package-offers.pdf', compact('offer', 'identity'))
            ->setPaper('a4')
            ->download(Str::slug($offer->title).'-v'.$offer->version.'.pdf');
    }

    public function respond(
        Request $request,
        CustomPackageOffer $offer,
        EventDateReservationService $reservations
    ) {
        abort_unless(auth()->user()?->hasRole('user') && $offer->user_uuid === auth()->id(), 404);

        $data = $request->validate([
            'action' => ['required', Rule::in(['accept', 'request_revision', 'reject'])],
            'revision_note' => ['nullable', 'string', 'max:3000', 'required_if:action,request_revision'],
        ]);

        $result = DB::transaction(function () use ($offer, $data, $reservations) {
            $locked = CustomPackageOffer::with(['user', 'basedPackage', 'basedPackageOption'])
                ->where('uuid', $offer->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($locked->user_uuid === auth()->id(), 404);

            if (! $locked->isLatestVersion()) {
                throw ValidationException::withMessages([
                    'offer' => 'Versi penawaran ini sudah digantikan. Buka versi terbaru untuk melanjutkan.',
                ]);
            }

            if ($locked->status === 'sent' && $locked->expires_at?->isPast()) {
                $locked->update(['status' => 'expired']);

                return 'expired';
            }

            if ($locked->status !== 'sent') {
                throw ValidationException::withMessages([
                    'offer' => 'Penawaran ini sudah tidak dapat ditanggapi.',
                ]);
            }

            if ($data['action'] === 'accept') {
                $this->validateEventDate($locked->event_date->toDateString(), $locked->basedPackage, $reservations);
                $locked->update([
                    'status' => 'accepted',
                    'accepted_at' => now(),
                    'revision_note' => null,
                ]);
                $this->notifyAdmins($locked, 'Penawaran disetujui pelanggan', $locked->user?->name.' menyetujui penawaran '.$locked->title.'.', 'bi-check-circle');

                return 'accepted';
            }

            if ($data['action'] === 'request_revision') {
                $locked->update([
                    'status' => 'revision_requested',
                    'revision_note' => trim($data['revision_note']),
                ]);
                $this->notifyAdmins($locked, 'Permintaan revisi penawaran', $locked->user?->name.' meminta perubahan pada penawaran '.$locked->title.'.', 'bi-pencil-square');

                return 'revision_requested';
            }

            $locked->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);
            $this->notifyAdmins($locked, 'Penawaran ditolak pelanggan', $locked->user?->name.' menolak penawaran '.$locked->title.'.', 'bi-x-circle');

            return 'rejected';
        });

        if ($result === 'expired') {
            return back()->with('error', 'Masa berlaku penawaran sudah berakhir. Silakan hubungi admin untuk penawaran baru.');
        }

        if ($result === 'accepted') {
            return redirect()
                ->route('package-offers.checkout', $offer->uuid)
                ->with('success', 'Penawaran disetujui. Silakan lanjutkan ke checkout.');
        }

        return back()->with('success', match ($result) {
            'revision_requested' => 'Permintaan revisi telah dikirim kepada admin.',
            default => 'Penawaran telah ditolak.',
        });
    }

    public function revise(Request $request, CustomPackageOffer $offer, EventDateReservationService $reservations)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) && auth()->user()->can('package.update'), 404);

        $data = $this->validatedOffer($request, true);
        $previous = CustomPackageOffer::with('user')
            ->where('uuid', $offer->uuid)
            ->firstOrFail();

        if ($previous->status === 'accepted' || $previous->status === 'rejected' || ! $previous->isLatestVersion()) {
            throw ValidationException::withMessages([
                'offer' => 'Penawaran ini sudah ditutup atau telah digantikan.',
            ]);
        }

        $user = $previous->user;
        $baseOption = filled($data['based_option_uuid'] ?? null)
            ? $this->findActiveBaseOption($data['based_option_uuid'])
            : null;
        [$items, $subtotal, $discount, $total] = $this->priceOffer($data);

        $this->validateEventDate($data['event_date'], $baseOption?->package, $reservations);

        $newOffer = DB::transaction(function () use ($data, $previous, $user, $baseOption, $items, $subtotal, $discount, $total) {
            $lockedPrevious = CustomPackageOffer::where('uuid', $previous->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                ! $lockedPrevious->isLatestVersion() ||
                in_array($lockedPrevious->status, ['accepted', 'rejected', 'superseded'], true)
            ) {
                throw ValidationException::withMessages([
                    'offer' => 'Penawaran sudah berubah atau tidak dapat direvisi.',
                ]);
            }

            if (CustomPackageOffer::where('thread_uuid', $lockedPrevious->thread_uuid)->where('status', 'draft')->exists()) {
                throw ValidationException::withMessages([
                    'offer' => 'Selesaikan atau hapus draf revisi yang sudah ada terlebih dahulu.',
                ]);
            }

            $version = (int) CustomPackageOffer::where('thread_uuid', $lockedPrevious->thread_uuid)->max('version') + 1;
            $newOffer = CustomPackageOffer::create([
                'thread_uuid' => $lockedPrevious->thread_uuid,
                'user_uuid' => $user->uuid,
                'created_by_uuid' => auth()->id(),
                'based_package_uuid' => $baseOption?->package_uuid,
                'based_package_option_uuid' => $baseOption?->uuid,
                'previous_offer_uuid' => $lockedPrevious->uuid,
                'source_request_uuid' => $lockedPrevious->source_request_uuid,
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'event_date' => $data['event_date'],
                'guest_count' => $data['guest_count'] ?? null,
                'venue_note' => $data['venue_note'] ?? null,
                'customer_note' => $data['customer_note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
                'revision_note' => null,
                'version' => $version,
                'status' => 'draft',
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'deposit_percentage' => (int) $data['deposit_percentage'],
                'payment_terms_note' => $data['payment_terms_note'] ?? null,
            ]);

            $this->saveItems($newOffer, $items);

            return $newOffer;
        });

        return redirect()
            ->route('package-offers.show', $newOffer->uuid)
            ->with('success', 'Draf revisi tersimpan. Versi sebelumnya tetap terlihat oleh pelanggan sampai draf ini dikirim.');
    }

    public function checkout(CustomPackageOffer $offer)
    {
        abort_unless(auth()->user()?->hasRole('user') && $offer->user_uuid === auth()->id(), 404);
        abort_unless($offer->isLatestVersion() && $offer->status === 'accepted', 404);

        $offer->load('items');
        $dueNow = (int) round($offer->total_amount * ($offer->deposit_percentage / 100));

        return view('pages.package-offers.checkout', compact('offer', 'dueNow'));
    }

    public function createOrder(
        CustomPackageOffer $offer,
        EventDateReservationService $reservations
    ) {
        abort_unless(auth()->user()?->hasRole('user') && $offer->user_uuid === auth()->id(), 404);

        $order = DB::transaction(function () use ($offer, $reservations) {
            $lockedOffer = CustomPackageOffer::with('items')
                ->where('uuid', $offer->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($lockedOffer->user_uuid === auth()->id(), 404);
            abort_unless($lockedOffer->isLatestVersion() && $lockedOffer->status === 'accepted', 404);

            $existingOrder = Order::packageOnly()
                ->where('custom_package_offer_uuid', $lockedOffer->uuid)
                ->where(function ($query) {
                    $query->whereIn('status', ['partially_paid', 'paid'])
                        ->orWhere(function ($pendingQuery) {
                            $pendingQuery->where('status', 'pending')
                                ->where(function ($expiryQuery) {
                                    $expiryQuery->whereNull('expired_at')
                                        ->orWhere('expired_at', '>', now());
                                });
                        });
                })
                ->latest('created_at')
                ->first();

            if ($existingOrder) {
                return $existingOrder;
            }

            $reservation = $reservations->lockDate($lockedOffer->event_date);
            $fullAmount = (int) $lockedOffer->total_amount;
            $depositPercentage = (int) $lockedOffer->deposit_percentage;
            $dueNow = (int) round($fullAmount * ($depositPercentage / 100));
            $remaining = max(0, $fullAmount - $dueNow);
            $isFullPayment = $remaining === 0;
            $schemeLabel = $isFullPayment ? 'Bayar Lunas' : 'DP '.$depositPercentage.'%';
            $snapshotItems = $lockedOffer->items->map(fn ($item) => [
                'uuid' => $item->uuid,
                'name' => $item->name,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'line_total' => $item->line_total,
            ])->all();

            $order = Order::create([
                'uuid' => (string) Str::uuid(),
                'user_uuid' => $lockedOffer->user_uuid,
                'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                'type' => 'package',
                'package_uuid' => $lockedOffer->based_package_uuid,
                'package_option_uuid' => null,
                'custom_package_offer_uuid' => $lockedOffer->uuid,
                'event_date' => $lockedOffer->event_date->toDateString(),
                'amount' => $dueNow,
                'payment_scheme' => $isFullPayment ? 'full' : 'custom_dp',
                'total_amount' => $fullAmount,
                'amount_paid' => 0,
                'remaining_amount' => $fullAmount,
                'balance_due_at' => $remaining > 0
                    ? $lockedOffer->event_date->copy()->subDays(30)->startOfDay()
                    : null,
                'installment_schedule' => $this->buildInstallmentSchedule(
                    $lockedOffer->event_date,
                    $dueNow,
                    $fullAmount
                ),
                'installment_reminders_sent' => [],
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
                'addons' => $snapshotItems,
                'promo_snapshot' => [
                    'custom_package_offer_uuid' => $lockedOffer->uuid,
                    'custom_package_offer_version' => $lockedOffer->version,
                    'scheme_label' => $schemeLabel,
                    'full_amount' => $fullAmount,
                    'booking_fee' => 0,
                    'due_now' => $dueNow,
                    'deposit_percentage' => $depositPercentage,
                ],
                'approval_snapshot' => [
                    'custom_package_offer' => [
                        'uuid' => $lockedOffer->uuid,
                        'thread_uuid' => $lockedOffer->thread_uuid,
                        'version' => $lockedOffer->version,
                        'title' => $lockedOffer->title,
                        'description' => $lockedOffer->description,
                        'items' => $snapshotItems,
                        'subtotal_amount' => $lockedOffer->subtotal_amount,
                        'discount_amount' => $lockedOffer->discount_amount,
                        'total_amount' => $lockedOffer->total_amount,
                        'deposit_percentage' => $depositPercentage,
                        'payment_terms_note' => $lockedOffer->payment_terms_note,
                    ],
                ],
            ]);

            $reservations->hold($reservation, $order);

            $admins = User::role(['super-admin', 'admin'])
                ->where('uuid', '!=', $lockedOffer->user_uuid)
                ->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new OrderBaruNotification(
                    $order->uuid,
                    $order->order_number,
                    auth()->user()->name,
                    $lockedOffer->title,
                    (float) $dueNow,
                ));
            }

            return $order;
        });

        return redirect()
            ->route('orders.show', $order->uuid)
            ->with('success', 'Pesanan berhasil dibuat. Tanggal acara ditahan sementara sampai pembayaran diverifikasi.');
    }

    private function validatedOffer(Request $request, bool $isRevision = false): array
    {
        return $request->validate([
            'user_uuid' => [$isRevision ? 'nullable' : 'required', 'uuid', 'exists:users,uuid'],
            'source_request_uuid' => ['nullable', 'uuid', 'exists:custom_package_offer_requests,uuid'],
            'based_option_uuid' => ['nullable', 'uuid', 'exists:package_options,uuid'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'event_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.now()->toDateString()],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'venue_note' => ['nullable', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:5000'],
            'admin_note' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:2000'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.unit_price' => ['required', 'integer', 'min:0', 'max:1000000000000'],
            'discount_amount' => ['nullable', 'integer', 'min:0', 'max:1000000000000000'],
            'deposit_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'payment_terms_note' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'user_uuid' => 'pengguna',
            'based_option_uuid' => 'paket dasar',
            'title' => 'nama penawaran',
            'event_date' => 'tanggal acara',
            'guest_count' => 'jumlah tamu',
            'items.*.name' => 'nama layanan',
            'items.*.quantity' => 'jumlah layanan',
            'items.*.unit_price' => 'harga satuan',
            'discount_amount' => 'diskon',
            'deposit_percentage' => 'persentase DP',
        ]);
    }

    private function buildItems(array $items): array
    {
        return collect($items)
            ->values()
            ->map(function (array $item, int $index) {
                $quantity = (int) $item['quantity'];
                $unitPrice = (int) $item['unit_price'];

                return [
                    'name' => trim($item['name']),
                    'description' => filled($item['description'] ?? null) ? trim($item['description']) : null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $quantity * $unitPrice,
                    'sort_order' => $index,
                ];
            })
            ->all();
    }

    private function priceOffer(array $data): array
    {
        $items = $this->buildItems($data['items']);
        $subtotal = array_sum(array_column($items, 'line_total'));
        $discount = (int) ($data['discount_amount'] ?? 0);

        if ($discount > $subtotal) {
            throw ValidationException::withMessages([
                'discount_amount' => 'Diskon tidak boleh melebihi subtotal penawaran.',
            ]);
        }

        $total = $subtotal - $discount;
        if ($total < 1) {
            throw ValidationException::withMessages([
                'items' => 'Total penawaran harus lebih dari Rp 0.',
            ]);
        }

        return [$items, $subtotal, $discount, $total];
    }

    private function notifyAdmins(
        CustomPackageOffer $offer,
        string $title,
        string $message,
        string $icon
    ): void {
        $admins = User::role(['super-admin', 'admin'])
            ->where('uuid', '!=', $offer->user_uuid)
            ->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new CustomPackageOfferNotification(
                $offer->uuid,
                $title,
                $message,
                $icon
            ));
        }
    }

    private function saveItems(CustomPackageOffer $offer, array $items): void
    {
        foreach ($items as $item) {
            $offer->items()->create($item);
        }
    }

    private function findActiveBaseOption(string $uuid): PackageOption
    {
        return PackageOption::with('package')
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->whereHas('package', fn ($query) => $query->where('is_active', 'active'))
            ->firstOrFail();
    }

    private function finalOptionPrice(PackageOption $option): int
    {
        return $option->discount_price !== null && $option->discount_price < $option->price
            ? (int) $option->discount_price
            : (int) $option->price;
    }

    private function validateEventDate(
        string $date,
        ?Package $package,
        EventDateReservationService $reservations
    ): void {
        $eventDate = Carbon::parse($date)->startOfDay();
        $earliest = now()->startOfDay()->addDays((int) ($package?->lead_time_days ?? 0));

        if ($eventDate->lt($earliest)) {
            throw ValidationException::withMessages([
                'event_date' => 'Tanggal acara belum memenuhi waktu persiapan minimum.',
            ]);
        }

        if ($package && PackageBlackoutDate::where('package_uuid', $package->uuid)
            ->whereDate('date', $eventDate->toDateString())
            ->exists()) {
            throw ValidationException::withMessages([
                'event_date' => 'Paket dasar tidak tersedia pada tanggal tersebut.',
            ]);
        }

        if ($reservations->isUnavailable($eventDate)) {
            throw ValidationException::withMessages([
                'event_date' => 'Tanggal acara sudah dipesan atau sedang ditahan. Silakan pilih tanggal lain.',
            ]);
        }
    }

    private function buildInstallmentSchedule(Carbon $eventDate, int $initialDueAmount, int $fullAmount): array
    {
        $scheduledBalance = max(0, $fullAmount - $initialDueAmount);
        if ($scheduledBalance === 0) {
            return [
                'policy' => 'equal_remaining_h90_h60_h30',
                'initial_due' => $initialDueAmount,
                'installments' => [],
            ];
        }

        $baseAmount = intdiv($scheduledBalance, 3);
        $remainder = $scheduledBalance % 3;
        $installmentsByDate = [];

        foreach ([90, 60, 30] as $index => $daysBeforeEvent) {
            $dueAt = $eventDate->copy()->subDays($daysBeforeEvent)->startOfDay();
            if ($dueAt->lt(now()->startOfDay())) {
                $dueAt = now()->startOfDay();
            }

            $dateKey = $dueAt->toDateString();
            $installmentsByDate[$dateKey] ??= [
                'labels' => [],
                'due_at' => $dateKey,
                'amount' => 0,
            ];
            $installmentsByDate[$dateKey]['labels'][] = 'Termin '.($index + 1);
            $installmentsByDate[$dateKey]['amount'] += $baseAmount + ($index < $remainder ? 1 : 0);
        }

        return [
            'policy' => 'equal_remaining_h90_h60_h30',
            'initial_due' => $initialDueAmount,
            'installments' => array_map(fn (array $installment) => [
                'label' => implode(' & ', $installment['labels']),
                'due_at' => $installment['due_at'],
                'amount' => $installment['amount'],
            ], array_values($installmentsByDate)),
        ];
    }

    private function latestOffersQuery(): Builder
    {
        return CustomPackageOffer::query()->whereNotExists(function ($query) {
            $query->selectRaw('1')
                ->from('custom_package_offers as newer')
                ->whereColumn('newer.thread_uuid', 'custom_package_offers.thread_uuid')
                ->whereColumn('newer.version', '>', 'custom_package_offers.version')
                ->where('newer.status', '!=', 'draft');
        });
    }

    private function markExpiredOffers(): void
    {
        CustomPackageOffer::query()
            ->where('status', 'sent')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);
    }
}
