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

    protected static bool $canCreateAnother = false;


    protected function handleRecordCreation(array $data): Model
    {
        $projectId = $data['project_mahasiswa_id'];
        $mahasiswaNims = $data['mahasiswa_nims'] ?? [];
        $aktif = $data['aktif'] ?? 1;
        $nilai = $data['nilai'] ?? 0;

        $ketuaNim = auth()->user()?->mahasiswa?->nim;

        $firstRecord = null;
        $jumlahBerhasil = 0;
        $jumlahDuplikat = 0;

        DB::transaction(function () use (
            $projectId,
            $mahasiswaNims,
            $ketuaNim,
            $aktif,
            $nilai,
            &$firstRecord,
            &$jumlahBerhasil,
            &$jumlahDuplikat
        ) {
            if ($ketuaNim) {
                $ketua = KelompokProject::firstOrCreate(
                    [
                        'project_mahasiswa_id' => $projectId,
                        'mahasiswa_nim' => $ketuaNim,
                    ],
                    [
                        'peran' => 'KETUA',
                        'aktif' => $aktif,
                        'nilai' => $nilai,
                    ]
                );

                if ($ketua->wasRecentlyCreated) {
                    $jumlahBerhasil++;
                } else {
                    $jumlahDuplikat++;
                }

                $firstRecord = $ketua;
            }

            foreach ($mahasiswaNims as $nim) {
                if ($nim === $ketuaNim) {
                    continue;
                }

                $record = KelompokProject::firstOrCreate(
                    [
                        'project_mahasiswa_id' => $projectId,
                        'mahasiswa_nim' => $nim,
                    ],
                    [
                        'peran' => 'ANGGOTA',
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
