<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductExport implements FromCollection, WithHeadings, WithMapping, WithEvents
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Judul di atas
                $event->sheet->insertNewRowBefore(1, 1);
                $event->sheet->setCellValue('A1', ' Data Produk Berrymarket');
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1')->getFont()
                    ->setBold(true)->setSize(12);
            },
        ];
    }
}
