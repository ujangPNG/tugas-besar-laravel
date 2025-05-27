<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the auction closure command
Schedule::command('auctions:tutup')
    ->everyMinute()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/auction-scheduler.log'));

// Optional: Add a manual command to test the scheduler
Artisan::command('scheduler:test', function () {
    $this->info('Testing scheduler...');
    $this->call('auctions:tutup');
})->purpose('Test the auction scheduler manually');