<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MahasiswaExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    // Terima query dari Filament (optional)
    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function query()
    {
        return ($this->query ?? Mahasiswa::query())
            ->with('prodi');
    }

    // Mapping data biar bisa ambil relasi
    public function map($item): array
    {
        return [
            $item->nim,
            $item->nama,
            $item->jk,
            $item->tmp_lahir,
            $item->tgl_lahir,
            $item->email,
            $item->thn_masuk,
            $item->prodi?->nama ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'JK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Email',
            'Tahun Masuk',
            'Prodi',
        ];
    }
}