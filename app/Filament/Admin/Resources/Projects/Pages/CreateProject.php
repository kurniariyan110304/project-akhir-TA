<?php

namespace App\Filament\Admin\Resources\Projects\Pages;

use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Models\KelompokProject;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->tugas?->kategori === 'KELOMPOK') {
            KelompokProject::firstOrCreate(
                [
                    'project_mahasiswa_id' => $this->record->id,
                    'mahasiswa_nim' => $this->record->mahasiswa_nim,
                ],
                [
                    'peran' => 'KETUA',
                    'nilai' => 0,
                    'aktif' => 1,
                ]
            );

            $this->record->refresh();
            $this->record->recalculateNilaiAkhir();
        }
    }
}