<?php

namespace App\Filament\Mahasiswa\Resources\Kelas;

use App\Filament\Mahasiswa\Resources\Kelas\Pages\ListKelas;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas';

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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('id', function ($subQuery) use ($mahasiswa) {
            $subQuery->select('kelas_id')
                ->from('kelas_mahasiswa')
                ->where('mahasiswa_nim', $mahasiswa->nim);
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari'),

                Tables\Columns\TextColumn::make('jam')
                    ->label('Jam'),

                Tables\Columns\TextColumn::make('ruang')
                    ->label('Ruang'),
            ])
            ->defaultSort('kode', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelas::route('/'),
        ];
    }
}