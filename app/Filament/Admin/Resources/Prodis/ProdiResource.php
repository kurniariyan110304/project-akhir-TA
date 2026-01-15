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
use Filament\Tables\Table;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel   = 'Prodi';
    protected static ?string $modelLabel        = 'Prodi';
    protected static ?string $pluralModelLabel  = 'Prodi';
    protected static ?string $recordTitleAttribute = 'nama';

    /**
     * ===============================
     * SIDEBAR (HIDE FOR DOSEN)
     * ===============================
     * - Prodi tampil untuk admin (dan role lain kalau Anda mau),
     * - tapi tidak tampil untuk dosen.
     */
    public static function shouldRegisterNavigation(): bool
    {
        $role = auth()->user()?->role;

        // Sembunyikan kalau dosen atau belum login
        if ($role === 'dosen' || $role === null) {
            return false;
        }

        return true;
    }

    /**
     * ===============================
     * ACCESS CONTROL (BLOCK DOSEN)
     * ===============================
     * Supaya dosen tidak bisa akses lewat URL langsung.
     */
    public static function canViewAny(): bool
    {
        $role = auth()->user()?->role;

        return $role !== 'dosen' && $role !== null;
    }

    // (Opsional) Kalau mau lebih ketat:
    public static function canCreate(): bool
    {
        return auth()->user()?->role !== 'dosen';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role !== 'dosen';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role !== 'dosen';
    }

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