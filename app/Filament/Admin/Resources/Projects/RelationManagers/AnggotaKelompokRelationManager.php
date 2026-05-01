<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Models\Mahasiswa;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AnggotaKelompokRelationManager extends RelationManager
{
    protected static string $relationship = 'anggotaKelompok';

    protected static ?string $title = 'Anggota Kelompok';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
                ->options(function (?object $record = null) {
                    $project = $this->getOwnerRecord();

                    $usedNim = $project->anggotaKelompok()
                        ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                        ->pluck('mahasiswa_nim')
                        ->toArray();

                    return Mahasiswa::query()
                        ->whereNotIn('nim', $usedNim)
                        ->orderBy('nama')
                        ->pluck('nama', 'nim')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('peran')
                ->label('Peran')
                ->options([
                    'KETUA' => 'KETUA',
                    'ANGGOTA' => 'ANGGOTA',
                ])
                ->required(),

            Select::make('aktif')
                ->label('Status')
                ->options([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif',
                ])
                ->default(1)
                ->required(),

            Select::make('nilai')
                ->label('Nilai')
                ->options([
                    0 => '0',
                    10 => '10',
                    20 => '20',
                    30 => '30',
                    40 => '40',
                    50 => '50',
                    60 => '60',
                    70 => '70',
                    80 => '80',
                    90 => '90',
                    100 => '100',
                ])
                ->default(0)
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'Aktif' : 'Tidak Aktif')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['project_mahasiswa_id'] = $this->getOwnerRecord()->id;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}