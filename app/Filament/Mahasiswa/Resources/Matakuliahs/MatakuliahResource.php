<?php

namespace App\Filament\Mahasiswa\Resources\Matakuliahs;

use App\Filament\Mahasiswa\Resources\Matakuliahs\Pages\ListMatakuliahs;
use App\Models\Matakuliah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatakuliahResource extends Resource
{
    protected static ?string $model = Matakuliah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Mata Kuliah';

    protected static ?string $modelLabel = 'Mata Kuliah';

    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    protected static ?int $navigationSort = 3;

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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('id', function ($subQuery) use ($mahasiswa) {
            $subQuery->select('kelas.matakuliah_id')
                ->from('kelas')
                ->join('kelas_mahasiswa', 'kelas_mahasiswa.kelas_id', '=', 'kelas.id')
                ->where('kelas_mahasiswa.mahasiswa_nim', $mahasiswa->nim);
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(60),
            ])
            ->defaultSort('nama', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatakuliahs::route('/'),
        ];
    }
}