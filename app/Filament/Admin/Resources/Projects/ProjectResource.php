<?php

namespace App\Filament\Admin\Resources\Projects;

use App\Filament\Admin\Resources\Projects\Pages\CreateProject;
use App\Filament\Admin\Resources\Projects\Pages\EditProject;
use App\Filament\Admin\Resources\Projects\Pages\ListProjects;
use App\Filament\Admin\Resources\Projects\Pages\ViewProject;
use App\Models\Mahasiswa;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Project';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $pluralModelLabel = 'Project';

    protected static ?string $recordTitleAttribute = 'nama_project';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_project')
                ->label('Nama Project')
                ->required()
                ->maxLength(255),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(4)
                ->columnSpanFull(),

            Select::make('mahasiswa_nim')
                ->label('Ketua / Mahasiswa')
                ->options(function () {
                    return Mahasiswa::query()
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function (Mahasiswa $mahasiswa) {
                            return [
                                $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),

            Select::make('tugas_project_id')
                ->label('Tugas Project')
                ->options(function () {
                    return Tugas::query()
                        ->with([
                            'kelas',
                            'kelas.matakuliah',
                            'kategoriProject',
                        ])
                        ->orderBy('id', 'desc')
                        ->get()
                        ->mapWithKeys(function (Tugas $tugas) {
                            $tipe = $tugas->kategori ?? '-';
                            $kelas = $tugas->kelas?->kode ?? '-';
                            $matkul = $tugas->kelas?->matakuliah?->nama ?? '-';
                            $kategori = $tugas->kategoriProject?->nama ?? '-';
                            $deskripsi = str($tugas->deskripsi ?? '-')->limit(40);

                            return [
                                $tugas->id => "{$tipe} - {$matkul} - {$kelas} - {$kategori} - {$deskripsi}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('nama_kelompok')
                ->label('Nama Kelompok')
                ->maxLength(255)
                ->helperText('Isi jika project bertipe kelompok. Kosongkan jika individu.'),

            TextInput::make('link_url')
                ->label('Link URL')
                ->url()
                ->maxLength(255)
                ->placeholder('https://github.com/...'),

            TextInput::make('link_video')
                ->label('Link Video')
                ->url()
                ->maxLength(255)
                ->placeholder('https://youtube.com/...'),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
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
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tugas.kategoriProject.nama')
                    ->label('Kategori Project')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nama_kelompok')
                    ->label('Nama Kelompok')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable()
                    ->placeholder('0'),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    ->limit(30)
                    ->url(fn (ProjectMahasiswa $record): ?string => $record->link_url)
                    ->openUrlInNewTab()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->limit(30)
                    ->url(fn (ProjectMahasiswa $record): ?string => $record->link_video)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}