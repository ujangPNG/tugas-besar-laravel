@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Daftar Lelang</h2>
    <a href="{{ route('auctions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Buat Lelang Baru</a>
    
    @if($auctions->count() > 0)
        <div class="space-y-4">
            @foreach ($auctions as $auction)
                <div class="border p-4 rounded-lg shadow">
                    <h2 class="text-white text-2xl font-bold text-white-800">{{ $auction->item_name }}</h3>
                    <p class="text-white text-white-600">{{ $auction->description }}</p>
                    <p class="text-white text-white-600">Pemilik lelang: <b>{{$auction->user->name}}</b></p>
                    <p class="text-green-600 font-semibold">Harga Saat Ini: Rp{{ number_format($auction->current_price, 2) }}</p>
                    <p class="text-gray-400">Harga awal: Rp{{number_format($auction->starting_price)}}</p>
                    <p class="text-gray-600">Berakhir pada: {{ $auction->end_date }}</p>
                    
                    @if (Auth::id() == $auction->user_id)
                        <form action="{{ route('auctions.close', $auction->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                Tutup Lelang
                            </button>
                        </form>
                    @endif
                    
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
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Belum ada lelang yang tersedia.</p>
    @endif
</div>
@endsection