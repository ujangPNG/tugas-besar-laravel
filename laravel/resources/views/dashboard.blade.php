@extends('layouts.app')
@section('content')
    <div class="dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Action Card -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-100 mb-2">Mulai Berpartisipasi</h3>
                            <p class="text-gray-400 text-sm">Temukan dan ikuti lelang yang menarik</p>
                        </div>
                        <a href="{{ route('auctions.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Lihat Semua Lelang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            @php
                $wonAuctions = \App\Models\Auction::where('winner_id', Auth::id())->get();
                $activeAuctions = \App\Models\Auction::where('is_closed', '0')->count();
                $totalAuctions = \App\Models\Auction::count();
                $userBids = \App\Models\Bid::where('user_id', Auth::id())->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Lelang Dimenangkan -->
                <div class="bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-20">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Lelang Dimenangkan</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $wonAuctions->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Bid -->
                <div class="bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-20">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Total Bid Saya</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $userBids }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lelang Aktif -->
                <div class="bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Lelang Aktif</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $activeAuctions }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Lelang -->
                <div class="bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-20">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H3a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Total Lelang</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $totalAuctions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lelang Yang Dimenangkan -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl text-gray-100 font-bold flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Lelang Yang Kamu Menangkan
                        </h2>
                        @if($wonAuctions->count() > 0)
                            <span class="bg-green-500 bg-opacity-20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $wonAuctions->count() }} Kemenangan
                            </span>
                        @endif
                    </div>

                    @if($wonAuctions->count() > 0)
                        <div class="space-y-4">
                            @foreach($wonAuctions as $auction)
                                <div class="bg-gray-700 border border-gray-600 p-6 rounded-lg hover:bg-gray-650 transition duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h3 class="font-bold text-gray-100 text-lg mr-3">{{ $auction->title }}</h3>
                                                <span class="bg-green-500 bg-opacity-20 text-green-400 px-2 py-1 rounded text-xs font-medium">
                                                    Menang
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <p class="text-sm text-gray-400 mb-1">Harga Akhir</p>
                                                    <p class="text-xl font-bold text-green-400">Rp{{ number_format($auction->current_price, 0, ',', '.') }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-400 mb-1">Pemilik</p>
                                                    <p class="text-gray-200 font-medium">{{ $auction->user->name }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-sm text-gray-400 mb-1">Deskripsi</p>
                                                <p class="text-gray-300">{{ Str::limit($auction->description, 120) }}</p>
                                            </div>
                                        </div>
                                        <div class="ml-6 flex-shrink-0">
                                            <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H3a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-300 mb-2">Belum Ada Kemenangan</h3>
                            <p class="text-gray-400 mb-6">Kamu belum memenangkan lelang apapun. Mulai berpartisipasi sekarang!</p>
                            <a href="{{ route('auctions.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Ikuti Lelang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection