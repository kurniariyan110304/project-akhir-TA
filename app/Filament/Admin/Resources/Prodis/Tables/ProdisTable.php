<?php

namespace App\Filament\Admin\Resources\Prodis\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Actions untuk Filament v4
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class ProdisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Prodi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kaprodi')
                    ->label('Ka. Prodi')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),   // ikon edit per baris
                DeleteAction::make(), // ikon delete per baris
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // hapus banyak sekaligus
                ]),
            ]);
    }
}