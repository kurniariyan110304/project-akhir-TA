<?php

namespace App\Filament\Asdos\Resources\Matakuliahs;

use App\Filament\Asdos\Resources\Matakuliahs\Pages\ListMatakuliahs;
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

    protected static ?int $navigationSort = 3;

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
            $subQuery->select('kelas.matakuliah_id')
                ->from('kelas')
                ->join('asdos_kelas', 'asdos_kelas.kelas_id', '=', 'kelas.id')
                ->where('asdos_kelas.asdos_id', $asdos->id);
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatakuliahs::route('/'),
        ];
    }
}