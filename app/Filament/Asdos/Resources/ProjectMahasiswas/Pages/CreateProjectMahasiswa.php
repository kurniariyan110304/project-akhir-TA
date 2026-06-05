<?php

namespace App\Filament\Asdos\Resources\ProjectMahasiswas\Pages;

use App\Filament\Asdos\Resources\ProjectMahasiswas\ProjectMahasiswaResource;
use App\Models\KelompokProject;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateProjectMahasiswa extends CreateRecord
{
    protected static string $resource = ProjectMahasiswaResource::class;

    protected array $anggotaKelompokState = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->anggotaKelompokState = $data['mahasiswa_nims'] ?? [];

        unset($data['mahasiswa_nims']);

        $tugas = Tugas::find($data['tugas_project_id'] ?? null);

        if ($tugas?->kategori === 'KELOMPOK') {
            $data['mahasiswa_nim'] = $this->anggotaKelompokState[0] ?? null;
            $data['nilai_akhir'] = 0;
        }

        if ($tugas?->kategori === 'INDIVIDU') {
            $data['nama_kelompok'] = null;
            $data['nilai_akhir'] = $data['nilai_akhir'] ?? 0;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $tugas = $this->record->tugas;

        if ($tugas?->kategori !== 'KELOMPOK') {
            return;
        }

        foreach ($this->anggotaKelompokState as $index => $nim) {
            KelompokProject::firstOrCreate(
                [
                    'project_mahasiswa_id' => $this->record->id,
                    'mahasiswa_nim' => $nim,
                ],
                [
                    'peran' => $index === 0 ? 'KETUA' : 'ANGGOTA',
                    'aktif' => 1,
                    'nilai' => 0,
                ]
            );
        }

        Notification::make()
            ->title('Project kelompok berhasil dibuat')
            ->body('Data project dan anggota kelompok berhasil disimpan.')
            ->success()
            ->send();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Project mahasiswa berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return ProjectMahasiswaResource::getUrl('index');
    }
}