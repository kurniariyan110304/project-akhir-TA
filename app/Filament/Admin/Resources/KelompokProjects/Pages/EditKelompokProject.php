<?php

namespace App\Filament\Admin\Resources\KelompokProjects\Pages;

use App\Filament\Admin\Resources\KelompokProjects\KelompokProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelompokProject extends EditRecord
{
    protected static string $resource = KelompokProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}