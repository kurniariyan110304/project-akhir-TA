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

                TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->sortable()
                    ->searchable(),
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