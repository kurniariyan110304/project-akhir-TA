<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas\Pages;

use App\Filament\Admin\Resources\KelasMahasiswas\KelasMahasiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKelasMahasiswa extends EditRecord
{
    protected static string $resource = KelasMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
