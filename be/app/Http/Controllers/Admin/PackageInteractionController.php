<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Package\PackageWishlist;
use App\Support\MemberView;
use Illuminate\Http\Request;

class PackageInteractionController extends Controller
{
    private const COMPARE_LIMIT = 4;

    public function wishlistToggle(Package $package)
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        $userUuid = (string) auth()->id();

        $wishlist = PackageWishlist::query()
            ->where('user_uuid', $userUuid)
            ->where('package_uuid', $package->uuid)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('success', __('flash.wishlist_removed'));
        }

        PackageWishlist::create([
            'user_uuid' => $userUuid,
            'package_uuid' => $package->uuid,
        ]);

        return back()->with('success', __('flash.wishlist_added'));
    }

    public function wishlistIndex()
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        $packages = Package::published()
            ->whereHas('wishlists', function ($query) {
                $query->where('user_uuid', (string) auth()->id());
            })
            ->with([
                'options' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
                'addons' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->latest()
            ->get();

        if (MemberView::isMobile()) {
            return view('pages.mobile.wishlist', compact('packages'));
        }

        return view('pages.package.wishlist', compact('packages'));
    }

    public function compareAdd(Request $request, Package $package)
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        if (! Package::published()->whereKey($package->uuid)->exists()) {
            return back()->with('error', __('flash.package_unavailable'));
        }

        $compare = $this->normalizeCompare(
            $request->session()->get('package_compare', [])
        );

        if (in_array($package->uuid, $compare, true)) {
            return back()->with('error', __('flash.compare_exists'));
        }

        if (count($compare) >= self::COMPARE_LIMIT) {
            return back()->with('error', __('flash.compare_full'));
        }

        $compare[] = $package->uuid;

        $request->session()->put('package_compare', $compare);

        return back()->with('success', __('flash.compare_added'));
    }

    public function compareRemove(Request $request, Package $package)
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        $compare = $this->normalizeCompare(
            $request->session()->get('package_compare', [])
        );

        $request->session()->put(
            'package_compare',
            array_values(array_filter(
                $compare,
                fn ($uuid) => $uuid !== $package->uuid
            ))
        );

        return back()->with('success', __('flash.compare_removed'));
    }

    public function compareIndex()
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        $uuids = $this->normalizeCompare(session('package_compare', []));

        $packages = collect();

        if ($uuids !== []) {
            $found = Package::published()
                ->with([
                    'options' => function ($query) {
                        $query->where('is_active', true)
                            ->orderBy('sort_order');
                    },
                    'addons' => function ($query) {
                        $query->where('is_active', true)
                            ->orderBy('sort_order');
                    },
                ])
                ->whereIn('uuid', $uuids)
                ->get()
                ->keyBy('uuid');

            $packages = collect($uuids)
                ->map(fn ($uuid) => $found->get($uuid))
                ->filter()
                ->values();
        }

        if (MemberView::isMobile()) {
            return view('pages.mobile.compare', compact('packages'));
        }

        return view('pages.package.compare', compact('packages'));
    }

    public function compareClear(Request $request)
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', __('flash.login_to_continue'));
        }

        $request->session()->forget('package_compare');

        return redirect()
            ->route('packages.compare.index')
            ->with('success', __('flash.compare_cleared'));
    }

    private function normalizeCompare($compare): array
    {
        if (! is_array($compare)) {
            return [];
        }

        $uuids = [];

        foreach ($compare as $uuid) {
            if (is_string($uuid) && $uuid !== '') {
                $uuids[] = $uuid;
            }
        }

        return array_slice(array_values(array_unique($uuids)), 0, self::COMPARE_LIMIT);
    }
}
