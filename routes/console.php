<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule daily encrypted database backups at 2 AM
Schedule::command('db:backup --encrypt')
    ->dailyAt('02:00')
    ->timezone('UTC')
    ->description('Daily encrypted database backup');

// Schedule weekly encrypted database backups on Sundays at 3 AM
Schedule::command('db:backup --encrypt')
    ->weeklyOn(0, '03:00')
    ->timezone('UTC')
    ->description('Weekly encrypted database backup');
