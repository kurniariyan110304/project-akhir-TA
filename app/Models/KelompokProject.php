<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelompokProject extends Model
{
    protected $table = 'kelompok_project';

    public $timestamps = false;

    protected $fillable = [
        'peran',
        'nilai',
        'project_mahasiswa_id',
        'mahasiswa_nim',
        'aktif',
    ];

    protected static function booted(): void
    {
        static::saved(function (KelompokProject $kelompok) {
            $kelompok->project?->recalculateNilaiAkhir();
        });

        static::deleted(function (KelompokProject $kelompok) {
            $kelompok->project?->recalculateNilaiAkhir();
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ProjectMahasiswa::class, 'project_mahasiswa_id');
    }

    public function projectMahasiswa(): BelongsTo
    {
        return $this->belongsTo(ProjectMahasiswa::class, 'project_mahasiswa_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
}