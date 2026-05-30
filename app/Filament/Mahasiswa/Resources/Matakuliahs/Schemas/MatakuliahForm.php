<?php

namespace App\Filament\Mahasiswa\Resources\Matakuliahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MatakuliahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode'),
                TextInput::make('nama'),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }
}
