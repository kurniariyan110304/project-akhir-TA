<?php

namespace App\Filament\Mahasiswa\Resources\KelompokProjects\Pages;

use App\Filament\Mahasiswa\Resources\KelompokProjects\KelompokProjectResource;
use App\Models\KelompokProject;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateKelompokProject extends CreateRecord
{
    protected static string $resource = KelompokProjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $projectId = $data['project_mahasiswa_id'];
        $mahasiswaNims = $data['mahasiswa_nims'] ?? [];
        $peran = $data['peran'];
        $aktif = $data['aktif'] ?? 1;
        $nilai = $data['nilai'] ?? 0;

        $firstRecord = null;
        $jumlahBerhasil = 0;
        $jumlahDuplikat = 0;

        DB::transaction(function () use (
            $projectId,
            $mahasiswaNims,
            $peran,
            $aktif,
            $nilai,
            &$firstRecord,
            &$jumlahBerhasil,
            &$jumlahDuplikat
        ) {
            foreach ($mahasiswaNims as $nim) {
                $record = KelompokProject::firstOrCreate(
                    [
                        'project_mahasiswa_id' => $projectId,
                        'mahasiswa_nim' => $nim,
                    ],
                    [
                        'peran' => $peran,
                        'aktif' => $aktif,
                        'nilai' => $nilai,
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
            ->title('Anggota kelompok berhasil diproses')
            ->body("Data baru: {$jumlahBerhasil}. Data sudah ada: {$jumlahDuplikat}.")
            ->success()
            ->send();

        return $firstRecord ?? KelompokProject::make();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}