@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Buat Lelang Baru</h2>
    
    <!-- Center the form container -->
    <div class="flex justify-center">
        <div class="flex flex-col md:flex-row gap-8 max-w-5xl w-full">
            <!-- Left side - Form -->
            <div class="w-full md:w-1/2">
                <form id="createAuctionForm" action="{{ route('auctions.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
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

                    <div class="flex gap-2">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Buat Lelang
                        </button>
                        <a href="{{ route('auctions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

            <!-- Right side - Image upload -->
            <div class="w-full md:w-1/2">
                <div class="sticky top-4">
                    <label class="block text-gray-700 font-bold mb-2">Gambar Produk (Opsional)</label>
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                        <input type="file" 
                            name="image" 
                            id="image" 
                            accept="image/*"
                            class="hidden"
                            form="createAuctionForm">
                        <input type="hidden" name="image_url" id="image_url" form="createAuctionForm">
                        
                        <div id="preview" class="mb-4">
                            <img id="previewImage" 
                                 src="{{ asset('images/no-image.png') }}" 
                                 alt="Preview" 
                                 class="max-h-96 mx-auto rounded-lg shadow">
                        </div>
                        
                        <p class="text-gray-600">
                            Drop gambar atau URL gambar disini<br>
                            atau <span class="text-blue-500 cursor-pointer" onclick="document.getElementById('image').click()">pilih file</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

<script>
const dropZone = document.getElementById('dropZone');
const preview = document.getElementById('preview');
const previewImage = document.getElementById('previewImage');
const imageUrlInput = document.getElementById('image_url');
const fileInput = document.getElementById('image');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

// Handle dropped files
dropZone.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const items = dt.items;

    for (let i = 0; i < items.length; i++) {
        if (items[i].type.indexOf('image') !== -1) {
            // If it's a file
            const file = items[i].getAsFile();
            handleFile(file);
            return;
        } else if (items[i].type === 'text/uri-list') {
            // If it's a URL
            items[i].getAsString(url => {
                if (isImageUrl(url)) {
                    handleImageUrl(url);
                }
            });
            return;
        }
    }
});

// Handle file input change
fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        handleFile(this.files[0]);
    }
});

function handleFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        preview.classList.remove('hidden');
        imageUrlInput.value = ''; // Clear URL if exists
    }
    reader.readAsDataURL(file);
}

function handleImageUrl(url) {
    previewImage.src = url;
    preview.classList.remove('hidden');
    imageUrlInput.value = url;
    fileInput.value = ''; // Clear file input
}

function isImageUrl(url) {
    return url.match(/\.(jpeg|jpg|gif|png)$/) != null;
}

// Highlight drop zone when dragging
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-blue-500');
}

function unhighlight(e) {
    dropZone.classList.remove('border-blue-500');
}

// Add error handling for image preview
previewImage.onerror = function() {
    this.src = '{{ asset('images/no-image.png') }}';
}
</script>
@endsection
