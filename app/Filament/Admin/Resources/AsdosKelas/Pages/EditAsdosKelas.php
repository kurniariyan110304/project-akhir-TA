<?php

namespace App\Filament\Admin\Resources\AsdosKelas\Pages;

use App\Filament\Admin\Resources\AsdosKelas\AsdosKelasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsdosKelas extends EditRecord
{
    protected static string $resource = AsdosKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
