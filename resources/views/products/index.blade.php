@extends('layouts.app')

@section('title', 'Produk')
@section('content')

<div class="container mx-auto px-4 py-6">
    @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#8c7cf0'
                    });
                });
            </script>
    @endif
    <h1 class="text-3xl font-semibold mb-6">Manajemen Produk</h1>

    @if(auth()->check() && auth()->user()->role == 'admin')
        <button onclick="openForm()" class="bg-green-500 text-white py-2 px-4 rounded-lg mb-4 inline-block hover:bg-green-600">
            Tambah Produk
        </button>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Gambar</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Produk</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Harga</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Stok</th>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 text-center">
                        <img src="{{ asset('storage/' . $product->gambar) }}" class="w-16 h-16 object-cover rounded-md mx-auto">
                    </td>
                    {{-- <td class="px-4 py-2 text-center">
                        <img src="{{ asset('storage/' . $product->gambar) }}" class="w-16 h-16 object-cover rounded-md">
                    </td> --}}
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $product->nama_produk }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $product->stok }}</td>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <td class="px-4 py-2 text-center space-y-1">
                        <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 text-white py-1 px-3 rounded-lg hover:bg-yellow-600 text-sm inline-block">Edit</a>
                        <a href="{{ route('products.updatestok', $product->id) }}" class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600 text-sm inline-block">Update Stok</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg hover:bg-red-600 text-sm">Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('products.create')
@endsection
