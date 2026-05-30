<?php

namespace App\Filament\Mahasiswa\Resources\Kategoris\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KategoriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama'),
            ]);
    }
}
