<?php

namespace App\Filament\Admin\Resources\Tugas;

use App\Filament\Admin\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Admin\Resources\Tugas\Pages\EditTugas;
use App\Filament\Admin\Resources\Tugas\Pages\ListTugas;
use App\Models\Tugas;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $navigationLabel = 'Tugas';
    protected static ?string $modelLabel = 'Tugas';
    protected static ?string $pluralModelLabel = 'Tugas';
    protected static ?string $recordTitleAttribute = 'deskripsi';
    protected static ?int $navigationSort = 7;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kategori')
                ->options([
                    'INDIVIDU' => 'Individu',
                    'KELOMPOK' => 'Kelompok',
                ])
                ->required(),

            TextInput::make('semester')
                ->numeric()
                ->required(),

            TextInput::make('kelas_id')
                ->numeric()
                ->required(),

            DatePicker::make('mulai')
                ->required(),

            DatePicker::make('akhir')
                ->required(),

            TextInput::make('kategori_project_id')
                ->numeric()
                ->nullable(),

            Textarea::make('deskripsi')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas_id')
                    ->label('Kelas ID'),

                Tables\Columns\TextColumn::make('mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('akhir')
                    ->label('Akhir')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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