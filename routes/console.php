<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('assignments:check-deadlines')->dailyAt('8am');
Schedule::command('sessions:auto-status')->everyMinute();
Schedule::command('events:generate-recurring')->dailyAt('1am');
