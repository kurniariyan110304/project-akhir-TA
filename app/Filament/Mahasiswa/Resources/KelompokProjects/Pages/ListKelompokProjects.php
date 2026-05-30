<?php

namespace App\Filament\Mahasiswa\Resources\KelompokProjects\Pages;

use App\Filament\Mahasiswa\Resources\KelompokProjects\KelompokProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelompokProjects extends ListRecords
{
    protected static string $resource = KelompokProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Kelompok'),
        ];
    }
}