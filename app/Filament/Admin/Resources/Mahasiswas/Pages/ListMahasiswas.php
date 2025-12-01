<?php

namespace App\Filament\Admin\Resources\Mahasiswas\Pages;

use App\Filament\Admin\Resources\Mahasiswas\MahasiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
