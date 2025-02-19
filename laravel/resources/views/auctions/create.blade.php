@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Buat Lelang Baru</h2>
    
    <form id="createAuctionForm" action="{{ route('auctions.store') }}" method="POST" class="max-w-md space-y-4" enctype="multipart/form-data">
        @csrf
        
        <div>
            <label for="title" class="block text-gray-700 font-bold mb-2">Nama Item</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   required 
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('title') }}">
        </div>

        <div>
            <label for="image" class="block text-gray-700 font-bold mb-2">Gambar Produk (Opsional)</label>
            <input type="file" 
                   name="image" 
                   id="image" 
                   accept="image/*"
                   class="w-full border rounded px-3 py-2 text-gray-700">
        </div>

        <div>
            <label for="description" class="block text-gray-700 font-bold mb-2">Deskripsi</label>
            <textarea name="description" 
                      id="description" 
                      required 
                      class="w-full border rounded px-3 py-2"
                      rows="4">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="starting_price" class="block text-gray-700 font-bold mb-2">Harga Awal (Rp)</label>
            <input type="number" 
                   name="starting_price" 
                   id="starting_price" 
                   required 
                   min="1"
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('starting_price') }}">
        </div>

        <div>
            <label for="end_date" class="block text-gray-700 font-bold mb-2">Waktu Berakhir</label>
            <input type="datetime-local" 
                   name="end_date" 
                   id="end_date" 
                   required 
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('end_date') }}">
        </div>

        <div>
            <button type="submit" id="createAuctionButton" onclick='berhasil()' class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Buat Lelang
            </button>
            <a href="{{ route('auctions.index') }}" class="ml-2 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </form>

    @if ($errors->any())
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<script src="{{ asset('resources/js/script.js') }}"></script>
@endsection
