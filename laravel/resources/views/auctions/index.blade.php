@extends('layouts.app')

@section('content')
<div class="dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lelang') }}
            </h2>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 flex justify-between items-center p-4 rounded-lg mb-6">
            <h2 class="text-2xl font-bold text-green-600">Daftar Lelang</h2>
            @auth
                <a href="{{ route('auctions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                    Buat Lelang Baru
                </a>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 inline-block">
                    <p><a href="{{ route('login') }}" class="underline">login</a> atau <a href="{{ route('register') }}" class="underline">register</a> untuk membuat atau mengikuti penawaran</p>
                </div>
            @endauth
        </div>

    <!-- Filter Section -->
    <div class="bg-gray-800 p-4 rounded-lg mb-6">
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
                <label class="text-gray-300 mb-1">Filter tanggal</label>
                <select name="date_sort" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                    <option value="created_desc" {{ request('date_sort') == 'created_desc' ? 'selected' : '' }}>Paling baru (default)</option>
                    <option value="created_asc" {{ request('date_sort') == 'created_asc' ? 'selected' : '' }}>Paling lama</option>
                    <option value="ending_soon" {{ request('date_sort') == 'ending_soon' ? 'selected' : '' }}>Segera berakhir</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-gray-300 mb-1">Filter harga</label>
                <select name="filter_harga" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                    <option value="">none</option>
                    <option value="murah" {{ request('filter_harga') == 'murah' ? 'selected' : '' }}>Harga baru terendah</option>
                    <option value="mahal" {{ request('filter_harga') == 'mahal' ? 'selected' : '' }}>Harga baru tertinggi</option>
                    <option value="MURAH" {{ request('filter_harga') == 'MURAH' ? 'selected' : '' }}>Harga awal terendah</option>
                    <option value="MAHAL" {{ request('filter_harga') == 'MAHAL' ? 'selected' : '' }}>Harga awal tertinggi</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-gray-300 mb-1">Filter ketersediaan</label>
                <select name="is_closed" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-300">
                    <option value="">none</option>
                    <option value="tersedia" {{ request('is_closed') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="berakhir" {{ request('is_closed') == 'berakhir' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-gray-300 mb-1">Filter gambar</label>
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
                        Apply Filters
                    </button>
                    <a href="{{ route('auctions.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if($auctions->count() > 0)
        <div class="space-y-4 container-fluid">
            @foreach ($auctions as $auction)
                <div class="border p-4 rounded-lg shadow">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side content -->
                        <div class="flex-1 pr-4 mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-white">{{ $auction->title }}</h3>
                            <p class="text-gray-200">Deskripsi Item: {{ $auction->description }}</p>
                            <p class="text-gray-400">Pemilik lelang: <b>{{$auction->user->name}}</b></p>
                            <p class="text-green-600 font-semibold">Harga Saat Ini: Rp{{ number_format($auction->current_price) }}</p>
                            <p class="text-green-400">Harga awal: Rp{{number_format($auction->starting_price)}}</p>
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
                        <div class="w-full md:w-1/3 h-48 flex-shrink-0">
                        <h4 class="font-bold text-gray-500 mb-2">Gambar</h4>
                            @if($auction->image_path)
                                <img src="{{ Storage::url($auction->image_path) }}" 
                                     alt="{{ $auction->title }}" 
                                     class="w-full h-full object-cover rounded-lg cursor-pointer"
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

                    @if(!Auth::check() && !$auction->is_closed)
                        <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                            kamu butuh <a href="{{ route('login') }}" class="underline">akun</a> untuk memasang penawaran.
                        </div>
                    @endif

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
</script>
@endsection
