<?php

namespace App\View\Components;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\Package\CustomPackageOffer;
use App\Models\Package\CustomPackageOfferRequest;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menus = MenuGroup::query()
            ->with('items', function ($query) {
                return $query->where('status', true)->orderBy('position');
            })
            ->where('status', true)
            ->orderBy('position')
            ->get();

        $pendingPackageOfferCount = 0;
        $pendingPackageRequestCount = 0;

        if (auth()->user()?->hasAnyRole(['super-admin', 'admin'])) {
            $pendingPackageOfferCount = CustomPackageOffer::whereIn('status', ['draft', 'revision_requested'])->count();
            $pendingPackageRequestCount = CustomPackageOfferRequest::whereIn('status', ['submitted', 'in_review'])->count();
        }

        return view('components.web.sidebar', compact(
            'menus',
            'pendingPackageOfferCount',
            'pendingPackageRequestCount'
        ));
    }
}
