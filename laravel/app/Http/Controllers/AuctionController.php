<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::query();

        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Date sorting
        if ($request->has('date_sort')) {
            switch ($request->date_sort) {
                case 'created_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'ending_soon':
                    $query->orderBy('end_date', 'desc');
                    break;
            }
        }
        // sort ketersediaan
        if ($request->has('is_closed')) {
            switch ($request->is_closed) {
                case 'tersedia':
                    $query->where('is_closed', '0');
                    break;
                case 'berakhir':
                    $query->where('is_closed', '1');
                    break;
            }
        }
        // sort gambar
        if ($request->has('image_path')) {
            switch ($request->image_path) {
                case 'ada':
                    $query->whereNotNull('image_path',);
                    break;
                case 'tidak':
                    $query->whereNull('image_path');
                    break;
            }
        }
        // Price sorting
        if ($request->has('price_sort')) {
            switch ($request->price_sort) {
                case 'price_asc':
                    $query->orderBy('current_price', 'asc');
                    break;
                    case 'price_desc':
                        $query->orderBy('current_price', 'desc');
                        break;
                    }
                }
                
                // Default sorting if no filters applied
                if (!$request->has('date_sort') && !$request->has('price_sort')) {
            $query->orderBy('id', 'desc');
        }

        $auctions = $query->get();
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
