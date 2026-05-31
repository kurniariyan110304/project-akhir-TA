<?php

namespace App\Filament\Dosen\Resources\KelasMahasiswas\Pages;

use App\Filament\Dosen\Resources\KelasMahasiswas\KelasMahasiswaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKelasMahasiswa extends ViewRecord
{
    protected static string $resource = KelasMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
