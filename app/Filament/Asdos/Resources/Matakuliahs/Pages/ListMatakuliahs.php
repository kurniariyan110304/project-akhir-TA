<?php

namespace App\Filament\Asdos\Resources\Matakuliahs\Pages;

use App\Filament\Asdos\Resources\Matakuliahs\MatakuliahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatakuliahs extends ListRecords
{
    protected static string $resource = MatakuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
