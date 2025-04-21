@extends('layouts.app')

@section('title', 'Pembelian')
@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Data Pembelian</h2>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-300 rounded">
            {{ session('success') }}
        </div>  
    @endif

    <div class="mb-6 flex justify-end">
        <form action="{{ route('purchases.index') }}" method="GET" class="relative w-full max-w-sm">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="search nama"
                class="w-full px-4 py-2 pr-24 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
            >

            @if(request()->filled('search'))
                <a href="{{ route('purchases.index') }}"
                   class="absolute right-1 top-1 bottom-1 px-4 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-full shadow transition duration-150 flex items-center justify-center">
                    ✕
                </a>
            @else
                <button
                    type="submit"
                    class="absolute right-1 top-1 bottom-1 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-full shadow transition duration-150"
                >
                    Cari
                </button>
            @endif
        </form>
    </div>

    {{-- Tombol Tambah Pembelian --}}
    <div class="flex items-center gap-4 mb-6">
        @if(auth()->check() && auth()->user()->role == 'petugas')
            <a href="{{ route('purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + Tambah Pembelian
            </a>
        @endif

        <a href="{{ route('purchases.export.excel') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            Export Excel
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full table-auto text-sm text-center border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-3 border-b">Nama Pelanggan</th>
                    <th class="px-4 py-3 border-b">Tanggal</th>
                    <th class="px-4 py-3 border-b">Total Harga</th>
                    <th class="px-4 py-3 border-b">Dibuat Oleh</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($purchases as $purchase)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="px-4 py-3">
                            {{ $purchase->member->name ?? 'Non Member' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $purchase->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $purchase->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('purchases.receipt', $purchase->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Detail
                                </a>
                                <a href="{{ route('purchases.download', $purchase->id) }}"
                                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Unduh Bukti
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Belum ada data pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
