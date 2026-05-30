<?php

namespace App\Filament\Admin\Resources\Tugas;

use App\Filament\Admin\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Admin\Resources\Tugas\Pages\EditTugas;
use App\Filament\Admin\Resources\Tugas\Pages\ListTugas;
use App\Models\Tugas;
use BackedEnum;
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

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kategori')
                ->label('Kategori')
                ->options([
                    'INDIVIDU' => 'Individu',
                    'KELOMPOK' => 'Kelompok',
                ])
                ->required(),

            TextInput::make('semester')
                ->label('Semester')
                ->numeric()
                ->required(),

            Select::make('kelas_id')
                ->label('Kelas')
                ->relationship('kelas', 'kode')
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('mulai')
                ->label('Mulai')
                ->required(),

            DatePicker::make('akhir')
                ->label('Akhir')
                ->required(),

            Select::make('kategori_project_id')
                ->label('Kategori Project')
                ->relationship('kategoriProject', 'nama')
                ->searchable()
                ->preload()
                ->nullable(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('akhir')
                    ->label('Akhir')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->searchable()
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