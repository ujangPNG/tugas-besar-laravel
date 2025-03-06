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
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search, $request) {
                $q->where('title', 'like', "%{$search}%");
                
                if ($request->has('include_description')) {
                    $q->orWhere('description', 'like', "%{$search}%");
                }
            });
        }

        // Check if any filter is applied
        $hasFilters = $request->has('filter_harga') || 
                     $request->has('date_sort') || 
                     $request->has('is_closed') || 
                     $request->has('image_path');

        // Apply filters only if they are present
        if ($hasFilters) {
            // Price sorting
            if ($request->has('filter_harga')) {
                switch ($request->filter_harga) {
                    case 'murah':
                        $query->orderBy('current_price', 'asc');
                        break;
                    case 'mahal':
                        $query->orderBy('current_price', 'desc');
                        break;
                    case 'MURAH':
                        $query->orderBy('starting_price', 'asc');
                        break;
                    case 'MAHAL':
                        $query->orderBy('starting_price', 'desc');
                        break;
                }
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
                        $query->orderBy('end_date', 'asc')
                              ->where('is_closed', false);
                        break;
                }
            }

            // Availability filter
            if ($request->has('is_closed')) {
                switch ($request->is_closed) {
                    case 'tersedia':
                        $query->where('is_closed', false);
                        break;
                    case 'berakhir':
                        $query->where('is_closed', true);
                        break;
                }
            }

            // Image filter
            if ($request->has('image_path')) {
                switch ($request->image_path) {
                    case 'ada':
                        $query->whereNotNull('image_path');
                        break;
                    case 'tidak':
                        $query->whereNull('image_path');
                        break;
                }
            }
        } else {
            // Default sorting when no filters are applied
            $query->latest('id');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'image_url' => 'nullable|url'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'end_date' => $request->end_date,
        ];

        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('auction-images', 'public');
            $data['image_path'] = $path;
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
            $contents = file_get_contents($imageUrl);
            $filename = 'auction-images/' . time() . '.jpg';
            Storage::disk('public')->put($filename, $contents);
            $data['image_path'] = $filename;
        }

        Auction::create($data);

        return redirect()->route('auctions.index')->with('toast_success', 'Lelang berhasil dibuat.');
    }
    public function closeAuction(Auction $auction)
    {
        if (Auth::id() !== $auction->user_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $auction->close();
        
        return back()->with('success', 'Auction has been closed and winner has been recorded.');
    }
}
