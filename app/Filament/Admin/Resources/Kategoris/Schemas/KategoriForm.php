<?php

namespace App\Filament\Admin\Resources\Kategoris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('kode')
                ->label('Kode')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->columnSpanFull(),
        ]);
    }
}