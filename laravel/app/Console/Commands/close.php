<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class close extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredAuctions = Auction::where('end_date', '<=', Carbon::now())
            ->where('is_closed', false)
            ->get();

        foreach ($expiredAuctions as $auction) {
            $auction->update(['is_closed' => true]);
            $this->info("Auction ID {$auction->id} has been closed.");
        }

        $this->info('tutup lelang selesai');
    }
}
