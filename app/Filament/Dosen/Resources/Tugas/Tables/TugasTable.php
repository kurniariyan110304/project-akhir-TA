<?php

namespace App\Filament\Dosen\Resources\Tugas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori'),
                TextColumn::make('semester')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kelas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('akhir')
                    ->date()
                    ->sortable(),
                TextColumn::make('kategori_project_id')
                    ->numeric()
                    ->sortable(),
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
