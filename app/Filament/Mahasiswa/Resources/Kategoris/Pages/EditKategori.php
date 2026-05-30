<?php

namespace App\Filament\Mahasiswa\Resources\Kategoris\Pages;

use App\Filament\Mahasiswa\Resources\Kategoris\KategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKategori extends EditRecord
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
