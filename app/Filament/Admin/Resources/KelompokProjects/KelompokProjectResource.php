<?php

namespace App\Filament\Admin\Resources\KelompokProjects;

use App\Filament\Admin\Resources\KelompokProjects\Pages\CreateKelompokProject;
use App\Filament\Admin\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Admin\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
    protected static ?int $navigationSort = 9;

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
            Select::make('project_mahasiswa_id')
                ->label('Project')
                ->relationship('project', 'nama_project')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
                ->relationship('mahasiswa', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('peran')
                ->options([
                    'KETUA' => 'KETUA',
                    'ANGGOTA' => 'ANGGOTA',
                ])
                ->required(),

            TextInput::make('nilai')
                ->numeric()
                ->default(0),

            Select::make('aktif')
                ->options([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif',
                ])
                ->default(1)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.nama_project')
                    ->label('Project')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Aktif')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Aktif' : 'Tidak Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
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
            'create' => CreateKelompokProject::route('/create'),
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}