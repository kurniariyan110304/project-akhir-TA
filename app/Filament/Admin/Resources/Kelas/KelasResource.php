<?php

namespace App\Filament\Admin\Resources\Kelas;

use App\Filament\Admin\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Admin\Resources\Kelas\Pages\EditKelas;
use App\Filament\Admin\Resources\Kelas\Pages\ListKelas;
use App\Filament\Admin\Resources\Kelas\Schemas\KelasForm;
use App\Filament\Admin\Resources\Kelas\Tables\KelasTable;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $recordTitleAttribute = 'admin';

    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasTable::configure($table);
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
            'index' => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'edit' => EditKelas::route('/{record}/edit'),
        ];
    }
}