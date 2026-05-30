<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasMahasiswa extends Model
{
    protected $table = 'kelas_mahasiswa';

    public $timestamps = false;

    protected $fillable = [
        'kelas_id',
        'mahasiswa_nim',
        'nilai_akhir',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
}