<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalPenjualan = null;
        $chartData = [];
        $totalPerProduk = [];
        $totalStokProduk = [];


        if ($user->role === 'petugas') {
            $totalPenjualan = Purchase::whereDate('created_at', Carbon::today())->count();
        }

        if ($user->role === 'admin') {
            // Data untuk grafik bar (penjualan per tanggal dan produk 7 hari terakhir)
            $salesPerDayRaw = DB::table('purchase_product')
            ->join('purchases', 'purchase_product.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_product.product_id', '=', 'products.id')
            ->select(
                DB::raw('DATE(purchases.created_at) as tanggal'),
                'products.nama_produk',
                DB::raw('CAST(SUM(purchase_product.quantity) AS UNSIGNED) as total') // Pastikan hasil total adalah integer
            )
            ->whereDate('purchases.created_at', '>=', Carbon::now()->subDays(6)->toDateString())
            ->groupBy('tanggal', 'products.nama_produk')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        // Menyusun data menjadi format yang sesuai untuk chart
        $chartData = [];

        foreach ($salesPerDayRaw as $tanggal => $items) {
            $entry = ['tanggal' => $tanggal]; // Menambahkan tanggal ke array entry
            foreach ($items as $item) {
                // Menambahkan nama produk dan total penjualan ke array entry, pastikan total adalah integer
                $entry[$item->nama_produk] = (int) $item->total;
            }
            $chartData[] = $entry; // Menambahkan entry ke chartData
        }

            // Data untuk grafik pie (total penjualan per produk)
            $totalPerProduk = DB::table('purchase_product')
                ->join('products', 'purchase_product.product_id', '=', 'products.id')
                ->select('products.nama_produk', DB::raw('SUM(purchase_product.quantity) as total'))
                ->groupBy('products.nama_produk')
                ->pluck('total', 'products.nama_produk')
                ->toArray();

            $totalStokProduk = Product::select('nama_produk', 'stok')
                ->orderBy('nama_produk')
                ->get()
                ->toArray();
        }


        return view('dashboard', compact('user', 'totalPenjualan', 'chartData', 'totalPerProduk', 'totalStokProduk'));
    }
}
