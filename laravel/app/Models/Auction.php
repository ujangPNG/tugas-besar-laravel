<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Auction extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'starting_price',
        'current_price',
        'end_date',
        'is_closed',
        'image_path',
        'winner_id'
    ];

    // Convert timestamps to local time
    protected $casts = [
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Format the end_time when displaying
    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta');
    }

    // Convert to UTC when saving to database
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value)->setTimezone('UTC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function getHighestBid()
    {
        return $this->bids()->orderBy('bid_amount', 'desc')->first();
    }

    public function closeAuctionIfExpired()
    {
        if (!$this->is_closed && Carbon::now() > $this->end_date) {
            $this->close();
        }
    }

    /**
     * Close the auction and record the winner if there's a highest bid
     * 
     * @return void
     */
    public function close()
    {
        // Skip if already closed
        if ($this->is_closed) {
            return;
        }

        // Get the highest bid
        $highestBid = $this->bids()->orderBy('bid_amount', 'desc')->first();
        
        // Log for debugging
        Log::info('Closing auction #' . $this->id . ' with highest bid: ' . ($highestBid ? $highestBid->user_id : 'none'));
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            // Set closure status
            $this->is_closed = true;
            
            // Set winner if we have bids
            if ($highestBid) {
                $this->winner_id = $highestBid->user_id;
                Log::info('Setting winner_id to: ' . $highestBid->user_id);
            } else {
                $this->winner_id = null;
                Log::info('No winner for auction #' . $this->id);
            }
            
            // Save the changes
            $saved = $this->save();
            
            // Verify save was successful
            if (!$saved) {
                Log::error('Failed to save auction #' . $this->id);
                throw new \Exception('Failed to save auction');
            }
            
            // Check if winner_id was actually saved
            $verifyAuction = self::find($this->id);
            Log::info('Auction #' . $this->id . ' after save: is_closed=' . 
                ($verifyAuction->is_closed ? 'true' : 'false') . 
                ', winner_id=' . ($verifyAuction->winner_id ?? 'null'));
            
            // Commit transaction
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            Log::error('Error closing auction #' . $this->id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
