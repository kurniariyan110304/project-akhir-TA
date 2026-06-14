<?php

namespace App\Exports;

use App\Models\KelasMahasiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiMahasiswaExcelExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected array $kelasIds;

    public function __construct(array $kelasIds)
    {
        $this->kelasIds = $kelasIds;
    }

    public function headings(): array
    {
        return [
            'Kode Kelas',
            'Mata Kuliah',
            'Semester',
            'NIM',
            'Nama Mahasiswa',
            'Email',
            'Nilai Akhir',
        ];
    }

    public function collection(): Collection
    {
        return KelasMahasiswa::query()
            ->with([
                'kelas',
                'kelas.matakuliah',
                'mahasiswa',
            ])
            ->whereIn('kelas_id', $this->kelasIds)
            ->orderBy('kelas_id')
            ->orderBy('mahasiswa_nim')
            ->get()
            ->map(function (KelasMahasiswa $item) {
                return [
                    $item->kelas?->kode ?? '-',
                    $item->kelas?->matakuliah?->nama ?? '-',
                    $item->kelas?->semester ?? '-',
                    $item->mahasiswa_nim ?? '-',
                    $item->mahasiswa?->nama ?? '-',
                    $item->mahasiswa?->email ?? '-',
                    $item->nilai_akhir ?? 0,
                ];
            });
    }
}