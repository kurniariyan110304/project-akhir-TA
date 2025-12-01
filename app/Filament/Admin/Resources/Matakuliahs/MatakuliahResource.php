<?php

namespace App\Filament\Admin\Resources\Matakuliahs;

use App\Filament\Admin\Resources\Matakuliahs\Pages;
use App\Models\Matakuliah;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Actions dan BulkAction untuk Filament v4 ada di namespace Filament\Actions
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class MatakuliahResource extends Resource
{
    protected static ?string $model = Matakuliah::class;

    // tipe harus BackedEnum|string|null (Filament v4)
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel  = 'Mata Kuliah'; // sidebar
    protected static ?string $modelLabel       = 'Mata Kuliah'; // label tunggal
    protected static ?string $pluralModelLabel = 'Mata Kuliah'; // judul list
    protected static ?string $recordTitleAttribute = 'nama';    // judul tiap record (breadcrumb, dsb)

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\TextInput::make('kode')
                ->label('Kode Matakuliah')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('nama')
                ->label('Nama Matakuliah')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('Kode')->searchable()->sortable(),
                TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                TextColumn::make('deskripsi')->label('Deskripsi')->limit(50),
            ])
            // gunakan recordActions() di v4
            ->recordActions([
                // Prebuilt EditAction (Filament\Actions\EditAction)
                EditAction::make(),

                // Prebuilt DeleteAction (Filament\Actions\DeleteAction)
                DeleteAction::make(),
                
            ])
            ->bulkActions([
                // Prebuilt DeleteBulkAction (Filament\Actions\DeleteBulkAction)
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMatakuliahs::route('/'),
            'create' => Pages\CreateMatakuliah::route('/create'),
            'edit'   => Pages\EditMatakuliah::route('/{record}/edit'),
        ];
    }
}