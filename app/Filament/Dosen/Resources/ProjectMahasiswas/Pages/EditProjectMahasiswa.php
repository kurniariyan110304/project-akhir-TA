<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas\Pages;

use App\Filament\Dosen\Resources\ProjectMahasiswas\ProjectMahasiswaResource;
use App\Models\KelompokProject;
use Filament\Resources\Pages\EditRecord;

class EditProjectMahasiswa extends EditRecord
{
    protected static string $resource = ProjectMahasiswaResource::class;

    protected array $anggotaKelompokState = [];

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->tugas?->kategori === 'KELOMPOK') {
            $data['anggota_kelompok'] = KelompokProject::query()
                ->with('mahasiswa')
                ->where('project_mahasiswa_id', $this->record->id)
                ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                ->orderBy('id')
                ->get()
                ->map(function (KelompokProject $anggota): array {
                    return [
                        'mahasiswa_nim' => $anggota->mahasiswa_nim,
                        'nama_mahasiswa' => $anggota->mahasiswa_nim . ' - ' . ($anggota->mahasiswa?->nama ?? '-'),
                        'peran' => $anggota->peran ?? '-',
                        'nilai' => $anggota->nilai ?? 0,
                    ];
                })
                ->toArray();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->anggotaKelompokState = $data['anggota_kelompok'] ?? [];

        unset($data['anggota_kelompok']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->tugas?->kategori === 'KELOMPOK') {
            foreach ($this->anggotaKelompokState as $anggota) {
                if (empty($anggota['mahasiswa_nim'])) {
                    continue;
                }

                KelompokProject::query()
                    ->where('project_mahasiswa_id', $this->record->id)
                    ->where('mahasiswa_nim', $anggota['mahasiswa_nim'])
                    ->update([
                        'nilai' => $anggota['nilai'] ?? 0,
                    ]);
            }

            $nilaiAkhir = KelompokProject::query()
                ->where('project_mahasiswa_id', $this->record->id)
                ->whereNotNull('nilai')
                ->avg('nilai');

            $this->record->update([
                'nilai_akhir' => $nilaiAkhir ? round($nilaiAkhir, 2) : 0,
            ]);
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Nilai project berhasil disimpan';
    }

    protected function getRedirectUrl(): string
    {
        return ProjectMahasiswaResource::getUrl('index');
    }
}