<?php

namespace App\Filament\Asdos\Resources\KelompokProjects\Pages;

use App\Filament\Asdos\Resources\KelompokProjects\KelompokProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelompokProjects extends ListRecords
{
    protected static string $resource = KelompokProjectResource::class;
}