<?php

namespace App\Filament\Dosen\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('semester')
                    ->numeric(),
                TextInput::make('kode'),
                TextInput::make('matakuliah_id')
                    ->required()
                    ->numeric(),
                TextInput::make('dosen_id')
                    ->required()
                    ->numeric(),
                TextInput::make('ruang'),
                TextInput::make('jam'),
                Select::make('hari')
                    ->options([
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
        ]),
            ]);
    }
}
