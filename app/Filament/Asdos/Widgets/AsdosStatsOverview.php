<?php

namespace App\Filament\Asdos\Widgets;

use App\Models\AsdosKelas;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Matakuliah;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use App\Models\KelompokProject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AsdosStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Asdos';

    protected function getStats(): array
    {
        $asdos = auth()->user()?->asdos;

        if (! $asdos || ! $asdos->aktif) {
            return [
                Stat::make('Kelas', 0),
                Stat::make('Mata Kuliah', 0),
                Stat::make('Tugas Project', 0),
                Stat::make('Mahasiswa Dinilai', 0),
                Stat::make('Project Mahasiswa', 0),
                Stat::make('Kelompok Project', 0),
            ];
        }

        $kelasIds = AsdosKelas::query()
            ->where('asdos_id', $asdos->id)
            ->pluck('kelas_id');

        $jumlahKelas = $kelasIds->count();

        $jumlahMataKuliah = Kelas::query()
            ->whereIn('id', $kelasIds)
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
            Stat::make('Kelas Ditugaskan', $jumlahKelas)
                ->description('Total kelas yang dipegang asdos')
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Mata Kuliah', $jumlahMataKuliah)
                ->description('Mata kuliah dari kelas asdos')
                ->icon('heroicon-o-book-open'),

            Stat::make('Tugas Project', $jumlahTugasProject)
                ->description('Tugas dari kelas asdos')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Mahasiswa Dinilai', $jumlahMahasiswaDinilai)
                ->description('Mahasiswa yang sudah punya nilai akhir')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Project Mahasiswa', $jumlahProjectMahasiswa)
                ->description('Project yang sudah dikumpulkan')
                ->icon('heroicon-o-folder-open'),

            Stat::make('Kelompok Project', $jumlahKelompokProject)
                ->description('Anggota kelompok project')
                ->icon('heroicon-o-user-group'),
        ];
    }
}