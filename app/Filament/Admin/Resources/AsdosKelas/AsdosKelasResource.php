<?php

namespace App\Filament\Admin\Resources\AsdosKelas;

use App\Filament\Admin\Resources\AsdosKelas\Pages\CreateAsdosKelas;
use App\Filament\Admin\Resources\AsdosKelas\Pages\EditAsdosKelas;
use App\Filament\Admin\Resources\AsdosKelas\Pages\ListAsdosKelas;
use App\Models\Asdos;
use App\Models\AsdosKelas;
use App\Models\Kelas;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class AsdosKelasResource extends Resource
{
    protected static ?string $model = AsdosKelas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Asdos Kelas';

    protected static ?string $modelLabel = 'Asdos Kelas';

    protected static ?string $pluralModelLabel = 'Asdos Kelas';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('asdos_id')
                ->label('Asdos')
                ->options(function () {
                    return Asdos::query()
                        ->with(['mahasiswa', 'user'])
                        ->where('aktif', 1)
                        ->get()
                        ->mapWithKeys(function ($asdos) {
                            $nama = $asdos->mahasiswa?->nama ?? '-';
                            $nim = $asdos->mahasiswa_nim;
                            $email = $asdos->user?->email ?? '-';

                            return [
                                $asdos->id => "{$nama} - {$nim} - {$email}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('kelas_id')
                ->label('Kelas')
                ->options(function () {
                    return Kelas::query()
                        ->with(['matakuliah', 'dosen'])
                        ->get()
                        ->mapWithKeys(function ($kelas) {
                            $kode = $kelas->kode;
                            $matkul = $kelas->matakuliah?->nama ?? '-';
                            $dosen = $kelas->dosen?->nama ?? '-';

                            return [
                                $kelas->id => "{$kode} - {$matkul} - {$dosen}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asdos.id')
                    ->label('ID Asdos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asdos.mahasiswa.nim')
                    ->label('NIM Asdos')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asdos.mahasiswa.nama')
                    ->label('Nama Asdos')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('asdos.user.email')
                    ->label('Email Login')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.dosen.nama')
                    ->label('Dosen Pengampu')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAsdosKelas::route('/'),
            'create' => CreateAsdosKelas::route('/create'),
            'edit' => EditAsdosKelas::route('/{record}/edit'),
        ];
    }
}