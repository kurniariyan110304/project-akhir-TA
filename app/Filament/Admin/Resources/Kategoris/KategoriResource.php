<?php

namespace App\Filament\Admin\Resources\Kategoris;

use App\Filament\Admin\Resources\Kategoris\Pages;
use App\Filament\Admin\Resources\Kategoris\Schemas\KategoriForm;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Actions dan BulkAction untuk Filament v4 ada di namespace Filament\Actions
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    // tipe HARUS BackedEnum|string|null (mengikuti parent Resource)
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel  = 'Kategori';
    protected static ?string $modelLabel       = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        // Delegasi ke KategoriForm
        return KategoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                // Akan muncul ikon Edit di tiap baris
                EditAction::make(),

                // Akan muncul ikon Delete di tiap baris
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Bulk delete (centang beberapa baris lalu hapus)
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit'   => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}