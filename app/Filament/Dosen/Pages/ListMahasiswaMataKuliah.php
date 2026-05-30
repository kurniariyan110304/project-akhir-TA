<?php

namespace App\Filament\Dosen\Resources\MataKuliah\Pages;

use App\Filament\Dosen\Resources\MataKuliah\MataKuliahResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class ListMahasiswaMataKuliah extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MataKuliahResource::class;

    protected string $view = 'filament.dosen.resources.mata-kuliah.pages.list-mahasiswa-mata-kuliah';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Mahasiswa - ' . $this->record->nama;
    }

    public function getMahasiswaProperty()
    {
        return DB::table('kelas_mahasiswa')
            ->join('kelas', 'kelas.id', '=', 'kelas_mahasiswa.kelas_id')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'kelas_mahasiswa.mahasiswa_nim')
            ->where('kelas.matakuliah_id', $this->record->id)
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.email',
                'mahasiswa.thn_masuk',
                'kelas.semester',
                'kelas.ruang',
                'kelas.jam',
                'kelas_mahasiswa.nilai_akhir'
            )
            ->orderBy('mahasiswa.nama')
            ->get();
    }
}