<?php

namespace App\Filament\Asdos\Resources\Tugas;

use App\Filament\Asdos\Resources\Tugas\Pages\ListTugas;
use App\Models\Tugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Tugas Project';

    protected static ?int $navigationSort = 4;

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

        return $query->whereIn('kelas_id', function ($subQuery) use ($asdos) {
            $subQuery->select('kelas_id')
                ->from('asdos_kelas')
                ->where('asdos_id', $asdos->id);
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Tipe')
                    ->badge(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mulai')
                    ->label('Mulai')
                    ->date(),

                Tables\Columns\TextColumn::make('akhir')
                    ->label('Akhir')
                    ->date(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(60),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTugas::route('/'),
        ];
    }
}