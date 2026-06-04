<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nim']) || empty($row['nama'])) {
            return null;
        }

        $prodiNama = trim((string) ($row['prodi'] ?? ''));

        $prodi = Prodi::where('nama', $prodiNama)->first();

        if (! $prodi) {
            throw new \Exception("Prodi '{$prodiNama}' tidak ditemukan di database.");
        }

        $nim = (string) $row['nim'];

        $mahasiswa = Mahasiswa::updateOrCreate(
            ['nim' => $nim],
            [
                'nama' => $row['nama'],
                'jk' => $row['jk'],
                'tmp_lahir' => $row['tmp_lahir'],
                'tgl_lahir' => $this->formatTanggal($row['tanggal_lahir']),
                'email' => $row['email'],
                'thn_masuk' => $row['tahun_masuk'],
                'prodi_id' => $prodi->id,
            ]
        );

        User::updateOrCreate(
            ['email' => $mahasiswa->email],
            [
                'name' => $mahasiswa->nama,
                'password' => Hash::make($mahasiswa->nim),
                'role' => 'mahasiswa',
                'nim' => $mahasiswa->nim,
            ]
        );

        return $mahasiswa;
    }

    private function formatTanggal($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return Carbon::parse($value)->format('Y-m-d');
    }
}