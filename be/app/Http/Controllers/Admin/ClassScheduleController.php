<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\ClassBooking;
use App\Models\Class\ClassModel;
use App\Models\Class\ClassSchedule;
use App\Models\Payment\Order;
use App\Models\Venue;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $day = (string) $request->get('day', '');
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $day = in_array($day, $days, true) ? $day : '';
        $venueUuid = (string) $request->get('venue_uuid', '');

        $query = ClassSchedule::with([
            'class.vendor',
            'venue',
        ])->where('status', 'active');

        if ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->whereHas('class', function ($q) use ($like) {
                $q->where('name', 'like', $like);
            });
        }

        if ($day !== '') {
            $query->where('day', $day);
        }

        if ($venueUuid !== '') {
            $query->where('venue_uuid', $venueUuid);
        }

        $schedules = (clone $query)
            ->orderByRaw("
            FIELD(
                day,
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            )
        ")
            ->orderBy('start_time')
            ->get();

        $stats = [
            'total' => (clone $query)->count(),
            'monday_friday' => (clone $query)->whereIn('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])->count(),
            'weekend' => (clone $query)->whereIn('day', ['saturday', 'sunday'])->count(),
            'venues' => (clone $query)->distinct('venue_uuid')->count('venue_uuid'),
        ];

        $classes = ClassModel::where('is_active', true)
            ->orderBy('name')
            ->get();

        $venues = Venue::where('status', 'active')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Member membership status (paywall banner)
        |--------------------------------------------------------------------------
        */

        $authUser = Auth::user();
        $isMember = $authUser && $authUser->hasRole('user');
        $activePackage = null;
        $pendingOrder = null;

        if ($isMember) {
            $activePackage = MembershipService::activePackage($authUser);
            $pendingOrder = Order::where('user_uuid', $authUser->uuid)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        $myBookings = collect();
        $todayDate = now()->format('Y-m-d');

        if ($isMember) {
            $myBookings = ClassBooking::where('user_uuid', $authUser->uuid)
                ->where('status', '!=', 'cancelled')
                ->get()
                ->keyBy(fn ($b) => $b->class_schedule_uuid.'|'.$b->booking_date?->format('Y-m-d'));
        }

        return view(
            'pages.class-schedule.index',
            compact(
                'schedules',
                'classes',
                'venues',
                'stats',
                'search',
                'day',
                'venueUuid',
                'isMember',
                'activePackage',
                'pendingOrder',
                'myBookings',
                'todayDate'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_uuid' => 'required|uuid|exists:venues,uuid',
            'class_uuid' => 'required|uuid|exists:classes,uuid',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        // Pastikan end time lebih besar dari start time
        $start = Carbon::createFromFormat(
            'H:i',
            $request->start_time
        );

        $end = Carbon::createFromFormat(
            'H:i',
            $request->end_time
        );

        if ($end->lessThanOrEqualTo($start)) {
            return back()
                ->withErrors([
                    'end_time' => 'End time must be after start time.',
                ])
                ->withInput();
        }

        // Cek bentrok pada venue yang sama
        $conflict = ClassSchedule::where('venue_uuid', $request->venue_uuid)
            ->where('day', $request->day)
            ->where('status', 'active')
            ->where(function ($query) use ($request) {
                $query->where(
                    'start_time',
                    '<',
                    $request->end_time
                )
                    ->where(
                        'end_time',
                        '>',
                        $request->start_time
                    );
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors([
                    'start_time' => 'This venue already has a class scheduled at this time.',
                ])
                ->withInput();
        }

        DB::beginTransaction();

        try {

            ClassSchedule::create([
                'uuid' => (string) Str::uuid(),
                'venue_uuid' => $request->venue_uuid,
                'class_uuid' => $request->class_uuid,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'capacity' => $request->capacity,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('class-schedules.index')
                ->with(
                    'success',
                    'Jadwal kelas berhasil ditambahkan.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($uuid, Request $request)
    {
        $request->validate([
            'venue_uuid' => 'required|uuid|exists:venues,uuid',
            'class_uuid' => 'required|uuid|exists:classes,uuid',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {

            $schedule = ClassSchedule::where('uuid', $uuid)
                ->firstOrFail();

            /*
        |--------------------------------------------------------------------------
        | Check Schedule Conflict
        |--------------------------------------------------------------------------
        | Hanya boleh ada satu kelas pada venue yang sama,
        | di hari dan waktu yang saling bertabrakan.
        */

            $conflict = ClassSchedule::where('venue_uuid', $request->venue_uuid)
                ->where('day', $request->day)
                ->where('status', 'active')
                ->where('uuid', '!=', $schedule->uuid)
                ->where(function ($query) use ($request) {
                    $query->where(
                        'start_time',
                        '<',
                        $request->end_time
                    )->where(
                        'end_time',
                        '>',
                        $request->start_time
                    );
                })
                ->exists();

            if ($conflict) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'start_time' => 'This venue already has a class scheduled at this time.',
                    ]);
            }

            $schedule->update([
                'venue_uuid' => $request->venue_uuid,
                'class_uuid' => $request->class_uuid,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'capacity' => $request->capacity,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('class-schedules.index')
                ->with(
                    'success',
                    'Jadwal kelas berhasil diperbarui.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan: '.$th->getMessage()
                );
        }
    }

    public function print()
    {
        $schedules = ClassSchedule::with([
            'class.vendor',
        ])
            ->where('status', 'active')
            ->get();

        return view(
            'pages.class-schedule.print',
            compact('schedules')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();

        try {
            $schedule = ClassSchedule::where('uuid', $uuid)
                ->firstOrFail();

            $schedule->update([
                'status' => 'inactive',
            ]);

            DB::commit();

            return redirect()
                ->route('class-schedules.index')
                ->with('success', 'Jadwal kelas berhasil dinonaktifkan.');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menonaktifkan jadwal kelas.');
        }
    }
}
