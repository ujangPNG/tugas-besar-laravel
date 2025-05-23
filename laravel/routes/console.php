<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Auction;
use App\Console\Commands\tutup;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('auctions:close-expired', function () {
    $expiredAuctions = Auction::where('end_date', '<=', Carbon::now())
        ->where('is_closed', false)
        ->get();

    foreach ($expiredAuctions as $auction) {
        $auction->update(['is_closed' => true]);
        $this->info("Auction ID {$auction->id} has been closed.");
        $this->info("harga terakhir: {$auction->current_price}\n");
    }

    $this->info('tutup lelang selesai');
})->purpose('Tutup lelang yang sudah expired');

// Register in schedule
Artisan::command('schedule:register', function () {
    $schedule = $this->laravel->make(Schedule::class);
    
    $schedule->exec('php artisan auctions:close-expired')
        ->everyMinute()
        ->appendOutputTo(storage_path('logs/scheduler.log'));
        
    $this->info('Schedule registered');
});

Schedule::command('auctions:close-expired')->everyMinute()->appendOutputTo(storage_path('logs/schedule.log'));