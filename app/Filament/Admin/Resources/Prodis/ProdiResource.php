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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel   = 'Prodi';
    protected static ?string $modelLabel        = 'Prodi';
    protected static ?string $pluralModelLabel  = 'Prodi';
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
            'index'  => ListProdis::route('/'),
            'create' => CreateProdi::route('/create'),
            'edit'   => EditProdi::route('/{record}/edit'),
        ];
    }
}