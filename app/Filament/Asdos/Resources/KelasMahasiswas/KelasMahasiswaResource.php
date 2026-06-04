<?php

namespace App\Filament\Asdos\Resources\KelasMahasiswas;

use App\Filament\Asdos\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Asdos\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\KelasMahasiswa;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelasMahasiswaResource extends Resource
{
    protected static ?string $model = KelasMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Nilai Mahasiswa';

    protected static ?string $modelLabel = 'Nilai Mahasiswa';

    protected static ?string $pluralModelLabel = 'Nilai Mahasiswa';

    protected static ?int $navigationSort = 5;

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
            ->where('kelas.id', $record->kelas_id)
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
                'kelas',
                'kelas.matakuliah',
                'kelas.dosen',
            ])
            ->whereIn('kelas_id', function ($subQuery) use ($asdos) {
                $subQuery->select('kelas_id')
                    ->from('asdos_kelas')
                    ->where('asdos_id', $asdos->id);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Biodata Mahasiswa')
                ->schema([
                    TextInput::make('mahasiswa_nim')
                        ->label('NIM')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('info_nama')
                        ->label('Nama Mahasiswa')
                        ->formatStateUsing(fn (?KelasMahasiswa $record): string => $record?->mahasiswa?->nama ?? '-')
                        ->disabled()
                        ->dehydrated(false),
                ])
                ->columns(2),

            Section::make('Input Nilai')
                ->schema([
                    TextInput::make('nilai_akhir')
                        ->label('Nilai Akhir')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.hari')
                    ->label('Hari'),

                Tables\Columns\TextColumn::make('kelas.jam')
                    ->label('Jam'),

                Tables\Columns\TextColumn::make('kelas.ruang')
                    ->label('Ruang'),

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
                        $asdos = auth()->user()?->asdos;

                        if (! $asdos) {
                            abort(403);
                        }

                        $data = KelasMahasiswa::query()
                            ->whereIn('kelas_id', function ($subQuery) use ($asdos) {
                                $subQuery->select('kelas_id')
                                    ->from('asdos_kelas')
                                    ->where('asdos_id', $asdos->id);
                            })
                            ->with([
                                'mahasiswa',
                                'kelas',
                                'kelas.matakuliah',
                                'kelas.dosen',
                            ])
                            ->orderBy('kelas_id')
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        $pdf = Pdf::loadView('pdf.nilai-mahasiswa-asdos', [
                            'data' => $data,
                            'asdos' => $asdos,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'nilai-mahasiswa-asdos.pdf');
                    }),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Input Nilai'),
            ])

            ->defaultSort('mahasiswa_nim', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelasMahasiswas::route('/'),
            'edit' => EditKelasMahasiswa::route('/{record}/edit'),
        ];
    }
}