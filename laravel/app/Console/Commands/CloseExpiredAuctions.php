<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Close auctions that have reached their end time';

    public function handle()
    {
        $expiredAuctions = Auction::where('end_date', '<=', Carbon::now())
            ->where('is_closed', false)
            ->get();

        foreach ($expiredAuctions as $auction) {
            $auction->update(['is_closed' => true]);
            $this->info("Auction ID {$auction->id} has been closed.");
        }

        $this->info('Expired auctions check completed.');
    }
} 