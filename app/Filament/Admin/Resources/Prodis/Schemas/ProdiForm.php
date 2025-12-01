<?php

namespace App\Filament\Admin\Resources\Prodis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProdiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('kode')
                ->label('Kode Prodi')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->label('Nama Prodi')
                ->required()
                ->maxLength(255),

            TextInput::make('kaprodi')
                ->label('Ka. Prodi')
                ->maxLength(255),
            ]);
    }
}