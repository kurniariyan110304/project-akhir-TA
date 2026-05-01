<?php

namespace App\Filament\Admin\Resources\Tugas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori')
                    ->options([
                        'INDIVIDU' => 'Individu',
                        'KELOMPOK' => 'Kelompok',
                    ])
                    ->required(),

                TextInput::make('semester')
                    ->numeric()
                    ->required(),

                Textarea::make('deskripsi')
                    ->columnSpanFull(),

                Select::make('kelas_id')
                    ->relationship('kelas', 'kode')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('mulai')
                    ->required(),

                DatePicker::make('akhir')
                    ->required(),

                Select::make('kategori_project_id')
                    ->label('Kategori Project')
                    ->relationship('kategoriProject', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}