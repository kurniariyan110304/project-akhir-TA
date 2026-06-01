<?php

namespace App\Filament\Asdos\Resources\Kelas;

use App\Filament\Asdos\Resources\Kelas\Pages\ListKelas;
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

    protected static ?int $navigationSort = 2;

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
        return false;
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

        return $query->whereIn('id', function ($subQuery) use ($asdos) {
            $subQuery->select('kelas_id')
                ->from('asdos_kelas')
                ->where('asdos_id', $asdos->id);
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
                    ->searchable(),

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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelas::route('/'),
        ];
    }
}