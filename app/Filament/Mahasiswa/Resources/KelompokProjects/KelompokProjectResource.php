<?php

namespace App\Filament\Mahasiswa\Resources\KelompokProjects;

use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class KelompokProjectResource extends Resource
{
    protected static ?string $model = KelompokProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Kelompok Project';
    protected static ?string $modelLabel = 'Kelompok Project';
    protected static ?string $pluralModelLabel = 'Kelompok Project';
    protected static ?string $recordTitleAttribute = 'peran';
    protected static ?int $navigationSort = 7;

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
                Tables\Columns\TextColumn::make('project.nama_project')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('project.nama_kelompok')
                    ->label('Nama Kelompok')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'Aktif' : 'Tidak Aktif')
                    ->badge(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelompokProjects::route('/'),
        ];
    }
}