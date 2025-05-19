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

    protected $casts = [
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Format the end_time when displaying
    public function getEndTimeAttribute($value)
    {
        // Assuming your column is end_date, not end_time
        return Carbon::parse($value)->setTimezone(config('app.timezone', 'UTC')); // Use app timezone
    }

    // Convert to UTC when saving to database - ensure this is for 'end_date'
    public function setEndDateAttribute($value) // Assuming this was meant for end_date
    {
        $this->attributes['end_date'] = Carbon::parse($value)->setTimezone('UTC');
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
        if (!$this->is_closed && $this->end_date && Carbon::now()->greaterThanOrEqualTo($this->end_date)) {
            $this->close();
        }
    }

    /**
     * Close the auction and record the winner if there's a highest bid.
     * * @return bool True on success, false on failure.
     */
    public function close()
    {
        if ($this->is_closed) {
            Log::info('Auction #' . $this->id . ' is already closed. Skipping closure process.');
            return true; // Already closed, consider it a success in this context
        }

        // Get the highest bid
        $highestBid = $this->bids()->orderBy('bid_amount', 'desc')->first();
        
        Log::info('Attempting to close auction #' . $this->id . '. Highest bid details: ' . 
            ($highestBid ? 'Bid ID: ' . $highestBid->id . ', User ID: ' . $highestBid->user_id . ', Amount: ' . $highestBid->bid_amount : 'No highest bid found.'));
        
        DB::beginTransaction();
        
        try {
            $this->is_closed = true;
            
            if ($highestBid && $highestBid->user_id) {
                // Ensure the user_id from the bid actually exists in the users table
                if (User::find($highestBid->user_id)) {
                    $this->winner_id = $highestBid->user_id;
                    Log::info('Auction #' . $this->id . ': Setting winner_id to: ' . $highestBid->user_id);
                } else {
                    $this->winner_id = null;
                    Log::warning('Auction #' . $this->id . ': Highest bid User ID ' . $highestBid->user_id . ' not found in users table. Cannot set winner.');
                }
            } else {
                $this->winner_id = null;
                Log::info('Auction #' . $this->id . ': No winner to set (highestBid was null or user_id on bid was missing/null).');
            }
            
            // Eloquent will automatically update `updated_at` if timestamps are enabled (default).
            // If you specifically don't want `updated_at` to change, you'd set $this->timestamps = false before save.
            // For now, let's assume updating `updated_at` is desired.
            $saved = $this->save();
            
            if (!$saved) {
                Log::error('Auction #' . $this->id . ': Failed to save auction model during close operation (save() returned false).');
                DB::rollBack();
                return false; // Indicate failure
            }
            
            // Re-fetch from database to verify the save operation immediately
            $verifyAuction = self::find($this->id); // Use self::find for fresh model
            if ($verifyAuction) {
                Log::info('Auction #' . $this->id . ' verification after save: is_closed=' . 
                    ($verifyAuction->is_closed ? 'true' : 'false') . 
                    ', winner_id=' . ($verifyAuction->winner_id ?? 'null') .
                    ', current_price=' . $verifyAuction->current_price .
                    ', updated_at=' . $verifyAuction->updated_at);
            } else {
                Log::error('Auction #' . $this->id . ': Could not re-fetch auction for verification after save. This is unexpected.');
            }
            
            DB::commit();
            Log::info('Auction #' . $this->id . ' successfully closed and transaction committed.');
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error closing auction #' . $this->id . ': ' . $e->getMessage() . ' at ' . $e->getFile() . ' L' . $e->getLine(), ['exception' => $e]);
            // Consider re-throwing if the command should halt on any single failure:
            // throw $e; 
            return false; // Indicate failure
        }
    }
}