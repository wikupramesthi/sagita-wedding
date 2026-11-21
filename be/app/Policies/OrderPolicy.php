<?php

namespace App\Policies;

use App\Models\Payment\Order;
use App\Models\User;

class OrderPolicy
{
    public function approve(User $user): bool
    {
        return $user->can('orders.approve') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function reject(User $user): bool
    {
        return $user->can('orders.reject') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function bulk(User $user): bool
    {
        return $user->can('orders.bulk') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function bulkUndo(User $user): bool
    {
        return $user->can('orders.bulkUndo') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function export(User $user): bool
    {
        return $user->can('orders.exportCsv') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function audit(User $user): bool
    {
        return $user->can('orders.audit') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function whatsapp(User $user): bool
    {
        return $user->can('orders.whatsapp') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function assign(User $user): bool
    {
        return $user->can('orders.assign') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function savedFilter(User $user): bool
    {
        return $user->can('orders.savedFilters') || $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function quickView(User $user): bool
    {
        return $user->can('orders.quickView') || $user->hasAnyRole(['super-admin', 'admin']);
    }
}
