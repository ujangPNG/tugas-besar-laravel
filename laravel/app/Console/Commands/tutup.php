<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\User; // Make sure User model is imported
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log facade

class tutup extends Command
{
    protected $signature = 'auctions:tutup';
    protected $description = 'Closes expired auctions and records winners. (Enhanced version)';

    public function handle()
    {
        $this->info('Starting auctions:tutup command...');

        $schemaBuilder = DB::getSchemaBuilder();
        if (!$schemaBuilder->hasColumn('auctions', 'winner_id')) {
            $this->error("CRITICAL: 'winner_id' column not found in 'auctions' table! Please run migrations.");
            Log::critical("auctions:tutup - 'winner_id' column not found in 'auctions' table.");
            return Command::FAILURE;
        }
        // $this->info("Columns in auctions table: " . implode(', ', $schemaBuilder->getColumnListing('auctions'))); // Can be verbose

        $now = Carbon::now();
        $expiredAuctions = Auction::where('end_date', '<=', $now)
            ->where('is_closed', false)
            // ->with('bids') // Eager loading bids can be useful if you access $auction->bids frequently before close()
            ->get();

        if ($expiredAuctions->isEmpty()) {
            $this->info("No expired auctions to close at this time ({$now->toDateTimeString()}).");
            Log::info("auctions:tutup - No expired auctions to close.");
            return Command::SUCCESS;
        }

        $this->info("Found " . $expiredAuctions->count() . " expired auctions to process.");
        Log::info("auctions:tutup - Found " . $expiredAuctions->count() . " expired auctions to process.");

        $closedCount = 0;
        $failedCount = 0;

        foreach ($expiredAuctions as $auction) {
            $this->info("Processing Auction #{$auction->id} (Title: \"{$auction->title}\"). End date: {$auction->end_date->toDateTimeString()}");
            Log::info("auctions:tutup - Processing Auction #{$auction->id}. End date: {$auction->end_date->toDateTimeString()}");

            // Log highest bid information before attempting to close
            // This is illustrative; the Auction::close() method will do its own bid fetching.
            $highestBidInstance = $auction->getHighestBid(); // Using the model's method
            if ($highestBidInstance) {
                $this->info("  Auction #{$auction->id} - Highest bid check before calling close(): User #{$highestBidInstance->user_id}, Amount: {$highestBidInstance->bid_amount}");
                Log::info("  auctions:tutup - Auction #{$auction->id} - Highest bid check before close(): User #{$highestBidInstance->user_id}, Amount: {$highestBidInstance->bid_amount}");
            } else {
                $this->info("  Auction #{$auction->id} - No bids found via getHighestBid() before calling close().");
                Log::info("  auctions:tutup - Auction #{$auction->id} - No bids found via getHighestBid() before close().");
            }
            
            $closeSuccessful = $auction->close(); // This now returns boolean
            
            if ($closeSuccessful) {
                // Refresh from database to get the absolute latest state after close() committed.
                // This is vital to confirm what was actually persisted.
                $auction->refresh(); 
                
                $this->info("  Auction ID {$auction->id} processed by close() method.");
                Log::info("  auctions:tutup - Auction ID {$auction->id} processed by close() method.");
                $this->info("    DB Status: is_closed = " . ($auction->is_closed ? 'true' : 'false (ERROR - should be true)') . 
                            ", winner_id = " . ($auction->winner_id ?? 'null'));
                Log::info("    auctions:tutup - DB Status for Auction #{$auction->id}: is_closed = " . ($auction->is_closed ? 'true' : 'false') . 
                            ", winner_id = " . ($auction->winner_id ?? 'null'));

                if ($auction->winner_id) {
                    $winner = $auction->winner; // Attempt to load winner relationship
                    if ($winner) {
                        $this->info("    Winner: User #{$auction->winner_id} (Name: {$winner->name})");
                        Log::info("    auctions:tutup - Winner for Auction #{$auction->id}: User #{$auction->winner_id} (Name: {$winner->name})");
                    } else {
                        $this->warn("    Winner ID {$auction->winner_id} recorded, but winner relationship (User model) could not be loaded. Check if user exists.");
                        Log::warning("    auctions:tutup - Winner ID {$auction->winner_id} for Auction #{$auction->id} recorded, but User model could not be loaded.");
                    }
                } else {
                    $this->info("    No winner recorded for Auction #{$auction->id}.");
                    Log::info("    auctions:tutup - No winner recorded for Auction #{$auction->id}.");
                }
                $closedCount++;
            } else {
                $this->error("  Auction ID {$auction->id} failed to close properly. Check laravel.log for details from Auction::close() method.");
                Log::error("  auctions:tutup - Auction ID {$auction->id} failed to close properly.");
                $failedCount++;
            }
            $this->line(''); // Add a blank line for readability
        }

        $this->info("Auction closing process finished. Successfully closed: {$closedCount}, Failed to close: {$failedCount}.");
        Log::info("auctions:tutup - Command finished. Successfully closed: {$closedCount}, Failed: {$failedCount}.");
        
        return $failedCount > 0 ? Command::WARNING : Command::SUCCESS;
    }
}