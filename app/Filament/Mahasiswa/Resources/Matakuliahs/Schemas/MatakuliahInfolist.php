<?php

namespace App\Filament\Mahasiswa\Resources\Matakuliahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MatakuliahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode'),
                TextEntry::make('nama'),
            ]);
    }
}
