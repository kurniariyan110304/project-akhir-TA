<?php

namespace App\Filament\Admin\Resources\Asdos\Pages;

use App\Filament\Admin\Resources\Asdos\AsdosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsdos extends EditRecord
{
    protected static string $resource = AsdosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}