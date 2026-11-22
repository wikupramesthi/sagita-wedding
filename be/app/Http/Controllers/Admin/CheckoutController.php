<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use App\Support\MemberView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function package(Request $request, string $packageUuid)
    {
        $package = Package::with([
            'options' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            },
            'addons' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            },
            'features',
            'blackoutDates',
            'promos' => function ($query) {
                $query->usable()
                    ->orderBy('sort_order');
            },
        ])
            ->where('uuid', $packageUuid)
            ->published()
            ->firstOrFail();

        $orderHistoryCount = Order::packageOnly()
            ->where('user_uuid', Auth::user()->uuid)
            ->count();
        $orderHistory = Order::packageOnly()
            ->with('package')
            ->where('user_uuid', Auth::user()->uuid)
            ->latest()
            ->limit(5)
            ->get();
        $activePackageOrder = Order::packageOnly()
            ->with('packageOption')
            ->where('user_uuid', Auth::user()->uuid)
            ->where('package_uuid', $package->uuid)
            ->where(function ($query) {
                $query->where('status', 'partially_paid')
                    ->orWhere(function ($pendingQuery) {
                        $pendingQuery->where('status', 'pending')
                            ->where(function ($expiryQuery) {
                                $expiryQuery->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', now());
                            });
                    });
            })
            ->latest()
            ->first();

        $viewData = [
            'package' => $package,
            'bookingFeeAmount' => (int) Order::BOOKING_FEE_AMOUNT,
            'orderHistory' => $orderHistory,
            'orderHistoryCount' => $orderHistoryCount,
            'activePackageOrder' => $activePackageOrder,
        ];

        /*
        |--------------------------------------------------------------------------
        | Member: laptop/desktop = view desktop, HP = phone-frame mobile.
        |--------------------------------------------------------------------------
        */
        if (auth()->check() && auth()->user()->hasRole('user') && MemberView::isMobile()) {
            return view('pages.mobile.checkout', $viewData);
        }

        /*
        |--------------------------------------------------------------------------
        | Website / Desktop
        |--------------------------------------------------------------------------
        */
        return view('pages.package.checkout', $viewData);
    }
}
