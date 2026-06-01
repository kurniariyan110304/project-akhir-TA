<?php

namespace App\Filament\Asdos\Resources\KelompokProjects;

use App\Filament\Asdos\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Asdos\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    protected static ?int $navigationSort = 7;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'asdos';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'asdos';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        $asdos = auth()->user()?->asdos;

        if (! $asdos || ! $asdos->aktif) {
            return false;
        }

        return \DB::table('asdos_kelas')
            ->where('asdos_id', $asdos->id)
            ->where('kelas_id', $record->project?->tugas?->kelas_id)
            ->exists();
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $asdos = auth()->user()?->asdos;

        if (! $asdos || ! $asdos->aktif) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('project.tugas', function (Builder $query) use ($asdos) {
            $query->whereIn('kelas_id', function ($subQuery) use ($asdos) {
                $subQuery->select('kelas_id')
                    ->from('asdos_kelas')
                    ->where('asdos_id', $asdos->id);
            });
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nilai')
                ->label('Nilai Anggota')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->required(),

            Select::make('aktif')
                ->label('Status')
                ->options([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.nama_project')
                    ->label('Project'),

                Tables\Columns\TextColumn::make('project.nama_kelompok')
                    ->label('Kelompok')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM'),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
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
                EditAction::make()
                    ->label('Input Nilai'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelompokProjects::route('/'),
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}