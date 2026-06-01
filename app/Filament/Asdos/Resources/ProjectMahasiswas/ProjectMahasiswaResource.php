<?php

namespace App\Filament\Asdos\Resources\ProjectMahasiswas;

use App\Filament\Asdos\Resources\ProjectMahasiswas\Pages\EditProjectMahasiswa;
use App\Filament\Asdos\Resources\ProjectMahasiswas\Pages\ListProjectMahasiswas;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectMahasiswaResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Project Mahasiswa';

    protected static ?int $navigationSort = 6;

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
            ->where('kelas_id', $record->tugas?->kelas_id)
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

        return $query->whereHas('tugas', function (Builder $query) use ($asdos) {
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
            TextInput::make('nilai_akhir')
                ->label('Nilai Project')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM'),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_project')
                    ->label('Project')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tugas.kelas.kode')
                    ->label('Kelas'),

                Tables\Columns\TextColumn::make('tugas.kelas.matakuliah.nama')
                    ->label('Mata Kuliah'),

                Tables\Columns\TextColumn::make('nilai_akhir')
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
            'index' => ListProjectMahasiswas::route('/'),
            'edit' => EditProjectMahasiswa::route('/{record}/edit'),
        ];
    }
}