<?php

namespace App\Filament\Mahasiswa\Resources\Tugas\Pages;

use App\Filament\Mahasiswa\Resources\Tugas\TugasResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTugas extends EditRecord
{
    protected static string $resource = TugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
