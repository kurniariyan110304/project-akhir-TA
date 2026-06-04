<?php

namespace App\Filament\Asdos\Resources\KelompokProjects;

use App\Filament\Asdos\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Asdos\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelompokProjectResource extends Resource
{
    protected static ?string $model = KelompokProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelompok Project';

    protected static ?string $modelLabel = 'Kelompok Project';

    protected static ?string $pluralModelLabel = 'Kelompok Project';

    protected static ?int $navigationSort = 7;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'asdos';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'asdos';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        $asdos = auth()->user()?->asdos;

        if (! $asdos) {
            return false;
        }

        return $asdos->kelas()
            ->where('kelas.id', $record->project?->tugas?->kelas_id)
            ->exists();
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $asdos = auth()->user()?->asdos;

        if (! $asdos) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->with([
                'mahasiswa',
                'project',
                'project.tugas',
                'project.tugas.kelas',
                'project.tugas.kelas.matakuliah',
            ])
            ->whereHas('project.tugas.kelas', function (Builder $query) use ($asdos) {
                $query->whereIn('kelas.id', function ($subQuery) use ($asdos) {
                    $subQuery->select('kelas_id')
                        ->from('asdos_kelas')
                        ->where('asdos_id', $asdos->id);
                });
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Project')
                ->schema([
                    TextInput::make('info_nama_project')
                        ->label('Nama Project')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->project?->nama_project ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_tipe_tugas')
                        ->label('Tipe Tugas')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->project?->tugas?->kategori ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_nama_kelompok')
                        ->label('Nama Kelompok')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->project?->nama_kelompok ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(3),

            Section::make('Informasi Anggota')
                ->schema([
                    TextInput::make('info_nim')
                        ->label('NIM')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->mahasiswa?->nim ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_nama_anggota')
                        ->label('Nama Anggota')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->mahasiswa?->nama ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_peran')
                        ->label('Peran')
                        ->afterStateHydrated(function (TextInput $component, ?KelompokProject $record): void {
                            $component->state($record?->peran ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(3),

            Section::make('Input Nilai')
                ->schema([
                    TextInput::make('nilai')
                        ->label('Nilai Anggota')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(),

                    Select::make('aktif')
                        ->label('Status Anggota')
                        ->options([
                            1 => 'Aktif',
                            0 => 'Tidak Aktif',
                        ])
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.nama_project')
                    ->label('Nama Project')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.nama_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Anggota')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => $state ? 'Aktif' : 'Tidak Aktif')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->placeholder('-')
                    ->sortable(),
            ])

            ->headerActions([
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        $asdos = auth()->user()?->asdos;

                        if (! $asdos) {
                            abort(403);
                        }

                        $data = KelompokProject::query()
                            ->whereHas('project.tugas.kelas', function (Builder $query) use ($asdos) {
                                $query->whereIn('kelas.id', function ($subQuery) use ($asdos) {
                                    $subQuery->select('kelas_id')
                                        ->from('asdos_kelas')
                                        ->where('asdos_id', $asdos->id);
                                });
                            })
                            ->with([
                                'mahasiswa',
                                'project',
                                'project.tugas',
                                'project.tugas.kelas',
                                'project.tugas.kelas.matakuliah',
                            ])
                            ->orderBy('project_mahasiswa_id')
                            ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                            ->orderBy('id')
                            ->get();

                        $pdf = Pdf::loadView('pdf.kelompok-project-asdos', [
                            'data' => $data,
                            'asdos' => $asdos,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'kelompok-project-asdos.pdf');
                    }),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square'),
            ])

            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelompokProjects::route('/'),
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}