<?php

namespace App\Filament\Dosen\Resources\ProjectMahasiswas;

use App\Filament\Dosen\Resources\ProjectMahasiswas\Pages\EditProjectMahasiswa;
use App\Filament\Dosen\Resources\ProjectMahasiswas\Pages\ListProjectMahasiswas;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectMahasiswaResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Project Mahasiswa';

    protected static ?string $modelLabel = 'Project Mahasiswa';

    protected static ?string $pluralModelLabel = 'Project Mahasiswa';

    protected static ?int $navigationSort = 7;

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
        return false;
    }

    public static function canEdit($record): bool
    {
        $dosen = auth()->user()?->dosen;

        if (! $dosen) {
            return false;
        }

        return $record->tugas?->kelas?->dosen_id === $dosen->id;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $dosen = auth()->user()?->dosen;

        if (! $dosen) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('tugas.kelas', function (Builder $query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir Project')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_project')
                    ->label('Nama Project')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tugas.kelas.kode')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tugas.kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge(),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    ->limit(30)
                    ->url(fn ($record) => $record->link_url)
                    ->openUrlInNewTab()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->limit(30)
                    ->url(fn ($record) => $record->link_video)
                    ->openUrlInNewTab()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->placeholder('-')
                    ->sortable(),
            ])

            ->headerActions([
    Action::make('exportPdf')
        ->label('Export PDF')
        ->icon('heroicon-o-document-arrow-down')
        ->action(function () {
            $dosen = auth()->user()?->dosen;

            if (! $dosen) {
                abort(403);
            }

            $data = ProjectMahasiswa::query()
                ->whereHas('tugas.kelas', function (Builder $query) use ($dosen) {
                    $query->where('dosen_id', $dosen->id);
                })
                ->with([
                    'mahasiswa',
                    'tugas',
                    'tugas.kelas',
                    'tugas.kelas.matakuliah',
                ])
                ->get();

            $pdf = Pdf::loadView('pdf.project-mahasiswa', [
                'data' => $data,
                'dosen' => $dosen,
            ])->setPaper('a4', 'landscape');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'project-mahasiswa.pdf');
        }),
])
            
            ->recordActions([
                EditAction::make()
                    ->label('Input Nilai'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectMahasiswas::route('/'),
            'edit' => EditProjectMahasiswa::route('/{record}/edit'),
        ];
    }
}