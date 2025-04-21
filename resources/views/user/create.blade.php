{{-- ALERT ERROR --}}
@if ($errors->any())
    <div id="errorAlert" class="bg-red-100 text-red-800 p-3 rounded mb-4 transition-opacity duration-1000">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        setTimeout(() => {
            const errorBox = document.getElementById('errorAlert');
            if (errorBox) {
                errorBox.classList.add('opacity-0');
                setTimeout(() => errorBox.style.display = 'none', 1000);
            }
        }, 5000);
    </script>
@endif
<div id="formCreate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
        <button onclick="closeForm()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>

        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Tambah User</h2>

        <form action="{{ route('user.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="role" name="role" class="w-full p-3 border border-gray-300 rounded-lg" required>
                    <option value="" disabled>Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                Tambah User
            </button>
        </form>
    </div>
</div>

<script>
    function openForm() {
        document.getElementById('formCreate').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formCreate').classList.add('hidden');
    }
</script>
