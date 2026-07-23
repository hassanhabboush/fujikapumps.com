<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:images-to-webp')->daily();

Schedule::call(fn () => Log::info('Running scheduled task...' . date('Y-m-d H:i:s')))->everyMinute();