<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
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
}
