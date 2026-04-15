<?php

namespace App\Filament\Dosen\Resources\Tugas\Schemas;

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
                    ->options(['INDIVIDU' => 'I n d i v i d u', 'KELOMPOK' => 'K e l o m p o k']),
                TextInput::make('semester')
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('mulai'),
                DatePicker::make('akhir'),
                TextInput::make('kategori_project_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
