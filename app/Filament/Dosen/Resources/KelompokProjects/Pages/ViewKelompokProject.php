<?php

namespace App\Filament\Dosen\Resources\KelompokProjects\Pages;

use App\Filament\Dosen\Resources\KelompokProjects\KelompokProjectResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKelompokProject extends ViewRecord
{
    protected static string $resource = KelompokProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
