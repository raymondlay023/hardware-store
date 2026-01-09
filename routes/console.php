<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule auto-reorder check daily at 6 AM
Schedule::command('inventory:auto-reorder')
    ->dailyAt('06:00')
    ->description('Check low stock and create draft purchase orders');

