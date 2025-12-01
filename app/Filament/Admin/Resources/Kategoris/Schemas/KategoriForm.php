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

            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(255),
        ]);
    }
}