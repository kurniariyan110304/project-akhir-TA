<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas\Pages;

use App\Filament\Dosen\Resources\ProjectMahasiswas\ProjectMahasiswaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectMahasiswa extends ViewRecord
{
    protected static string $resource = ProjectMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
