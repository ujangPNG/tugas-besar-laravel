<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class tutup extends Command
{
    protected $signature = 'auctions:tutup';
    protected $description = 'tutup lelang (scheduled blm work)';

    public function handle()
    {
        // Check for the winner_id column
        $columns = DB::getSchemaBuilder()->getColumnListing('auctions');
        $this->info("Columns in auctions table: " . implode(', ', $columns));
        
        if (!in_array('winner_id', $columns)) {
            $this->error("winner_id column not found in auctions table!");
            return;
        }
        
        $expiredAuctions = Auction::where('end_date', '<=', Carbon::now())
            ->where('is_closed', false)
            ->get();

        $this->info("Found " . $expiredAuctions->count() . " expired auctions to close");

        foreach ($expiredAuctions as $auction) {
            // Get highest bid before closing
            $highestBid = $auction->bids()->orderBy('bid_amount', 'desc')->first();
            $this->info("Auction #{$auction->id} - Highest bid before closing: " . ($highestBid ? "User #{$highestBid->user_id}" : "None"));
            
            // Close the auction - this will record the winner
            $auction->close();
            
            // Refresh from database to see actual saved values
            $auction->refresh();
            
            $this->info("Auction ID {$auction->id} has been closed.");
            $this->info("Current price: {$auction->current_price}");
            $this->info("Winner ID: " . ($auction->winner_id ?? 'No winner') . "\n");
        }

        $this->info('tutup lelang selesai');
        
        // Summary of all closed auctions
        $closedAuctions = Auction::where('is_closed', true)->get();
        $this->info("\nSummary of all closed auctions:");
        foreach ($closedAuctions as $auction) {
            $this->info("Auction #{$auction->id} - Winner ID: " . ($auction->winner_id ?? 'No winner'));
        }
    }
} 