<?php

namespace App\Exports;

use App\Models\KelompokProject;
use App\Models\ProjectMahasiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectMahasiswaExcelExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected array $projectIds;

    public function __construct(array $projectIds)
    {
        $this->projectIds = $projectIds;
    }

    public function headings(): array
    {
        return [
            'Nama Project',
            'Tipe Tugas',
            'Nama Kelompok',
            'NIM Mahasiswa',
            'Nama Mahasiswa',
            'Peran',
            'Nilai Anggota',
            'Nilai Akhir Project',
        ];
    }

    public function collection(): Collection
    {
        $projects = ProjectMahasiswa::query()
            ->with([
                'tugas',
                'mahasiswa',
            ])
            ->whereIn('id', $this->projectIds)
            ->orderBy('nama_project')
            ->get();

        $rows = collect();

        foreach ($projects as $project) {
            $tipeTugas = $project->tugas?->kategori ?? '-';

            if ($tipeTugas === 'KELOMPOK') {
                $anggota = KelompokProject::query()
                    ->with('mahasiswa')
                    ->where('project_mahasiswa_id', $project->id)
                    ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                    ->orderBy('id')
                    ->get();

                foreach ($anggota as $item) {
                    $rows->push([
                        $project->nama_project,
                        $tipeTugas,
                        $project->nama_kelompok ?? '-',
                        $item->mahasiswa_nim,
                        $item->mahasiswa?->nama ?? '-',
                        $item->peran ?? '-',
                        $item->nilai ?? 0,
                        $project->nilai_akhir ?? 0,
                    ]);
                }
            } else {
                $rows->push([
                    $project->nama_project,
                    $tipeTugas,
                    '-',
                    $project->mahasiswa_nim,
                    $project->mahasiswa?->nama ?? '-',
                    'INDIVIDU',
                    $project->nilai_akhir ?? 0,
                    $project->nilai_akhir ?? 0,
                ]);
            }
        }

        return $rows;
    }
}