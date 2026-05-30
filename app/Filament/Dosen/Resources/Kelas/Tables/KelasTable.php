<?php

namespace App\Filament\Dosen\Resources\Kelas\Tables;

use App\Models\Kelas;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class KelasTable
{
    public static function configure(Table $table): Table
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
            ->recordActions([
                Action::make('lihatMahasiswa')
                    ->label('Lihat Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalWidth('7xl')
                    ->modalHeading(fn (Kelas $record): string => 'Mahasiswa Kelas ' . $record->kode)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (Kelas $record) {
                        $dosen = auth()->user()?->dosen;

                        if (! $dosen || $record->dosen_id !== $dosen->id) {
                            $mahasiswas = collect();
                        } else {
                            $mahasiswas = DB::table('kelas_mahasiswa')
                                ->join('mahasiswa', 'mahasiswa.nim', '=', 'kelas_mahasiswa.mahasiswa_nim')
                                ->join('kelas', 'kelas.id', '=', 'kelas_mahasiswa.kelas_id')
                                ->where('kelas_mahasiswa.kelas_id', $record->id)
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
                                ->orderBy('mahasiswa.nama')
                                ->get();
                        }

                        return view('filament.dosen.pages.list-mahasiswa-mata-kuliah', [
                            'mahasiswas' => $mahasiswas,
                        ]);
                    }),
            ])
            ->defaultSort('kode', 'asc');
    }
}