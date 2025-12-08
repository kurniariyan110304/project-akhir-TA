<?php

namespace App\Filament\Admin\Resources\Kategoris;

use App\Filament\Admin\Resources\Kategoris\Pages;
use App\Filament\Admin\Resources\Kategoris\Schemas\KategoriForm;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

//Actions untuk TABLE (Filament 4)
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel  = 'Kategori';
    protected static ?string $modelLabel       = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
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
                // ✅ hanya tombol Edit di tiap baris
                EditAction::make(),
            ])
            ->toolbarActions([
                // ✅ bulk delete muncul di toolbar (dropdown Bulk actions)
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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