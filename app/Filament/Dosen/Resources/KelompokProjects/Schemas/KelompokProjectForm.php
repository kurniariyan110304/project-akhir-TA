<?php

namespace App\Filament\Dosen\Resources\KelompokProjects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelompokProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('peran')
                    ->options(['KETUA' => 'K e t u a', 'ANGGOTA' => 'A n g g o t a']),
                TextInput::make('nilai')
                    ->numeric()
                    ->default(0),
                Select::make('project_mahasiswa_id')
                    ->relationship('projectMahasiswa', 'id')
                    ->required(),
                TextInput::make('mahasiswa_nim')
                    ->required(),
                TextInput::make('aktif')
                    ->numeric(),
            ]);
    }
}
