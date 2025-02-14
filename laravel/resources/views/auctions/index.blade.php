@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Daftar Lelang</h2>
    <a href="{{ route('auctions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Buat Lelang Baru</a>
    
    @if($auctions->count() > 0)
        <div class="space-y-4 container-fluid">
            @foreach ($auctions as $auction)
                <div class="border p-4 rounded-lg shadow">
                    <div class="flex">
                        <!-- Left side content -->
                        <div class="flex-1 pr-4">
                            <h3 class="text-xl font-bold text-white">{{ $auction->title }}</h3>
                            <p class="text-gray-200">Deskripsi Item: {{ $auction->description }}</p>
                            <p class="text-gray-400">Pemilik lelang: <b>{{$auction->user->name}}</b></p>
                            <p class="text-green-600 font-semibold">Harga Saat Ini: Rp{{ number_format($auction->current_price, 2) }}</p>
                            <p class="text-green-400">Harga awal: Rp{{number_format($auction->starting_price)}}</p>
                            <p class="text-gray-600">Dimulai pada: {{ $auction->created_at }}</p>
                            <p class="text-gray-600">Berakhir pada: {{ $auction->end_date }}</p>
                            
                            @if($auction->is_closed)
                                <p class="text-red-500 text-3xl">Lelang telah ditutup</p>
                            @endif

                            @if (Auth::id() == $auction->user_id && !$auction->is_closed)
                                <form action="{{ route('auctions.close', $auction->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                        Tutup Lelang
                                    </button>
                                </form>
                            @endif

                            @if (!$auction->is_closed && Auth::id())
                                <form action="{{ route('bids.store', $auction->id) }}" method="POST" class="mt-2 flex gap-2">
                                    @csrf
                                    <input type="number" 
                                           name="bid_amount" 
                                           min="{{ $auction->current_price + 1 }}" 
                                           required
                                           class="border rounded px-2 py-1">
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                                        Ajukan Bid
                                    </button>
                                </form>
                            @endif
                        </div>
                        <!-- Center - Bid History -->
                        <div class="w-200 px-4 flex-shrink-0">
                            <h4 class="font-bold text-gray-500 mb-2">Riwayat Bid</h4>
                            <div class="border rounded-lg h-48 overflow-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 sticky top-0">
                                        <tr>
                                            <th class="px-2 py-1 text-left">Bidder</th>
                                            <th class="px-2 py-1 text-right">Jumlah</th>
                                            <th class="px-2 py-1 text-right">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @php
                                            $bidCounts = $auction->bids()
                                                ->select('user_id')
                                                ->selectRaw('COUNT(*) as bid_count')
                                                ->groupBy('user_id')
                                                ->pluck('bid_count', 'user_id');
                                        @endphp
                                        
                                        @foreach($auction->bids()->with('user')->latest()->get() as $bid)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-2 py-1 text-gray-200">
                                                    {{ $bid->user->name }}
                                                    <span class="text-xs text-gray-500">
                                                        ({{ $bidCounts[$bid->user_id] }} bids)
                                                    </span>
                                                </td>
                                                <td class="px-2 py-1 text-right text-green-600">
                                                    {{ number_format($bid->bid_amount) }}
                                                </td>
                                                <td class="px-2 py-1 text-right text-gray-500 text-xs">
                                                    {{ $bid->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right side image -->
                        <div class="w-48 h-48 flex-shrink-0">
                            @if($auction->image_path)
                                <img src="{{ Storage::url($auction->image_path) }}" 
                                     alt="{{ $auction->title }}" 
                                     class="w-full h-full object-cover rounded-lg"
                                     draggable="false">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" 
                                     alt="No Image Available" 
                                     class="w-full h-full object-cover rounded-lg"
                                     draggable="false">
                            @endif
                        </div>
                    </div>

                    <!-- Debug info at bottom -->
                    <div class="mt-2 flex gap-2 justify-center">
                        <p class="text-gray-600 px-2 text-xs">id user: {{ $auction->user_id }}</p>
                        <p class="text-gray-600 px-2 text-xs">id auction: {{ $auction->id }}</p>
                        <p class="text-gray-600 px-2 text-xs">id bid: {{ $bid->bid_id }}</p>
                        <p class="text-gray-600 px-2 text-xs">is_closed: {{ $auction->is_closed }}</p>
                        <p class="text-gray-600 px-2 text-xs">image: {{ $auction->image_path ? 'true' : 'false' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Belum ada lelang yang tersedia.</p>
    @endif
</div>
@endsection
