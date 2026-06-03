<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas;

use App\Filament\Admin\Resources\KelasMahasiswas\Pages\CreateKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;


class KelasMahasiswaResource extends Resource
{
    protected static ?string $model = KelasMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelas Mahasiswa';

    protected static ?string $modelLabel = 'Kelas Mahasiswa';

    protected static ?string $pluralModelLabel = 'Kelas Mahasiswa';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kelas_id')
                ->label('Kelas')
                ->options(function () {
                    return Kelas::query()
                        ->with(['matakuliah', 'dosen'])
                        ->orderBy('kode')
                        ->get()
                        ->mapWithKeys(function ($kelas) {
                            $kode = $kelas->kode ?? '-';
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

            Select::make('mahasiswa_nims')
                ->label('Mahasiswa')
                ->options(function () {
                    return Mahasiswa::query()
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($mahasiswa) {
                            return [
                                $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                            ];
                        });
                })
                ->multiple()
                ->searchable()
                ->preload()
                ->required()
                ->helperText('Pilih satu atau banyak mahasiswa yang akan dimasukkan ke kelas.')
                ->visible(fn(string $operation): bool => $operation === 'create')
                ->dehydrated(fn(string $operation): bool => $operation === 'create'),

            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
                ->options(function () {
                    return Mahasiswa::query()
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($mahasiswa) {
                            return [
                                $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required()
                ->visible(fn(string $operation): bool => $operation === 'edit')
                ->dehydrated(fn(string $operation): bool => $operation === 'edit'),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('kelas_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelasMahasiswas::route('/'),
            'create' => CreateKelasMahasiswa::route('/create'),
            'edit' => EditKelasMahasiswa::route('/{record}/edit'),
        ];
    }
}
