@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Buat Lelang Baru</h2>
    
    <form action="{{ route('auctions.store') }}" method="POST" class="max-w-md space-y-4">
        @csrf
        
        <div>
            <label for="item_name" class="block text-gray-700 font-bold mb-2">Nama Item</label>
            <input type="text" 
                   name="item_name" 
                   id="item_name" 
                   required 
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('item_name') }}">
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
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Buat Lelang
            </button>
            <a href="{{ route('auctions.index') }}" class="ml-2 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
