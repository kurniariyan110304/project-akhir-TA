<?php

namespace App\Filament\Admin\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('semester')
                    ->numeric(),

                    TextInput::make('kode')
                    ->label('Kode Kelas')
                    ->required()
                    ->maxLength(20),

                Select::make('matakuliah_id')
                    ->label('Mata Kuliah')
                    ->relationship('matakuliah', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('dosen_id')
                    ->label('Dosen')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('ruang'),

                TimePicker::make('jam')
                    ->label('Jam Kuliah')
                    ->seconds(false)
                    ->required(),

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