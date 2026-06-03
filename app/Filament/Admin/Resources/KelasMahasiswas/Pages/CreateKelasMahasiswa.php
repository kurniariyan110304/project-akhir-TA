<?php

namespace App\Filament\Admin\Resources\KelasMahasiswas\Pages;

use App\Filament\Admin\Resources\KelasMahasiswas\KelasMahasiswaResource;
use App\Models\KelasMahasiswa;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateKelasMahasiswa extends CreateRecord
{
    protected static string $resource = KelasMahasiswaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $kelasId = $data['kelas_id'];
        $mahasiswaNims = $data['mahasiswa_nims'] ?? [];
        $nilaiAkhir = $data['nilai_akhir'] ?? null;

        $firstRecord = null;
        $jumlahBerhasil = 0;
        $jumlahDuplikat = 0;

        DB::transaction(function () use (
            $kelasId,
            $mahasiswaNims,
            $nilaiAkhir,
            &$firstRecord,
            &$jumlahBerhasil,
            &$jumlahDuplikat
        ) {
            foreach ($mahasiswaNims as $nim) {
                $record = KelasMahasiswa::firstOrCreate(
                    [
                        'kelas_id' => $kelasId,
                        'mahasiswa_nim' => $nim,
                    ],
                    [
                        'nilai_akhir' => $nilaiAkhir,
                    ]
                );

                if ($record->wasRecentlyCreated) {
                    $jumlahBerhasil++;
                } else {
                    $jumlahDuplikat++;
                }

                if (! $firstRecord) {
                    $firstRecord = $record;
                }
            }
        });

        Notification::make()
            ->title('Mahasiswa berhasil diproses')
            ->body("Data baru: {$jumlahBerhasil}. Data sudah ada: {$jumlahDuplikat}.")
            ->success()
            ->send();

        return $firstRecord ?? KelasMahasiswa::make();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}