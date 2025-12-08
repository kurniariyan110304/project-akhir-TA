<?php

namespace App\Filament\Admin\Resources\Tugas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                //ambil dari relasi `kelas` → kolom `kode` (atau ganti ke `nama` kalau ada)
                TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                TextColumn::make('akhir')
                    ->label('Akhir')
                    ->date()
                    ->sortable(),

                //ambil dari relasi `kategoriProject` → kolom `nama`
                TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}