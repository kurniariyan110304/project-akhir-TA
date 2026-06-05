<?php

namespace App\Filament\Admin\Resources\KelompokProjects;

use App\Filament\Admin\Resources\KelompokProjects\Pages\CreateKelompokProject;
use App\Filament\Admin\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Admin\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use App\Models\Mahasiswa;
use App\Models\ProjectMahasiswa;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class KelompokProjectResource extends Resource
{
    protected static ?string $model = KelompokProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelompok Project';

    protected static ?string $modelLabel = 'Kelompok Project';

    protected static ?string $pluralModelLabel = 'Kelompok Project';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('project_mahasiswa_id')
                ->label('Project')
                ->options(function () {
                    return ProjectMahasiswa::query()
                        ->with([
                            'tugas',
                            'tugas.kelas',
                            'tugas.kelas.matakuliah',
                        ])
                        ->whereHas('tugas', function (Builder $query) {
                            $query->where('kategori', 'KELOMPOK');
                        })
                        ->orderBy('nama_project')
                        ->get()
                        ->mapWithKeys(function (ProjectMahasiswa $project) {
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
                ->required(),

            CheckboxList::make('mahasiswa_nims')
                ->label('Anggota Mahasiswa')
                ->options(function ($get) {
                    $projectId = $get('project_mahasiswa_id');

                    if (! $projectId) {
                        return [];
                    }

                    return Mahasiswa::query()
                        ->whereNotIn('nim', function ($query) use ($projectId) {
                            $query->select('mahasiswa_nim')
                                ->from('kelompok_project')
                                ->where('project_mahasiswa_id', $projectId);
                        })
                        ->orderBy('nama')
                        ->get()
                        ->mapWithKeys(function (Mahasiswa $mahasiswa) {
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
                ->visible(fn(string $operation): bool => $operation === 'create')
                ->dehydrated(fn(string $operation): bool => $operation === 'create'),

            Select::make('mahasiswa_nim')
                ->label('Mahasiswa')
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
                ->required()
                ->visible(fn(string $operation): bool => $operation === 'edit')
                ->dehydrated(fn(string $operation): bool => $operation === 'edit'),

            Select::make('peran')
                ->label('Peran')
                ->options([
                    'KETUA' => 'KETUA',
                    'ANGGOTA' => 'ANGGOTA',
                ])
                ->default('ANGGOTA')
                ->required(),

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'project',
                'project.tugas',
            ])
            ->select('kelompok_project.*')
            ->selectSub(function ($query) {
                $query->from('kelompok_project as kp2')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('kp2.project_mahasiswa_id', 'kelompok_project.project_mahasiswa_id');
            }, 'jumlah_anggota')
            ->whereIn('kelompok_project.id', function ($query) {
                $query->from('kelompok_project')
                    ->selectRaw('MIN(id)')
                    ->groupBy('project_mahasiswa_id');
            });
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
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('project.tugas.kategori')
                    ->label('Tipe Tugas')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jumlah_anggota')
                    ->label('Jumlah Anggota')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('listAnggota')
                    ->label('List Anggota')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn(KelompokProject $record): string => 'Anggota Kelompok - ' . ($record->project?->nama_kelompok ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(function (KelompokProject $record) {
                        $anggotas = KelompokProject::query()
                            ->with(['mahasiswa', 'project'])
                            ->where('project_mahasiswa_id', $record->project_mahasiswa_id)
                            ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                            ->orderBy('id')
                            ->get();

                        return view('filament.admin.pages.list-anggota-kelompok-project', [
                            'project' => $record->project,
                            'anggotas' => $anggotas,
                        ]);
                    }),

                EditAction::make()
                    ->label('Edit'),

                Action::make('hapusKelompok')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Kelompok Project')
                    ->modalDescription(
                        fn(KelompokProject $record): string =>
                        'Apakah kamu yakin ingin menghapus kelompok "' . ($record->project?->nama_kelompok ?? '-') . '" beserta semua anggotanya?'
                    )
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->action(function (KelompokProject $record): void {
                        KelompokProject::query()
                            ->where('project_mahasiswa_id', $record->project_mahasiswa_id)
                            ->delete();
                    })
                    ->successNotificationTitle('Kelompok project berhasil dihapus'),
            ])
            ->defaultSort('id', 'asc');
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
