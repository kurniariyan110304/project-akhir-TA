<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 🔥 CARI PRODI
        $prodi = Prodi::where('nama', $row['prodi'])->first();
        $prodiId = $prodi?->id;

        // 🔥 SIMPAN MAHASISWA
        $mahasiswa = Mahasiswa::updateOrCreate(
            ['nim' => (string) $row['nim']],
            [
                'nama' => $row['nama'],
                'jk' => $row['jk'],
                'tmp_lahir' => $row['tempat_lahir'],
                'tgl_lahir' => $row['tanggal_lahir'],
                'email' => $row['email'],
                'thn_masuk' => $row['tahun_masuk'],
                'prodi_id' => $prodiId,
            ]
        );

        // 🔥 AUTO CREATE USER (FIX)
        User::updateOrCreate(
            ['email' => $mahasiswa->email],
            [
                'name' => $mahasiswa->nama,
                'password' => Hash::make($mahasiswa->nim),
                'role' => 'mahasiswa',
            ]
        );

        return $mahasiswa;
    }
}