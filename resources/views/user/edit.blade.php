@extends('layouts.app')

@section('title', 'User')

@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-lg relative">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit User</h2>

        <!-- Tombol close -->
        <a href="{{ route('user.index') }}" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-xl font-bold">
            &times;
        </a>

        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (opsional)</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Biarkan kosong jika tidak ingin diubah">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select id="role" name="role"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ $user->role === 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between">
                <a href="{{ route('user.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-gray-800 hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
