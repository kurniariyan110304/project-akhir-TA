<?php

namespace App\Filament\Dosen\Resources\KelasMahasiswas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasMahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kelas_id')
                    ->relationship('kelas', 'id')
                    ->required(),
                TextInput::make('mahasiswa_nim')
                    ->required(),
                TextInput::make('nilai_akhir')
                    ->numeric(),
            ]);
    }
}
