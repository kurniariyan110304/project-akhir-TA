<?php

namespace App\Filament\Asdos\Resources\Tugas\Pages;

use App\Filament\Asdos\Resources\Tugas\TugasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTugas extends ListRecords
{
    protected static string $resource = TugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
