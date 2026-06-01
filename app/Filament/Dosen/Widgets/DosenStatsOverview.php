<?php

namespace App\Filament\Dosen\Widgets;

use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use App\Models\KelompokProject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DosenStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Dosen';

    protected function getStats(): array
    {
        $dosen = auth()->user()?->dosen;

        if (! $dosen) {
            return [
                Stat::make('Kelas Diajar', 0),
                Stat::make('Mata Kuliah', 0),
                Stat::make('Tugas Project', 0),
                Stat::make('Mahasiswa Dinilai', 0),
                Stat::make('Project Mahasiswa', 0),
                Stat::make('Kelompok Project', 0),
            ];
        }

        $kelasIds = Kelas::query()
            ->where('dosen_id', $dosen->id)
            ->pluck('id');

        $jumlahKelas = $kelasIds->count();

        $jumlahMataKuliah = Kelas::query()
            ->where('dosen_id', $dosen->id)
            ->distinct('matakuliah_id')
            ->count('matakuliah_id');

        $jumlahTugasProject = Tugas::query()
            ->whereIn('kelas_id', $kelasIds)
            ->count();

        $jumlahMahasiswaDinilai = KelasMahasiswa::query()
            ->whereIn('kelas_id', $kelasIds)
            ->whereNotNull('nilai_akhir')
            ->count();

        $jumlahProjectMahasiswa = ProjectMahasiswa::query()
            ->whereHas('tugas', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->count();

        $jumlahKelompokProject = KelompokProject::query()
            ->whereHas('project.tugas', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->count();

        return [
            Stat::make('Kelas Diajar', $jumlahKelas)
                ->description('Total kelas yang diajar dosen')
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Mata Kuliah', $jumlahMataKuliah)
                ->description('Mata kuliah dari kelas yang diajar')
                ->icon('heroicon-o-book-open'),

            Stat::make('Tugas Project', $jumlahTugasProject)
                ->description('Total tugas project yang diberikan')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Mahasiswa Dinilai', $jumlahMahasiswaDinilai)
                ->description('Mahasiswa yang sudah punya nilai akhir')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Project Mahasiswa', $jumlahProjectMahasiswa)
                ->description('Project yang sudah dikumpulkan')
                ->icon('heroicon-o-folder-open'),

            Stat::make('Kelompok Project', $jumlahKelompokProject)
                ->description('Data anggota kelompok project')
                ->icon('heroicon-o-user-group'),
        ];
    }
}