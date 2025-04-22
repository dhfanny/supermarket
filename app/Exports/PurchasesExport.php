<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchasesExport implements FromArray, WithHeadings, WithEvents
{
    protected $data = [];

    public function array(): array
    {
        $purchases = Purchase::with(['member', 'products'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        foreach ($purchases as $purchase) {
            $member = $purchase->member;
            $productCount = $purchase->products->count();

            foreach ($purchase->products as $index => $product) {
                $row = [];

                $row[] = $index === 0 ? ($member->name ?? 'Non Member') : '';
                $row[] = $index === 0 ? ($member->no_phone ?? '-') : '';
                $row[] = $index === 0 ? ($member->poin ?? 0) : '';

                $row[] = $product->nama_produk;
                $row[] = $product->pivot->quantity;
                $row[] = 'Rp ' . number_format($product->harga, 0, ',', '.');

                $row[] = ($index === 0 || $productCount === 1)
                    ? 'Rp ' . number_format($purchase->total_price, 0, ',', '.')
                    : '';

                $row[] = $index === 0 ? 'Rp ' . number_format($purchase->total_bayar, 0, ',', '.') : '';
                $row[] = $index === 0 ? 'Rp ' . number_format($purchase->diskon_poin ?? 0, 0, ',', '.') : '';
                $row[] = $index === 0 ? 'Rp ' . number_format($purchase->kembalian ?? 0, 0, ',', '.') : '';
                $row[] = $index === 0 ? $purchase->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '';

                $this->data[] = $row;
            }
        }

        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No Telepon',
            'Poin Pelanggan',
            'Nama Produk',
            'Qty',
            'Harga Satuan',
            'Subtotal Produk',
            'Total Bayar',
            'Diskon Poin',
            'Kembalian',
            'Tanggal Pembelian',
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            // Judul di atas
            $event->sheet->insertNewRowBefore(1, 1);
            $event->sheet->setCellValue('A1', 'Berrymarket');
            $event->sheet->mergeCells('A1:K1');

            $event->sheet->getStyle('A1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $event->sheet->getStyle('A1')->getFont()
                ->setBold(true)->setSize(12);

            // Format kolom angka menjadi rata kanan
            $rightAlignedColumns = ['F', 'G', 'H', 'I', 'J'];
            foreach ($rightAlignedColumns as $col) {
                $event->sheet->getStyle("{$col}2:{$col}" . ($event->sheet->getHighestRow()))
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            }
        },
    ];
}

}
