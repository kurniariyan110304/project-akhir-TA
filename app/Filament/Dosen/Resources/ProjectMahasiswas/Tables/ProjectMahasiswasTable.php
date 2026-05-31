<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectMahasiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_project')
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->searchable(),
                TextColumn::make('link_url')
                    ->searchable(),
                TextColumn::make('link_video')
                    ->searchable(),
                TextColumn::make('mahasiswa_nim')
                    ->searchable(),
                TextColumn::make('tugasProject.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nilai_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nama_kelompok')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
