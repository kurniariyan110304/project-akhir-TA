<?php

namespace App\Filament\Dosen\Resources\KelompokProjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelompokProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('peran'),
                TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('project_mahasiswa_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mahasiswa_nim')
                    ->searchable(),
                TextColumn::make('aktif')
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
