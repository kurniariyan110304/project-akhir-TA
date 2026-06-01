<?php

namespace App\Filament\Admin\Resources\Asdos;

use App\Filament\Admin\Resources\Asdos\Pages\CreateAsdos;
use App\Filament\Admin\Resources\Asdos\Pages\EditAsdos;
use App\Filament\Admin\Resources\Asdos\Pages\ListAsdos;
use App\Models\Asdos;
use App\Models\Mahasiswa;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class AsdosResource extends Resource
{
    protected static ?string $model = Asdos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Asdos';

    protected static ?string $modelLabel = 'Asdos';

    protected static ?string $pluralModelLabel = 'Asdos';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('Akun User Asdos')
                ->options(function ($record) {
                    return User::query()
                        ->whereIn('role', ['mahasiswa', 'asdos'])
                        ->where(function ($query) use ($record) {
                            $query->whereDoesntHave('asdos');

                            if ($record?->user_id) {
                                $query->orWhere('id', $record->user_id);
                            }
                        })
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
                ->options(function ($record) {
                    return Mahasiswa::query()
                        ->where(function ($query) use ($record) {
                            $query->whereDoesntHave('asdos');

                            if ($record?->mahasiswa_nim) {
                                $query->orWhere('nim', $record->mahasiswa_nim);
                            }
                        })
                        ->orderBy('nama')
                        ->pluck('nama', 'nim');
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('aktif')
                ->label('Status')
                ->options([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif',
                ])
                ->default(1)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Asdos')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email Login')
                    ->searchable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                    ->badge(),
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
            'index' => ListAsdos::route('/'),
            'create' => CreateAsdos::route('/create'),
            'edit' => EditAsdos::route('/{record}/edit'),
        ];
    }
}