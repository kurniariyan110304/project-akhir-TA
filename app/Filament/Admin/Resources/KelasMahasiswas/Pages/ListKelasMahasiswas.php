<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas\Pages;

use App\Filament\Admin\Resources\KelasMahasiswas\KelasMahasiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelasMahasiswas extends ListRecords
{
    protected static string $resource = KelasMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
