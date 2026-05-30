<?php

namespace App\Filament\Admin\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class MahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nim')
                    ->required(),

                TextInput::make('nama'),

                Select::make('jk')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('tmp_lahir'),

                DatePicker::make('tgl_lahir'),

                TextInput::make('email')
                    ->label('Email address')
                    ->email(),

                TextInput::make('thn_masuk')
                    ->numeric(),

                Select::make('prodi_id')
                    ->label('Program Studi')
                    ->relationship('prodi', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                    Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}