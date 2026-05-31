<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectMahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_project'),
                TextInput::make('deskripsi'),
                TextInput::make('link_url'),
                TextInput::make('link_video'),
                TextInput::make('mahasiswa_nim')
                    ->required(),
                Select::make('tugas_project_id')
                    ->relationship('tugasProject', 'id')
                    ->required(),
                TextInput::make('nilai_akhir')
                    ->numeric()
                    ->default(0),
                TextInput::make('nama_kelompok'),
            ]);
    }
}
