<?php

namespace App\Filament\Dosen\Resources\Tugas\Pages;

use App\Filament\Dosen\Resources\Tugas\TugasResource;
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
