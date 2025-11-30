<?php

namespace App\Filament\Admin\Resources\Prodis;

use App\Filament\Admin\Resources\Prodis\Pages\CreateProdi;
use App\Filament\Admin\Resources\Prodis\Pages\EditProdi;
use App\Filament\Admin\Resources\Prodis\Pages\ListProdis;
use App\Filament\Admin\Resources\Prodis\Schemas\ProdiForm;
use App\Filament\Admin\Resources\Prodis\Tables\ProdisTable;
use App\Models\Prodi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel   = 'Prodi';  // label di sidebar
    protected static ?string $modelLabel        = 'Prodi';  // label tunggal
    protected static ?string $pluralModelLabel  = 'Prodi';  // label jamak (judul "Prodi" di halaman list)

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProdiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProdisTable::configure($table);
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
            'index' => ListProdis::route('/'),
            'create' => CreateProdi::route('/create'),
            'edit' => EditProdi::route('/{record}/edit'),
        ];
    }
}