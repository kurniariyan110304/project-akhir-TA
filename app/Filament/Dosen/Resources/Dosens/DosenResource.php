<?php

namespace App\Filament\Dosen\Resources\Dosens;

use App\Filament\Dosen\Resources\Dosens\Pages\ListDosens;
use App\Models\Dosen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $modelLabel = 'Dosen';
    protected static ?string $pluralModelLabel = 'Dosen';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'dosen';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'dosen';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nidn')
                    ->label('NIDN')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Dosen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gelar_depan')
                    ->label('Gelar Depan'),

                Tables\Columns\TextColumn::make('gelar_belakang')
                    ->label('Gelar Belakang'),

                Tables\Columns\TextColumn::make('jk')
                    ->label('JK'),

                Tables\Columns\TextColumn::make('prodi.nama')
                    ->label('Prodi')
                    ->searchable(),
            ])
            ->defaultSort('nama', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDosens::route('/'),
        ];
    }
}