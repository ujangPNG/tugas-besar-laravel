<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Close all expired auctions and set winners';

    public function handle()
    {
        $expiredAuctions = Auction::where('is_closed', false)
            ->where('end_date', '<=', Carbon::now())
            ->get();

        foreach ($expiredAuctions as $auction) {
            $auction->close();
        }

        $this->info(count($expiredAuctions) . ' auctions have been closed.');
    }
}