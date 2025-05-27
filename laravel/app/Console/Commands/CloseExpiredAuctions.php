<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:tutup';
    protected $description = 'Close expired auctions and record winners automatically';

    public function handle()
    {
        $this->info('🔄 Starting auction closure process...');
        Log::info('auctions:tutup - Command started');

        // Check if winner_id column exists
        $schemaBuilder = DB::getSchemaBuilder();
        if (!$schemaBuilder->hasColumn('auctions', 'winner_id')) {
            $this->error("❌ CRITICAL: 'winner_id' column not found in 'auctions' table!");
            $this->error("Please run: php artisan migrate");
            Log::critical("auctions:tutup - 'winner_id' column missing in auctions table");
            return Command::FAILURE;
        }

        $now = Carbon::now();
        $expiredAuctions = Auction::where('end_date', '<=', $now)
            ->where('is_closed', false)
            ->with(['bids' => function($query) {
                $query->orderBy('bid_amount', 'desc');
            }])
            ->get();

        if ($expiredAuctions->isEmpty()) {
            $this->info("✅ No expired auctions found at {$now->format('Y-m-d H:i:s')}");
            Log::info("auctions:tutup - No expired auctions to process");
            return Command::SUCCESS;
        }

        $this->info("📋 Found {$expiredAuctions->count()} expired auctions to process");
        Log::info("auctions:tutup - Processing {$expiredAuctions->count()} expired auctions");

        $closedCount = 0;
        $failedCount = 0;

        foreach ($expiredAuctions as $auction) {
            $this->line(""); // Empty line for readability
            $this->info("🔨 Processing Auction #{$auction->id}: \"{$auction->title}\"");
            $this->info("   End date: {$auction->end_date->format('Y-m-d H:i:s')}");
            
            Log::info("auctions:tutup - Processing Auction #{$auction->id}", [
                'title' => $auction->title,
                'end_date' => $auction->end_date->toDateTimeString()
            ]);

            try {
                DB::beginTransaction();

                // Get highest bid (gunakan method yang sudah ada di model)
                $highestBid = $auction->getHighestBid();

                $updateData = ['is_closed' => true];
                
                if ($highestBid) {
                    $updateData['winner_id'] = $highestBid->user_id;
                    $updateData['current_price'] = $highestBid->bid_amount;
                    
                    $this->info("   💰 Highest bid: Rp " . number_format($highestBid->bid_amount, 0, ',', '.'));
                    $this->info("   🏆 Winner: User #{$highestBid->user_id}");
                    
                    Log::info("auctions:tutup - Auction #{$auction->id} winner set", [
                        'winner_id' => $highestBid->user_id,
                        'winning_bid' => $highestBid->bid_amount
                    ]);
                } else {
                    $this->info("   ❌ No bids found - closing without winner");
                    Log::info("auctions:tutup - Auction #{$auction->id} closed without bids");
                }

                // Update auction
                $auction->update($updateData);
                
                DB::commit();

                // Verify the update
                $auction->refresh();
                
                if ($auction->is_closed) {
                    $this->info("   ✅ Auction #{$auction->id} successfully closed");
                    
                    if ($auction->winner_id) {
                        $winner = User::find($auction->winner_id);
                        if ($winner) {
                            $this->info("   👤 Winner confirmed: {$winner->name} ({$winner->email})");
                        } else {
                            $this->warn("   ⚠️  Winner ID recorded but user not found");
                        }
                    }
                    
                    $closedCount++;
                } else {
                    throw new \Exception("Failed to update is_closed status");
                }

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("   ❌ Failed to close Auction #{$auction->id}: " . $e->getMessage());
                Log::error("auctions:tutup - Failed to close Auction #{$auction->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $failedCount++;
            }
        }

        $this->line(""); // Empty line
        $this->info("🎯 Process completed:");
        $this->info("   ✅ Successfully closed: {$closedCount} auctions");
        
        if ($failedCount > 0) {
            $this->error("   ❌ Failed to close: {$failedCount} auctions");
        }

        Log::info("auctions:tutup - Command completed", [
            'closed_count' => $closedCount,
            'failed_count' => $failedCount
        ]);

        return $failedCount > 0 ? Command::WARNING : Command::SUCCESS;
    }
}