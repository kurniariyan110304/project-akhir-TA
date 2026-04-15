<?php

namespace App\Filament\Dosen\Resources\Kelas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('semester')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('matakuliah_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dosen_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ruang')
                    ->searchable(),
                TextColumn::make('jam')
                    ->searchable(),
                TextColumn::make('hari'),
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
