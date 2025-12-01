<?php

namespace App\Filament\Admin\Resources\Dosens;

use App\Filament\Admin\Resources\Dosens\Pages\CreateDosen;
use App\Filament\Admin\Resources\Dosens\Pages\EditDosen;
use App\Filament\Admin\Resources\Dosens\Pages\ListDosens;
use App\Filament\Admin\Resources\Dosens\Schemas\DosenForm;
use App\Filament\Admin\Resources\Dosens\Tables\DosensTable;
use App\Models\Dosen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';
    

    protected static ?string $navigationLabel   = 'Dosen';  // label di sidebar
    protected static ?string $modelLabel        = 'Dosen';  // label tunggal
    protected static ?string $pluralModelLabel  = 'Dosen';  // label jamak (judul "Prodi" di halaman list)

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return DosenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DosensTable::configure($table);
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
            'index' => ListDosens::route('/'),
            'create' => CreateDosen::route('/create'),
            'edit' => EditDosen::route('/{record}/edit'),
        ];
    }
}