<?php

namespace App\Filament\Asdos\Resources\ProjectMahasiswas;

use App\Exports\ProjectMahasiswaExcelExport;
use App\Filament\Asdos\Resources\ProjectMahasiswas\Pages\EditProjectMahasiswa;
use App\Filament\Asdos\Resources\ProjectMahasiswas\Pages\ListProjectMahasiswas;
use App\Models\KelompokProject;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ProjectMahasiswaResource extends Resource
{
    protected static ?string $model = ProjectMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Project Mahasiswa';

    protected static ?string $modelLabel = 'Project Mahasiswa';

    protected static ?string $pluralModelLabel = 'Project Mahasiswa';

    protected static ?int $navigationSort = 6;

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
            ->where('kelas.id', $record->tugas?->kelas_id)
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
                'tugas',
                'tugas.kelas',
                'tugas.kelas.matakuliah',
                'anggotaKelompok',
                'anggotaKelompok.mahasiswa',
            ])
            ->whereHas('tugas.kelas', function (Builder $query) use ($asdos) {
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
                        ->afterStateHydrated(function (TextInput $component, ?ProjectMahasiswa $record): void {
                            $component->state($record?->nama_project ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_tipe_tugas')
                        ->label('Tipe Tugas')
                        ->afterStateHydrated(function (TextInput $component, ?ProjectMahasiswa $record): void {
                            $component->state($record?->tugas?->kategori ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(2),

            Section::make('Input Nilai')
                ->schema([
                    TextInput::make('nilai_akhir')
                        ->label('Nilai Akhir Project')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(fn (?ProjectMahasiswa $record): bool => $record?->tugas?->kategori !== 'KELOMPOK')
                        ->disabled(fn (?ProjectMahasiswa $record): bool => $record?->tugas?->kategori === 'KELOMPOK')
                        ->dehydrated(fn (?ProjectMahasiswa $record): bool => $record?->tugas?->kategori !== 'KELOMPOK')
                        ->helperText('Untuk project kelompok, nilai akhir otomatis dihitung dari rata-rata nilai anggota.'),
                ]),

            Section::make('Informasi Mahasiswa')
                ->schema([
                    TextInput::make('info_nim')
                        ->label('NIM')
                        ->afterStateHydrated(function (TextInput $component, ?ProjectMahasiswa $record): void {
                            $component->state($record?->mahasiswa?->nim ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_nama_mahasiswa')
                        ->label('Nama Mahasiswa')
                        ->afterStateHydrated(function (TextInput $component, ?ProjectMahasiswa $record): void {
                            $component->state($record?->mahasiswa?->nama ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(2)
                ->visible(fn (?ProjectMahasiswa $record): bool => $record?->tugas?->kategori === 'INDIVIDU'),

            Section::make('Informasi Kelompok')
                ->schema([
                    TextInput::make('info_nama_kelompok')
                        ->label('Nama Kelompok')
                        ->afterStateHydrated(function (TextInput $component, ?ProjectMahasiswa $record): void {
                            $component->state($record?->nama_kelompok ?? '-');
                        })
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),

                    Repeater::make('anggota_kelompok')
                        ->label('Nilai Per Anggota Kelompok')
                        ->schema([
                            Hidden::make('mahasiswa_nim'),

                            TextInput::make('nama_mahasiswa')
                                ->label('Mahasiswa')
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 6,
                                ]),

                            TextInput::make('peran')
                                ->label('Peran')
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 3,
                                ]),

                            TextInput::make('nilai')
                                ->label('Nilai')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 3,
                                ]),
                        ])
                        ->columns([
                            'default' => 1,
                            'md' => 12,
                        ])
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->helperText('Input nilai masing-masing anggota kelompok. Nilai akhir project akan dihitung otomatis.'),
                ])
                ->visible(fn (?ProjectMahasiswa $record): bool => $record?->tugas?->kategori === 'KELOMPOK')
                ->columnSpanFull(),
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

                Tables\Columns\TextColumn::make('tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->placeholder('-')
                    ->sortable(),
            ])

            ->headerActions([
                Action::make('exportExcelSemua')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $projectIds = static::getEloquentQuery()
                            ->pluck('id')
                            ->toArray();

                        return Excel::download(
                            new ProjectMahasiswaExcelExport($projectIds),
                            'project-mahasiswa-asdos-semua.xlsx'
                        );
                    }),

                Action::make('exportPdfSemua')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function () {
                        $asdos = auth()->user()?->asdos;

                        if (! $asdos) {
                            abort(403);
                        }

                        $data = static::getEloquentQuery()
                            ->with([
                                'mahasiswa',
                                'tugas',
                                'tugas.kelas',
                                'tugas.kelas.matakuliah',
                                'anggotaKelompok',
                                'anggotaKelompok.mahasiswa',
                            ])
                            ->get();

                        $pdf = Pdf::loadView('pdf.project-mahasiswa-asdos', [
                            'data' => $data,
                            'asdos' => $asdos,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'project-mahasiswa-asdos-semua.pdf');
                    }),
            ])

            ->recordActions([
                Action::make('listMahasiswa')
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(function (ProjectMahasiswa $record): string {
                        if ($record->tugas?->kategori === 'KELOMPOK') {
                            return 'List Mahasiswa Kelompok - ' . ($record->nama_kelompok ?? '-');
                        }

                        return 'Mahasiswa Project Individu - ' . ($record->nama_project ?? '-');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('6xl')
                    ->modalContent(function (ProjectMahasiswa $record) {
                        $record->load([
                            'mahasiswa',
                            'tugas',
                            'tugas.kelas',
                            'tugas.kelas.matakuliah',
                        ]);

                        $anggotas = collect();

                        if ($record->tugas?->kategori === 'KELOMPOK') {
                            $anggotas = KelompokProject::query()
                                ->with(['mahasiswa', 'project'])
                                ->where('project_mahasiswa_id', $record->id)
                                ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                                ->orderBy('id')
                                ->get();
                        }

                        return view('filament.asdos.pages.list-mahasiswa-project', [
                            'project' => $record,
                            'anggotas' => $anggotas,
                        ]);
                    }),

                Action::make('exportProjectPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function (ProjectMahasiswa $record) {
                        $asdos = auth()->user()?->asdos;

                        if (! $asdos) {
                            abort(403);
                        }

                        $bolehAkses = $asdos->kelas()
                            ->where('kelas.id', $record->tugas?->kelas_id)
                            ->exists();

                        if (! $bolehAkses) {
                            abort(403);
                        }

                        $record->load([
                            'mahasiswa',
                            'tugas',
                            'tugas.kelas',
                            'tugas.kelas.matakuliah',
                            'anggotaKelompok',
                            'anggotaKelompok.mahasiswa',
                        ]);

                        $anggotas = collect();

                        if ($record->tugas?->kategori === 'KELOMPOK') {
                            $anggotas = KelompokProject::query()
                                ->with(['mahasiswa', 'project'])
                                ->where('project_mahasiswa_id', $record->id)
                                ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                                ->orderBy('id')
                                ->get();
                        }

                        $pdf = Pdf::loadView('pdf.project-mahasiswa-detail-asdos', [
                            'project' => $record,
                            'anggotas' => $anggotas,
                            'asdos' => $asdos,
                        ])->setPaper('a4', 'landscape');

                        $namaProject = str($record->nama_project)->slug();
                        $namaFile = "project-mahasiswa-{$namaProject}.pdf";

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $namaFile);
                    }),

                Action::make('exportProjectExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (ProjectMahasiswa $record) {
                        $asdos = auth()->user()?->asdos;

                        if (! $asdos) {
                            abort(403);
                        }

                        $bolehAkses = $asdos->kelas()
                            ->where('kelas.id', $record->tugas?->kelas_id)
                            ->exists();

                        if (! $bolehAkses) {
                            abort(403);
                        }

                        $namaProject = str($record->nama_project)->slug();
                        $namaFile = "project-mahasiswa-{$namaProject}.xlsx";

                        return Excel::download(
                            new ProjectMahasiswaExcelExport([$record->id]),
                            $namaFile
                        );
                    }),

                EditAction::make()
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square'),
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