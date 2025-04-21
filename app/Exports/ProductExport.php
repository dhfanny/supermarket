<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::all();
    }

    public function map($product): array
    {
        return [
            $product->nama_produk,
            'Rp ' . number_format($product->harga, 0, ',', '.'),
            $product->stok,
            $product->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y')
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Harga',
            'Stok',
            'Tanggal Dibuat',
        ];
    }
}
