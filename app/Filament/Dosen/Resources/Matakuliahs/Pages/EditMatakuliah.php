<?php

namespace App\Filament\Dosen\Resources\Matakuliahs\Pages;

use App\Filament\Dosen\Resources\Matakuliahs\MatakuliahResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMatakuliah extends EditRecord
{
    protected static string $resource = MatakuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
