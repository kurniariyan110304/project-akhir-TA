<?php

namespace App\Filament\Mahasiswa\Resources\KelompokProjects;

use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\CreateKelompokProject;
use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use App\Models\Mahasiswa;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelompokProjectResource extends Resource
{
    protected static ?string $model = KelompokProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelompok Project';

    protected static ?string $modelLabel = 'Kelompok Project';

    protected static ?string $pluralModelLabel = 'Kelompok Project';

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
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    public static function canEdit($record): bool
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return false;
        }

        return $record->projectMahasiswa?->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function canDelete($record): bool
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return false;
        }

        return $record->projectMahasiswa?->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $query) use ($mahasiswa) {
            $query
                ->where('mahasiswa_nim', $mahasiswa->nim)
                ->orWhereHas('projectMahasiswa', function (Builder $query) use ($mahasiswa) {
                    $query->where('mahasiswa_nim', $mahasiswa->nim);
                });
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('project_mahasiswa_id')
                ->label('Project')
                ->options(function () {
                    $mahasiswa = auth()->user()?->mahasiswa;

                    if (! $mahasiswa) {
                        return [];
                    }

                    return ProjectMahasiswa::query()
                        ->where('mahasiswa_nim', $mahasiswa->nim)
                        ->pluck('nama_project', 'id');
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('mahasiswa_nim')
                ->label('Anggota Mahasiswa')
                ->options(function () {
                    $mahasiswa = auth()->user()?->mahasiswa;

                    if (! $mahasiswa) {
                        return [];
                    }

                    return Mahasiswa::query()
                        ->whereHas('kelas', function (Builder $query) use ($mahasiswa) {
                            $query->whereHas('mahasiswa', function (Builder $query) use ($mahasiswa) {
                                $query->where('mahasiswa.nim', $mahasiswa->nim);
                            });
                        })
                        ->orderBy('nama')
                        ->pluck('nama', 'nim');
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('peran')
                ->label('Peran')
                ->options([
                    'KETUA' => 'Ketua',
                    'ANGGOTA' => 'Anggota',
                ])
                ->required(),

            Select::make('aktif')
                ->label('Status')
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
                Tables\Columns\TextColumn::make('projectMahasiswa.nama_project')
                    ->label('Project')
                    ->searchable(),

                Tables\Columns\TextColumn::make('projectMahasiswa.tugasProject.kelas.matakuliah.nama')
                    ->label('Mata Kuliah'),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                    ->badge(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->placeholder('-'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
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