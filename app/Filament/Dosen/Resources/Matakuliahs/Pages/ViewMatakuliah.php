<?php

namespace App\Filament\Dosen\Resources\Matakuliahs\Pages;

use App\Filament\Dosen\Resources\Matakuliahs\MatakuliahResource;
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
