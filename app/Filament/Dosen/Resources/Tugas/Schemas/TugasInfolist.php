<?php

namespace App\Filament\Dosen\Resources\Tugas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kategori'),
                TextEntry::make('semester')
                    ->numeric(),
                TextEntry::make('kelas_id')
                    ->numeric(),
                TextEntry::make('mulai')
                    ->date(),
                TextEntry::make('akhir')
                    ->date(),
                TextEntry::make('kategori_project_id')
                    ->numeric(),
            ]);
    }
}
