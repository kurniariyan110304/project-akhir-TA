<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Asdos;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\KelompokProject;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Admin';

    protected function getStats(): array
    {
        return [
            Stat::make('User', User::query()->count())
                ->description('Total akun pengguna')
                ->icon('heroicon-o-users'),

            Stat::make('Dosen', Dosen::query()->count())
                ->description('Total data dosen')
                ->icon('heroicon-o-user'),

            Stat::make('Mahasiswa', Mahasiswa::query()->count())
                ->description('Total data mahasiswa')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Asdos', Asdos::query()->count())
                ->description('Total asisten dosen')
                ->icon('heroicon-o-user-group'),

            Stat::make('Mata Kuliah', Matakuliah::query()->count())
                ->description('Total mata kuliah')
                ->icon('heroicon-o-book-open'),

            Stat::make('Kelas', Kelas::query()->count())
                ->description('Total kelas')
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Kelas Mahasiswa', KelasMahasiswa::query()->count())
                ->description('Total mahasiswa dalam kelas')
                ->icon('heroicon-o-clipboard-document-check'),

            Stat::make('Tugas Project', Tugas::query()->count())
                ->description('Total tugas project')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Project Mahasiswa', ProjectMahasiswa::query()->count())
                ->description('Total project yang dikumpulkan')
                ->icon('heroicon-o-folder-open'),

            Stat::make('Kelompok Project', KelompokProject::query()->count())
                ->description('Total anggota kelompok project')
                ->icon('heroicon-o-user-group'),
        ];
    }
}