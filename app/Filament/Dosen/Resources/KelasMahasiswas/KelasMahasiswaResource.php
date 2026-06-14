<?php

namespace App\Filament\Dosen\Resources\KelasMahasiswas;

use App\Exports\NilaiMahasiswaExcelExport;
use App\Filament\Dosen\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\KelasMahasiswa;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

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
        return false;
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
                'kelas',
                'kelas.matakuliah',
                'kelas.dosen',
            ])
            ->select('kelas_mahasiswa.*')
            ->selectSub(function ($query) {
                $query->from('kelas_mahasiswa as km2')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('km2.kelas_id', 'kelas_mahasiswa.kelas_id');
            }, 'total_mahasiswa')
            ->whereHas('kelas', function (Builder $query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->whereIn('kelas_mahasiswa.id', function ($subQuery) {
                $subQuery->selectRaw('MIN(id)')
                    ->from('kelas_mahasiswa')
                    ->groupBy('kelas_id');
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_mahasiswa')
                    ->label('Total Mahasiswa')
                    ->sortable(),
            ])

            ->headerActions([
                Action::make('exportExcelSemua')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $kelasIds = static::getEloquentQuery()
                            ->pluck('kelas_id')
                            ->unique()
                            ->values()
                            ->toArray();

                        return Excel::download(
                            new NilaiMahasiswaExcelExport($kelasIds),
                            'nilai-mahasiswa-dosen-semua.xlsx'
                        );
                    }),

                Action::make('exportPdfSemua')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function () {
                        $dosen = auth()->user()?->dosen;

                        if (! $dosen) {
                            abort(403);
                        }

                        $kelasIds = static::getEloquentQuery()
                            ->pluck('kelas_id')
                            ->unique()
                            ->values()
                            ->toArray();

                        $data = KelasMahasiswa::query()
                            ->with([
                                'kelas',
                                'kelas.matakuliah',
                                'kelas.dosen',
                                'mahasiswa',
                            ])
                            ->whereIn('kelas_id', $kelasIds)
                            ->orderBy('kelas_id')
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        $pdf = Pdf::loadView('pdf.nilai-mahasiswa-dosen', [
                            'data' => $data,
                            'dosen' => $dosen,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'nilai-mahasiswa-dosen-semua.pdf');
                    }),
            ])

            ->recordActions([
                Action::make('listMahasiswa')
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn (KelasMahasiswa $record): string => 'Mahasiswa Kelas ' . ($record->kelas?->kode ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(function (KelasMahasiswa $record) {
                        $mahasiswas = KelasMahasiswa::query()
                            ->with(['mahasiswa', 'kelas', 'kelas.matakuliah', 'kelas.dosen'])
                            ->where('kelas_id', $record->kelas_id)
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        return view('filament.dosen.pages.list-mahasiswa-kelas', [
                            'kelas' => $record->kelas,
                            'mahasiswas' => $mahasiswas,
                        ]);
                    }),

                Action::make('inputNilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn (KelasMahasiswa $record): string => 'Input Nilai Mahasiswa - ' . ($record->kelas?->kode ?? '-'))
                    ->modalWidth('5xl')
                    ->form(function (KelasMahasiswa $record): array {
                        return [
                            Repeater::make('nilai_mahasiswa')
                                ->label('Daftar Nilai Mahasiswa')
                                ->schema([
                                    Hidden::make('id'),

                                    TextInput::make('mahasiswa')
                                        ->label('Mahasiswa')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 8,
                                        ]),

                                    TextInput::make('nilai_akhir')
                                        ->label('Nilai Akhir')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->required()
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 4,
                                        ]),
                                ])
                                ->columns([
                                    'default' => 1,
                                    'md' => 12,
                                ])
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->default(function () use ($record) {
                                    return KelasMahasiswa::query()
                                        ->with('mahasiswa')
                                        ->where('kelas_id', $record->kelas_id)
                                        ->orderBy('mahasiswa_nim')
                                        ->get()
                                        ->map(function (KelasMahasiswa $item): array {
                                            return [
                                                'id' => $item->id,
                                                'mahasiswa' => $item->mahasiswa_nim . ' - ' . ($item->mahasiswa?->nama ?? '-'),
                                                'nilai_akhir' => $item->nilai_akhir ?? 0,
                                            ];
                                        })
                                        ->toArray();
                                })
                                ->helperText('Input nilai semua mahasiswa yang mengambil kelas ini.'),
                        ];
                    })
                    ->action(function (array $data): void {
                        foreach ($data['nilai_mahasiswa'] ?? [] as $item) {
                            if (empty($item['id'])) {
                                continue;
                            }

                            KelasMahasiswa::query()
                                ->where('id', $item['id'])
                                ->update([
                                    'nilai_akhir' => $item['nilai_akhir'] ?? 0,
                                ]);
                        }
                    })
                    ->successNotificationTitle('Nilai mahasiswa berhasil disimpan'),

                Action::make('exportExcelKelas')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (KelasMahasiswa $record) {
                        $dosen = auth()->user()?->dosen;

                        if (! $dosen || $record->kelas?->dosen_id !== $dosen->id) {
                            abort(403);
                        }

                        $kodeKelas = str($record->kelas?->kode ?? 'kelas')->slug();

                        return Excel::download(
                            new NilaiMahasiswaExcelExport([$record->kelas_id]),
                            "nilai-mahasiswa-{$kodeKelas}.xlsx"
                        );
                    }),

                Action::make('exportPdfKelas')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function (KelasMahasiswa $record) {
                        $dosen = auth()->user()?->dosen;

                        if (! $dosen || $record->kelas?->dosen_id !== $dosen->id) {
                            abort(403);
                        }

                        $data = KelasMahasiswa::query()
                            ->with([
                                'kelas',
                                'kelas.matakuliah',
                                'kelas.dosen',
                                'mahasiswa',
                            ])
                            ->where('kelas_id', $record->kelas_id)
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        $pdf = Pdf::loadView('pdf.nilai-mahasiswa-dosen', [
                            'data' => $data,
                            'dosen' => $dosen,
                        ])->setPaper('a4', 'landscape');

                        $kodeKelas = str($record->kelas?->kode ?? 'kelas')->slug();

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "nilai-mahasiswa-{$kodeKelas}.pdf");
                    }),
            ])
            ->defaultSort('kelas_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelasMahasiswas::route('/'),
        ];
    }
}