<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMahasiswa extends Model
{
    protected $table = 'project_mahasiswa';

    public $timestamps = false;

    protected $fillable = [
        'nama_project',
        'deskripsi',
        'link_url',
        'link_video',
        'mahasiswa_nim',
        'tugas_project_id',
        'nilai_akhir',
        'nama_kelompok',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_project_id');
    }

    public function anggotaKelompok()
    {
        return $this->hasMany(KelompokProject::class, 'project_mahasiswa_id');
    }

    public function recalculateNilaiAkhir(): void
    {
        $avg = $this->anggotaKelompok()
            ->where('aktif', 1)
            ->avg('nilai');

        $this->updateQuietly([
            'nilai_akhir' => round((float) ($avg ?? 0), 2),
        ]);
    }
}