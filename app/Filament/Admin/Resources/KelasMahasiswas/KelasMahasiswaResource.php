<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas;

use App\Filament\Admin\Resources\KelasMahasiswas\Pages\CreateKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Admin\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class KelasMahasiswaResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kelas Mahasiswa';

    protected static ?string $modelLabel = 'Kelas Mahasiswa';

    protected static ?string $pluralModelLabel = 'Kelas Mahasiswa';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('kelas_id')
                ->label('Kelas')
                ->options(function () {
                    return Kelas::query()
                        ->with(['matakuliah', 'dosen'])
                        ->orderBy('kode')
                        ->get()
                        ->mapWithKeys(function (Kelas $kelas) {
                            $kode = $kelas->kode ?? '-';
                            $matkul = $kelas->matakuliah?->nama ?? '-';
                            $dosen = $kelas->dosen?->nama ?? '-';

                            return [
                                $kelas->id => "{$kode} - {$matkul} - {$dosen}",
                            ];
                        });
                })
                ->searchable()
                ->preload()
                ->live()
                ->required(),

            CheckboxList::make('mahasiswa_nims')
                ->label('Mahasiswa')
                ->options(function ($get) {
                    $kelasId = $get('kelas_id');

                    if (! $kelasId) {
                        return [];
                    }

                    return Mahasiswa::query()
                        ->whereNotIn('nim', function ($query) use ($kelasId) {
                            $query->select('mahasiswa_nim')
                                ->from('kelas_mahasiswa')
                                ->where('kelas_id', $kelasId);
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
                ->helperText('Centang satu atau banyak mahasiswa untuk dimasukkan ke kelas.'),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Kelas::query()
            ->with([
                'matakuliah',
                'dosen',
            ])
            ->withCount([
                'kelasMahasiswa as total_mahasiswa',
            ])
            ->withAvg([
                'kelasMahasiswa as rata_rata_nilai',
            ], 'nilai_akhir');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('total_mahasiswa')
                    ->label('Total Mahasiswa')
                    ->sortable(),
            ])

            ->recordActions([
                Action::make('listMahasiswa')
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn($record) => 'Mahasiswa Kelas ' . ($record->kode ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(function ($record) {
                        $mahasiswas = DB::table('mahasiswa')
                            ->join('kelas_mahasiswa', 'mahasiswa.nim', '=', 'kelas_mahasiswa.mahasiswa_nim')
                            ->where('kelas_mahasiswa.kelas_id', $record->id)
                            ->select(
                                'kelas_mahasiswa.id as kelas_mahasiswa_id',
                                'mahasiswa.nim',
                                'mahasiswa.nama',
                                'mahasiswa.email',
                                'mahasiswa.thn_masuk',
                                'kelas_mahasiswa.nilai_akhir'
                            )
                            ->orderBy('mahasiswa.nama')
                            ->get();

                        return view('filament.admin.pages.list-mahasiswa-kelas', [
                            'kelas' => $record,
                            'mahasiswas' => $mahasiswas,
                        ]);
                    })
                    ->label('List Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn(Kelas $record): string => 'Mahasiswa Kelas ' . ($record->kode ?? '-'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('4xl')
                    ->modalContent(function (Kelas $record) {
                        $mahasiswas = KelasMahasiswa::query()
                            ->with(['mahasiswa'])
                            ->where('kelas_id', $record->id)
                            ->orderBy('mahasiswa_nim')
                            ->get();

                        return view('filament.admin.pages.list-mahasiswa-kelas', [
                            'kelas' => $record,
                            'mahasiswas' => $mahasiswas,
                        ]);
                    }),
            ])

            ->defaultSort('kode', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelasMahasiswas::route('/'),
            'create' => CreateKelasMahasiswa::route('/create'),
            'edit' => EditKelasMahasiswa::route('/{record}/edit'),
        ];
    }
}
