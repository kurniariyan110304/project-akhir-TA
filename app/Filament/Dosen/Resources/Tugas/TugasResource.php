<?php

namespace App\Filament\Dosen\Resources\Tugas;

use App\Filament\Dosen\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Dosen\Resources\Tugas\Pages\EditTugas;
use App\Filament\Dosen\Resources\Tugas\Pages\ListTugas;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Tugas;
use BackedEnum;
use Filament\Actions\DeleteAction;
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
use Illuminate\Database\Eloquent\Builder;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Tugas Project';

    protected static ?string $modelLabel = 'Tugas Project';

    protected static ?string $pluralModelLabel = 'Tugas Project';

    protected static ?string $recordTitleAttribute = 'deskripsi';

    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'dosen';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'dosen';
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'dosen';
    }

    public static function canEdit($record): bool
    {
        $dosen = auth()->user()?->dosen;

        return $dosen && $record->kelas?->dosen_id === $dosen->id;
    }

    public static function canDelete($record): bool
    {
        $dosen = auth()->user()?->dosen;

        return $dosen && $record->kelas?->dosen_id === $dosen->id;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $dosen = auth()->user()?->dosen;

        if (! $dosen) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('kelas', function (Builder $query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kategori')
                ->label('Tipe Tugas')
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
                ->options(function () {
                    $dosen = auth()->user()?->dosen;

                    if (! $dosen) {
                        return [];
                    }

                    return Kelas::query()
                        ->where('dosen_id', $dosen->id)
                        ->with('matakuliah')
                        ->get()
                        ->mapWithKeys(function ($kelas) {
                            $label = $kelas->kode . ' - ' . ($kelas->matakuliah?->nama ?? 'Mata Kuliah');

                            return [$kelas->id => $label];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('kategori_project_id')
                ->label('Kategori Project')
                ->options(fn () => Kategori::query()->pluck('nama', 'id'))
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('mulai')
                ->label('Mulai')
                ->required(),

            DatePicker::make('akhir')
                ->label('Akhir')
                ->required(),

            Textarea::make('deskripsi')
                ->label('Deskripsi Tugas')
                ->rows(4)
                ->columnSpanFull()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Tipe')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategoriProject.nama')
                    ->label('Kategori Project')
                    ->placeholder('-')
                    ->searchable(),

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
            ])
            ->defaultSort('akhir', 'asc');
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