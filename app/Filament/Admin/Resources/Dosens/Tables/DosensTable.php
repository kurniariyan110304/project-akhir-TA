<?php

namespace App\Filament\Admin\Resources\Dosens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DosensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nidn')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('gelar_depan')
                    ->searchable(),
                TextColumn::make('gelar_belakang')
                    ->searchable(),
                TextColumn::make('jk')
                    ->searchable(),
                TextColumn::make('tmp_lahir')
                    ->searchable(),
                TextColumn::make('tgl_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('prodi.nama')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('User Login')
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