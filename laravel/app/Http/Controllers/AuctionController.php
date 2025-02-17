<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::orderBy('id', 'desc')->get(); // Ambil data dengan urutan terbaru
        return view('auctions.index', compact('auctions'));
    }
    public function create()
    {
        return view('auctions.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:1',
            'end_date' => 'required|date|after:now',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'end_date' => $request->end_date,
        ];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('auction-images', 'public');
            $data['image_path'] = $path;
        }

        Auction::create($data);

        return redirect()->route('auctions.index')->with('toast_success', 'Lelang berhasil dibuat.');
    }
    public function closeAuction(Auction $auction)
    {
        if (Auth::id() !== $auction->user_id) {
            return back()->with('toast_error', 'Anda tidak memiliki izin untuk menutup lelang ini.');
        }

        $auction->update(['is_closed' => true]);

        return back()->with('toast_success', 'Lelang berhasil ditutup.');
    }
}
