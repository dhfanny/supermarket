<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role,
            $user->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y')
        ];
    }

    public function headings(): array
    {
        return [
            'Username',
            'Email',
            'Role',
            'Tanggal Dibuat',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Judul di atas
                $event->sheet->insertNewRowBefore(1, 1);
                $event->sheet->setCellValue('A1', 'Data User');
                $event->sheet->mergeCells('A1:D1');
                $event->sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1')->getFont()
                    ->setBold(true)->setSize(12);
            },
        ];
    }
}
