<div id="formCreate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
        <button onclick="closeForm()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>

        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Tambah Produk</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition duration-200" required>
            </div>

            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <input type="text" id="harga" name="harga" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition duration-200" required oninput="formatRupiah(this)">
            </div>

            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" id="stok" name="stok" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition duration-200" required>
            </div>

            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                <input type="file" id="gambar" name="gambar" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition duration-200" onchange="previewImage(event)">
                <img id="preview" class="mt-3 hidden w-full h-48 object-cover rounded-lg shadow-sm" />
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">Tambah Produk</button>
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

    function formatRupiah(element) {
        let value = element.value.replace(/\D/g, '');
        let formattedValue = new Intl.NumberFormat('id-ID').format(value);
        element.value = 'Rp ' + formattedValue;
    }

    function previewImage(event) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.classList.remove('hidden');
    }
</script>

