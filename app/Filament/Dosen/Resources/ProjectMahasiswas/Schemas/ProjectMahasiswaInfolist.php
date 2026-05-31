<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectMahasiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_project'),
                TextEntry::make('deskripsi'),
                TextEntry::make('link_url'),
                TextEntry::make('link_video'),
                TextEntry::make('mahasiswa_nim'),
                TextEntry::make('tugasProject.id')
                    ->numeric(),
                TextEntry::make('nilai_akhir')
                    ->numeric(),
                TextEntry::make('nama_kelompok'),
            ]);
    }
}
