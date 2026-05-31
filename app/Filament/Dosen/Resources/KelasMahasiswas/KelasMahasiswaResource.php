<?php

namespace App\Filament\Dosen\Resources\KelasMahasiswas;

use App\Filament\Dosen\Resources\KelasMahasiswas\Pages\EditKelasMahasiswa;
use App\Filament\Dosen\Resources\KelasMahasiswas\Pages\ListKelasMahasiswas;
use App\Models\KelasMahasiswa;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KelasMahasiswaResource extends Resource
{
    protected static ?string $model = KelasMahasiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Nilai Mahasiswa';

    protected static ?string $modelLabel = 'Nilai Mahasiswa';

    protected static ?string $pluralModelLabel = 'Nilai Mahasiswa';

    protected static ?int $navigationSort = 7;

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

        return $record->kelas?->dosen_id === $dosen->id;
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

        return $query->whereHas('kelas', function (Builder $query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

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
                        $dosen = auth()->user()?->dosen;
            
                        if (! $dosen) {
                            abort(403);
                        }
            
                        $data = KelasMahasiswa::query()
                            ->whereHas('kelas', function (Builder $query) use ($dosen) {
                                $query->where('dosen_id', $dosen->id);
                            })
                            ->with([
                                'mahasiswa',
                                'kelas',
                                'kelas.matakuliah',
                            ])
                            ->get();
            
                        $pdf = Pdf::loadView('pdf.nilai-mahasiswa', [
                            'data' => $data,
                            'dosen' => $dosen,
                        ])->setPaper('a4', 'landscape');
            
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'nilai-mahasiswa.pdf');
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