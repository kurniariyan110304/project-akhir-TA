<?php

namespace App\Filament\Dosen\Resources\Matakuliahs;

use App\Filament\Dosen\Resources\Matakuliahs\Pages\ListMatakuliahs;
use App\Models\Matakuliah;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class MatakuliahResource extends Resource
{
    protected static ?string $model = Matakuliah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static ?string $navigationLabel = 'Mata Kuliah';
    protected static ?string $modelLabel = 'Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Mata Kuliah';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'dosen';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'dosen';
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
                    ->limit(50),
            ])
            ->recordActions([
                Action::make('lihatMahasiswa')
                    ->label('Lihat Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn (Matakuliah $record): string => 'Mahasiswa yang Mengambil ' . $record->nama)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (Matakuliah $record) {
                        $mahasiswas = DB::table('mahasiswa')
                            ->join('kelas_mahasiswa', 'mahasiswa.id', '=', 'kelas_mahasiswa.mahasiswa_id')
                            ->join('kelas', 'kelas_mahasiswa.kelas_id', '=', 'kelas.id')
                            ->where('kelas.matakuliah_id', $record->id)
                            ->select('mahasiswa.*')
                            ->distinct()
                            ->orderBy('mahasiswa.nama')
                            ->get();

                        return view('filament.dosen.resources.matakuliahs.mahasiswa-list', [
                            'mahasiswas' => $mahasiswas,
                        ]);
                    }),
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