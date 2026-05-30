<?php

namespace App\Filament\Mahasiswa\Resources\Projects;

use App\Filament\Mahasiswa\Resources\Projects\Pages\CreateProject;
use App\Filament\Mahasiswa\Resources\Projects\Pages\EditProject;
use App\Filament\Mahasiswa\Resources\Projects\Pages\ListProjects;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Project Mahasiswa';

    protected static ?string $modelLabel = 'Project Mahasiswa';

    protected static ?string $pluralModelLabel = 'Project Mahasiswa';

    protected static ?string $recordTitleAttribute = 'nama_project';

    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    public static function canEdit($record): bool
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        return $mahasiswa && $record->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function canDelete($record): bool
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        return $mahasiswa && $record->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('mahasiswa_nim', $mahasiswa->nim);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('mahasiswa_nim')
                ->default(fn () => auth()->user()?->mahasiswa?->nim)
                ->required(),

            Select::make('tugas_project_id')
                ->label('Tugas Project')
                ->options(function () {
                    $mahasiswa = auth()->user()?->mahasiswa;

                    if (! $mahasiswa) {
                        return [];
                    }

                    return Tugas::query()
                        ->whereHas('kelas.mahasiswa', function (Builder $query) use ($mahasiswa) {
                            $query->where('mahasiswa.nim', $mahasiswa->nim);
                        })
                        ->with([
                            'kelas',
                            'kelas.matakuliah',
                            'kategoriProject',
                        ])
                        ->get()
                        ->mapWithKeys(function ($tugas) {
                            $mataKuliah = $tugas->kelas?->matakuliah?->nama ?? 'Mata Kuliah';
                            $kelas = $tugas->kelas?->kode ?? 'Kelas';
                            $kategori = $tugas->kategoriProject?->nama ?? 'Kategori';
                            $deskripsi = str($tugas->deskripsi)->limit(40);

                            return [
                                $tugas->id => "{$mataKuliah} - {$kelas} - {$kategori} - {$deskripsi}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('nama_project')
                ->label('Nama Project')
                ->required()
                ->maxLength(100),

            TextInput::make('nama_kelompok')
                ->label('Nama Kelompok')
                ->maxLength(100)
                ->nullable(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(4)
                ->columnSpanFull()
                ->nullable(),

            TextInput::make('link_url')
                ->label('Link URL / GitHub / Deploy')
                ->url()
                ->maxLength(100)
                ->nullable(),

            TextInput::make('link_video')
                ->label('Link Video')
                ->url()
                ->maxLength(100)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_project')
                    ->label('Nama Project')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tugas.kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kategoriProject.nama')
                    ->label('Kategori Project')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nama_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    ->limit(25)
                    ->url(fn ($record) => $record->link_url)
                    ->openUrlInNewTab()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->limit(25)
                    ->url(fn ($record) => $record->link_video)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}