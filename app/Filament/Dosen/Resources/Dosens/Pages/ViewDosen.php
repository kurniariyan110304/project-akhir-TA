<?php

namespace App\Filament\Dosen\Resources\Dosens\Pages;

use App\Filament\Dosen\Resources\Dosens\DosenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDosen extends ViewRecord
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
