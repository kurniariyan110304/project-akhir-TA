<?php

namespace App\Filament\Admin\Resources\Tugas;

use App\Filament\Admin\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Admin\Resources\Tugas\Pages\EditTugas;
use App\Filament\Admin\Resources\Tugas\Pages\ListTugas;
use App\Filament\Admin\Resources\Tugas\Schemas\TugasForm;
use App\Filament\Admin\Resources\Tugas\Tables\TugasTable;
use App\Models\Tugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $recordTitleAttribute = 'deskripsi';

    public static function form(Schema $schema): Schema
    {
        return TugasForm::configure($schema);
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
            'edit' => EditTugas::route('/{record}/edit'),
        ];
    }
}