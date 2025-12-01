<?php

namespace App\Filament\Admin\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('jk'),
                TextInput::make('tmp_lahir'),
                DatePicker::make('tgl_lahir'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('thn_masuk')
                    ->numeric(),
                TextInput::make('prodi_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
            ]);
    }
}
