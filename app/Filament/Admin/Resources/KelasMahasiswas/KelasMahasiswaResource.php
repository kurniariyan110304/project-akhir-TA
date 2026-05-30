<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas;

use App\Filament\Admin\Resources\KelasMahasiswas\Pages\CreateKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\KelasMahasiswa;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                ->relationship('kelas', 'kode')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
                ->relationship('mahasiswa', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
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
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.dosen.nama')
                    ->label('Dosen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable(),
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
            'index' => ListKelasMahasiswas::route('/'),
            'create' => CreateKelasMahasiswa::route('/create'),
            'edit' => EditKelasMahasiswa::route('/{record}/edit'),
        ];
    }
}