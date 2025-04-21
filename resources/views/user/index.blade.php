@extends('layouts.app')

@section('title', 'User')

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
    <h1 class="text-3xl font-semibold mb-6">Manajemen User</h1>

        <button onclick="openForm()" class="bg-green-500 text-white py-2 px-4 rounded-lg mb-4 inline-block hover:bg-green-600">
            Tambah User
        </button>
        <a href="{{ route('user.export') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 mb-3">
            Export ke Excel
        </a>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    @if(auth()->user()->role === 'admin')
                        <th class="px-4 py-2 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2 text-center space-x-2">
                        <a href="{{ route('user.edit', $user->id) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                            Edit
                        </a>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                              class="inline-block" onsubmit="return confirm('Hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('user.create')

@endsection
