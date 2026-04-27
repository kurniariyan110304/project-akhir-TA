<?php

namespace App\Filament\Dosen\Resources\Kategoris;

use App\Filament\Dosen\Resources\Kategoris\Pages\CreateKategori;
use App\Filament\Dosen\Resources\Kategoris\Pages\EditKategori;
use App\Filament\Dosen\Resources\Kategoris\Pages\ListKategoris;
use App\Filament\Dosen\Resources\Kategoris\Pages\ViewKategori;
use App\Filament\Dosen\Resources\Kategoris\Schemas\KategoriForm;
use App\Filament\Dosen\Resources\Kategoris\Schemas\KategoriInfolist;
use App\Filament\Dosen\Resources\Kategoris\Tables\KategorisTable;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KategoriForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KategoriInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategorisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoris::route('/'),
            'create' => CreateKategori::route('/create'),
            'view' => ViewKategori::route('/{record}'),
            'edit' => EditKategori::route('/{record}/edit'),
        ];
    }
}