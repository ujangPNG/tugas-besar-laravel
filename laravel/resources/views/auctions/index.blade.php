@extends('layouts.app')

@section('content')
<div class="dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lelang') }}
            </h2>
        </div>
    </div>
        @auth
        @else
            <div class="bg-yellow-100 border-l-4 flex border-yellow-500 text-yellow-700 p-4 inline-block">
                <p><a href="{{ route('login') }}" class="underline">login</a> atau <a href="{{ route('register') }}" class="underline">register</a> untuk membuat atau mengikuti penawaran</p>
            </div>
        @endauth
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 flex justify-between items-center p-4 rounded-lg mb-6">
            
            <h2 class="text-2xl font-bold text-green-600">Daftar Lelang</h2>
            @auth
                <a href="{{ route('auctions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                    Buat Lelang Baru
                </a> 
            @endauth
        </div>

    <!-- Combined Filter and Grid Toggle Section -->
    <div class="flex gap-4 mb-6">
        <!-- Filter Section - make it flex-grow -->
        <div class="bg-gray-800 p-4 rounded-lg flex-grow">
        <h2 class="text-2xl font-bold mb-2 text-green-600">Filter</h2>
            <form action="{{ route('auctions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="flex flex-col">
                    <label class="text-gray-300 mb-1">Search</label>
                    <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search')=='no_desc' ? '' : request('search') }}" 
                        placeholder="cari bedasarkan nama atau id" 
                        class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                </div>
                <div class="flex flex-col">
                    <label class="text-gray-300 mb-1">Harga</label>
                    <select name="filter_harga" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">none</option>
                        <option value="murah" {{ request('filter_harga') == 'murah' ? 'selected' : '' }}>Harga baru termurah</option>
                        <option value="mahal" {{ request('filter_harga') == 'mahal' ? 'selected' : '' }}>Harga baru termahal</option>
                        <option value="MURAH" {{ request('filter_harga') == 'MURAH' ? 'selected' : '' }}>Harga awal termurah</option>
                        <option value="MAHAL" {{ request('filter_harga') == 'MAHAL' ? 'selected' : '' }}>Harga awal termahal</option>
                    </select>
                </div>
                
                <div class="flex flex-col">
                    <label class="text-gray-300 mb-1">Tanggal</label>
                    <select name="date_sort" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">none</option>
                        <option value="created_desc" {{ request('date_sort') == 'created_desc' ? 'selected' : '' }}>Paling baru</option>
                        <option value="created_asc" {{ request('date_sort') == 'created_asc' ? 'selected' : '' }}>Paling lama</option>
                        <option value="ending_soon" {{ request('date_sort') == 'ending_soon' ? 'selected' : '' }}>Segera berakhir</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-gray-300 mb-1">Ketersediaan</label>
                    <select name="is_closed" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">none</option>
                        <option value="tersedia" {{ request('is_closed') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="berakhir" {{ request('is_closed') == 'berakhir' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-gray-300 mb-1">Gambar</label>
                    <select name="image_path" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">none</option>
                        <option value="ada" {{ request('image_path') == 'ada' ? 'selected' : '' }}>Memiliki gambar</option>
                        <option value="tidak" {{ request('image_path') == 'tidak' ? 'selected' : '' }}>Tidak memiliki gambar</option>
                    </select>
                </div>
                <div class="md:col-span-5 flex justify-between items-center">
                    <!-- Left side checkboxes -->
                    <div class="flex gap-4">
                        <label class="flex items-center text-gray-300">
                            <input type="checkbox" 
                                name="include_description" 
                                {{ request('include_description') ? 'checked' : '' }}
                                class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 dark:border-gray-700">
                            <span class="ml-2">Cari deskripsi juga</span>
                        </label>
                    </div>
                    
                    <!-- Right side buttons (existing) -->
                    <div class="flex">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Apply!
                        </button>
                        <a href="{{ route('auctions.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Toggle Section - fixed width -->
        <div class="bg-gray-800 p-4 rounded-lg w-24 flex items-center justify-center">
            <div class="flex flex-col gap-4">
                <button onclick="setViewMode('list')" 
                        class="px-4 py-2 rounded transition-colors duration-200 flex flex-col items-center" 
                        id="listViewBtn">
                    <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="text-sm mt-1 text-gray-300">List</span>
                </button>
                <button onclick="setViewMode('grid')" 
                        class="px-4 py-2 rounded transition-colors duration-200 flex flex-col items-center" 
                        id="gridViewBtn">
                    <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-sm mt-1 text-gray-300">Grid</span>
                </button>
            </div>
        </div>
    </div>

    @if($auctions->count() > 0)
        <div id="auctionContainer" class="space-y-4 container-fluid">
            <div id="listView" class="space-y-4">
                <!-- Existing list view code -->
                @foreach ($auctions as $auction)
                <div class="border p-4 rounded-lg shadow">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side content -->
                        <div class="flex-1 pr-4 mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-white">{{ $auction->title }}</h3>
                            <p class="text-gray-200">Deskripsi Item: {{ $auction->description }}</p>
                            <p class="text-gray-400">Pemilik lelang: <b>{{$auction->user->name}}</b></p>
                            <p class="text-green-600 font-semibold">Harga Saat Ini: Rp{{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            <p class="text-green-400">Harga awal: Rp{{number_format($auction->starting_price, 0, ',', '.')}}</p>
                            <p class="text-gray-600">Dimulai pada: {{ $auction->created_at }}</p>
                            <p class="text-gray-600">Berakhir pada: {{ $auction->end_date }}</p>
                            
                            @if($auction->is_closed)
                                @if($auction->winner_id)
                                    <p class="text-green-500">Lelang telah ditutup - Dimenangkan oleh: {{ $auction->winner->name }}</p>
                                @else
                                    <p class="text-red-500">Lelang dibatalkan - Tidak ada penawar</p>
                                @endif
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
                            <form id="bidForm" action="{{ route('bids.store', $auction->id) }}" method="POST" class="mt-2 flex gap-2">
                                @csrf
                                <input type="number" 
                                    name="bid_amount" 
                                    min="{{ $auction->current_price + 1 }}" 
                                    required
                                    class="border rounded px-2 py-1">
                                
                                <button type="button" onclick="confirmBid(this.form, {{ $auction->current_price }})" 
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                                    Ajukan Penawaran
                                </button>
                            </form>
                            @elseif(!Auth::check() && !$auction->is_closed)
                            <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                                kamu butuh <a href="{{ route('login') }}" class="underline">akun</a> untuk memasang penawaran.
                            </div>
                            @endif
                        </div>
                        <!-- Center - Bid History -->
                        <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                            <h4 class="font-bold text-gray-500 mb-2">Riwayat Bid</h4>
                            <div class="border rounded-lg h-48 overflow-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 sticky top-0">
                                        <tr>
                                            <th class="px-2 py-1 text-left">Nama Bidder</th>
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
                                            <tr class="">
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
                        <div class="w-full md:w-1/3 h-48 flex-shrink-0">
                        <h4 class="font-bold text-gray-500 mb-2">Gambar</h4>
                            @if($auction->image_path)
                                <img src="{{ Storage::url($auction->image_path) }}" 
                                     alt="{{ $auction->title }}" 
                                     class="w-full h-full object-cover rounded-lg cursor-pointer hover:focus"
                                     draggable="false"
                                     onclick="showImageModal('{{ Storage::url($auction->image_path) }}')">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" 
                                     alt="No Image Available" 
                                     class="w-full h-full object-cover rounded-lg"
                                     draggable="false">
                            @endif
                        </div>
                    </div>


                    <!-- Debug info at bottom -->
                    <div class="mt-10 flex gap-2 justify-center">
                        <p class="text-gray-600 px-2 text-xs">id user: {{ $auction->user_id }}</p>
                        <p class="text-gray-600 px-2 text-xs">id auction: {{ $auction->id }}</p>
                        <p class="text-gray-600 px-2 text-xs">is closed: {{ $auction->is_closed ? 'true' : 'false' }}</p>
                        <p class="text-gray-600 px-2 text-xs">image: {{ $auction->image_path ? 'true' : 'false' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($auctions as $auction)
                    <div class="border p-4 rounded-lg shadow bg-gray-800">
                        <!-- Image at top -->
                        <div class="h-48 mb-4">
                            @if($auction->image_path)
                                <img src="{{ Storage::url($auction->image_path) }}" 
                                     alt="{{ $auction->title }}" 
                                     class="w-full h-full object-cover rounded-lg cursor-pointer"
                                     onclick="showImageModal('{{ Storage::url($auction->image_path) }}')">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" 
                                     alt="No Image Available" 
                                     class="w-full h-full object-cover rounded-lg">
                            @endif
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-white mb-2">{{ $auction->title }}</h3>
                        <p class="text-gray-200 mb-2 line-clamp-2">{{ $auction->description }}</p>
                        <p class="text-gray-400 mb-2">Pemilik: {{$auction->user->name}}</p>
                        <p class="text-green-600 font-semibold mb-1">Rp{{ number_format($auction->current_price) }}</p>
                        <p class="text-gray-500 text-sm mb-2">Berakhir: {{ $auction->end_date }}</p>

                        @if($auction->is_closed)
                            @if($auction->winner_id)
                                <p class="text-green-500 text-xl">Dimenangkan: {{ $auction->winner->name }}</p>
                            @else
                                <p class="text-red-500">Dibatalkan</p>
                            @endif
                        @elseif(Auth::check())
                        <form id="bidForm" action="{{ route('bids.store', $auction->id) }}" method="POST" class="flex justify-center gap-2">
                                @csrf
                                <input type="number" 
                                    name="bid_amount" 
                                    min="{{ $auction->current_price + 1 }}" 
                                    required
                                    class="border rounded px-2 w-full py-1">
                                
                                <button type="button" onclick="confirmBid(this.form, {{ $auction->current_price }})" 
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                                    Ajukan!
                                </button>
                            </form>
                        @elseif(!Auth::check() && !$auction->is_closed)
                        <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                            kamu butuh <a href="{{ route('login') }}" class="underline">akun</a> untuk memasang penawaran.
                        </div>
                        @endif
                        <div class="mt-2 flex gap-2 justify-center">
                        <p class="text-gray-600 px-2 text-xs">id user: {{ $auction->user_id }}</p>
                        <p class="text-gray-600 px-2 text-xs">id auction: {{ $auction->id }}</p>
                        <p class="text-gray-600 px-2 text-xs">is closed: {{ $auction->is_closed ? 'true' : 'false' }}</p>
                        <p class="text-gray-600 px-2 text-xs">image: {{ $auction->image_path ? 'true' : 'false' }}</p>
                        <p class="text-gray-600 px-2 text-xs">id winner: {{ $auction->winner_id }}</p>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-gray-600">Belum ada lelang yang tersedia.</p>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Full Image" class="max-w-full max-h-full rounded-lg" onclick="event.stopPropagation()">
    <button class="absolute top-4 right-4 text-white text-2xl" onclick="closeImageModal()">×</button>
</div>

<script>
    function showImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function confirmBid(form, currentPrice) {
    var bidInput = form.querySelector('input[name="bid_amount"]');
    var bidValue = parseFloat(bidInput.value);

    if (isNaN(bidValue) || bidValue <= currentPrice) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Bid harus lebih tinggi dari Rp' + currentPrice
        });
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Penawaran berhasil diajukan!',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
}

    function setViewMode(mode) {
        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridViewBtn = document.getElementById('gridViewBtn');

        if (mode === 'grid') {
            listView.classList.add('hidden');
            gridView.classList.remove('hidden');
            gridViewBtn.classList.add('bg-blue-500', 'text-white');
            listViewBtn.classList.remove('bg-blue-500', 'text-white');
        } else {
            listView.classList.remove('hidden');
            gridView.classList.add('hidden');
            listViewBtn.classList.add('bg-blue-500', 'text-white');
            gridViewBtn.classList.remove('bg-blue-500', 'text-white');
        }

        // Save preference
        localStorage.setItem('auctionViewMode', mode);
    }

    // Load saved preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedMode = localStorage.getItem('auctionViewMode') || 'list';
        setViewMode(savedMode);
    });
</script>

<!-- Remove or comment out the Font Awesome CSS since we're using SVG now -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
@endsection
