<?php

namespace App\Filament\Mahasiswa\Resources\Tugas;

use App\Filament\Mahasiswa\Resources\Tugas\Pages\ListTugas;
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

    protected static ?string $modelLabel = 'Tugas Project';

    protected static ?string $pluralModelLabel = 'Tugas Project';

    protected static ?int $navigationSort = 4;

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

        return $query->whereIn('kelas_id', function ($subQuery) use ($mahasiswa) {
            $subQuery->select('kelas_id')
                ->from('kelas_mahasiswa')
                ->where('mahasiswa_nim', $mahasiswa->nim);
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kelas.dosen.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('akhir')
                    ->label('Akhir')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(80)
                    ->searchable(),
            ])
            ->defaultSort('akhir', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTugas::route('/'),
        ];
    }
}