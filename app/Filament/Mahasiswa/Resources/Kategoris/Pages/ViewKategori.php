<?php

namespace App\Filament\Mahasiswa\Resources\Kategoris\Pages;

use App\Filament\Mahasiswa\Resources\Kategoris\KategoriResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKategori extends ViewRecord
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
