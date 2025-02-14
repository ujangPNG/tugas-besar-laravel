<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::all(); // Mengambil semua data lelang
        return view('auctions.index', compact('auctions'));
    }
    public function create()
    {
        return view('auctions.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:1',
            'end_date' => 'required|date|after:now',
        ]);

        Auction::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('auctions.index')->with('success', 'Lelang berhasil dibuat.');
    }
    public function closeAuction(Auction $auction)
    {
        if (Auth::id() !== $auction->user_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menutup lelang ini.');
        }

        $auction->update(['is_closed' => true]);

        return back()->with('success', 'Lelang berhasil ditutup.');
    }
}
