<?php

namespace App\Filament\Mahasiswa\Resources\Kelas;

use App\Filament\Mahasiswa\Resources\Kelas\Pages\ListKelas;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $recordTitleAttribute = 'kode';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'mahasiswa';
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jam')
                    ->label('Jam')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('ruang')
                    ->label('Ruang')
                    ->placeholder('-'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelas::route('/'),
        ];
    }
}