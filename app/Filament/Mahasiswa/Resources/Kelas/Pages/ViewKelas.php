<?php

namespace App\Filament\Mahasiswa\Resources\Kelas\Pages;

use App\Filament\Mahasiswa\Resources\Kelas\KelasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKelas extends ViewRecord
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
