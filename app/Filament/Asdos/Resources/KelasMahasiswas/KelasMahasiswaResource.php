<?php

namespace App\Filament\Asdos\Resources\KelasMahasiswas;

use App\Filament\Asdos\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Asdos\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\KelasMahasiswa;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
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
            ->whereIn('kelas_mahasiswa.kelas_id', function ($subQuery) use ($asdos) {
                $subQuery->select('kelas_id')
                    ->from('asdos_kelas')
                    ->where('asdos_id', $asdos->id);
            })
            ->whereIn('kelas_mahasiswa.id', function ($subQuery) {
                $subQuery->selectRaw('MIN(id)')
                    ->from('kelas_mahasiswa')
                    ->groupBy('kelas_id');
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
                        ->formatStateUsing(fn(?KelasMahasiswa $record): string => $record?->mahasiswa?->nama ?? '-')
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
                Tables\Columns\TextColumn::make('kelas.kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.dosen.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_mahasiswa')
                    ->label('Total Mahasiswa')
                    ->sortable(),
            ])

            ->recordActions([
                Action::make('listMahasiswa')
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn(KelasMahasiswa $record): string => 'Mahasiswa Kelas ' . ($record->kelas?->kode ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(function (KelasMahasiswa $record) {
                        $mahasiswas = KelasMahasiswa::query()
                            ->with(['mahasiswa', 'kelas', 'kelas.matakuliah', 'kelas.dosen'])
                            ->where('kelas_id', $record->kelas_id)
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        return view('filament.asdos.pages.list-mahasiswa-kelas', [
                            'kelas' => $record->kelas,
                            'mahasiswas' => $mahasiswas,
                        ]);
                    }),

                Action::make('inputNilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn(KelasMahasiswa $record): string => 'Input Nilai Mahasiswa - ' . ($record->kelas?->kode ?? '-'))
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
            ])

            ->defaultSort('kelas_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelasMahasiswas::route('/'),
            'edit' => EditKelasMahasiswa::route('/{record}/edit'),
        ];
    }
}
