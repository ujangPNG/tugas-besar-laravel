@extends('layouts.app')
@section('content')
    <div class="dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                <div>Hello, {{ Auth::user()->name }}</div>
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <a href="{{ route('auctions.index') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Ke Halaman Lelang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl text-gray-100 font-bold mb-4">Lelang Yang Kamu Menangkan</h2>
                    @php
                        $wonAuctions = \App\Models\Auction::where('winner_id', Auth::id())->get();
                    @endphp

                    @if($wonAuctions->count() > 0)
                        <div class="space-y-4">
                            @foreach($wonAuctions as $auction)
                                <div class="border p-4 rounded-lg">
                                    <h3 class="font-bold text-gray-200">{{ $auction->title }}</h3>
                                    <p class="text-gray-200">Harga Akhir: Rp{{ number_format($auction->current_price) }}</p>
                                    <p class="text-gray-200">Deskripsi item: {{ $auction->description }}</p>
                                    <p class="text-gray-200">Pemilik: {{ $auction->user->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400">Kamu belum memenangkan lelang apapun.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection