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
use Illuminate\Database\Eloquent\Builder;
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
        return auth()->check() && auth()->user()->role === 'dosen';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'dosen';
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

        $dosen = auth()->user()?->dosen;

        if (! $dosen) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('kelas', function (Builder $query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
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
                    ->limit(50),
            ])
            ->recordActions([
                Action::make('lihatMahasiswa')
                    ->label('Lihat Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalWidth('7xl')
                    ->modalHeading(fn (Matakuliah $record): string => 'Mahasiswa yang Mengambil ' . $record->nama)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (Matakuliah $record) {
                        $dosen = auth()->user()?->dosen;

                        if (! $dosen) {
                            $mahasiswas = collect();
                        } else {
                            $mahasiswas = DB::table('kelas_mahasiswa')
                                ->join('mahasiswa', 'mahasiswa.nim', '=', 'kelas_mahasiswa.mahasiswa_nim')
                                ->join('kelas', 'kelas.id', '=', 'kelas_mahasiswa.kelas_id')
                                ->where('kelas.matakuliah_id', $record->id)
                                ->where('kelas.dosen_id', $dosen->id)
                                ->select(
                                    'mahasiswa.nim',
                                    'mahasiswa.nama',
                                    'mahasiswa.email',
                                    'mahasiswa.thn_masuk',
                                    'kelas.kode as kode_kelas',
                                    'kelas.semester',
                                    'kelas.hari',
                                    'kelas.jam',
                                    'kelas.ruang',
                                    'kelas_mahasiswa.nilai_akhir'
                                )
                                ->distinct()
                                ->orderBy('mahasiswa.nama')
                                ->get();
                        }

                        return view('filament.dosen.pages.list-mahasiswa-mata-kuliah', [
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