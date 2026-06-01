<?php

namespace App\Filament\Mahasiswa\Widgets;

use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use App\Models\KelompokProject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MahasiswaStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Mahasiswa';

    protected function getStats(): array
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return [
                Stat::make('Kelas Diambil', 0),
                Stat::make('Mata Kuliah', 0),
                Stat::make('Tugas Project', 0),
                Stat::make('Project Mahasiswa', 0),
                Stat::make('Kelompok Project', 0),
                Stat::make('Nilai Akhir Terisi', 0),
            ];
        }

        $kelasIds = KelasMahasiswa::query()
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->pluck('kelas_id');

        $jumlahKelas = $kelasIds->count();

        $jumlahMataKuliah = Kelas::query()
            ->whereIn('id', $kelasIds)
            ->distinct('matakuliah_id')
            ->count('matakuliah_id');

        $jumlahTugasProject = Tugas::query()
            ->whereIn('kelas_id', $kelasIds)
            ->count();

        $jumlahProjectMahasiswa = ProjectMahasiswa::query()
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->count();

        $jumlahKelompokProject = KelompokProject::query()
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->count();

        $jumlahNilaiAkhirTerisi = KelasMahasiswa::query()
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->whereNotNull('nilai_akhir')
            ->count();

        return [
            Stat::make('Kelas Diambil', $jumlahKelas)
                ->description('Total kelas yang sedang diikuti')
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Mata Kuliah', $jumlahMataKuliah)
                ->description('Mata kuliah dari kelas yang diambil')
                ->icon('heroicon-o-book-open'),

            Stat::make('Tugas Project', $jumlahTugasProject)
                ->description('Tugas project dari kelas yang diambil')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Project Mahasiswa', $jumlahProjectMahasiswa)
                ->description('Project yang sudah dibuat')
                ->icon('heroicon-o-folder-open'),

            Stat::make('Kelompok Project', $jumlahKelompokProject)
                ->description('Data anggota kelompok project')
                ->icon('heroicon-o-user-group'),

            Stat::make('Nilai Akhir Terisi', $jumlahNilaiAkhirTerisi)
                ->description('Nilai akhir kelas yang sudah tersedia')
                ->icon('heroicon-o-academic-cap'),
        ];
    }
}