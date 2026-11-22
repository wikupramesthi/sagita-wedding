<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use App\Support\MemberView;
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

        /*
        |--------------------------------------------------------------------------
        | Member: laptop/desktop = view desktop, HP = phone-frame mobile.
        |--------------------------------------------------------------------------
        */
        if (auth()->check() && auth()->user()->hasRole('user') && MemberView::isMobile()) {
            return view('pages.mobile.checkout', [
                'package' => $package,
                'bookingFeeAmount' => (int) Order::BOOKING_FEE_AMOUNT,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Website / Desktop
        |--------------------------------------------------------------------------
        */
        return view('pages.package.checkout', [
            'package' => $package,
            'bookingFeeAmount' => (int) Order::BOOKING_FEE_AMOUNT,
        ]);
    }
}
