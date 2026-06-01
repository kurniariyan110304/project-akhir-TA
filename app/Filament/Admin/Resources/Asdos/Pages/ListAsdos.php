<?php

namespace App\Filament\Admin\Resources\Asdos\Pages;

use App\Filament\Admin\Resources\Asdos\AsdosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsdos extends ListRecords
{
    protected static string $resource = AsdosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Asdos'),
        ];
    }
}