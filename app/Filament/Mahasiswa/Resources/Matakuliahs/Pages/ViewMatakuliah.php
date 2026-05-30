<?php

namespace App\Filament\Mahasiswa\Resources\Matakuliahs\Pages;

use App\Filament\Mahasiswa\Resources\Matakuliahs\MatakuliahResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMatakuliah extends ViewRecord
{
    protected static string $resource = MatakuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
