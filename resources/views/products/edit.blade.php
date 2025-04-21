@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-lg relative">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Produk</h2>

        <!-- Tombol close -->
        <a href="{{ route('products.index') }}" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-xl font-bold">
            &times;
        </a>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                <input type="text" id="harga" name="harga" value="Rp {{ number_format($product->harga, 0, ',', '.') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required oninput="formatRupiah(this)">
            </div>

            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" id="stok" name="stok" value="{{ $product->stok }}" disabled
                    class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                <input type="file" id="gambar" name="gambar"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="previewImage(event)">
                @if ($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}" id="preview" class="mt-3 w-full h-48 object-cover rounded-lg shadow-sm">
                @else
                    <img id="preview" class="mt-3 hidden w-full h-48 object-cover rounded-lg shadow-sm">
                @endif
            </div>

            <div class="flex justify-between">
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-gray-800 hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function formatRupiah(element) {
        let value = element.value.replace(/\D/g, '');
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        element.value = 'Rp ' + formatted;
    }

    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    }
</script>
@endsection
