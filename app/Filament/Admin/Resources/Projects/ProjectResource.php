<?php

namespace App\Filament\Admin\Resources\Projects;

use App\Filament\Admin\Resources\Projects\Pages;
use App\Filament\Admin\Resources\Projects\RelationManagers\AnggotaKelompokRelationManager;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Project';
    protected static ?string $modelLabel = 'Project';
    protected static ?string $pluralModelLabel = 'Project';
    protected static ?string $recordTitleAttribute = 'nama_project';
    protected static ?int $navigationSort = 8;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_project')
                ->label('Nama Project')
                ->required()
                ->maxLength(100),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(4)
                ->columnSpanFull(),

            TextInput::make('link_url')
                ->label('Link URL')
                ->maxLength(100)
                ->nullable(),

            TextInput::make('link_video')
                ->label('Link Video')
                ->maxLength(100)
                ->nullable(),

            Select::make('mahasiswa_nim')
                ->label('Ketua / Owner Project')
                ->relationship('mahasiswa', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('tugas_project_id')
                ->label('Tugas Project')
                ->relationship('tugas', 'deskripsi')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('nama_kelompok')
                ->label('Nama Kelompok')
                ->maxLength(30)
                ->nullable(),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->disabled()
                ->dehydrated(false)
                ->placeholder('Otomatis dari anggota kelompok'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('nama_project')
                ->label('Nama Project'),

            TextEntry::make('mahasiswa.nama')
                ->label('Ketua / Owner Project')
                ->placeholder('-'),

            TextEntry::make('tugas.deskripsi')
                ->label('Tugas Project')
                ->placeholder('-'),

            TextEntry::make('nama_kelompok')
                ->label('Nama Kelompok')
                ->placeholder('-'),

            TextEntry::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->placeholder('0'),

            TextEntry::make('link_url')
                ->label('Link URL')
                ->placeholder('-'),

            TextEntry::make('link_video')
                ->label('Link Video')
                ->placeholder('-'),

            TextEntry::make('deskripsi')
                ->label('Deskripsi')
                ->placeholder('-')
                ->columnSpanFull(),
        ]);
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
                    ->label('Ketua')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.deskripsi')
                    ->label('Tugas')
                    ->limit(30)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nama_kelompok')
                    ->label('Kelompok')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable(),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->limit(25)
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AnggotaKelompokRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}