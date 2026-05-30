<?php

namespace App\Filament\Dosen\Resources\Kelas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KelasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('semester')
                    ->numeric(),
                TextEntry::make('kode'),
                TextEntry::make('matakuliah_id')
                    ->numeric(),
                TextEntry::make('dosen_id')
                    ->numeric(),
                TextEntry::make('ruang'),
                TextEntry::make('jam'),
                TextEntry::make('hari'),
            ]);
    }
}