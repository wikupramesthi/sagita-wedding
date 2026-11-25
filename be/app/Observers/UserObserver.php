<?php

namespace App\Observers;

use App\Models\User;
use App\Services\TelegramAdminNotifier;

class UserObserver
{
    public function created(User $user): void
    {
        app(TelegramAdminNotifier::class)->newUser($user);
    }
}
