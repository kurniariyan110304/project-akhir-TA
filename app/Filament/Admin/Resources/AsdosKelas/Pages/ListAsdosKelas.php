<?php

namespace App\Filament\Admin\Resources\AsdosKelas\Pages;

use App\Filament\Admin\Resources\AsdosKelas\AsdosKelasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsdosKelas extends ListRecords
{
    protected static string $resource = AsdosKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
