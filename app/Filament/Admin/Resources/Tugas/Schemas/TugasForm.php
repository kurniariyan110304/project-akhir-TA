<?php

namespace App\Filament\Admin\Resources\Tugas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'INDIVIDU' => 'Individu',
                        'KELOMPOK' => 'Kelompok',
                    ])
                    ->required(),

                TextInput::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->required(),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'kode')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('mulai')
                    ->label('Mulai')
                    ->required(),

                DatePicker::make('akhir')
                    ->label('Akhir')
                    ->required(),

                Select::make('kategori_project_id')
                    ->label('Kategori Project')
                    ->relationship('kategoriProject', 'nama')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}