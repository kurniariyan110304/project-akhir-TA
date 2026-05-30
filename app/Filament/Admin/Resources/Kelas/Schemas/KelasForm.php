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
                TextInput::make('kode')
                    ->label('Kode Kelas')
                    ->placeholder('Contoh: BD-01')
                    ->required()
                    ->maxLength(20),

                Select::make('matakuliah_id')
                    ->label('Mata Kuliah')
                    ->relationship('matakuliah', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('dosen_id')
                    ->label('Dosen Pengampu')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->placeholder('Contoh: 20251')
                    ->required(),

                Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->required(),

                TimePicker::make('jam')
                    ->label('Jam Kuliah')
                    ->seconds(false)
                    ->required(),

                TextInput::make('ruang')
                    ->label('Ruang')
                    ->placeholder('Contoh: R201')
                    ->maxLength(10)
                    ->required(),
            ]);
    }
}