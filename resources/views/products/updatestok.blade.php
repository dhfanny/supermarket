@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-lg relative">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Stok Produk</h2>

        <a href="{{ route('products.index') }}" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-xl font-bold">
            &times;
        </a>

        <form action="{{ route('products.updatestok', $product->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" id="nama_produk" value="{{ $product->nama_produk }}" disabled
                    class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
            </div>

            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" required min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex justify-between">
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-gray-800 hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
