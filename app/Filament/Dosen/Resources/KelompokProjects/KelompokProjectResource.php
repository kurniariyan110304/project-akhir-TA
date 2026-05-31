<?php

namespace App\Filament\Dosen\Resources\KelompokProjects;

use App\Filament\Dosen\Resources\KelompokProjects\Pages\EditKelompokProject;
use App\Filament\Dosen\Resources\KelompokProjects\Pages\ListKelompokProjects;
use App\Models\KelompokProject;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

        return $query->whereHas('project.tugas.kelas', function (Builder $query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.nama_project')
                    ->label('Nama Project')
                    ->searchable(),

                Tables\Columns\TextColumn::make('project.nama_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('project.tugas.kelas.kode')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('project.tugas.kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge(),

                Tables\Columns\TextColumn::make('aktif')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                    ->badge(),

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
                        $dosen = auth()->user()?->dosen;
            
                        if (! $dosen) {
                            abort(403);
                        }
            
                        $data = KelompokProject::query()
                            ->whereHas('project.tugas.kelas', function (Builder $query) use ($dosen) {
                                $query->where('dosen_id', $dosen->id);
                            })
                            ->with([
                                'mahasiswa',
                                'project',
                                'project.tugas',
                                'project.tugas.kelas',
                                'project.tugas.kelas.matakuliah',
                            ])
                            ->get();
            
                        $pdf = Pdf::loadView('pdf.kelompok-project', [
                            'data' => $data,
                            'dosen' => $dosen,
                        ])->setPaper('a4', 'landscape');
            
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'kelompok-project.pdf');
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
            'index' => ListKelompokProjects::route('/'),
            'edit' => EditKelompokProject::route('/{record}/edit'),
        ];
    }
}