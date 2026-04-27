<?php

namespace App\Filament\Admin\Resources\Mahasiswas\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use App\Imports\MahasiswaImport;

class MahasiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('jk')
                    ->label('JK')
                    ->searchable(),

                TextColumn::make('tmp_lahir')
                    ->label('Tempat Lahir')
                    ->searchable(),

                TextColumn::make('tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('thn_masuk')
                    ->label('Tahun Masuk')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('prodi.nama')
                    ->label('Prodi')
                    ->sortable(),

                TextColumn::make('user.nim')
                    ->label('User (NIM)')
                    ->sortable(),
            ])

            ->filters([
                //
            ])

            // 🔥 HEADER ACTIONS (IMPORT + EXPORT)
            ->headerActions([
                // ✅ EXPORT
                Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        return response()->streamDownload(
                            fn () => print(
                                Excel::raw(
                                    new MahasiswaExport($livewire->getFilteredTableQuery()),
                                    \Maatwebsite\Excel\Excel::XLSX
                                )
                            ),
                            'mahasiswa.xlsx'
                        );
                    }),

                // ✅ IMPORT
                Action::make('import')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel')
                            ->required()
                            ->disk('public')
                            ->directory('imports')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                            ]),
                    ])
                    ->action(function (array $data) {
                        Excel::import(new MahasiswaImport, storage_path('app/public/' . $data['file']));

                        Notification::make()
                            ->title('Import berhasil')
                            ->success()
                            ->send();
                    }),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}