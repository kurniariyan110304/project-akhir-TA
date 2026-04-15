<?php

namespace App\Filament\Admin\Resources\Kelas;

use App\Filament\Admin\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Admin\Resources\Kelas\Pages\EditKelas;
use App\Filament\Admin\Resources\Kelas\Pages\ListKelas;
use App\Filament\Admin\Resources\Kelas\Schemas\KelasForm;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Actions (Filament 4)
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    // Sidebar icon
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    // Label & title
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $recordTitleAttribute = 'kode';

    /**
     * ===============================
     * SIDEBAR (ADMIN ONLY)
     * ===============================
     */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * ===============================
     * ACCESS CONTROL (ADMIN ONLY)
     * ===============================
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * ===============================
     * FORM
     * ===============================
     */
    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    /**
     * ===============================
     * TABLE
     * ===============================
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->sortable()
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()?->role === 'admin'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->role === 'admin'),
                ]),
            ]);
    }

    /**
     * ===============================
     * PAGES
     * ===============================
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'edit'   => EditKelas::route('/{record}/edit'),
        ];
    }
}