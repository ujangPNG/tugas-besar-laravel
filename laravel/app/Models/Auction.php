<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function close()
    {
        if ($this->is_closed) {
            return;
        }

        $highestBid = $this->bids()->orderBy('bid_amount', 'desc')->first();
        
        $this->is_closed = true;
        $this->winner_id = $highestBid ? $highestBid->user_id : null;
        $this->save();
    }
}
