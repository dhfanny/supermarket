<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|string',
            'stok' => 'required|integer',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // untuk menggilangkan rp dan simbol .
        $price = str_replace(['Rp', '.'], '', $request->harga);

        // Simpan gambar dan memeriksa periksa pathnya
        $imagePath = $request->file('gambar')->store('products', 'public');


        Product::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $price,
            'stok' => $request->stok,
            'gambar' => $imagePath,  // Menyimpan path gambar di database
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar boleh kosong saat edit
        ]);

        // Simpan gambar dan memeriksa periksa pathnya
        $price = str_replace(['Rp', '.'], '', $request->harga);

        $updateData = [
            'nama_produk' => $request->nama_produk,
            'harga' => $price,
        ];

        // mengecek apa ada gambar baru
        if ($request->hasFile('gambar')) {
            // hapus gambar lama jika ada
            Storage::disk('public')->delete($product->gambar);

            // mentimpan gambar baru
            $updateData['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($updateData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function editstok(Product $product)
    {
        return view('products.updatestok', compact('product'));
    }

    public function updatestok(Request $request, Product $product)
    {
        $request->validate([
            'stok' => 'required|integer|min:0', // pakai min:0 biar nggak bisa input angka minus
        ]);

        $product->update([
            'stok' => $request->stok,
        ]);

        return redirect()->route('products.index')->with('success', 'Stok produk berhasil diperbarui.');
    }


    public function destroy(Product $product)
    {
        // Hapus gambar dari storage
        Storage::disk('public')->delete($product->gambar);

        // Hapus produk dari database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
    public function exportExcel()
    {
        return Excel::download(new ProductExport, 'data_produk.xlsx');
    }
}
