<?php

namespace App\Filament\Admin\Resources\Dosens\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DosenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nidn'),
                TextInput::make('nama'),
                TextInput::make('gelar_depan'),
                TextInput::make('gelar_belakang'),
                TextInput::make('jk'),
                TextInput::make('tmp_lahir'),
                DatePicker::make('tgl_lahir'),
                TextInput::make('prodi_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
            ]);
    }
}