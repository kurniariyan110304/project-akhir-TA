<?php

namespace App\Filament\Dosen\Resources\Mahasiswas\Pages;

use App\Filament\Dosen\Resources\Mahasiswas\MahasiswaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMahasiswa extends ViewRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
