<?php

namespace App\Filament\Dosen\Resources\KelasMahasiswas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KelasMahasiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kelas.id')
                    ->numeric(),
                TextEntry::make('mahasiswa_nim'),
                TextEntry::make('nilai_akhir')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
