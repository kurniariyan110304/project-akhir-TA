<?php

namespace App\Filament\Mahasiswa\Resources\Tugas\Pages;

use App\Filament\Mahasiswa\Resources\Tugas\TugasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTugas extends ViewRecord
{
    protected static string $resource = TugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
