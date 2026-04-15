<?php

namespace App\Filament\Dosen\Resources\Tugas;

use App\Filament\Dosen\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Dosen\Resources\Tugas\Pages\EditTugas;
use App\Filament\Dosen\Resources\Tugas\Pages\ListTugas;
use App\Filament\Dosen\Resources\Tugas\Pages\ViewTugas;
use App\Filament\Dosen\Resources\Tugas\Schemas\TugasForm;
use App\Filament\Dosen\Resources\Tugas\Schemas\TugasInfolist;
use App\Filament\Dosen\Resources\Tugas\Tables\TugasTable;
use App\Models\Tugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tugas';

    public static function form(Schema $schema): Schema
    {
        return TugasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TugasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TugasTable::configure($table);
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
            'index' => ListTugas::route('/'),
            'create' => CreateTugas::route('/create'),
            'view' => ViewTugas::route('/{record}'),
            'edit' => EditTugas::route('/{record}/edit'),
        ];
    }
}
