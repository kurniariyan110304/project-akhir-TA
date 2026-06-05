<?php

namespace App\Filament\Dosen\Resources\KelompokProjects;

use App\Filament\Dosen\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Dosen\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KelompokProjectResource extends Resource
{
    protected static ?string $model = KelompokProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelompok Project';

    protected static ?string $modelLabel = 'Kelompok Project';

    protected static ?string $pluralModelLabel = 'Kelompok Project';

    protected static ?int $navigationSort = 8;

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

        return $record->project?->tugas?->kelas?->dosen_id === $dosen->id;
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

        return $query
            ->with([
                'project',
                'project.tugas',
                'project.tugas.kelas',
                'project.tugas.kelas.matakuliah',
                'mahasiswa',
            ])
            ->select('kelompok_project.*')
            ->whereHas('project.tugas.kelas', function (Builder $query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->whereIn('kelompok_project.id', function ($subQuery) {
                $subQuery->selectRaw('MIN(id)')
                    ->from('kelompok_project')
                    ->groupBy('project_mahasiswa_id');
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

                Tables\Columns\TextColumn::make('project.nama_kelompok')
                    ->label('Nama Kelompok')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->recordActions([
                Action::make('listAnggota')
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn(KelompokProject $record): string => 'Anggota Kelompok - ' . ($record->project?->nama_kelompok ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(function (KelompokProject $record) {
                        $anggotas = KelompokProject::query()
                            ->with(['mahasiswa', 'project', 'project.tugas'])
                            ->where('project_mahasiswa_id', $record->project_mahasiswa_id)
                            ->orderByRaw("FIELD(peran, 'KETUA', 'ANGGOTA')")
                            ->orderBy('id')
                            ->get();

                        return view('filament.dosen.pages.list-anggota-kelompok-project', [
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
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}
