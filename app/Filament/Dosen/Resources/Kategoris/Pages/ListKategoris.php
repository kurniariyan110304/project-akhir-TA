<?php

namespace App\Filament\Dosen\Resources\Kategoris\Pages;

use App\Filament\Dosen\Resources\Kategoris\KategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoris extends ListRecords
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
