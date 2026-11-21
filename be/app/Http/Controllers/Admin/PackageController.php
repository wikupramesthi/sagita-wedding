<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Support\MemberView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $is_active = $request->get('is_active', '');
        $is_active = in_array($is_active, ['active', 'inactive'], true) ? $is_active : '';
        $is_popular = $request->get('is_popular', '');
        $is_popular = in_array($is_popular, ['0', '1'], true) ? $is_popular : '';
        $quota_type = $request->get('quota_type', '');
        $quota_type = in_array($quota_type, ['limited', 'unlimited'], true) ? $quota_type : '';
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;
        $searchTooShort = $search !== '' && mb_strlen($search) < 2;

        $query = Package::query();

        if ($searchTooShort) {
            $query->whereRaw('1 = 0');
        } elseif ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->where('name', 'like', $like);
        }

        // Filter status
        if ($is_active !== '') {
            $query->where('is_active', $is_active);
        }

        // Filter popular
        if ($is_popular !== '') {
            $query->where('is_popular', $is_popular);
        }

        // Filter quota
        if ($quota_type === 'unlimited') {
            $query->whereNull('quota');
        }

        if ($quota_type === 'limited') {
            $query->whereNotNull('quota');
        }

        $packages = (clone $query)
            ->with(['features', 'options', 'addons'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // Stats follow the active filter so numbers stay in sync with the data.
        $stats = [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('is_active', 'active')->count(),
            'inactive' => (clone $query)->where('is_active', 'inactive')->count(),
            'popular' => (clone $query)->where('is_popular', 1)->count(),
        ];

        return view('pages.package.index', compact(
            'packages',
            'stats',
            'search',
            'is_active',
            'is_popular',
            'quota_type',
            'perPage',
            'searchTooShort'
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

        /*
    |--------------------------------------------------------------------------
    | Validate Discount Price
    |--------------------------------------------------------------------------
    */

        foreach ($validated['options'] as $index => $option) {

            if (
                isset($option['discount_price']) &&
                $option['discount_price'] !== null &&
                $option['discount_price'] >= $option['price']
            ) {

                return back()
                    ->withErrors([
                        "options.$index.discount_price" => __('flash.package_discount_price_higher'),
                    ])
                    ->withInput();
            }
        }

        foreach ($validated['addons'] ?? [] as $index => $addon) {

            if (
                isset($addon['discount_price']) &&
                $addon['discount_price'] !== null &&
                $addon['discount_price'] >= $addon['price']
            ) {

                return back()
                    ->withErrors([
                        "addons.$index.discount_price" => __('flash.package_discount_price_higher'),
                    ])
                    ->withInput();
            }
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

            $package = Package::create([

                'uuid' => (string) Str::uuid(),

                'name' => $validated['name'],

                'slug' => $slug,

                'description' => $validated['description'] ?? null,

                'guest_capacity' => $validated['guest_capacity'] ?? null,

                'event_duration_hours' => $validated['event_duration_hours'] ?? null,

                'venue_note' => $validated['venue_note'] ?? null,

                'cover_url' => $validated['cover_url'] ?? null,

                'is_popular' => $validated['is_popular'] ?? false,

                'is_active' => $validated['is_active'],

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

            /*
        |--------------------------------------------------------------------------
        | Create Package Addons
        |--------------------------------------------------------------------------
        */

            foreach (
                $validated['addons'] ?? [] as $index => $addon
            ) {

                if (blank($addon['name'] ?? null)) {
                    continue;
                }

                PackageAddon::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'name' => $addon['name'],

                    'description' => $addon['description'] ?? null,

                    'price' => $addon['price'],

                    'discount_price' => $addon['discount_price'] ?? null,

                    'sort_order' => $index,

                    'is_active' => true,

                ]);
            }

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
            'features' => function ($query) {
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

        /*
    |--------------------------------------------------------------------------
    | Validate Discount
    |--------------------------------------------------------------------------
    */

        foreach ($validated['options'] as $index => $option) {

            if (
                isset($option['discount_price']) &&
                $option['discount_price'] !== null &&
                $option['discount_price'] >= $option['price']
            ) {

                return back()
                    ->withErrors([
                        "options.$index.discount_price" => __('flash.package_discount_price_higher'),
                    ])
                    ->withInput();
            }
        }

        foreach ($validated['addons'] ?? [] as $index => $addon) {

            if (
                isset($addon['discount_price']) &&
                $addon['discount_price'] !== null &&
                $addon['discount_price'] >= $addon['price']
            ) {

                return back()
                    ->withErrors([
                        "addons.$index.discount_price" => __('flash.package_discount_price_higher'),
                    ])
                    ->withInput();
            }
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

            /*
        |--------------------------------------------------------------------------
        | Replace Package Addons
        |--------------------------------------------------------------------------
        */

            $package->addons()->delete();

            foreach (
                $validated['addons'] ?? [] as $index => $addon
            ) {

                if (blank($addon['name'] ?? null)) {
                    continue;
                }

                PackageAddon::create([

                    'uuid' => (string) Str::uuid(),

                    'package_uuid' => $package->uuid,

                    'name' => $addon['name'],

                    'description' => $addon['description'] ?? null,

                    'price' => $addon['price'],

                    'discount_price' => $addon['discount_price'] ?? null,

                    'sort_order' => $index,

                    'is_active' => true,

                ]);
            }

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

        $query = Package::with([
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
                compact('packages', 'guests', 'budgetMax')
            );
        }

        // =========================
        // DESKTOP VIEW
        // =========================

        return view(
            'pages.package.member',
            compact('packages', 'guests', 'budgetMax')
        );
    }
}
