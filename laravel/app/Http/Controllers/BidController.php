<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $request->validate([
            'bid_amount' => 'required|numeric|min:' . ($auction->current_price + 1),
        ]);

        $bid = Bid::create([
            'user_id' => Auth::id(),
            'auction_id' => $auction->id,
            'bid_amount' => $request->bid_amount,
            'id'=> $request->id,
        ]);

        $auction->update(['current_price' => $bid->bid_amount]);

        return back()->with('success', 'Bid berhasil diajukan.');
    }
}
