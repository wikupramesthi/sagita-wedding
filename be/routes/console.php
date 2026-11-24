<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('records:expire')->hourly();
Schedule::command('consultations:remind-followups')->everyThirtyMinutes();
Schedule::command('package-offers:remind-expiry')->hourly();
Schedule::command('orders:remind-sla')->everyThirtyMinutes();
Schedule::command('orders:remind-balance-due')->dailyAt('08:00');
Schedule::command('checklists:remind-due')->dailyAt('08:15');
Schedule::command('orders:prune-audit')->weeklyOn(0, '03:15');
Schedule::command('sita:prune-analytics')->dailyAt('03:30');
Schedule::call(fn () => DB::table('site_visits')->where('visited_at', '<', now()->subMonths(13))->delete())
    ->dailyAt('02:30')
    ->name('site-visits:prune');
