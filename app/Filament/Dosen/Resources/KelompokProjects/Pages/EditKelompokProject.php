<?php

namespace App\Filament\Dosen\Resources\KelompokProjects\Pages;

use App\Filament\Dosen\Resources\KelompokProjects\KelompokProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKelompokProject extends EditRecord
{
    protected static string $resource = KelompokProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
