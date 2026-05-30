<?php

namespace App\Filament\Mahasiswa\Resources\Projects;

use App\Filament\Mahasiswa\Resources\Projects\Pages\ListProjects;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;
    protected static ?string $navigationLabel = 'Project Mahasiswa';
    protected static ?string $modelLabel = 'Project Mahasiswa';
    protected static ?string $pluralModelLabel = 'Project Mahasiswa';
    protected static ?string $recordTitleAttribute = 'nama_project';
    protected static ?int $navigationSort = 6;

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
                Tables\Columns\TextColumn::make('nama_project')
                    ->label('Nama Project')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.deskripsi')
                    ->label('Tugas Project')
                    ->limit(30)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nama_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable(),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    ->limit(25)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->limit(25)
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
            'index' => ListProjects::route('/'),
        ];
    }
}