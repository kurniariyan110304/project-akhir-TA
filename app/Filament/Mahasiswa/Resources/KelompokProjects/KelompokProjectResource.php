<?php

namespace App\Filament\Mahasiswa\Resources\KelompokProjects;

use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\CreateKelompokProject;
use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Mahasiswa\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use App\Models\Mahasiswa;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
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

        if (! $mahasiswa) {
            return false;
        }

        return $record->project?->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function canDelete($record): bool
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return false;
        }

        return $record->project?->mahasiswa_nim === $mahasiswa->nim;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $mahasiswa = auth()->user()?->mahasiswa;

        if (! $mahasiswa) {
            return $query->whereRaw('1 = 0');
        }

        /*
         * Supaya tabel utama tidak menampilkan anggota satu-satu.
         * Kita ambil 1 data saja dari setiap project_mahasiswa_id.
         */
        return $query->whereIn('id', function ($subQuery) use ($mahasiswa) {
            $subQuery->selectRaw('MIN(kelompok_project.id)')
                ->from('kelompok_project')
                ->join('project_mahasiswa', 'project_mahasiswa.id', '=', 'kelompok_project.project_mahasiswa_id')
                ->join('tugas_project', 'tugas_project.id', '=', 'project_mahasiswa.tugas_project_id')
                ->where('project_mahasiswa.mahasiswa_nim', $mahasiswa->nim)
                ->where('tugas_project.kategori', 'KELOMPOK')
                ->groupBy('kelompok_project.project_mahasiswa_id');
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('project_mahasiswa_id')
                ->label('Project')
                ->options(function () {
                    $mahasiswa = auth()->user()?->mahasiswa;

                    if (! $mahasiswa) {
                        return [];
                    }

                    return ProjectMahasiswa::query()
                        ->with(['tugas', 'tugas.kelas', 'tugas.kelas.matakuliah'])
                        ->where('mahasiswa_nim', $mahasiswa->nim)
                        ->whereHas('tugas', function (Builder $query) {
                            $query->where('kategori', 'KELOMPOK');
                        })
                        ->orderBy('nama_project')
                        ->get()
                        ->mapWithKeys(function ($project) {
                            $namaProject = $project->nama_project ?? '-';
                            $matkul = $project->tugas?->kelas?->matakuliah?->nama ?? '-';
                            $kelas = $project->tugas?->kelas?->kode ?? '-';
                            $kelompok = $project->nama_kelompok ?? '-';

                            return [
                                $project->id => "{$namaProject} - {$matkul} - {$kelas} - Kelompok: {$kelompok}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->helperText('Hanya project dengan tipe tugas KELOMPOK yang muncul.'),

            CheckboxList::make('mahasiswa_nims')
                ->label('Anggota Mahasiswa')
                ->options(function ($get) {
                    $projectId = $get('project_mahasiswa_id');

                    if (! $projectId) {
                        return [];
                    }

                    $project = ProjectMahasiswa::query()
                        ->with('tugas')
                        ->find($projectId);

                    if (! $project || ! $project->tugas) {
                        return [];
                    }

                    $kelasId = $project->tugas->kelas_id;

                    return Mahasiswa::query()
                        ->whereIn('nim', function ($query) use ($kelasId) {
                            $query->select('mahasiswa_nim')
                                ->from('kelas_mahasiswa')
                                ->where('kelas_id', $kelasId);
                        })
                        ->whereNotIn('nim', function ($query) use ($projectId) {
                            $query->select('mahasiswa_nim')
                                ->from('kelompok_project')
                                ->where('project_mahasiswa_id', $projectId);
                        })
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($mahasiswa) {
                            return [
                                $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                            ];
                        });
                })
                ->columns(2)
                ->bulkToggleable()
                ->searchable()
                ->required()
                ->helperText('Centang satu atau banyak mahasiswa untuk dimasukkan ke kelompok.')
                ->visible(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn (string $operation): bool => $operation === 'create'),

            Select::make('mahasiswa_nim')
                ->label('Anggota Mahasiswa')
                ->options(function ($record) {
                    $projectId = $record?->project_mahasiswa_id;

                    if (! $projectId) {
                        return Mahasiswa::query()
                            ->orderBy('nama')
                            ->get()
                            ->mapWithKeys(function ($mahasiswa) {
                                return [
                                    $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                                ];
                            });
                    }

                    $project = ProjectMahasiswa::query()
                        ->with('tugas')
                        ->find($projectId);

                    if (! $project || ! $project->tugas) {
                        return [];
                    }

                    $kelasId = $project->tugas->kelas_id;

                    return Mahasiswa::query()
                        ->whereIn('nim', function ($query) use ($kelasId) {
                            $query->select('mahasiswa_nim')
                                ->from('kelas_mahasiswa')
                                ->where('kelas_id', $kelasId);
                        })
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function ($mahasiswa) {
                            return [
                                $mahasiswa->nim => "{$mahasiswa->nim} - {$mahasiswa->nama}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->required()
                ->visible(fn (string $operation): bool => $operation === 'edit')
                ->dehydrated(fn (string $operation): bool => $operation === 'edit'),

            Select::make('peran')
                ->label('Peran')
                ->options([
                    'KETUA' => 'Ketua',
                    'ANGGOTA' => 'Anggota',
                ])
                ->required(),

            Hidden::make('nilai')
                ->default(0),

            Select::make('aktif')
                ->label('Status')
                ->options([
                    1 => 'Aktif',
                    0 => 'Tidak Aktif',
                ])
                ->default(1)
                ->required(),
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

            Tables\Columns\TextColumn::make('project.nama_kelompok')
                ->label('Nama Kelompok')
                ->placeholder('-')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('project.tugas.kategori')
                ->label('Tipe Tugas')
                ->badge()
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('jumlah_anggota')
                ->label('Jumlah Anggota')
                ->getStateUsing(function (KelompokProject $record) {
                    return KelompokProject::query()
                        ->where('project_mahasiswa_id', $record->project_mahasiswa_id)
                        ->count();
                }),
        ])
        ->recordActions([
            Action::make('listAnggota')
                ->label('List Anggota')
                ->icon('heroicon-o-users')
                ->modalHeading(fn (KelompokProject $record): string => 'Anggota Kelompok - ' . ($record->project?->nama_kelompok ?? '-'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalWidth('5xl')
                ->modalContent(function (KelompokProject $record) {
                    $mahasiswa = auth()->user()?->mahasiswa;

                    if (! $mahasiswa || $record->project?->mahasiswa_nim !== $mahasiswa->nim) {
                        $anggotas = collect();
                    } else {
                        $anggotas = KelompokProject::query()
                            ->with(['mahasiswa', 'project'])
                            ->where('project_mahasiswa_id', $record->project_mahasiswa_id)
                            ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                            ->orderBy('id')
                            ->get();
                    }

                    return view('filament.mahasiswa.pages.list-anggota-kelompok', [
                        'project' => $record->project,
                        'anggotas' => $anggotas,
                    ]);
                }),
        ])
        ->defaultSort('id', 'desc');
}

    public static function getPages(): array
    {
        return [
            'index' => ListKelompokProjects::route('/'),
            'create' => CreateKelompokProject::route('/create'),
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}