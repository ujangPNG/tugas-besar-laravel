<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'starting_price',
        'current_price',
        'end_date',
        'is_closed'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
