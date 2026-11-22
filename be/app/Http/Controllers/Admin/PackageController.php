<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Package\PackageWishlist;
use App\Support\MemberView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $context = $this->packageListContext($request);
        extract($context);

        $packages = (clone $query)
            ->with(['features', 'options', 'addons', 'blackoutDates', 'promos'])
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('is_active', 'active')->count(),
            'inactive' => (clone $query)->where('is_active', 'inactive')->count(),
            'popular' => (clone $query)->where('is_popular', 1)->count(),
            'published' => (clone $query)->where('is_published', true)->count(),
            'draft' => (clone $query)->where('is_published', false)->count(),
        ];

        $lowQuotaOptions = $packages->getCollection()
            ->flatMap(function (Package $package) {
                return $package->options
                    ->filter(fn ($option) => $option->is_active && $option->quota !== null && $option->quota <= 5)
                    ->map(fn ($option) => [
                        'package' => $package->name,
                        'package_uuid' => $package->uuid,
                        'option' => $option->name,
                        'quota' => (int) $option->quota,
                    ]);
            })
            ->values();

        return view('pages.package.index', compact(
            'packages',
            'stats',
            'search',
            'is_active',
            'is_popular',
            'publish',
            'quota_type',
            'perPage',
            'sort',
            'direction',
            'searchTooShort',
            'lowQuotaOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.package.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'guest_capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'event_duration_hours' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'venue_note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'cover_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'is_active' => [
                'required',
                'in:active,inactive',
            ],

            'is_popular' => [
                'nullable',
                'boolean',
            ],

            'is_published' => [
                'nullable',
                'boolean',
            ],

            'lead_time_days' => [
                'nullable',
                'integer',
                'min:0',
                'max:365',
            ],

            'blackout_dates' => [
                'nullable',
            ],

            'options' => [
                'required',
                'array',
                'min:1',
            ],

            'options.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'options.*.quota' => [
                'required',
                'integer',
                'min:1',
            ],

            'options.*.price' => [
                'required',
                'integer',
                'min:0',
            ],

            'options.*.discount_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'options.*.duration' => [
                'required',
                'integer',
                'min:1',
            ],

            'options.*.duration_unit' => [
                'required',
                'in:day,week,month,year',
            ],

            'addons' => [
                'nullable',
                'array',
            ],

            'addons.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'addons.*.description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'addons.*.price' => [
                'required',
                'integer',
                'min:0',
            ],

            'addons.*.discount_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'addons.*.min_quantity' => [
                'nullable',
                'integer',
                'min:1',
                'max:99',
            ],

            'addons.*.max_quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'addons.*.requires_addon_index' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'promos' => [
                'nullable',
                'array',
            ],

            'promos.*.code' => [
                'required',
                'string',
                'max:255',
            ],

            'promos.*.type' => [
                'required',
                'in:percent,fixed',
            ],

            'promos.*.value' => [
                'required',
                'integer',
                'min:1',
            ],

            'promos.*.min_purchase' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'promos.*.max_discount' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'promos.*.max_uses' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'promos.*.starts_at' => [
                'nullable',
                'date',
            ],

            'promos.*.ends_at' => [
                'nullable',
                'date',
            ],

            'promos.*.is_active' => [
                'nullable',
                'boolean',
            ],

            'features' => [
                'nullable',
                'array',
            ],

            'features.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        if ($error = $this->validatePackageCollections($validated)) {
            return $error;
        }

        /*
    |--------------------------------------------------------------------------
    | Save Package
    |--------------------------------------------------------------------------
    */

        DB::transaction(function () use ($validated) {

            /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

            $baseSlug = Str::slug($validated['name']);

            if ($baseSlug === '') {
                $baseSlug = 'package';
            }

            $slug = $baseSlug;
            $counter = 2;

            while (
                Package::where('slug', $slug)->exists()
            ) {

                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            /*
        |--------------------------------------------------------------------------
        | Create Package
        |--------------------------------------------------------------------------
        */

            $firstOption = collect($validated['options'])->sortBy('sort_order')->first();
            $basePrice = (float) ($firstOption['price'] ?? 0);
            $package = Package::create([

                'uuid' => (string) Str::uuid(),

                'name' => $validated['name'],

                'slug' => $slug,

                'description' => $validated['description'] ?? null,

                'price' => $basePrice,

                'guest_capacity' => $validated['guest_capacity'] ?? null,

                'event_duration_hours' => $validated['event_duration_hours'] ?? null,

                'venue_note' => $validated['venue_note'] ?? null,

                'cover_url' => $validated['cover_url'] ?? null,

                'is_popular' => $validated['is_popular'] ?? false,

                'is_active' => $validated['is_active'],

                'is_published' => filter_var(
                    $validated['is_published'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),

                'published_at' => filter_var(
                    $validated['is_published'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ) ? now() : null,

                'lead_time_days' => $validated['lead_time_days'] ?? 30,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Create Package Options
        |--------------------------------------------------------------------------
        */

            foreach (
                array_values($validated['options']) as $index => $option
            ) {

                PackageOption::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'name' => $option['name'],

                    'quota' => $option['quota'],

                    'price' => $option['price'],

                    'discount_price' => $option['discount_price'] ?? null,

                    'duration' => $option['duration'],

                    'duration_unit' => $option['duration_unit'],

                    'sort_order' => $index,

                    'is_active' => true,

                ]);
            }

            $this->savePackageAddons($package, $validated);

            /*
         |--------------------------------------------------------------------------
         | Create Package Features
         |--------------------------------------------------------------------------
         */

            foreach (
                $validated['features'] ?? [] as $index => $feature
            ) {

                if (blank($feature)) {
                    continue;
                }

                PackageFeature::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'feature' => $feature,

                    'sort_order' => $index,

                ]);
            }

            $this->saveBlackoutDates($package, $validated);
            $this->savePackagePromos($package, $validated);
        });

        /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route('packages.index')
            ->with(
                'success',
                __('flash.package_created')
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $package = Package::with([
            'options' => function ($query) {
                $query->orderBy('sort_order');
            },
            'addons' => function ($query) {
                $query->orderBy('sort_order');
            },
            'features' => function ($query) {
                $query->orderBy('sort_order');
            },
            'blackoutDates' => function ($query) {
                $query->orderBy('date');
            },
            'promos' => function ($query) {
                $query->orderBy('sort_order');
            },
        ])->where('uuid', $uuid)->firstOrFail();

        return view('pages.package.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $package = Package::where('uuid', $uuid)
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'guest_capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'event_duration_hours' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'venue_note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'cover_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'is_active' => [
                'required',
                'in:active,inactive',
            ],

            'is_popular' => [
                'nullable',
                'boolean',
            ],

            'is_published' => [
                'nullable',
                'boolean',
            ],

            'lead_time_days' => [
                'nullable',
                'integer',
                'min:0',
                'max:365',
            ],

            'blackout_dates' => [
                'nullable',
            ],

            'options' => [
                'required',
                'array',
                'min:1',
            ],

            'options.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'options.*.quota' => [
                'required',
                'integer',
                'min:1',
            ],

            'options.*.price' => [
                'required',
                'integer',
                'min:0',
            ],

            'options.*.discount_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'options.*.duration' => [
                'required',
                'integer',
                'min:1',
            ],

            'options.*.duration_unit' => [
                'required',
                'in:day,week,month,year',
            ],

            'addons' => [
                'nullable',
                'array',
            ],

            'addons.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'addons.*.description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'addons.*.price' => [
                'required',
                'integer',
                'min:0',
            ],

            'addons.*.discount_price' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'addons.*.min_quantity' => [
                'nullable',
                'integer',
                'min:1',
                'max:99',
            ],

            'addons.*.max_quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'addons.*.requires_addon_index' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'promos' => [
                'nullable',
                'array',
            ],

            'promos.*.code' => [
                'required',
                'string',
                'max:255',
            ],

            'promos.*.type' => [
                'required',
                'in:percent,fixed',
            ],

            'promos.*.value' => [
                'required',
                'integer',
                'min:1',
            ],

            'promos.*.min_purchase' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'promos.*.max_discount' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'promos.*.max_uses' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'promos.*.starts_at' => [
                'nullable',
                'date',
            ],

            'promos.*.ends_at' => [
                'nullable',
                'date',
            ],

            'promos.*.is_active' => [
                'nullable',
                'boolean',
            ],

            'features' => [
                'nullable',
                'array',
            ],

            'features.*' => [
                'nullable',
                'string',
                'max:255',
            ],

        ]);

        if ($error = $this->validatePackageCollections($validated, $package)) {
            return $error;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Package
    |--------------------------------------------------------------------------
    */

        DB::transaction(function () use (
            $validated,
            $package
        ) {

            /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

            $baseSlug = Str::slug(
                $validated['name']
            );

            if ($baseSlug === '') {
                $baseSlug = 'package';
            }

            $slug = $baseSlug;
            $counter = 2;

            while (
                Package::where('slug', $slug)
                    ->where('uuid', '!=', $package->uuid)
                    ->exists()
            ) {

                $slug =
                    $baseSlug.'-'.$counter;

                $counter++;
            }

            /*
        |--------------------------------------------------------------------------
        | Update Package
        |--------------------------------------------------------------------------
        */

            $package->update([

                'name' => $validated['name'],

                'slug' => $slug,

                'description' => $validated['description'] ?? null,

                'guest_capacity' => $validated['guest_capacity'] ?? null,

                'event_duration_hours' => $validated['event_duration_hours'] ?? null,

                'venue_note' => $validated['venue_note'] ?? null,

                'cover_url' => $validated['cover_url'] ?? null,

                'is_popular' => $validated['is_popular'] ?? false,

                'is_active' => $validated['is_active'],

                'is_published' => filter_var(
                    $validated['is_published'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),

                'published_at' => filter_var(
                    $validated['is_published'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ) ? now() : null,

                'lead_time_days' => $validated['lead_time_days'] ?? 30,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Replace Package Options
        |--------------------------------------------------------------------------
        */

            $package->options()->delete();

            foreach (
                array_values($validated['options']) as $index => $option
            ) {

                PackageOption::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'name' => $option['name'],

                    'quota' => $option['quota'],

                    'price' => $option['price'],

                    'discount_price' => $option['discount_price'] ?? null,

                    'duration' => $option['duration'],

                    'duration_unit' => $option['duration_unit'],

                    'sort_order' => $index,

                    'is_active' => true,

                ]);
            }

            $this->savePackageAddons($package, $validated);

            /*
         |--------------------------------------------------------------------------
         | Replace Package Features
         |--------------------------------------------------------------------------
         */

            $package->features()->delete();

            foreach (
                $validated['features'] ?? [] as $index => $feature
            ) {

                if (blank($feature)) {
                    continue;
                }

                PackageFeature::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'feature' => $feature,

                    'sort_order' => $index,

                ]);
            }

            $this->saveBlackoutDates($package, $validated);
            $this->savePackagePromos($package, $validated);
        });

        /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route('packages.index')
            ->with(
                'success',
                __('flash.package_updated')
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();

        try {

            $package = Package::where('uuid', $uuid)
                ->firstOrFail();

            $package->delete();

            DB::commit();

            return redirect()
                ->route('packages.index')
                ->with('success', __('flash.package_deleted'));
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'string',
        ], [
            'action.required' => 'Pilih tindakan massal terlebih dahulu.',
            'action.in' => 'Tindakan massal tidak dikenali.',
            'ids.required' => 'Tidak ada paket yang dipilih.',
            'ids.min' => 'Tidak ada paket yang dipilih.',
            'ids.max' => 'Maksimal 100 paket yang dapat diproses sekali waktu.',
        ]);

        $action = $validated['action'];
        $permission = $action === 'delete' ? 'package.destroy' : 'package.update';
        abort_unless(auth()->user()?->can($permission), 404);

        $ids = array_values(array_unique(array_filter($validated['ids'])));
        $packages = Package::whereIn('uuid', $ids)->get();

        if ($packages->isEmpty()) {
            return redirect()->route('packages.index')->with('error', 'Data paket yang dipilih tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            if ($action === 'delete') {
                foreach ($packages as $package) {
                    $package->delete();
                }

                $message = $packages->count().' paket berhasil dihapus.';
            } else {
                $isActive = $action === 'activate' ? 'active' : 'inactive';

                foreach ($packages as $package) {
                    $package->update(['is_active' => $isActive]);
                }

                $message = $packages->count().' paket berhasil di'.($isActive === 'active' ? 'aktifkan.' : 'nonaktifkan.');
            }

            DB::commit();

            return redirect()->route('packages.index')->with('success', $message);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('packages.index')->with('error', $this->safeErrorMessage($th));
        }
    }

    public function members(Request $request)
    {
        $validated = $request->validate([
            'guests' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'budget_max' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $guests = $validated['guests'] ?? null;
        $budgetMax = $validated['budget_max'] ?? null;

        $query = Package::published()
            ->with([
                'options' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
                'addons' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
                'features' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->where('is_active', 'active');

        // FILTER
        if ($request->filter === 'popular') {

            $query->where('is_popular', true);
        } elseif ($request->filter === 'unlimited') {

            $query->whereHas('options', function ($query) {
                $query->whereNull('quota');
            });
        }

        $packages = $query->get();

        // SORT
        switch ($request->sort) {

            case 'price_low':

                $packages = $packages->sortBy(function ($package) {
                    return $package->options->min(
                        fn ($option) => $option->discount_price ?? $option->price
                    );
                });

                break;

            case 'price_high':

                $packages = $packages->sortByDesc(function ($package) {
                    return $package->options->max(
                        fn ($option) => $option->discount_price ?? $option->price
                    );
                });

                break;

            case 'duration_short':

                $packages = $packages->sortBy(function ($package) {
                    return $package->options->min(function ($option) {

                        return match ($option->duration_unit) {
                            'day' => $option->duration,
                            'week' => $option->duration * 7,
                            'month' => $option->duration * 30,
                            'year' => $option->duration * 365,
                            default => $option->duration,
                        };
                    });
                });

                break;

            case 'duration_long':

                $packages = $packages->sortByDesc(function ($package) {
                    return $package->options->max(function ($option) {

                        return match ($option->duration_unit) {
                            'day' => $option->duration,
                            'week' => $option->duration * 7,
                            'month' => $option->duration * 30,
                            'year' => $option->duration * 365,
                            default => $option->duration,
                        };
                    });
                });

                break;

            default:

                $packages = $packages
                    ->sortByDesc('is_popular')
                    ->values();

                break;
        }

        if ($guests !== null) {
            $packages = $packages->filter(function ($package) use ($guests) {
                return $package->guest_capacity === null ||
                    $package->guest_capacity >= $guests;
            });
        }

        if ($budgetMax !== null) {
            $packages = $packages->filter(function ($package) use ($budgetMax) {
                return $package->options->contains(function ($option) use ($budgetMax) {
                    $finalPrice = $option->discount_price !== null &&
                        $option->discount_price < $option->price
                        ? $option->discount_price
                        : $option->price;

                    return $finalPrice <= $budgetMax;
                });
            });
        }

        $packages = $packages->values();
        $packageUuids = $packages->pluck('uuid')->all();
        $wishlistedUuids = auth()->check() && $packageUuids
            ? PackageWishlist::query()
                ->where('user_uuid', auth()->id())
                ->whereIn('package_uuid', $packageUuids)
                ->pluck('package_uuid')
                ->all()
            : [];

        // =========================
        // MOBILE VIEW
        // =========================

        if (
            auth()->check() &&
            auth()->user()->hasRole('user') &&
            MemberView::isMobile()
        ) {
            return view(
                'pages.mobile.package',
                compact('packages', 'guests', 'budgetMax', 'wishlistedUuids')
            );
        }

        // =========================
        // DESKTOP VIEW
        // =========================

        return view(
            'pages.package.member',
            compact('packages', 'guests', 'budgetMax', 'wishlistedUuids')
        );
    }

    public function duplicate(string $uuid)
    {
        abort_unless(auth()->user()?->can('package.store'), 404);

        $source = Package::with([
            'options',
            'features',
            'addons',
            'blackoutDates',
            'promos',
        ])->where('uuid', $uuid)->firstOrFail();

        $copyData = $source->only([
            'name',
            'slug',
            'description',
            'price',
            'guest_capacity',
            'event_duration_hours',
            'venue_note',
            'cover_url',
            'lead_time_days',
        ]);
        $copyData['name'] = 'Salinan '.$source->name;
        $copyData['slug'] = $this->uniquePackageSlug(Str::slug($copyData['name']).'-'.Str::lower(Str::random(4)));
        $copyData['is_active'] = 'inactive';
        $copyData['is_published'] = false;
        $copyData['published_at'] = null;
        $copyData['is_popular'] = false;

        $copy = DB::transaction(function () use ($source, $copyData) {
            $created = Package::create($copyData);

            foreach ($source->options as $option) {
                PackageOption::create([
                    'package_uuid' => $created->uuid,
                    'name' => $option->name,
                    'quota' => $option->quota,
                    'price' => $option->price,
                    'discount_price' => $option->discount_price,
                    'duration' => $option->duration,
                    'duration_unit' => $option->duration_unit,
                    'sort_order' => $option->sort_order,
                    'is_active' => $option->is_active,
                ]);
            }

            foreach ($source->features as $feature) {
                PackageFeature::create([
                    'package_uuid' => $created->uuid,
                    'feature' => $feature->feature,
                    'sort_order' => $feature->sort_order,
                ]);
            }

            $addonUuidMap = [];

            foreach ($source->addons as $addon) {
                $newAddon = PackageAddon::create([
                    'package_uuid' => $created->uuid,
                    'name' => $addon->name,
                    'description' => $addon->description,
                    'price' => $addon->price,
                    'discount_price' => $addon->discount_price,
                    'min_quantity' => $addon->min_quantity,
                    'max_quantity' => $addon->max_quantity,
                    'sort_order' => $addon->sort_order,
                    'is_active' => $addon->is_active,
                ]);
                $addonUuidMap[$addon->uuid] = $newAddon->uuid;
            }

            foreach ($source->addons as $addon) {
                if (! $addon->requires_addon_uuid || ! isset($addonUuidMap[$addon->requires_addon_uuid])) {
                    continue;
                }

                PackageAddon::where('uuid', $addonUuidMap[$addon->uuid])
                    ->update(['requires_addon_uuid' => $addonUuidMap[$addon->requires_addon_uuid]]);
            }

            foreach ($source->blackoutDates as $blackoutDate) {
                PackageBlackoutDate::create([
                    'package_uuid' => $created->uuid,
                    'date' => $blackoutDate->date,
                    'reason' => $blackoutDate->reason,
                ]);
            }

            foreach ($source->promos as $promo) {
                PackagePromo::create([
                    'package_uuid' => $created->uuid,
                    'code' => $this->uniquePromoCode($promo->code),
                    'type' => $promo->type,
                    'value' => $promo->value,
                    'min_purchase' => $promo->min_purchase,
                    'max_discount' => $promo->max_discount,
                    'max_uses' => $promo->max_uses,
                    'used_count' => 0,
                    'starts_at' => $promo->starts_at,
                    'ends_at' => $promo->ends_at,
                    'is_active' => $promo->is_active,
                    'sort_order' => $promo->sort_order,
                ]);
            }

            return $created;
        });

        return redirect()->route('packages.edit', $copy->uuid)
            ->with('success', 'Paket berhasil diduplikasi.');
    }

    public function toggleStatus(Request $request, string $uuid)
    {
        abort_unless(auth()->user()?->can('package.update'), 404);

        $validated = $request->validate([
            'field' => 'required|in:is_active,is_published',
        ]);

        $package = Package::where('uuid', $uuid)->firstOrFail();

        if ($validated['field'] === 'is_active') {
            $value = $package->is_active === 'active' ? 'inactive' : 'active';
            $package->update(['is_active' => $value]);
            $label = $value === 'active' ? 'Aktif' : 'Nonaktif';
        } else {
            $value = ! $package->is_published;
            $package->update([
                'is_published' => $value,
                'published_at' => $value ? Carbon::now() : null,
            ]);
            $label = $value ? 'Dipublikasikan' : 'Draft';
        }

        $payload = [
            'ok' => true,
            'field' => $validated['field'],
            'value' => $value,
            'label' => $label,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return back()->with('success', $label.' berhasil diperbarui.');
    }

    public function preview(string $uuid)
    {
        $package = Package::with([
            'options' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            },
            'addons' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            },
            'features',
        ])->where('uuid', $uuid)->firstOrFail();

        abort_unless(auth()->user()?->hasAnyRole(['super-admin', 'admin']) || $package->is_published, 404);

        return view('pages.package.member', [
            'packages' => collect([$package]),
            'wishlistedUuids' => [],
            'previewMode' => true,
            'previewPackage' => $package,
            'wishlistedUuids' => [],
        ]);
    }

    public function export(Request $request)
    {
        abort_unless(auth()->user()?->can('package.update'), 404);

        $context = $this->packageListContext($request);
        extract($context);

        $packages = (clone $query)->with('options')->get();
        $filename = 'paket-'.now()->format('Ymd-His').'.csv';

        return response()->stream(function () use ($packages) {
            $handle = fopen('php://output', 'w+');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Nama Paket',
                'Slug',
                'Status',
                'Publikasi',
                'Paling Dipilih',
                'Kapasitas Tamu',
                'Durasi Acara (jam)',
                'Harga Mulai',
                'Harga Tertinggi',
                'Kuota Minimum',
                'Jumlah Opsi',
                'Dibuat',
            ], ';', '"', '\\');

            foreach ($packages as $package) {
                $prices = $package->options->map(fn ($option) => (int) (
                    $option->discount_price !== null && $option->discount_price < $option->price
                        ? $option->discount_price
                        : $option->price
                ));
                $quotas = $package->options
                    ->filter(fn ($option) => $option->quota !== null)
                    ->pluck('quota');

                fputcsv($handle, [
                    $this->csvCell($package->name),
                    $this->csvCell($package->slug),
                    $package->is_active === 'active' ? 'Aktif' : 'Nonaktif',
                    $package->is_published ? 'Dipublikasikan' : 'Draft',
                    $package->is_popular ? 'Ya' : 'Tidak',
                    $package->guest_capacity ?? '',
                    $package->event_duration_hours ?? '',
                    $prices->count() ? $prices->min() : 0,
                    $prices->count() ? $prices->max() : 0,
                    $quotas->count() ? $quotas->min() : '',
                    $package->options->count(),
                    $package->created_at?->format('Y-m-d H:i:s') ?? '',
                ], ';', '"', '\\');
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function reorder(Request $request, string $uuid)
    {
        abort_unless(auth()->user()?->can('package.update'), 404);

        $validated = $request->validate([
            'type' => 'required|in:options,features,addons',
            'uuids' => 'required|array|min:1',
            'uuids.*' => 'string',
        ]);

        $package = Package::where('uuid', $uuid)->firstOrFail();

        $model = match ($validated['type']) {
            'options' => PackageOption::class,
            'features' => PackageFeature::class,
            'addons' => PackageAddon::class,
        };

        $currentUuids = $model::where('package_uuid', $package->uuid)->pluck('uuid')->all();
        $providedUuids = array_values(array_unique($validated['uuids']));
        sort($currentUuids);
        sort($providedUuids);

        if ($currentUuids !== $providedUuids) {
            return response()->json(['message' => 'Urutan tidak valid.'], 422);
        }

        DB::transaction(function () use ($model, $package, $validated) {
            foreach (array_values($validated['uuids']) as $index => $childUuid) {
                $model::where('package_uuid', $package->uuid)
                    ->where('uuid', $childUuid)
                    ->update(['sort_order' => $index]);
            }
        });

        return response()->json(['ok' => true]);
    }

    private function packageListContext(Request $request): array
    {
        $search = trim((string) $request->get('search', ''));
        $is_active = $request->get('is_active', '');
        $is_active = in_array($is_active, ['active', 'inactive'], true) ? $is_active : '';
        $is_popular = $request->get('is_popular', '');
        $is_popular = in_array($is_popular, ['0', '1'], true) ? $is_popular : '';
        $publish = $request->get('publish', '');
        $publish = in_array($publish, ['published', 'draft'], true) ? $publish : '';
        $quota_type = $request->get('quota_type', '');
        $quota_type = in_array($quota_type, ['limited', 'unlimited'], true) ? $quota_type : '';
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;
        $searchTooShort = $search !== '' && mb_strlen($search) < 2;

        $sortColumns = ['name', 'price', 'guest_capacity', 'is_active', 'is_popular', 'is_published', 'created_at'];
        $sort = (string) $request->get('sort', 'created_at');
        $sort = in_array($sort, $sortColumns, true) ? $sort : 'created_at';
        $direction = strtolower((string) $request->get('direction', ''));
        $defaultDirection = $sort === 'name' || $sort === 'price' ? 'asc' : 'desc';
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : $defaultDirection;

        $query = Package::query();

        if ($searchTooShort) {
            $query->whereRaw('1 = 0');
        } elseif ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->where('name', 'like', $like);
        }

        if ($is_active !== '') {
            $query->where('is_active', $is_active);
        }

        if ($is_popular !== '') {
            $query->where('is_popular', $is_popular);
        }

        if ($publish === 'published') {
            $query->where('is_published', true);
        }

        if ($publish === 'draft') {
            $query->where('is_published', false);
        }

        if ($quota_type === 'unlimited') {
            $query->whereDoesntHave('options', function ($query) {
                $query->whereNotNull('quota');
            });
        }

        if ($quota_type === 'limited') {
            $query->whereHas('options', function ($query) {
                $query->whereNotNull('quota');
            });
        }

        if ($sort === 'price') {
            $query->orderByRaw(
                '(select min(coalesce(po.discount_price, po.price)) from package_options po where po.package_uuid = packages.uuid) '.$direction
            );
        } else {
            $query->orderBy($sort, $direction);
        }

        $query->orderBy('uuid');

        return compact(
            'query',
            'search',
            'is_active',
            'is_popular',
            'publish',
            'quota_type',
            'perPage',
            'sort',
            'direction',
            'searchTooShort'
        );
    }

    private function uniquePackageSlug(string $base, ?string $excludeUuid = null): string
    {
        $slug = Str::slug($base) ?: 'paket';
        $original = $slug;
        $suffix = 2;

        while (Package::where('slug', $slug)
            ->when($excludeUuid, fn ($query) => $query->where('uuid', '!=', $excludeUuid))
            ->exists()
        ) {
            $slug = $original.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function uniquePromoCode(string $code): string
    {
        $base = Str::upper(Str::slug($code, '') ?: 'PROMO');

        do {
            $candidate = Str::limit($base, 12, '').'-'.Str::upper(Str::random(4));
        } while (PackagePromo::whereRaw('UPPER(code)=UPPER(?)', [$candidate])->exists());

        return $candidate;
    }

    private function csvCell($value): string
    {
        $value = trim((string) $value);

        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
            return "'".$value;
        }

        return $value;
    }

    private function validatePackageCollections(array $validated, ?Package $package = null)
    {
        $errors = [];

        foreach ($validated['options'] as $index => $option) {
            if (
                isset($option['discount_price']) &&
                $option['discount_price'] !== null &&
                $option['discount_price'] >= $option['price']
            ) {
                $errors["options.$index.discount_price"] = __('flash.package_discount_price_higher');
            }
        }

        $addonIndexes = [];

        foreach ($validated['addons'] ?? [] as $index => $addon) {
            if (filled($addon['name'] ?? null)) {
                $addonIndexes[$index] = true;
            }
        }

        foreach ($validated['addons'] ?? [] as $index => $addon) {
            if (blank($addon['name'] ?? null)) {
                continue;
            }

            if (
                isset($addon['discount_price']) &&
                $addon['discount_price'] !== null &&
                $addon['discount_price'] >= $addon['price']
            ) {
                $errors["addons.$index.discount_price"] = __('flash.package_discount_price_higher');
            }

            $minQuantity = (int) ($addon['min_quantity'] ?? 1);
            $maxQuantity = $addon['max_quantity'] ?? null;

            if ($maxQuantity !== null && $maxQuantity !== '' && (int) $maxQuantity < $minQuantity) {
                $errors["addons.$index.max_quantity"] = 'Jumlah maksimal harus lebih besar atau sama dengan jumlah minimal.';
            }

            $requiresIndex = $addon['requires_addon_index'] ?? null;

            if ($requiresIndex !== null && $requiresIndex !== '') {
                $requiresIndex = (int) $requiresIndex;

                if (
                    $requiresIndex === (int) $index ||
                    ! array_key_exists($requiresIndex, $addonIndexes)
                ) {
                    $errors["addons.$index.requires_addon_index"] = 'Pilih layanan lain yang dibuat pada formulir ini.';
                }
            }
        }

        $blackoutDates = $this->normalizeBlackoutDates($validated['blackout_dates'] ?? null);

        if (! empty($blackoutDates['invalid'])) {
            $errors['blackout_dates'] = 'Tanggal blackout harus menggunakan format Y-m-d, misalnya 2026-12-24.';
        }

        $promoCodes = [];

        foreach ($validated['promos'] ?? [] as $index => $promo) {
            if (blank($promo['code'] ?? null)) {
                continue;
            }

            $code = trim((string) $promo['code']);
            $codeKey = mb_strtoupper($code);

            if (isset($promoCodes[$codeKey])) {
                $errors["promos.$index.code"] = 'Kode promo harus unik.';
            }

            $promoCodes[$codeKey] = true;

            $codeExists = PackagePromo::query()
                ->whereRaw('LOWER(code) = ?', [mb_strtolower($code)])
                ->when($package !== null, function ($query) use ($package) {
                    $query->where(function ($query) use ($package) {
                        $query->whereNull('package_uuid')
                            ->orWhere('package_uuid', '!=', $package->uuid);
                    });
                })
                ->exists();

            if ($codeExists) {
                $errors["promos.$index.code"] = 'Kode promo sudah digunakan.';
            }

            $value = (int) ($promo['value'] ?? 0);

            if ($promo['type'] === 'percent' && ($value < 1 || $value > 100)) {
                $errors["promos.$index.value"] = 'Nilai promo persentase harus antara 1 sampai 100.';
            }

            if ($promo['type'] === 'fixed' && $value < 1000) {
                $errors["promos.$index.value"] = 'Nilai promo tetap minimal 1000.';
            }

            $maxDiscount = $promo['max_discount'] ?? null;

            if ($maxDiscount !== null && $maxDiscount !== '' && (int) $maxDiscount <= 0) {
                $errors["promos.$index.max_discount"] = 'Maksimal diskon harus lebih besar dari 0.';
            }

            $startsAt = $this->parseDateTime($promo['starts_at'] ?? null);
            $endsAt = $this->parseDateTime($promo['ends_at'] ?? null);

            if ($startsAt && $endsAt && $endsAt->lt($startsAt)) {
                $errors["promos.$index.ends_at"] = 'Tanggal akhir harus lebih besar atau sama dengan tanggal awal.';
            }
        }

        if (! empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput();
        }

        return null;
    }

    private function normalizeBlackoutDates($input): array
    {
        $items = [];

        if (is_array($input)) {
            $items = $input;
        } elseif (is_string($input) && trim($input) !== '') {
            $items = preg_split('/[\s,;]+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);
        }

        $dates = [];
        $invalid = [];

        foreach ($items as $item) {
            $value = trim((string) $item);

            if ($value === '') {
                continue;
            }

            try {
                $date = Carbon::createFromFormat('!Y-m-d', $value);
            } catch (\Throwable $exception) {
                $invalid[] = $value;

                continue;
            }

            if (! $date || $date->format('Y-m-d') !== $value) {
                $invalid[] = $value;

                continue;
            }

            $dates[$value] = $value;
        }

        return [
            'dates' => array_values($dates),
            'invalid' => $invalid,
        ];
    }

    private function parseDateTime($value)
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function formatDateTime($value)
    {
        $date = $this->parseDateTime($value);

        return $date ? $date->format('Y-m-d H:i:s') : null;
    }

    private function savePackageAddons(Package $package, array $validated): void
    {
        $package->addons()->delete();

        $addonUuids = [];

        foreach ($validated['addons'] ?? [] as $index => $addon) {
            if (blank($addon['name'] ?? null)) {
                continue;
            }

            $uuid = (string) Str::uuid();
            $addonUuids[$index] = $uuid;

            PackageAddon::create([
                'uuid' => $uuid,
                'package_uuid' => $package->uuid,
                'name' => $addon['name'],
                'description' => $addon['description'] ?? null,
                'price' => $addon['price'],
                'discount_price' => $addon['discount_price'] ?? null,
                'min_quantity' => $addon['min_quantity'] ?? 1,
                'max_quantity' => $addon['max_quantity'] ?? null,
                'requires_addon_uuid' => null,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        foreach ($validated['addons'] ?? [] as $index => $addon) {
            $requiresIndex = $addon['requires_addon_index'] ?? null;

            if ($requiresIndex === null || $requiresIndex === '') {
                continue;
            }

            $requiresIndex = (int) $requiresIndex;

            if (! isset($addonUuids[$index], $addonUuids[$requiresIndex])) {
                continue;
            }

            PackageAddon::where('uuid', $addonUuids[$index])
                ->update([
                    'requires_addon_uuid' => $addonUuids[$requiresIndex],
                ]);
        }
    }

    private function saveBlackoutDates(Package $package, array $validated): void
    {
        $package->blackoutDates()->delete();

        $blackoutDates = $this->normalizeBlackoutDates(
            $validated['blackout_dates'] ?? null
        );

        foreach ($blackoutDates['dates'] as $date) {
            PackageBlackoutDate::create([
                'uuid' => (string) Str::uuid(),
                'package_uuid' => $package->uuid,
                'date' => $date,
                'reason' => null,
            ]);
        }
    }

    private function savePackagePromos(Package $package, array $validated): void
    {
        $package->promos()->delete();

        foreach ($validated['promos'] ?? [] as $index => $promo) {
            if (blank($promo['code'] ?? null)) {
                continue;
            }

            PackagePromo::create([
                'uuid' => (string) Str::uuid(),
                'package_uuid' => $package->uuid,
                'code' => trim((string) $promo['code']),
                'type' => $promo['type'],
                'value' => $promo['value'],
                'min_purchase' => $promo['min_purchase'] ?? 0,
                'max_discount' => $promo['max_discount'] ?? null,
                'max_uses' => $promo['max_uses'] ?? null,
                'used_count' => 0,
                'starts_at' => $this->formatDateTime($promo['starts_at'] ?? null),
                'ends_at' => $this->formatDateTime($promo['ends_at'] ?? null),
                'is_active' => filter_var(
                    $promo['is_active'] ?? true,
                    FILTER_VALIDATE_BOOLEAN
                ),
                'sort_order' => $index,
            ]);
        }
    }
}
