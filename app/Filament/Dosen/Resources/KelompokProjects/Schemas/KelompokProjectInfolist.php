<?php

namespace App\Filament\Dosen\Resources\KelompokProjects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KelompokProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('peran'),
                TextEntry::make('nilai')
                    ->numeric(),
                TextEntry::make('projectMahasiswa.id')
                    ->numeric(),
                TextEntry::make('mahasiswa_nim'),
                TextEntry::make('aktif')
                    ->numeric(),
            ]);
    }
}
