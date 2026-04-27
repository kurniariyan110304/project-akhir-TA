<?php

namespace App\Filament\Admin\Resources\Mahasiswas;

use App\Filament\Admin\Resources\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Admin\Resources\Mahasiswas\Pages\EditMahasiswa;
use App\Filament\Admin\Resources\Mahasiswas\Pages\ListMahasiswas;
use App\Filament\Admin\Resources\Mahasiswas\Schemas\MahasiswaForm;
use App\Filament\Admin\Resources\Mahasiswas\Tables\MahasiswasTable;
use App\Models\Mahasiswa;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel   = 'Mahasiswa';
    protected static ?string $modelLabel        = 'Mahasiswa';
    protected static ?string $pluralModelLabel  = 'Mahasiswa';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return MahasiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // 🔥 HANYA panggil Table config
        return MahasiswasTable::configure($table);
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
            'index' => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'edit' => EditMahasiswa::route('/{record}/edit'),
        ];
    }
}