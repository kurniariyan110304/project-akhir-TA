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

// 👇 Actions di Filament 4 ada di namespace ini
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    // icon sidebar
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    // judul record, sesuaikan dengan kolom yang ada di tabel `kelas`
    protected static ?string $recordTitleAttribute = 'kode';

    public static function form(Schema $schema): Schema
    {
        // kalau kamu memang punya App\Filament\Admin\Resources\Kelas\Schemas\KelasForm
        return KelasForm::configure($schema);
    }

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

                // relasi ke matakuliah, tampilkan kolom `nama`
                TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->sortable()
                    ->searchable(),

                // relasi ke dosen, tampilkan kolom `nama`
                TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->sortable()
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index'  => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'edit'   => EditKelas::route('/{record}/edit'),
        ];
    }
}